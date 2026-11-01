<?php
/**
 * Admin API: Send Reply Message
 * Processes admin messages with optional attachments, validates file upload,
 * enforces 24-hour expiration lock, and dispatches an official response via Gmail PHPMailer.
 */
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Support being included from Admin/api/ or root api/
if (file_exists(__DIR__ . '/../db_connect.php')) {
    require_once __DIR__ . '/../db_connect.php';
} elseif (file_exists(__DIR__ . '/../../admin/db_connect.php')) {
    require_once __DIR__ . '/../../admin/db_connect.php';
} else {
    require_once __DIR__ . '/../admin/db_connect.php';
}

require_once __DIR__ . '/mail_helper.php';

$thread_id = $_POST['thread_id'] ?? $_POST['session_id'] ?? '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($thread_id)) {
    echo json_encode(["status" => "error", "message" => "Invalid or missing thread ID"]);
    exit;
}

// 1. Enforce 24-Hour Expiration Rule
$exp_stmt = $conn->prepare("SELECT (TIMESTAMPDIFF(SECOND, MIN(created_at), NOW()) > 86400) AS is_expired FROM feedback_chats WHERE thread_id = ?");
if ($exp_stmt) {
    $exp_stmt->bind_param("s", $thread_id);
    $exp_stmt->execute();
    $exp_res = $exp_stmt->get_result();
    if ($exp_row = $exp_res->fetch_assoc()) {
        if (!empty($exp_row['is_expired'])) {
            $exp_stmt->close();
            echo json_encode([
                "status" => "error",
                "message" => "This inquiry thread was opened more than 24 hours ago and has expired. Replies are disabled to maintain session freshness."
            ]);
            exit;
        }
    }
    $exp_stmt->close();
}

$file_attachment = null;
$file_name = null;
$disk_attachment_path = null;

// 2. Handle File Attachment (Images, Documents up to 10MB)
$file_field = isset($_FILES['attachment']) ? 'attachment' : (isset($_FILES['file']) ? 'file' : null);

if ($file_field && isset($_FILES[$file_field]) && $_FILES[$file_field]['error'] === UPLOAD_ERR_OK) {
    $file_info = $_FILES[$file_field];
    $max_size = 10 * 1024 * 1024; // 10MB

    if ($file_info['size'] > $max_size) {
        echo json_encode(["status" => "error", "message" => "Uploaded file exceeds the maximum 10MB limit."]);
        exit;
    }

    $ext = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'webp', 'zip', 'xls', 'xlsx', 'txt'];

    if (!in_array($ext, $allowed_extensions)) {
        echo json_encode(["status" => "error", "message" => "Invalid file format. Allowed formats: PDF, DOC, DOCX, JPG, PNG, WEBP, ZIP, XLS, XLSX."]);
        exit;
    }

    // Resolve upload directory
    $upload_dir = __DIR__ . '/../../uploads/chat_files/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $clean_filename = 'admin_' . time() . '_' . uniqid() . '.' . $ext;
    $target_path = $upload_dir . $clean_filename;

    if (move_uploaded_file($file_info['tmp_name'], $target_path)) {
        $file_attachment = 'uploads/chat_files/' . $clean_filename;
        $file_name = $file_info['name'];
        $disk_attachment_path = $target_path;

        if (empty($message)) {
            $message = "Sent an attachment: " . $file_name;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save uploaded attachment on server."]);
        exit;
    }
}

// 3. Validation: Message or File must be present
if (empty($message) && empty($file_attachment)) {
    echo json_encode(["status" => "error", "message" => "Please enter a reply message or attach a document."]);
    exit;
}

// Admin sender information
$admin_name = $_SESSION['admin_name'] ?? 'Barangay Admin';
$admin_email = $_SESSION['admin_email'] ?? 'admin@barangaymaruing.gov.ph';

// 4. Insert Admin Reply into Database
$stmt = $conn->prepare("INSERT INTO feedback_chats (thread_id, sender_name, email, sender_type, message, file_attachment, file_name, is_read) VALUES (?, ?, ?, 'admin', ?, ?, ?, 0)");
if ($stmt) {
    $stmt->bind_param("ssssss", $thread_id, $admin_name, $admin_email, $message, $file_attachment, $file_name);
    if ($stmt->execute()) {
        $new_id = $conn->insert_id;
        $stmt->close();

        // 5. Look up resident's email and details for Gmail SMTP dispatch
        $resident_email = null;
        $resident_name = 'Resident';
        $service_type = 'General Inquiry';

        $r_stmt = $conn->prepare("SELECT sender_name, email, message FROM feedback_chats WHERE thread_id = ? AND sender_type = 'user' AND email IS NOT NULL AND email != '' ORDER BY id ASC LIMIT 1");
        if ($r_stmt) {
            $r_stmt->bind_param("s", $thread_id);
            $r_stmt->execute();
            $r_res = $r_stmt->get_result();
            if ($r_row = $r_res->fetch_assoc()) {
                $resident_email = $r_row['email'];
                if (!empty($r_row['sender_name'])) $resident_name = $r_row['sender_name'];
                if (preg_match('/^\[Service:\s*([^\]]+)\]/i', $r_row['message'], $m)) {
                    $service_type = trim($m[1]);
                }
            }
            $r_stmt->close();
        }

        // 6. Send official reply to resident's Gmail via PHPMailer
        $email_sent = false;
        $mail_error = '';

        if (!empty($resident_email)) {
            $mail_res = send_admin_reply_email(
                $resident_email,
                $resident_name,
                $service_type,
                $message,
                $disk_attachment_path,
                $file_name
            );
            $email_sent = !empty($mail_res['mail_sent']);
            if (!$email_sent) {
                $mail_error = $mail_res['error'] ?? 'Mail dispatch failed';
            }
        }

        echo json_encode([
            "status"          => "success",
            "message_id"      => $new_id,
            "message"         => $email_sent 
                ? "Official reply saved and dispatched to resident's Gmail (" . $resident_email . ")." 
                : "Reply saved to record.",
            "email_sent"      => $email_sent,
            "recipient_email" => $resident_email,
            "mail_error"      => $mail_error,
            "file_attachment" => $file_attachment,
            "file_name"       => $file_name,
            "time"            => date('h:i A')
        ]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Database prepare error: " . $conn->error]);
}
