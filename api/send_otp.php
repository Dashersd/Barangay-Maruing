<?php
/**
 * api/send_otp.php
 * Generates and dispatches a 6-digit OTP to the user's Gmail using PHPMailer.
 */
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../admin/db_connect.php';
require_once __DIR__ . '/mail_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

$fullName = trim($_POST['fullName'] ?? $_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$service  = trim($_POST['service_type'] ?? 'General Inquiry');
$message  = trim($_POST['message'] ?? '');

if (empty($fullName)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter your Full Name.']);
    exit();
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid Gmail or email address.']);
    exit();
}

if (empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter your inquiry details.']);
    exit();
}

try {
    // 1. Ensure email_verifications table exists
    $conn->query("
    CREATE TABLE IF NOT EXISTS `email_verifications` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `email` varchar(150) NOT NULL,
      `pin_code` varchar(10) NOT NULL,
      `full_name` varchar(150) NOT NULL,
      `service_type` varchar(150) DEFAULT 'General Inquiry',
      `message` text NOT NULL,
      `expires_at` datetime NOT NULL,
      `is_verified` tinyint(1) DEFAULT 0,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `email` (`email`),
      KEY `expires_at` (`expires_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 2. Generate secure 6-digit OTP code
    $otp_code = sprintf("%06d", mt_rand(100000, 999999));

    // 5 minutes expiration (300 seconds)
    $expires_at = date('Y-m-d H:i:s', time() + 300);

    // 3. Invalidate any existing unverified codes for this email
    $inv_stmt = $conn->prepare("UPDATE `email_verifications` SET `is_verified` = 2 WHERE `email` = ? AND `is_verified` = 0");
    if ($inv_stmt) {
        $inv_stmt->bind_param("s", $email);
        $inv_stmt->execute();
        $inv_stmt->close();
    }

    // 4. Insert new verification record
    $stmt = $conn->prepare("INSERT INTO `email_verifications` 
        (`email`, `pin_code`, `full_name`, `service_type`, `message`, `expires_at`, `is_verified`) 
        VALUES (?, ?, ?, ?, ?, ?, 0)");

    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $email, $otp_code, $fullName, $service, $message, $expires_at);

    if (!$stmt->execute()) {
        throw new Exception("Database insert failed: " . $stmt->error);
    }
    $stmt->close();

    // 5. Send OTP via Gmail PHPMailer
    $mailResult = send_otp_email($email, $fullName, $otp_code, 'Inquiry Verification');

    echo json_encode([
        'status'     => 'success',
        'message'    => !empty($mailResult['mail_sent'])
            ? 'A 6-digit verification code (OTP) has been sent to your Gmail inbox. Please enter it below to confirm.'
            : 'OTP generated. Please check your inbox or spam folder.',
        'mail_sent'  => (bool)$mailResult['mail_sent'],
        'mail_error' => $mailResult['error'] ?? null,
        'dev_otp'    => (!empty($mailResult['mail_sent']) ? null : $otp_code), // Fallback if dummy email used during dev
        'email'      => $email,
        'expires_in' => 180 // 3 minutes for countdown
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to process OTP: ' . $e->getMessage()
    ]);
}
