<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../admin/db_connect.php';

$thread_id = $_POST['session_id'] ?? $_POST['thread_id'] ?? '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($thread_id) || empty($message)) {
    echo json_encode(["status" => "error", "message" => "Invalid input or empty message"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO feedback_chats (thread_id, sender_name, sender_type, message, is_read) VALUES (?, 'Admin', 'admin', ?, 0)");
if ($stmt) {
    $stmt->bind_param("ss", $thread_id, $message);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
