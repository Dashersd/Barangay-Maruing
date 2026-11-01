<?php
/**
 * Admin Feedback & Resident Inquiry Management System
 * Production-ready two-way messaging portal for Barangay Maruing Administrators.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db_connect.php';

// Ensure feedback_chats table exists with required schema
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

$admin_name = $_SESSION['admin_name'] ?? 'System Admin';
$admin_role = $_SESSION['admin_role'] ?? 'Administrator';
$admin_initial = strtoupper(substr($admin_name, 0, 1));

// Load official SMTP sender email
$smtp_config = file_exists(__DIR__ . '/../api/smtp_config.php') ? require __DIR__ . '/../api/smtp_config.php' : [];
$official_sender_email = $smtp_config['from_email'] ?? 'mugiw200@gmail.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback &amp; Resident Inquiries | Barangay Maruing</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons & FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Base Admin Stylesheet Fallback -->
    <link rel="stylesheet" href="../public/assets/css/style.css">
    <link rel="stylesheet" href="Admin CSS/admin.css?v=5">

    <!-- Complete Self-Contained Portal Styles -->
    <style>
        :root {
            --primary: #093d25;
            --primary-hover: #0c4d2f;
            --primary-light: #e8f5e9;
            --accent: #ffc107;
            --accent-hover: #e0a800;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --success: #10b981;
            --success-light: #ecfdf5;
            --bg-page: #f1f5f9;
            --bg-surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
            --shadow-subtle: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-card: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            --shadow-elevated: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.04);
        }

        * {
            box-sizing: border-box;
            font-family: 'Montserrat', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Topbar Enhancements */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1.75rem;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title h2 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-title p {
            margin: 3px 0 0 0;
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-header-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 30px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: #ffffff;
            color: #334155;
            transition: all 0.2s ease;
        }

        .btn-header-pill:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-header-pill.active-sound {
            background: var(--primary-light);
            border-color: #a7f3d0;
            color: #065f46;
        }

        .btn-header-pill.muted-sound {
            background: #f1f5f9;
            color: #94a3b8;
        }

        .btn-mark-read-all {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .btn-mark-read-all:hover {
            background: #dcfce7;
            border-color: #86efac;
            color: #166534;
        }

        .unread-indicator-badge {
            display: inline-block;
            background: var(--danger);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 12px;
            margin-left: 4px;
        }

        /* Messenger Container */
        .messenger-layout {
            display: flex;
            height: calc(100vh - 75px);
            max-height: calc(100vh - 75px);
            background: #ffffff;
            border-radius: 0;
            overflow: hidden;
            position: relative;
        }

        /* Left Panel: Threads List (350px) */
        .threads-panel {
            width: 360px;
            min-width: 340px;
            max-width: 380px;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            background: #ffffff;
            flex-shrink: 0;
            height: 100%;
        }

        .threads-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            background: #fbfcfd;
        }

        .threads-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .threads-title-row h3 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .threads-count-badge {
            background: #e2e8f0;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
        }

        /* Live Search Bar */
        .threads-search-box {
            position: relative;
            margin-bottom: 12px;
        }

        .threads-search-box i.search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .threads-search-input {
            width: 100%;
            padding: 9px 34px 9px 34px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 0.86rem;
            background: #ffffff;
            color: var(--text-main);
            transition: all 0.2s ease;
        }

        .threads-search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(9, 61, 37, 0.12);
        }

        .threads-search-clear {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 0 4px;
            line-height: 1;
            display: none;
        }

        .threads-search-clear:hover {
            color: #475569;
        }

        /* Filter Tabs */
        .threads-filter-tabs {
            display: flex;
            gap: 6px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: var(--radius-sm);
        }

        .filter-tab-btn {
            flex: 1;
            padding: 6px 10px;
            border: none;
            background: transparent;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .filter-tab-btn.active {
            background: #ffffff;
            color: var(--primary);
            box-shadow: var(--shadow-subtle);
        }

        /* Threads List Items */
        .threads-scroll-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px 0;
        }

        .thread-item {
            display: flex;
            gap: 12px;
            padding: 13px 16px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: all 0.18s ease;
            position: relative;
            background: #ffffff;
        }

        .thread-item:hover {
            background: #f8fafc;
        }

        .thread-item.active {
            background: #f0fdf4;
            border-left: 4px solid var(--primary);
        }

        .thread-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #093d25 0%, #166534 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .thread-content {
            flex: 1;
            min-width: 0;
        }

        .thread-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .thread-citizen-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .thread-timestamp {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
            white-space: nowrap;
        }

        .thread-service-tag {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            color: #166534;
            background: #dcfce7;
            padding: 1px 7px;
            border-radius: 4px;
            margin-bottom: 4px;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .thread-snippet {
            font-size: 0.8rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.35;
        }

        .thread-unread-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 800;
            margin-left: 6px;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

        .thread-expired-badge {
            font-size: 0.68rem;
            padding: 1px 6px;
            border-radius: 4px;
            background: #fef3c7;
            color: #92400e;
            font-weight: 700;
            margin-left: 6px;
        }

        /* Right Panel: Active Conversation */
        .conversation-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        /* Empty State */
        .conversation-empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem;
            background: #fafbfd;
        }

        .empty-state-icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #ecfdf5;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        .conversation-empty-state h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 0;
        }

        .conversation-empty-state p {
            max-width: 440px;
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.5;
            margin: 0 0 20px 0;
        }

        .empty-stats-row {
            display: flex;
            gap: 14px;
        }

        .stat-badge-chip {
            background: #ffffff;
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.84rem;
            color: #334155;
            box-shadow: var(--shadow-subtle);
        }

        .stat-badge-chip strong {
            color: var(--primary);
            font-weight: 800;
        }

        /* Active Conversation Header (Matched to exact layout) */
        .conversation-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            z-index: 10;
            gap: 16px;
        }

        .active-user-meta {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .active-user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #137547 0%, #093d25 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
            flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(19, 117, 71, 0.2);
        }

        .active-user-text {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .active-user-text h3 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
            color: #093d25;
            line-height: 1.2;
        }

        .active-badges-row-1 {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 2px;
        }

        .badge-citizen-inquirer {
            background: #e8f5ee;
            color: #137547;
            border: 1px solid #d1fae5;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-citizen-inquirer .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #137547;
            display: inline-block;
        }

        .badge-service-topic {
            background: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
            padding: 2px 10px;
            border-radius: 6px;
            font-size: 0.76rem;
            font-weight: 700;
            display: inline-block;
        }

        .active-badges-row-2 {
            display: flex;
            align-items: center;
            margin-top: 3px;
        }

        .badge-verified-email {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 3px 10px;
            font-size: 0.78rem;
            color: #334155;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-verified-email i.bi-envelope {
            color: #64748b;
        }

        .badge-verified-email .verified-tag {
            color: #137547;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-left: 4px;
        }

        .conversation-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-refresh-pill {
            background: #ffffff;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-refresh-pill:hover {
            background: #f8fafc;
            color: #093d25;
            border-color: #cbd5e1;
        }

        /* Message Scroll Stream Area (Clean Chat Stream matching UI) */
        .messages-scroll-area {
            flex: 1;
            overflow-y: auto;
            padding: 28px 32px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            background: #ffffff;
        }

        .message-row {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            max-width: 620px;
            animation: fadeInMsg 0.2s ease-out;
        }

        @keyframes fadeInMsg {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message-row.user-message {
            align-self: flex-start;
        }

        .message-row.admin-message {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .msg-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #0284c7;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            margin-top: 20px;
        }

        .user-message .msg-avatar {
            background: #0284c7; /* Matching screenshot teal/cyan or brand emerald */
            color: #ffffff;
        }

        .admin-message .msg-avatar {
            background: #093d25;
            color: #ffc107;
            box-shadow: 0 2px 6px rgba(9, 61, 37, 0.25);
        }

        .msg-bubble-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            max-width: 100%;
        }

        .admin-message .msg-bubble-container {
            align-items: flex-end;
        }

        .msg-sender-name {
            font-size: 0.84rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
            padding-left: 2px;
        }

        .admin-message .msg-sender-name {
            color: #093d25;
            padding-right: 2px;
        }

        .msg-bubble {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 18px 22px;
            font-size: 0.92rem;
            line-height: 1.6;
            color: #1e293b;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            word-break: break-word;
        }

        .admin-message .msg-bubble {
            background: linear-gradient(135deg, #093d25 0%, #137547 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 3px 12px rgba(9, 61, 37, 0.2);
        }

        .bubble-service-tag {
            background: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .admin-message .bubble-service-tag {
            background: rgba(255, 255, 255, 0.15);
            color: #ffc107;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .msg-text-content {
            white-space: pre-wrap;
            margin-bottom: 4px;
        }

        .msg-timestamp {
            font-size: 0.74rem;
            color: #94a3b8;
            font-weight: 500;
            margin-top: 6px;
            padding-left: 4px;
        }

        .admin-message .msg-timestamp {
            text-align: right;
            padding-right: 4px;
        }

        /* Attachments Styling */
        .attachment-wrap {
            margin-top: 10px;
        }

        .attachment-image-thumb {
            max-width: 260px;
            max-height: 200px;
            border-radius: var(--radius-sm);
            border: 2px solid rgba(255,255,255,0.4);
            cursor: pointer;
            transition: all 0.2s ease;
            display: block;
            object-fit: cover;
        }

        .user-message .attachment-image-thumb {
            border-color: #e2e8f0;
        }

        .attachment-image-thumb:hover {
            transform: scale(1.02);
            box-shadow: var(--shadow-elevated);
        }

        .attachment-doc-card {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            transition: all 0.2s ease;
            max-width: 320px;
        }

        .user-message .attachment-doc-card {
            background: #f1f5f9;
            color: #0f172a;
            border: 1px solid #cbd5e1;
        }

        .user-message .attachment-doc-card:hover {
            background: #e2e8f0;
        }

        .admin-message .attachment-doc-card {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .admin-message .attachment-doc-card:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .attachment-doc-icon {
            font-size: 1.5rem;
            color: #ef4444;
        }

        .attachment-doc-details {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .attachment-doc-name {
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .attachment-doc-size {
            font-size: 0.7rem;
            opacity: 0.75;
        }

        /* Official Resident Response Bottom Bar (Matched to exact screenshot) */
        .official-response-bar {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 18px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.02);
            z-index: 10;
        }

        .official-response-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
            min-width: 0;
        }

        .official-response-icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #000000;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        .official-response-text {
            min-width: 0;
        }

        .official-response-text h4 {
            margin: 0 0 3px 0;
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
        }

        .official-response-text p {
            margin: 0;
            font-size: 0.82rem;
            color: #64748b;
            line-height: 1.4;
        }

        .official-response-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-left: auto;
            flex-shrink: 0;
        }

        .btn-send-response-gmail {
            background: #000000;
            color: #ffffff !important;
            padding: 12px 26px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.92rem;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            text-decoration: none !important;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-send-response-gmail:hover {
            background: #093d25;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(9, 61, 37, 0.25);
        }

        .btn-send-response-gmail i {
            color: #ffffff;
            font-size: 1rem;
        }

        /* 24-Hour Expiration Warning Banner */
        .session-expired-banner {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #fffbeb;
            border-top: 1px solid #fde68a;
            padding: 14px 22px;
            color: #92400e;
            font-size: 0.85rem;
            line-height: 1.45;
        }

        .session-expired-banner i {
            font-size: 1.5rem;
            color: #d97706;
            flex-shrink: 0;
        }

        .session-expired-banner strong {
            display: block;
            color: #78350f;
            font-size: 0.9rem;
            margin-bottom: 2px;
        }

        /* Fullscreen Lightbox Modal */
        .lightbox-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            flex-direction: column;
        }

        .lightbox-content-box {
            position: relative;
            max-width: 90vw;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .lightbox-image {
            max-width: 90vw;
            max-height: 80vh;
            border-radius: var(--radius-sm);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5);
            object-fit: contain;
        }

        .lightbox-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 14px;
        }

        .lightbox-btn {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.84rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .lightbox-btn:hover {
            background: rgba(255, 255, 255, 0.35);
            color: #ffffff;
        }

        .lightbox-close-btn {
            position: absolute;
            top: -40px;
            right: 0;
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 2rem;
            cursor: pointer;
            line-height: 1;
        }

        /* Floating Toast Container */
        .portal-toast-stack {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9990;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .portal-toast {
            pointer-events: auto;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 12px 18px;
            box-shadow: var(--shadow-elevated);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.86rem;
            color: #0f172a;
            min-width: 280px;
            max-width: 380px;
            animation: slideInToast 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideInToast {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .portal-toast.toast-success {
            border-left: 4px solid #10b981;
        }

        .portal-toast.toast-info {
            border-left: 4px solid #3b82f6;
        }

        .portal-toast.toast-error {
            border-left: 4px solid #ef4444;
        }

        /* Notifications Dropdown */
        .notif-menu-container {
            position: relative;
        }

        .notif-dropdown-box {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 320px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-elevated);
            z-index: 100;
            display: none;
            overflow: hidden;
        }

        .notif-dropdown-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            background: #f8fafc;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .notif-dropdown-items {
            max-height: 320px;
            overflow-y: auto;
        }

        .notif-item-row {
            padding: 10px 14px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .notif-item-row:hover {
            background: #f8fafc;
        }

        /* Mobile Messenger View Switching */
        .btn-mobile-back-threads {
            display: none;
            background: #e8f5ee;
            border: 1px solid #d1fae5;
            color: #093d25;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
            flex-shrink: 0;
            margin-right: 4px;
            transition: all 0.2s ease;
        }

        .btn-mobile-back-threads:hover {
            background: #137547;
            color: #ffffff;
        }

        /* Responsive Breakpoints */
        @media (max-width: 960px) {
            .messenger-layout {
                position: relative;
                height: calc(100vh - 120px) !important;
                max-height: none !important;
                overflow: hidden !important;
            }

            .threads-panel {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 100% !important;
                height: 100% !important;
                border-right: none !important;
                display: flex !important;
            }

            .conversation-panel {
                width: 100% !important;
                height: 100% !important;
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                background: #ffffff !important;
                display: none !important;
                z-index: 20 !important;
            }

            /* When showing conversation on mobile */
            .messenger-layout.show-conversation .threads-panel {
                display: none !important;
            }

            .messenger-layout.show-conversation .conversation-panel {
                display: flex !important;
            }

            .btn-mobile-back-threads {
                display: inline-flex !important;
            }

            .conversation-header {
                padding: 12px 14px !important;
                gap: 8px !important;
            }

            .active-user-meta {
                gap: 8px !important;
                min-width: 0 !important;
            }

            .active-user-avatar {
                width: 38px !important;
                height: 38px !important;
                font-size: 1rem !important;
            }

            .active-user-text h3 {
                font-size: 0.95rem !important;
            }

            .active-badges-row-1 {
                gap: 4px !important;
            }

            .badge-citizen-inquirer,
            .badge-service-topic {
                font-size: 0.68rem !important;
                padding: 2px 6px !important;
            }

            .badge-verified-email {
                font-size: 0.7rem !important;
                padding: 2px 6px !important;
                max-width: 100% !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            .messages-scroll-area {
                padding: 16px 12px !important;
                gap: 16px !important;
            }

            .message-row {
                max-width: 92% !important;
                gap: 8px !important;
            }

            .msg-avatar {
                width: 32px !important;
                height: 32px !important;
                font-size: 0.85rem !important;
                margin-top: 16px !important;
            }

            .msg-bubble {
                padding: 12px 14px !important;
                font-size: 0.88rem !important;
                border-radius: 14px !important;
            }

            .official-response-bar {
                padding: 12px 14px !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 10px !important;
            }

            .official-response-left {
                width: 100% !important;
                gap: 10px !important;
            }

            .official-response-icon-circle {
                width: 36px !important;
                height: 36px !important;
                font-size: 1rem !important;
            }

            .official-response-text h4 {
                font-size: 0.9rem !important;
            }

            .official-response-text p {
                font-size: 0.75rem !important;
            }

            .official-response-right {
                width: 100% !important;
                justify-content: stretch !important;
                margin-left: 0 !important;
            }

            .btn-send-response-gmail {
                width: 100% !important;
                justify-content: center !important;
                padding: 10px 16px !important;
                font-size: 0.88rem !important;
            }

            .topbar {
                padding: 10px 14px !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 10px !important;
            }

            .topbar-title {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
            }

            .topbar-title h2 {
                font-size: 1.15rem !important;
            }

            .header-actions {
                flex-wrap: wrap !important;
                gap: 6px !important;
            }

            .btn-header-pill span {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Admin Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo">
            <h3>Admin Panel</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
            <li><a href="about.php"><i class="bi bi-info-circle-fill"></i> About</a></li>
            <li class="has-submenu">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-people-fill"></i> Officials
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="barangayofficials.php">Barangay Officials</a></li>
                    <li><a href="SKofficials.php">SK Officials</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-geo-alt-fill"></i> Spot Map
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="legend.php">Legend</a></li>
                    <li><a href="purok 1.php">Purok 1</a></li>
                    <li><a href="purok 2.php">Purok 2</a></li>
                    <li><a href="purok 3.php">Purok 3</a></li>
                    <li><a href="purok 4.php">Purok 4</a></li>
                    <li><a href="purok 5.php">Purok 5</a></li>
                    <li><a href="purok 6.php">Purok 6</a></li>
                    <li><a href="purok 7.php">Purok 7</a></li>
                    <li><a href="purok 8.php">Purok 8</a></li>
                    <li><a href="purok 9.php">Purok 9</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-house-door-fill"></i> Households
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="legends file.php">Legends File</a></li>
                    <li><a href="resident 1.php">Resident 1</a></li>
                    <li><a href="resident 2.php">Resident 2</a></li>
                    <li><a href="resident 3.php">Resident 3</a></li>
                    <li><a href="resident 4.php">Resident 4</a></li>
                    <li><a href="resident 5.php">Resident 5</a></li>
                    <li><a href="resident 6.php">Resident 6</a></li>
                    <li><a href="resident 7.php">Resident 7</a></li>
                    <li><a href="resident 8.php">Resident 8</a></li>
                    <li><a href="resident 9.php">Resident 9</a></li>
                </ul>
            </li>
            <li><a href="mediagallery.php"><i class="bi bi-image-fill"></i> Media Gallery</a></li>
            <li><a href="services.php"><i class="bi bi-card-list"></i> Services</a></li>
            <li><a href="announcements.php"><i class="bi bi-megaphone-fill"></i> Announcements</a></li>
            <li>
                <a href="feedback.php" class="active">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span>Feedback &amp; Inquiries</span>
                    <span class="sidebar-badge" id="nav-inquiries-badge" style="display:none;">0</span>
                </a>
            </li>
            <li><a href="settings.php"><i class="bi bi-gear-fill"></i> Settings</a></li>
            <li><a href="logout.php" class="logout-link" style="color: #ef4444 !important;"><i class="bi bi-box-arrow-left" style="color: #ef4444 !important;"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="sidebar-overlay-backdrop" id="sidebar-overlay-backdrop"></div>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-title">
                <button type="button" class="admin-mobile-toggle-btn" id="btn-admin-mobile-sidebar-toggle" aria-label="Toggle Sidebar Menu">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h2><i class="bi bi-chat-left-text-fill"></i> Feedback &amp; Inquiries</h2>
                    <p style="margin:0; font-size:0.76rem; color:#64748b;">Live Citizen Inquiry &amp; Two-Way Official Messaging Portal</p>
                </div>
            </div>
            
            <div class="header-actions">
                <!-- Sound Alert Toggle -->
                <button type="button" class="btn-header-pill active-sound" id="btn-sound-toggle" title="Toggle audio chime on new resident messages">
                    <i class="bi bi-volume-up-fill" id="sound-icon"></i>
                    <span id="sound-label">Sound On</span>
                </button>

                <!-- Desktop Notification Toggle -->
                <button type="button" class="btn-header-pill" id="btn-desktop-notif-toggle" title="Enable browser push notifications">
                    <i class="bi bi-bell-fill"></i>
                    <span>Desktop Alerts</span>
                </button>

                <!-- Mark All As Read -->
                <button type="button" class="btn-header-pill btn-mark-read-all" id="btn-mark-all-read" title="Mark all unread resident inquiries as read">
                    <i class="bi bi-check2-all"></i>
                    <span>Mark All as Read</span>
                    <span class="unread-indicator-badge" id="header-unread-count" style="display:none;">0</span>
                </button>

                <!-- Bell Notifications Dropdown -->
                <div class="notif-menu-container">
                    <button type="button" class="btn-header-pill" id="btn-notif-bell" title="Latest Inquiries">
                        <i class="bi bi-inbox-fill"></i>
                        <span class="unread-indicator-badge" id="bell-unread-badge" style="display:none;">0</span>
                    </button>
                    <div class="notif-dropdown-box" id="notif-dropdown">
                        <div class="notif-dropdown-header">
                            <span>Recent Unread Messages</span>
                            <span id="notif-dropdown-counter" class="unread-indicator-badge" style="background:#093d25;">0 New</span>
                        </div>
                        <div class="notif-dropdown-items" id="notif-dropdown-list">
                            <div style="padding:16px; text-align:center; color:#94a3b8; font-size:0.82rem;">No unread messages</div>
                        </div>
                    </div>
                </div>

                <!-- Admin Profile -->
                <div style="display:flex; align-items:center; gap:8px; margin-left:6px;">
                    <div style="width:36px; height:36px; border-radius:50%; background:#093d25; color:#ffc107; font-weight:700; display:flex; align-items:center; justify-content:center;">
                        <?= $admin_initial ?>
                    </div>
                    <div style="display:flex; flex-direction:column; line-height:1.2;">
                        <span style="font-size:0.84rem; font-weight:700; color:#0f172a;"><?= htmlspecialchars($admin_name) ?></span>
                        <span style="font-size:0.72rem; color:#64748b;"><?= htmlspecialchars($admin_role) ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Split-Screen Messenger Layout -->
        <main class="messenger-layout">
            
            <!-- Left Panel: Thread / Inquiry List -->
            <section class="threads-panel">
                <div class="threads-header">
                    <div class="threads-title-row">
                        <h3>
                            <i class="bi bi-inboxes-fill" style="color:var(--primary);"></i> Inquiries
                            <span class="threads-count-badge" id="total-threads-pill">0</span>
                        </h3>
                    </div>

                    <!-- Search Input -->
                    <div class="threads-search-box">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="threads-search-input" id="search-threads-input" placeholder="Search by name, email, or message...">
                        <button type="button" class="threads-search-clear" id="clear-search-btn" title="Clear search">&times;</button>
                    </div>

                    <!-- Filter Tabs: All vs Unread -->
                    <div class="threads-filter-tabs">
                        <button type="button" class="filter-tab-btn active" id="tab-all-inquiries" data-tab="all">
                            All Inquiries
                        </button>
                        <button type="button" class="filter-tab-btn" id="tab-unread-inquiries" data-tab="unread">
                            Unread (<span id="unread-tab-count">0</span>)
                        </button>
                    </div>
                </div>

                <!-- Scrollable Threads List -->
                <div class="threads-scroll-list" id="threads-container">
                    <div style="padding:28px 16px; text-align:center; color:#94a3b8; font-size:0.85rem;">
                        <i class="bi bi-arrow-repeat" style="font-size:1.5rem; display:block; margin-bottom:8px; animation:spin 1s linear infinite;"></i>
                        Loading inquiries...
                    </div>
                </div>
            </section>

            <!-- Right Panel: Conversation Stream & Reply Box -->
            <section class="conversation-panel">
                
                <!-- Empty State (When no inquiry is selected) -->
                <div class="conversation-empty-state" id="conversation-empty-state">
                    <div class="empty-state-icon-circle">
                        <i class="bi bi-chat-square-quote-fill"></i>
                    </div>
                    <h3>Select an Inquiry</h3>
                    <p>Select an inquiry from the list on the left to read their message history and send official replies.</p>
                    <div class="empty-stats-row">
                        <div class="stat-badge-chip">Total Inquiries: <strong id="stat-total-threads">0</strong></div>
                        <div class="stat-badge-chip">Unread Messages: <strong id="stat-unread-count" style="color:var(--danger);">0</strong></div>
                    </div>
                </div>

                <!-- Active Conversation Container (Hidden until thread selected) -->
                <div id="active-conversation-wrapper" style="display:none; flex-direction:column; height:100%; width:100%;">
                    
                    <!-- Thread Header matching screenshot -->
                    <header class="conversation-header">
                        <button type="button" class="btn-mobile-back-threads" id="btn-mobile-back-threads" title="Back to inquiries list">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <div class="active-user-meta">
                            <div class="active-user-avatar" id="active-resident-avatar">R</div>
                            <div class="active-user-text">
                                <h3 id="active-resident-name">Resident Name</h3>
                                <div class="active-badges-row-1">
                                    <span class="badge-citizen-inquirer">
                                        <span class="status-dot"></span> Citizen Inquirer
                                    </span>
                                    <span class="badge-service-topic" id="active-service-tag">General Inquiry</span>
                                </div>
                                <div class="active-badges-row-2">
                                    <span class="badge-verified-email" id="active-verified-email-badge" style="display:none;">
                                        <i class="bi bi-envelope"></i>
                                        <span id="active-email-text"></span>
                                        <span class="verified-tag"><i class="bi bi-shield-check"></i> Verified</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="conversation-header-actions">
                            <button type="button" class="btn-refresh-pill" id="btn-refresh-conversation" title="Refresh conversation history">
                                <i class="bi bi-arrow-clockwise"></i> <span>Refresh</span>
                            </button>
                        </div>
                    </header>

                    <!-- Messages Stream Area -->
                    <div class="messages-scroll-area" id="messages-container">
                        <!-- Populated dynamically via JS -->
                    </div>

                    <!-- Bottom Official Resident Response Bar matching screenshot -->
                    <div class="official-response-bar" id="official-response-bar" style="display:none;">
                        <div class="official-response-left">
                            <div class="official-response-icon-circle">
                                <i class="bi bi-envelope-check-fill"></i>
                            </div>
                            <div class="official-response-text">
                                <h4>Official Resident Response</h4>
                                <p>Resident inquiries and document requests are responded to directly via verified Gmail.</p>
                            </div>
                        </div>
                        <div class="official-response-right">
                            <a href="#" target="_blank" class="btn-send-response-gmail" id="btn-send-response-gmail" title="Send official response to resident via Gmail">
                                <i class="bi bi-envelope-fill"></i>
                                <span>Send Response via Gmail</span>
                            </a>
                        </div>
                    </div>

                </div>

            </section>
        </main>
    </div>

    <!-- Fullscreen Lightbox Modal for Image Attachments -->
    <div class="lightbox-modal" id="image-lightbox-modal">
        <div class="lightbox-content-box">
            <button type="button" class="lightbox-close-btn" id="lightbox-close-btn" title="Close preview">&times;</button>
            <img src="" alt="Full view preview" class="lightbox-image" id="lightbox-target-img">
            <div class="lightbox-toolbar">
                <a href="" download class="lightbox-btn" id="lightbox-download-link">
                    <i class="bi bi-download"></i> Download Full Image
                </a>
            </div>
        </div>
    </div>

    <!-- Floating Toast Notification Stack -->
    <div class="portal-toast-stack" id="toast-stack"></div>

    <!-- jQuery 3.6+ -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Messaging Portal Engine -->
    <script>
    $(document).ready(function() {
        // State Management
        let activeThreadId = null;
        let cachedThreads = [];
        let currentTabFilter = 'all'; // 'all' or 'unread'
        let currentSearchQuery = '';
        let soundEnabled = (localStorage.getItem('maruing_inquiry_sound') !== 'false');
        let highestSeenMsgId = null;
        let isInitialLoad = true;
        let isSending = false;
        let lastRenderedMessagesJson = '';

        // Synthesized Web Audio API Alert Chime
        function playChimeSound() {
            if (!soundEnabled) return;
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const now = ctx.currentTime;

                // High pleasant two-tone chime (F5 -> C6)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(698.46, now); // F5
                gain1.gain.setValueAtTime(0.2, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.3);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.3);

                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(1046.50, now + 0.1); // C6
                gain2.gain.setValueAtTime(0.25, now + 0.1);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now + 0.1);
                osc2.stop(now + 0.5);
            } catch(e) {
                console.log("Audio chime note:", e);
            }
        }

        // Sound Toggle Handler
        function updateSoundButtonUI() {
            if (soundEnabled) {
                $('#btn-sound-toggle').removeClass('muted-sound').addClass('active-sound');
                $('#sound-icon').attr('class', 'bi bi-volume-up-fill');
                $('#sound-label').text('Sound On');
            } else {
                $('#btn-sound-toggle').removeClass('active-sound').addClass('muted-sound');
                $('#sound-icon').attr('class', 'bi bi-volume-mute-fill');
                $('#sound-label').text('Sound Muted');
            }
        }
        updateSoundButtonUI();

        $('#btn-sound-toggle').on('click', function() {
            soundEnabled = !soundEnabled;
            localStorage.setItem('maruing_inquiry_sound', soundEnabled ? 'true' : 'false');
            updateSoundButtonUI();
            if (soundEnabled) playChimeSound();
        });

        // HTML5 Desktop Push Notification Request
        $('#btn-desktop-notif-toggle').on('click', function() {
            if ("Notification" in window) {
                Notification.requestPermission().then(function(perm) {
                    if (perm === 'granted') {
                        showToast('Notifications Enabled', 'You will receive desktop alerts when residents reach out.', 'success');
                        new Notification("Barangay Maruing Portal", {
                            body: "Desktop notifications are now active for resident inquiries.",
                            icon: "../public/image/Maruing Logo 2.png"
                        });
                    } else {
                        showToast('Notification Status', 'Permission was: ' + perm, 'info');
                    }
                });
            } else {
                showToast('Not Supported', 'Desktop notifications are not supported in this browser.', 'error');
            }
        });

        // In-App Toast System
        function showToast(title, message, type = 'info') {
            const iconMap = {
                'success': 'bi-check-circle-fill text-success',
                'error': 'bi-exclamation-triangle-fill text-danger',
                'info': 'bi-info-circle-fill text-primary'
            };
            const icon = iconMap[type] || 'bi-info-circle-fill';
            const toast = $(`
                <div class="portal-toast toast-${type}">
                    <i class="bi ${icon}" style="font-size:1.3rem;"></i>
                    <div style="flex:1;">
                        <strong style="display:block; font-size:0.86rem; color:#0f172a;">${title}</strong>
                        <span style="font-size:0.78rem; color:#64748b;">${message}</span>
                    </div>
                </div>
            `);
            $('#toast-stack').append(toast);
            setTimeout(function() {
                toast.fadeOut(300, function() { $(this).remove(); });
            }, 4500);
        }

        // Notification Bell Dropdown Toggle
        $('#btn-notif-bell').on('click', function(e) {
            e.stopPropagation();
            $('#notif-dropdown').fadeToggle(150);
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.notif-menu-container').length) {
                $('#notif-dropdown').fadeOut(150);
            }
        });

        // Mark All Read API
        $('#btn-mark-all-read').on('click', function() {
            $.post('api/admin_mark_all_read.php', function(res) {
                if (res && res.status === 'success') {
                    showToast('Inquiries Updated', 'All resident messages marked as read.', 'success');
                    fetchThreads();
                    if (activeThreadId) fetchMessages(activeThreadId, false);
                }
            });
        });

        // Filter Tabs
        $('.filter-tab-btn').on('click', function() {
            $('.filter-tab-btn').removeClass('active');
            $(this).addClass('active');
            currentTabFilter = $(this).data('tab');
            renderThreadsList();
        });

        // Live Search Input
        $('#search-threads-input').on('input', function() {
            currentSearchQuery = $(this).val().toLowerCase().trim();
            if (currentSearchQuery.length > 0) {
                $('#clear-search-btn').show();
            } else {
                $('#clear-search-btn').hide();
            }
            renderThreadsList();
        });

        $('#clear-search-btn').on('click', function() {
            $('#search-threads-input').val('');
            currentSearchQuery = '';
            $(this).hide();
            renderThreadsList();
        });

        // Fullscreen Lightbox Handlers
        $(document).on('click', '.attachment-image-thumb', function() {
            const fullSrc = $(this).attr('data-full-src') || $(this).attr('src');
            $('#lightbox-target-img').attr('src', fullSrc);
            $('#lightbox-download-link').attr('href', fullSrc);
            $('#image-lightbox-modal').fadeIn(200).css('display', 'flex');
        });

        $('#lightbox-close-btn, #image-lightbox-modal').on('click', function(e) {
            if (e.target === this || e.target.id === 'lightbox-close-btn') {
                $('#image-lightbox-modal').fadeOut(200);
            }
        });

        // Fetch Inquiries / Threads List (Background Polling)
        function fetchThreads() {
            $.get('api/admin_fetch_sessions.php', function(res) {
                if (res && res.status === 'success') {
                    cachedThreads = res.sessions || [];
                    
                    // Update Unread Counters
                    const totalUnread = res.total_unread || 0;
                    $('#total-threads-pill').text(cachedThreads.length);
                    $('#stat-total-threads').text(cachedThreads.length);
                    $('#stat-unread-count').text(totalUnread);
                    $('#unread-tab-count').text(totalUnread);

                    if (totalUnread > 0) {
                        $('#header-unread-count').text(totalUnread).show();
                        $('#bell-unread-badge').text(totalUnread).show();
                        $('#nav-inquiries-badge').text(totalUnread).show();
                        $('#notif-dropdown-counter').text(totalUnread + ' New');
                    } else {
                        $('#header-unread-count').hide();
                        $('#bell-unread-badge').hide();
                        $('#nav-inquiries-badge').hide();
                        $('#notif-dropdown-counter').text('0 New');
                    }

                    // Render Notifications Dropdown
                    if (res.notifications && res.notifications.length > 0) {
                        let notifHtml = '';
                        res.notifications.forEach(n => {
                            notifHtml += `
                                <div class="notif-item-row" data-thread-id="${n.session_id}">
                                    <div style="display:flex; justify-content:space-between; margin-bottom:2px;">
                                        <strong style="font-size:0.82rem; color:#0f172a;">${n.user_name}</strong>
                                        <span style="font-size:0.7rem; color:#94a3b8;">${n.time}</span>
                                    </div>
                                    <div style="font-size:0.76rem; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        ${n.message}
                                    </div>
                                </div>
                            `;
                        });
                        $('#notif-dropdown-list').html(notifHtml);
                    } else {
                        $('#notif-dropdown-list').html('<div style="padding:16px; text-align:center; color:#94a3b8; font-size:0.82rem;">No unread messages</div>');
                    }

                    // Check for new incoming resident messages (Play chime & desktop notification)
                    if (res.latest_message) {
                        const lm = res.latest_message;
                        if (isInitialLoad) {
                            highestSeenMsgId = lm.id;
                            isInitialLoad = false;
                        } else if (highestSeenMsgId && lm.id > highestSeenMsgId) {
                            highestSeenMsgId = lm.id;
                            playChimeSound();

                            // Trigger Desktop Notification
                            if ("Notification" in window && Notification.permission === "granted") {
                                new Notification(`New Inquiry: ${lm.user_name}`, {
                                    body: lm.message || "Sent an inquiry to Barangay Maruing",
                                    icon: "../public/image/Maruing Logo 2.png"
                                });
                            }
                            showToast(`New Inquiry: ${lm.user_name}`, lm.message, 'info');

                            // If this thread is active, refresh stream immediately
                            if (activeThreadId === lm.session_id) {
                                fetchMessages(activeThreadId, true);
                            }
                        }
                    }

                    renderThreadsList();
                }
            });
        }

        // Render Threads List with Filters and Search
        function renderThreadsList() {
            let filtered = cachedThreads.slice();

            // Apply Tab Filter (Unread only)
            if (currentTabFilter === 'unread') {
                filtered = filtered.filter(t => t.unread_count > 0);
            }

            // Apply Search Query
            if (currentSearchQuery) {
                filtered = filtered.filter(t => {
                    const name = (t.user_name || '').toLowerCase();
                    const email = (t.email || '').toLowerCase();
                    const msg = (t.last_msg || '').toLowerCase();
                    const service = (t.service_type || '').toLowerCase();
                    return name.includes(currentSearchQuery) || 
                           email.includes(currentSearchQuery) || 
                           msg.includes(currentSearchQuery) ||
                           service.includes(currentSearchQuery);
                });
            }

            if (filtered.length === 0) {
                $('#threads-container').html(`
                    <div style="padding:32px 16px; text-align:center; color:#94a3b8; font-size:0.85rem;">
                        <i class="bi bi-chat-dots" style="font-size:2rem; display:block; margin-bottom:8px; opacity:0.6;"></i>
                        No inquiries matching your criteria.
                    </div>
                `);
                return;
            }

            let html = '';
            filtered.forEach(t => {
                const isActive = (t.id === activeThreadId) ? 'active' : '';
                const initial = (t.user_name || 'R').charAt(0).toUpperCase();
                const unreadPill = (t.unread_count > 0) ? `<span class="thread-unread-pill">${t.unread_count}</span>` : '';
                const expiredBadge = t.is_expired ? `<span class="thread-expired-badge">Expired</span>` : '';

                html += `
                    <div class="thread-item ${isActive}" data-thread-id="${t.id}" data-name="${t.user_name}" data-email="${t.email || ''}" data-service="${t.service_type || 'General Inquiry'}">
                        <div class="thread-avatar">${initial}</div>
                        <div class="thread-content">
                            <div class="thread-top-row">
                                <span class="thread-citizen-name">${t.user_name}</span>
                                <span class="thread-timestamp">${t.time_ago || t.time}</span>
                            </div>
                            <div>
                                <span class="thread-service-tag">${t.service_type || 'General Inquiry'}</span>
                                ${expiredBadge}
                                ${unreadPill}
                            </div>
                            <div class="thread-snippet">${t.last_msg || 'No messages yet'}</div>
                        </div>
                    </div>
                `;
            });

            $('#threads-container').html(html);
        }

        // Thread Item Click Handler
        $(document).on('click', '.thread-item, .notif-item-row', function() {
            const threadId = $(this).data('thread-id');
            if (!threadId) return;
            selectThread(threadId);
            $('#notif-dropdown').fadeOut(150);
        });

        // Select and Open a Thread
        function selectThread(threadId) {
            activeThreadId = threadId;
            $('.thread-item').removeClass('active');
            $(`.thread-item[data-thread-id="${threadId}"]`).addClass('active');

            // Show active conversation wrapper, hide empty state
            $('#conversation-empty-state').hide();
            $('#active-conversation-wrapper').css('display', 'flex');
            $('.messenger-layout').addClass('show-conversation');

            fetchMessages(threadId, true);
        }

        // Fetch Messages for Selected Thread
        function fetchMessages(threadId, forceScroll = false) {
            if (!threadId) return;

            $.get('api/admin_fetch_messages.php', { thread_id: threadId }, function(res) {
                if (res && res.status === 'success') {
                    // Update Header Meta matching screenshot
                    const residentName = res.resident_name || 'Resident';
                    const serviceType = res.service_type || 'General Inquiry';
                    currentServiceType = serviceType;

                    $('#active-resident-name').text(residentName);
                    $('#active-resident-avatar').text(residentName.charAt(0).toUpperCase());
                    $('#active-service-tag').text(serviceType);
                    
                    if (res.resident_email) {
                        const email = res.resident_email;
                        
                        // 1. General Official Response Gmail URL
                        const responseSubject = `Barangay Maruing Official Response: ${serviceType}`;
                        const responseBody = `Dear ${residentName},\n\nThank you for reaching out to Barangay Maruing regarding your inquiry on "${serviceType}".\n\n\n\nBest regards,\nBarangay Maruing Administration\nLapuyan, Zamboanga del Sur`;
                        const officialSender = <?= json_encode($official_sender_email) ?>;
                        const responseGmailUrl = `https://mail.google.com/mail/?authuser=${encodeURIComponent(officialSender)}&view=cm&fs=1&to=${encodeURIComponent(email)}&su=${encodeURIComponent(responseSubject)}&body=${encodeURIComponent(responseBody)}`;
                        
                        $('#active-email-text').text(email);
                        $('#active-verified-email-badge').css('display', 'inline-flex');
                        $('#btn-send-response-gmail').attr('href', responseGmailUrl);
                        $('#official-response-bar').css('display', 'flex');
                    } else {
                        $('#active-verified-email-badge').hide();
                        $('#official-response-bar').hide();
                    }

                    // Avoid re-rendering if unchanged
                    const currentJson = JSON.stringify(res.messages || []);
                    if (currentJson === lastRenderedMessagesJson && !forceScroll) {
                        return;
                    }
                    lastRenderedMessagesJson = currentJson;

                    // Render Messages
                    renderMessagesStream(res.messages || [], serviceType, forceScroll);
                }
            });
        }

        // Render Messages Stream in Scroll Area (Matched to exact screenshot UI)
        function renderMessagesStream(messages, serviceType = '', forceScroll = false) {
            const container = $('#messages-container');
            const wasScrolledToBottom = (container[0].scrollHeight - container.scrollTop() - container.outerHeight() < 60);

            if (messages.length === 0) {
                container.html(`
                    <div style="padding:40px; text-align:center; color:#94a3b8; font-size:0.88rem;">
                        <i class="bi bi-chat-text" style="font-size:2rem; display:block; margin-bottom:8px;"></i>
                        No messages in this inquiry thread yet.
                    </div>
                `);
                return;
            }

            let html = '';
            messages.forEach((msg, idx) => {
                const isAdmin = (msg.sender === 'admin');
                const rowClass = isAdmin ? 'admin-message' : 'user-message';
                const initial = (msg.sender_name || (isAdmin ? 'A' : 'R')).charAt(0).toUpperCase();

                // Attachment Rendering
                let attachHtml = '';
                if (msg.file_attachment) {
                    const fileUrl = '../' + msg.file_attachment;
                    if (msg.is_image) {
                        attachHtml = `
                            <div class="attachment-wrap">
                                <img src="${fileUrl}" data-full-src="${fileUrl}" alt="Attached Image" class="attachment-image-thumb" loading="lazy">
                            </div>
                        `;
                    } else {
                        const docIcon = msg.file_name.endsWith('.pdf') ? 'bi-file-earmark-pdf-fill' : 'bi-file-earmark-arrow-down-fill';
                        attachHtml = `
                            <div class="attachment-wrap">
                                <a href="${fileUrl}" download="${msg.file_name}" class="attachment-doc-card">
                                    <i class="bi ${docIcon} attachment-doc-icon"></i>
                                    <div class="attachment-doc-details">
                                        <span class="attachment-doc-name">${msg.file_name}</span>
                                        <span class="attachment-doc-size">${msg.file_size || 'Download Attachment'}</span>
                                    </div>
                                    <i class="bi bi-download" style="margin-left:auto; font-size:1.1rem;"></i>
                                </a>
                            </div>
                        `;
                    }
                }

                // Show service tag badge inside the user's first bubble
                const serviceTagHtml = (!isAdmin && serviceType)
                    ? `<div class="bubble-service-tag"><i class="bi bi-tag-fill"></i> Service: ${escapeHtml(serviceType)}</div>`
                    : '';

                html += `
                    <div class="message-row ${rowClass}">
                        <div class="msg-avatar">${initial}</div>
                        <div class="msg-bubble-container">
                            <div class="msg-sender-name">${msg.sender_name}</div>
                            <div class="msg-bubble">
                                ${serviceTagHtml}
                                <div class="msg-text-content">${escapeHtml(msg.message)}</div>
                                ${attachHtml}
                            </div>
                            <div class="msg-timestamp">${msg.time}</div>
                        </div>
                    </div>
                `;
            });

            container.html(html);

            if (forceScroll || wasScrolledToBottom) {
                container.animate({ scrollTop: container[0].scrollHeight }, 200);
            }
        }

        $('#btn-refresh-conversation').on('click', function() {
            if (activeThreadId) fetchMessages(activeThreadId, true);
        });

        // Mobile Sidebar Drawer Toggle
        $('#btn-admin-mobile-sidebar-toggle').on('click', function(e) {
            e.stopPropagation();
            $('.sidebar').toggleClass('open');
            $('#sidebar-overlay-backdrop').toggleClass('active');
        });

        $('#sidebar-overlay-backdrop').on('click', function() {
            $('.sidebar').removeClass('open');
            $(this).removeClass('active');
        });

        // Mobile Back to Threads List
        $('#btn-mobile-back-threads').on('click', function() {
            $('.messenger-layout').removeClass('show-conversation');
        });

        // Copy Email to Clipboard
        $(document).on('click', '#btn-copy-resident-email', function(e) {
            e.preventDefault();
            const email = $(this).data('email') || $('#gmail-console-email').text();
            if (email) {
                navigator.clipboard.writeText(email).then(function() {
                    showToast('Email Copied', email + ' copied to clipboard.', 'success');
                }).catch(function() {
                    prompt('Copy email address:', email);
                });
            }
        });

        // Helper: Escape HTML
        function escapeHtml(string) {
            return String(string || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
                .replace(/\n/g, '<br>');
        }

        // Initialize First Fetch
        fetchThreads();

        // 4.5-Second Live Polling Loop
        setInterval(function() {
            fetchThreads();
            if (activeThreadId) {
                fetchMessages(activeThreadId, false);
            }
        }, 4500);
    });
    </script>
    <script src="Admin JS/admin.js"></script>
</body>
</html>

