<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../admin/db_connect.php';

$thread_id = $_POST['session_id'] ?? $_POST['thread_id'] ?? '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($thread_id)) {
    echo json_encode(["status" => "error", "message" => "Invalid thread ID"]);
    exit;
}

// Block replies if the chat thread is older than 1 day (86400 seconds)
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
                "message" => "Cannot send reply: This chat session has expired after 1 day (24 hours)."
            ]);
            exit;
        }
    }
    $exp_stmt->close();
}

$file_attachment = null;
$file_name = null;

// Handle file upload if present
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = __DIR__ . '/../uploads/chat_files/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $original_name = pathinfo($_FILES['attachment']['name'], PATHINFO_FILENAME);
    $ext = strtolower(pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION));
    $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'zip', 'xls', 'xlsx'];

    if (in_array($ext, $allowed)) {
        $clean_name = time() . '_' . uniqid() . '.' . $ext;
        $target_path = $upload_dir . $clean_name;
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_path)) {
            $file_attachment = 'uploads/chat_files/' . $clean_name;
            $file_name = $_FILES['attachment']['name'];
            if (empty($message)) {
                $message = "Sent an attachment: " . $file_name;
            }
        }
    }
}

if (empty($thread_id) || (empty($message) && empty($file_attachment))) {
    echo json_encode(["status" => "error", "message" => "Invalid input, empty message, or no file attached"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO feedback_chats (thread_id, sender_name, sender_type, message, file_attachment, file_name, is_read) VALUES (?, 'Admin', 'admin', ?, ?, ?, 0)");
if ($stmt) {
    $stmt->bind_param("ssss", $thread_id, $message, $file_attachment, $file_name);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "file_attachment" => $file_attachment, "file_name" => $file_name]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
