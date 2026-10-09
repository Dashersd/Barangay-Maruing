<?php
require_once '../admin/db_connect.php';

header('Content-Type: application/json');

$thread_id = $_GET['thread_id'] ?? '';

if (empty($thread_id)) {
    echo json_encode(['status' => 'error', 'message' => 'No thread ID provided']);
    exit;
}

// Mark messages as read depending on who is fetching
$reader_type = $_GET['reader_type'] ?? 'user';
$sender_to_update = ($reader_type === 'admin') ? 'user' : 'admin';
$update_stmt = $conn->prepare("UPDATE feedback_chats SET is_read = 1 WHERE thread_id = ? AND sender_type = ?");
$update_stmt->bind_param("ss", $thread_id, $sender_to_update);
$update_stmt->execute();
$update_stmt->close();

$stmt = $conn->prepare("SELECT * FROM feedback_chats WHERE thread_id = ? ORDER BY created_at ASC");
$stmt->bind_param("s", $thread_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'id' => $row['id'],
        'sender_name' => htmlspecialchars($row['sender_name']),
        'sender_type' => $row['sender_type'],
        'message' => htmlspecialchars($row['message']),
        'created_at' => date('h:i A', strtotime($row['created_at']))
    ];
}

echo json_encode(['status' => 'success', 'messages' => $messages]);
$stmt->close();
?>
