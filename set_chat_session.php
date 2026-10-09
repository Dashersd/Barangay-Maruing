<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['chat_user_name'] = $_POST['name'] ?? 'Guest';
    echo json_encode(['status' => 'success']);
}
?>
