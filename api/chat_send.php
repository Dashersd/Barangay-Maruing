<?php
require_once '../admin/db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $thread_id = $_POST['thread_id'] ?? '';
    $sender_name = $_POST['sender_name'] ?? '';
    $sender_type = $_POST['sender_type'] ?? 'user';
    $message = $_POST['message'] ?? '';

    if (empty($thread_id) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO feedback_chats (thread_id, sender_name, sender_type, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $thread_id, $sender_name, $sender_type, $message);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
}
?>
