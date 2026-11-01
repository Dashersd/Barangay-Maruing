<?php
/**
 * Admin API: Fetch Messages for a Thread
 * Retrieves complete message stream, marks user messages as read, checks 24-hour expiration.
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

$thread_id = $_GET['thread_id'] ?? $_GET['session_id'] ?? '';

if (empty($thread_id)) {
    echo json_encode(["status" => "error", "message" => "Invalid or missing thread ID"]);
    exit;
}

// 1. Mark all user messages for this thread as read
$upd = $conn->prepare("UPDATE feedback_chats SET is_read = 1 WHERE thread_id = ? AND sender_type = 'user' AND is_read = 0");
if ($upd) {
    $upd->bind_param("s", $thread_id);
    $upd->execute();
    $upd->close();
}

// 2. Check 24-hour expiration and thread info
$is_expired = false;
$thread_started_at = '';
$resident_name = 'Resident';
$resident_email = '';
$service_type = 'General Inquiry';

$info_stmt = $conn->prepare("
    SELECT 
        (TIMESTAMPDIFF(SECOND, MIN(created_at), NOW()) > 86400) AS is_expired,
        MIN(created_at) AS started_at 
    FROM feedback_chats 
    WHERE thread_id = ?
");
if ($info_stmt) {
    $info_stmt->bind_param("s", $thread_id);
    $info_stmt->execute();
    $info_res = $info_stmt->get_result();
    if ($info_row = $info_res->fetch_assoc()) {
        $is_expired = !empty($info_row['is_expired']);
        $thread_started_at = $info_row['started_at'] ?? '';
    }
    $info_stmt->close();
}

// Helper to determine image file extensions
function is_image_file($filename) {
    if (!$filename) return false;
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
}

// Helper to format file size
function format_bytes($bytes, $precision = 1) {
    if (!$bytes || $bytes <= 0) return '';
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// 3. Fetch all messages in this thread
$stmt = $conn->prepare("
    SELECT id, sender_name, email, sender_type, message, file_attachment, file_name, created_at 
    FROM feedback_chats 
    WHERE thread_id = ? 
    ORDER BY created_at ASC, id ASC
");

$messages = [];
if ($stmt) {
    $stmt->bind_param("s", $thread_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $sender = ($row['sender_type'] === 'admin') ? 'admin' : 'user';

        // Extract resident details if user sender
        if ($sender === 'user') {
            if (!empty($row['sender_name']) && $resident_name === 'Resident') {
                $resident_name = $row['sender_name'];
            }
            if (!empty($row['email']) && empty($resident_email)) {
                $resident_email = $row['email'];
            }
        }

        // Clean message and check service prefix
        $raw_msg = $row['message'];
        $clean_msg = $raw_msg;
        if (preg_match('/^\[Service:\s*([^\]]+)\]\s*(.*)$/si', $raw_msg, $matches)) {
            $service_type = trim($matches[1]);
            $clean_msg = trim($matches[2]);
        }

        // File details
        $file_attachment = $row['file_attachment'] ?? null;
        $file_name = $row['file_name'] ?? '';
        $is_img = false;
        $file_size_formatted = '';

        if (!empty($file_attachment)) {
            $is_img = is_image_file($file_name ? $file_name : $file_attachment);
            
            // Resolve full disk path to check file size
            $disk_path = __DIR__ . '/../../' . ltrim($file_attachment, '/');
            if (!file_exists($disk_path)) {
                $disk_path = __DIR__ . '/../' . ltrim($file_attachment, '/');
            }
            if (file_exists($disk_path)) {
                $file_size_formatted = format_bytes(filesize($disk_path));
            }
        }

        $messages[] = [
            'id' => (int)$row['id'],
            'sender' => $sender,
            'sender_name' => htmlspecialchars($row['sender_name'] ?? ($sender === 'admin' ? 'Admin' : 'Resident'), ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8'),
            'message' => $clean_msg,
            'file_attachment' => $file_attachment,
            'file_name' => htmlspecialchars($file_name, ENT_QUOTES, 'UTF-8'),
            'is_image' => $is_img,
            'file_size' => $file_size_formatted,
            'created_at' => $row['created_at'],
            'time' => date('h:i A', strtotime($row['created_at'])),
            'date_formatted' => date('M d, Y', strtotime($row['created_at']))
        ];
    }
    $stmt->close();
}

echo json_encode([
    "status" => "success",
    "thread_id" => $thread_id,
    "resident_name" => htmlspecialchars($resident_name, ENT_QUOTES, 'UTF-8'),
    "resident_email" => htmlspecialchars($resident_email, ENT_QUOTES, 'UTF-8'),
    "service_type" => htmlspecialchars($service_type, ENT_QUOTES, 'UTF-8'),
    "started_at" => $thread_started_at ? date('M d, Y h:i A', strtotime($thread_started_at)) : '',
    "is_expired" => $is_expired,
    "messages" => $messages
]);
