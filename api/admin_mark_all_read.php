<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../admin/db_connect.php';

$res = $conn->query("UPDATE feedback_chats SET is_read = 1 WHERE sender_type = 'user' AND is_read = 0");

if ($res) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
