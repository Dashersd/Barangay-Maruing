<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../admin/db_connect.php';

$thread_id = $_GET['session_id'] ?? $_GET['thread_id'] ?? '';

if (empty($thread_id)) {
    echo json_encode(["status" => "error", "message" => "Invalid thread ID"]);
    exit;
}

// 1. Mark all user messages for this thread as read
$upd = $conn->prepare("UPDATE feedback_chats SET is_read = 1 WHERE thread_id = ? AND sender_type = 'user' AND is_read = 0");
if ($upd) {
    $upd->bind_param("s", $thread_id);
    $upd->execute();
    $upd->close();
}

// 2. Fetch all messages in this thread
$stmt = $conn->prepare("
    SELECT id, sender_name, sender_type, message, file_attachment, file_name, created_at 
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
        
        $messages[] = [
            'id' => (int)$row['id'],
            'sender' => $sender,
            'sender_name' => htmlspecialchars($row['sender_name']),
            'message' => $row['message'],
            'file_attachment' => $row['file_attachment'] ?? null,
            'file_name' => htmlspecialchars($row['file_name'] ?? ''),
            'time' => date('h:i A', strtotime($row['created_at']))
        ];
    }
    $stmt->close();
}

echo json_encode(["status" => "success", "messages" => $messages]);
?>
