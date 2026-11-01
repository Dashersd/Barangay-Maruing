<?php
/**
 * Admin API: Mark All Messages as Read
 * Updates all unread citizen messages in feedback_chats to is_read = 1.
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

$res = $conn->query("UPDATE feedback_chats SET is_read = 1 WHERE sender_type = 'user' AND is_read = 0");

if ($res) {
    echo json_encode([
        "status" => "success",
        "message" => "All resident inquiries marked as read."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $conn->error
    ]);
}
