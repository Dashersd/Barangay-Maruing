<?php
require_once '../admin/db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $thread_id = $_POST['thread_id'] ?? '';
    $sender_name = $_POST['sender_name'] ?? '';
    $sender_type = $_POST['sender_type'] ?? 'user';
    $message = $_POST['message'] ?? '';
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;

    if (empty($thread_id) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
        exit;
    }

    // Preserve email across subsequent replies in the thread if not re-sent
    if (empty($email)) {
        $check_stmt = $conn->prepare("SELECT email FROM feedback_chats WHERE thread_id = ? AND email IS NOT NULL AND email != '' LIMIT 1");
        if ($check_stmt) {
            $check_stmt->bind_param("s", $thread_id);
            $check_stmt->execute();
            $res = $check_stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $email = $row['email'];
            }
            $check_stmt->close();
        }
    }

    $stmt = $conn->prepare("INSERT INTO feedback_chats (thread_id, sender_name, email, sender_type, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $thread_id, $sender_name, $email, $sender_type, $message);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
}
?>
