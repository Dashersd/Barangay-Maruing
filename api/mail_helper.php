<?php
/**
 * api/mail_helper.php
 * Email Dispatch Helper for Barangay Maruing with Gmail SMTP (PHPMailer)
 * Optimized with anti-spam deliverability headers for Gmail & transactional OTP delivery.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Configure and instantiate PHPMailer with Gmail SMTP
 */
function get_barangay_mailer() {
    // Locate PHPMailer files
    $base_dir = __DIR__;
    if (!file_exists($base_dir . '/phpmailer/PHPMailer.php')) {
        $base_dir = __DIR__ . '/../../api';
    }

    require_once $base_dir . '/phpmailer/Exception.php';
    require_once $base_dir . '/phpmailer/PHPMailer.php';
    require_once $base_dir . '/phpmailer/SMTP.php';

    // Locate SMTP configuration
    $smtp_config = [];
    if (file_exists(__DIR__ . '/smtp_config.php')) {
        $smtp_config = require __DIR__ . '/smtp_config.php';
    } elseif (file_exists(__DIR__ . '/../../api/smtp_config.php')) {
        $smtp_config = require __DIR__ . '/../../api/smtp_config.php';
    }

    $mail = new PHPMailer(true);

    if (!empty($smtp_config['smtp_enabled']) && !empty($smtp_config['smtp_username']) && !empty($smtp_config['smtp_password'])) {
        $mail->isSMTP();
        $mail->Host       = $smtp_config['smtp_host'] ?: 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_config['smtp_username'];
        $mail->Password   = $smtp_config['smtp_password'];
        $mail->SMTPSecure = ($smtp_config['smtp_secure'] === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)($smtp_config['smtp_port'] ?: 587);
        $mail->Timeout    = 12; // Prevent indefinite execution
        $mail->CharSet    = 'UTF-8';
        $mail->Encoding   = 'base64';

        // Deliverability optimizations:
        $mail->Hostname = 'gmail.com';
        $mail->XMailer = ' '; // Suppress default PHPMailer signature to avoid spam score

        $fromEmail = !empty($smtp_config['from_email']) ? $smtp_config['from_email'] : $smtp_config['smtp_username'];
        $fromName  = !empty($smtp_config['from_name']) ? $smtp_config['from_name'] : 'Barangay Maruing Information System';

        $mail->setFrom($fromEmail, $fromName);
        $mail->Sender = $fromEmail; // Ensures Return-Path matches From
        $mail->addReplyTo($fromEmail, $fromName);

        // Anti-spam transactional headers recognized by email providers
        $mail->addCustomHeader('Auto-Submitted', 'auto-generated');
        $mail->addCustomHeader('X-Auto-Response-Suppress', 'All');
    }

    return $mail;
}

/**
 * 1. Send 6-Digit OTP Verification Code to Gmail
 */
function send_otp_email($to_email, $to_name, $otp_code, $purpose = 'Inquiry Verification') {
    $subject = "Your Barangay Maruing Verification Code (OTP): " . $otp_code;
    $safe_name = htmlspecialchars($to_name ?: 'Resident', ENT_QUOTES, 'UTF-8');
    $safe_otp  = htmlspecialchars($otp_code, ENT_QUOTES, 'UTF-8');
    $safe_purp = htmlspecialchars($purpose, ENT_QUOTES, 'UTF-8');

    $html_body = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Barangay Maruing Verification Code</title>
    </head>
    <body style='margin: 0; padding: 24px 12px; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; color: #1e293b;'>
        <table role='presentation' width='100%' cellpadding='0' cellspacing='0' border='0'>
            <tr>
                <td align='center'>
                    <table role='presentation' width='100%' cellpadding='0' cellspacing='0' border='0' style='max-width: 520px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); border: 1px solid #e2e8f0;'>
                        
                        <!-- Header (Forest Green) -->
                        <tr>
                            <td align='center' style='background: linear-gradient(135deg, #093d25 0%, #166534 100%); padding: 28px 24px; color: #ffffff;'>
                                <h1 style='margin: 0; font-size: 22px; font-weight: 800; letter-spacing: 1px; color: #ffffff;'>BARANGAY MARUING</h1>
                                <p style='margin: 6px 0 0 0; font-size: 13px; color: #ffc107; font-weight: 600;'>Official Information &amp; Citizen Services</p>
                            </td>
                        </tr>

                        <!-- Body Content -->
                        <tr>
                            <td align='center' style='padding: 34px 28px;'>
                                <h2 style='font-size: 19px; color: #093d25; margin: 0 0 14px 0; font-weight: 700;'>{$safe_purp} OTP</h2>
                                <p style='font-size: 14px; line-height: 1.6; color: #475569; margin: 0 0 16px 0; text-align: center;'>
                                    Hello <strong>{$safe_name}</strong>,
                                </p>
                                <p style='font-size: 14px; line-height: 1.6; color: #475569; margin: 0 0 24px 0; text-align: center;'>
                                    You recently submitted a resident inquiry or document request through the Barangay Maruing Portal. Please enter the single-use 6-digit OTP below to verify and complete your submission:
                                </p>
                                
                                <!-- OTP Code Box -->
                                <table role='presentation' cellpadding='0' cellspacing='0' border='0' style='background-color: #f0fdf4; border: 2px dashed #16a34a; border-radius: 12px; margin: 0 auto 24px auto; width: 290px;'>
                                    <tr>
                                        <td align='center' style='padding: 22px 16px;'>
                                            <div style='font-family: \"Courier New\", Courier, monospace; font-size: 38px; font-weight: 800; letter-spacing: 8px; color: #093d25; margin: 0; line-height: 1;'>
                                                {$safe_otp}
                                            </div>
                                            <div style='display: inline-block; background-color: #093d25; color: #ffc107; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-top: 14px; letter-spacing: 0.3px;'>
                                                &#9201; Valid for 5 Minutes
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Security Notice -->
                                <div style='font-size: 12px; color: #64748b; margin-top: 10px; line-height: 1.6; border-top: 1px solid #f1f5f9; padding-top: 18px; text-align: center;'>
                                    <strong style='color: #093d25;'>Security Notice:</strong> Never share this OTP with anyone. Barangay personnel will never ask for your code. If you did not make this request, you can safely ignore this email.
                                </div>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td align='center' style='background-color: #f8fafc; padding: 20px 24px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b; line-height: 1.5;'>
                                <p style='margin: 0; font-weight: 700; color: #0f172a;'>Barangay Maruing Administration</p>
                                <p style='margin: 4px 0 0 0;'>Municipality of Lapuyan, Zamboanga del Sur</p>
                                <p style='margin: 8px 0 0 0; color: #94a3b8; font-size: 11px;'>This is an automated verification email. Please do not reply directly to this message.</p>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    ";

    $plain_body = "BARANGAY MARUING - INFORMATION & CITIZEN SERVICES\n\n"
                . "{$purpose} - One-Time Password (OTP)\n\n"
                . "Hello {$safe_name},\n\n"
                . "Your 6-digit verification code is: {$otp_code}\n\n"
                . "This code is valid for 5 minutes.\n\n"
                . "Security Notice: Never share this OTP with anyone. If you did not request this, please disregard.\n\n"
                . "Barangay Maruing Administration\nLapuyan, Zamboanga del Sur";

    $mail_sent = false;
    $error_msg = '';

    try {
        $mail = get_barangay_mailer();
        $mail->addAddress($to_email, $to_name);
        $mail->addCustomHeader('X-Entity-Ref-ID', uniqid('maruing_otp_'));
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html_body;
        $mail->AltBody = $plain_body;

        $mail->send();
        $mail_sent = true;
    } catch (Exception $e) {
        $error_msg = isset($mail) ? $mail->ErrorInfo : $e->getMessage();
        $mail_sent = false;
    }

    // Dev logging
    $log_dir = __DIR__ . '/../Admin/uploads';
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0777, true);
    }
    $status_label = $mail_sent ? 'SENT_VIA_GMAIL_SMTP' : ('FAILED: ' . $error_msg);
    $log_entry = sprintf("[%s] TO: %s (%s) | OTP: %s | STATUS: %s\n",
        date('Y-m-d H:i:s'),
        $to_email,
        $to_name,
        $otp_code,
        $status_label
    );
    @file_put_contents($log_dir . '/email_otp_dev.log', $log_entry, FILE_APPEND);

    return [
        'success'   => $mail_sent,
        'mail_sent' => $mail_sent,
        'otp'       => $otp_code,
        'error'     => $error_msg
    ];
}

/**
 * 2. Send Official Admin Reply Email via Gmail PHPMailer
 */
function send_admin_reply_email($to_email, $to_name, $service_type, $reply_message, $attachment_path = null, $attachment_name = null) {
    if (empty($to_email) || !filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Invalid recipient email address.'];
    }

    $safe_name = htmlspecialchars($to_name ?: 'Resident', ENT_QUOTES, 'UTF-8');
    $safe_serv = htmlspecialchars($service_type ?: 'General Inquiry', ENT_QUOTES, 'UTF-8');
    $formatted_msg = nl2br(htmlspecialchars($reply_message, ENT_QUOTES, 'UTF-8'));
    $subject = "Official Response: " . $service_type . " - Barangay Maruing";

    $attach_notice = "";
    if ($attachment_name) {
        $safe_att = htmlspecialchars($attachment_name, ENT_QUOTES, 'UTF-8');
        $attach_notice = "
        <div style='margin-top: 18px; padding: 12px 16px; background-color: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0;'>
            <strong style='color: #166534; font-size: 13px;'>&#128206; Attached Document:</strong>
            <span style='color: #15803d; font-size: 13px; font-weight: 600; margin-left: 6px;'>{$safe_att}</span>
            <p style='margin: 4px 0 0 0; font-size: 12px; color: #475569;'>Please find the document attached to this email message.</p>
        </div>";
    }

    $html_body = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Official Response - Barangay Maruing</title>
    </head>
    <body style='margin: 0; padding: 24px 12px; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; color: #1e293b;'>
        <table role='presentation' width='100%' cellpadding='0' cellspacing='0' border='0'>
            <tr>
                <td align='center'>
                    <table role='presentation' width='100%' cellpadding='0' cellspacing='0' border='0' style='max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); border: 1px solid #e2e8f0;'>
                        
                        <!-- Header -->
                        <tr>
                            <td align='center' style='background: linear-gradient(135deg, #093d25 0%, #166534 100%); padding: 28px 24px; color: #ffffff;'>
                                <h1 style='margin: 0; font-size: 22px; font-weight: 800; letter-spacing: 1px; color: #ffffff;'>BARANGAY MARUING</h1>
                                <p style='margin: 6px 0 0 0; font-size: 13px; color: #ffc107; font-weight: 600;'>Official Communication &amp; Response Center</p>
                            </td>
                        </tr>

                        <!-- Body -->
                        <tr>
                            <td style='padding: 34px 28px;'>
                                <div style='display: inline-block; background-color: #ecfdf5; color: #065f46; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 20px; border: 1px solid #a7f3d0; margin-bottom: 16px;'>
                                    Category: {$safe_serv}
                                </div>
                                <h2 style='font-size: 18px; color: #093d25; margin: 0 0 14px 0; font-weight: 700;'>Official Response to Your Inquiry</h2>
                                <p style='font-size: 14px; line-height: 1.6; color: #334155; margin: 0 0 16px 0;'>
                                    Dear <strong>{$safe_name}</strong>,
                                </p>
                                <p style='font-size: 14px; line-height: 1.6; color: #475569; margin: 0 0 20px 0;'>
                                    Thank you for contacting the Barangay Maruing Administration. Below is the official response regarding your inquiry:
                                </p>

                                <!-- Response Card -->
                                <div style='background-color: #f8fafc; border-left: 4px solid #093d25; border-radius: 4px 8px 8px 4px; padding: 18px 20px; margin-bottom: 20px; font-size: 14px; line-height: 1.7; color: #0f172a;'>
                                    {$formatted_msg}
                                </div>

                                {$attach_notice}

                                <div style='margin-top: 24px; font-size: 13px; line-height: 1.6; color: #64748b; border-top: 1px solid #f1f5f9; padding-top: 18px;'>
                                    <strong>Office Inquiries &amp; Clearance Pickups:</strong><br>
                                    Barangay Hall Office Hours: Monday to Friday, 8:00 AM &ndash; 5:00 PM.<br>
                                    Please bring 1 valid government-issued ID when claiming documents.
                                </div>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td align='center' style='background-color: #f8fafc; padding: 20px 24px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b; line-height: 1.5;'>
                                <p style='margin: 0; font-weight: 700; color: #0f172a;'>Barangay Maruing Administration</p>
                                <p style='margin: 4px 0 0 0;'>Municipality of Lapuyan, Zamboanga del Sur</p>
                                <p style='margin: 8px 0 0 0; color: #94a3b8; font-size: 11px;'>This official correspondence was sent via Barangay Maruing's Automated Information System.</p>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    ";

    $plain_body = "BARANGAY MARUING - OFFICIAL RESPONSE\n\n"
                . "Category: {$service_type}\n\n"
                . "Dear {$to_name},\n\n"
                . "Thank you for contacting Barangay Maruing. Below is our official response:\n\n"
                . strip_tags($reply_message) . "\n\n"
                . ($attachment_name ? "Attached file: {$attachment_name}\n\n" : "")
                . "Office Hours: Monday - Friday, 8:00 AM - 5:00 PM\n"
                . "Barangay Maruing Administration\nLapuyan, Zamboanga del Sur";

    $mail_sent = false;
    $error_msg = '';

    try {
        $mail = get_barangay_mailer();
        $mail->addAddress($to_email, $to_name);
        $mail->addCustomHeader('X-Entity-Ref-ID', uniqid('maruing_reply_'));
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html_body;
        $mail->AltBody = $plain_body;

        // Attach file if present and exists on disk
        if ($attachment_path && file_exists($attachment_path)) {
            $mail->addAttachment($attachment_path, $attachment_name ?: basename($attachment_path));
        }

        $mail->send();
        $mail_sent = true;
    } catch (Exception $e) {
        $error_msg = isset($mail) ? $mail->ErrorInfo : $e->getMessage();
        $mail_sent = false;
    }

    return [
        'success'   => $mail_sent,
        'mail_sent' => $mail_sent,
        'error'     => $error_msg
    ];
}

