<?php
/**
 * api/verify_otp.php
 * Validates 6-digit OTP, marks verified, and securely records the inquiry into feedback_chats.
 */
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../admin/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

$email    = trim($_POST['email'] ?? '');
$otp_code = trim($_POST['otp_code'] ?? $_POST['pin_code'] ?? '');

if (empty($email) || empty($otp_code)) {
    echo json_encode(['status' => 'error', 'message' => 'Please provide both your Gmail address and the 6-digit verification code.']);
    exit();
}

try {
    // 1. Ensure feedback_chats table exists
    $conn->query("
    CREATE TABLE IF NOT EXISTS `feedback_chats` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `thread_id` VARCHAR(100) NOT NULL,
      `sender_name` VARCHAR(255) NOT NULL,
      `email` VARCHAR(150) DEFAULT NULL,
      `sender_type` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
      `message` TEXT NOT NULL,
      `file_attachment` VARCHAR(255) DEFAULT NULL,
      `file_name` VARCHAR(255) DEFAULT NULL,
      `is_read` TINYINT(1) DEFAULT 0,
      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      INDEX `idx_thread_id` (`thread_id`),
      INDEX `idx_sender_type_read` (`sender_type`, `is_read`),
      INDEX `idx_created_at` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 2. Look up pending OTP verification record
    $stmt = $conn->prepare("SELECT `id`, `pin_code`, `full_name`, `service_type`, `message`, `expires_at` 
        FROM `email_verifications` 
        WHERE `email` = ? AND `is_verified` = 0 
        ORDER BY `id` DESC LIMIT 1");

    if (!$stmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $record = $res->fetch_assoc();
    $stmt->close();

    if (!$record) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'No active OTP verification request found for this email. Please submit your inquiry again.'
        ]);
        exit();
    }

    // 3. Check expiration (5 minutes)
    if (strtotime($record['expires_at']) < time()) {
        $exp_up = $conn->prepare("UPDATE `email_verifications` SET `is_verified` = 3 WHERE `id` = ?");
        if ($exp_up) {
            $exp_up->bind_param("i", $record['id']);
            $exp_up->execute();
            $exp_up->close();
        }

        echo json_encode([
            'status'  => 'error',
            'message' => 'Your OTP code has expired (5-minute limit). Please click "Resend Code" to receive a new one.'
        ]);
        exit();
    }

    // 4. Verify Code
    if ($record['pin_code'] !== $otp_code) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Invalid OTP code. Please enter the correct 6-digit code sent to your Gmail.'
        ]);
        exit();
    }

    // 5. Mark as verified
    $v_stmt = $conn->prepare("UPDATE `email_verifications` SET `is_verified` = 1 WHERE `id` = ?");
    if ($v_stmt) {
        $v_stmt->bind_param("i", $record['id']);
        $v_stmt->execute();
        $v_stmt->close();
    }

    // 6. Record verified inquiry into feedback_chats
    $thread_id = 'inquiry_' . time() . '_' . substr(md5($email . time()), 0, 6);
    $full_message = "[Service: " . $record['service_type'] . "]\n\n" . $record['message'];

    $chat_stmt = $conn->prepare("INSERT INTO `feedback_chats` 
        (`thread_id`, `sender_name`, `email`, `sender_type`, `message`, `is_read`) 
        VALUES (?, ?, ?, 'user', ?, 0)");

    if ($chat_stmt) {
        $chat_stmt->bind_param("ssss", $thread_id, $record['full_name'], $email, $full_message);
        $chat_stmt->execute();
        $chat_stmt->close();
    }

    echo json_encode([
        'status'    => 'success',
        'message'   => 'Inquiry successfully verified and submitted to Barangay Maruing!',
        'full_name' => $record['full_name'],
        'email'     => $email,
        'thread_id' => $thread_id
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Verification failed: ' . $e->getMessage()
    ]);
}

