<?php
/**
 * Admin API: Fetch Sessions / Threads
 * Fetches inquiry threads with unread counts, latest snippets, and expiration status.
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

// Auto-create feedback_chats table if not exists
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

// Helper to extract service_type and clean message from raw text
function parse_chat_message($raw) {
    $service_type = 'General Inquiry';
    $clean_msg = $raw;

    if (preg_match('/^\[Service:\s*([^\]]+)\]\s*(.*)$/si', $raw, $matches)) {
        $service_type = trim($matches[1]);
        $clean_msg = trim($matches[2]);
    }

    return [
        'service_type' => $service_type,
        'clean_msg' => $clean_msg
    ];
}

// Helper to format human-friendly relative time (e.g. 5m ago, 2h ago, Yesterday)
function format_time_ago($timestamp_str) {
    $time = strtotime($timestamp_str);
    if (!$time) return '';
    $diff = time() - $time;

    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $m = floor($diff / 60);
        return $m . 'm ago';
    } elseif ($diff < 86400) {
        $h = floor($diff / 3600);
        return $h . 'h ago';
    } elseif ($diff < 172800) {
        return 'Yesterday ' . date('h:i A', $time);
    } elseif ($diff < 604800) {
        $d = floor($diff / 86400);
        return $d . 'd ago';
    } else {
        return date('M d, Y', $time);
    }
}

// 1. Fetch all distinct thread_ids ordered by latest activity
$threads_query = "
    SELECT 
        c.thread_id,
        MIN(c.created_at) as thread_started_at,
        MAX(c.created_at) as last_activity,
        MAX(c.id) as max_id,
        (TIMESTAMPDIFF(SECOND, MIN(c.created_at), NOW()) > 86400) as is_expired
    FROM feedback_chats c
    GROUP BY c.thread_id
    ORDER BY last_activity DESC
";

$result = $conn->query($threads_query);
$sessions = [];

if ($result && $result->num_rows > 0) {
    while ($tRow = $result->fetch_assoc()) {
        $tid = $tRow['thread_id'];

        // Get resident's name, email, and service from earliest user message
        $user_name = 'Resident';
        $user_email = '';
        $service_type = 'General Inquiry';

        $uStmt = $conn->prepare("SELECT sender_name, email, message FROM feedback_chats WHERE thread_id = ? AND sender_type = 'user' ORDER BY id ASC LIMIT 1");
        if ($uStmt) {
            $uStmt->bind_param("s", $tid);
            $uStmt->execute();
            $uRes = $uStmt->get_result();
            if ($uRow = $uRes->fetch_assoc()) {
                if (!empty($uRow['sender_name'])) {
                    $user_name = $uRow['sender_name'];
                }
                if (!empty($uRow['email'])) {
                    $user_email = $uRow['email'];
                }
                $parsed = parse_chat_message($uRow['message']);
                if ($parsed['service_type'] !== 'General Inquiry') {
                    $service_type = $parsed['service_type'];
                }
            }
            $uStmt->close();
        }

        // Also check if any other message in thread has an email recorded
        if (empty($user_email)) {
            $eStmt = $conn->prepare("SELECT email FROM feedback_chats WHERE thread_id = ? AND email IS NOT NULL AND email != '' LIMIT 1");
            if ($eStmt) {
                $eStmt->bind_param("s", $tid);
                $eStmt->execute();
                $eRes = $eStmt->get_result();
                if ($eRow = $eRes->fetch_assoc()) {
                    $user_email = $eRow['email'];
                }
                $eStmt->close();
            }
        }

        // Get last message in this thread
        $last_msg = '';
        $time_str = '';
        $last_msg_id = (int)$tRow['max_id'];
        $last_sender = 'user';

        $lStmt = $conn->prepare("SELECT id, sender_type, message, file_name, created_at FROM feedback_chats WHERE thread_id = ? ORDER BY id DESC LIMIT 1");
        if ($lStmt) {
            $lStmt->bind_param("s", $tid);
            $lStmt->execute();
            $lRes = $lStmt->get_result();
            if ($lRow = $lRes->fetch_assoc()) {
                $last_sender = $lRow['sender_type'];
                $parsedLast = parse_chat_message($lRow['message']);
                $cleanText = !empty($parsedLast['clean_msg']) ? $parsedLast['clean_msg'] : $lRow['message'];
                if (empty($cleanText) && !empty($lRow['file_name'])) {
                    $cleanText = "Attachment: " . $lRow['file_name'];
                }
                $last_msg = $cleanText;
                $time_str = date('h:i A', strtotime($lRow['created_at']));
                $last_msg_id = (int)$lRow['id'];
            }
            $lStmt->close();
        }

        // Count unread user messages in this thread
        $unread_count = 0;
        $unStmt = $conn->prepare("SELECT COUNT(*) as unread FROM feedback_chats WHERE thread_id = ? AND sender_type = 'user' AND is_read = 0");
        if ($unStmt) {
            $unStmt->bind_param("s", $tid);
            $unStmt->execute();
            $unRes = $unStmt->get_result();
            if ($unRow = $unRes->fetch_assoc()) {
                $unread_count = (int)$unRow['unread'];
            }
            $unStmt->close();
        }

        $sessions[] = [
            'id' => $tid,
            'thread_id' => $tid,
            'user_name' => htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'),
            'service_type' => htmlspecialchars($service_type, ENT_QUOTES, 'UTF-8'),
            'created_at' => $tRow['thread_started_at'],
            'last_activity' => $tRow['last_activity'],
            'time' => $time_str,
            'time_ago' => format_time_ago($tRow['last_activity']),
            'last_msg' => htmlspecialchars($last_msg, ENT_QUOTES, 'UTF-8'),
            'last_msg_id' => $last_msg_id,
            'last_sender' => $last_sender,
            'unread_count' => $unread_count,
            'is_expired' => !empty($tRow['is_expired'])
        ];
    }
}

// 2. Total unread user messages
$total_unread = 0;
$unreadRes = $conn->query("SELECT COUNT(*) as total_unread FROM feedback_chats WHERE sender_type = 'user' AND is_read = 0");
if ($unreadRes && $uRow = $unreadRes->fetch_assoc()) {
    $total_unread = (int)$uRow['total_unread'];
}

// 3. Latest incoming user message across system
$latest_message = null;
$latestRes = $conn->query("SELECT id, thread_id, sender_name, message, file_name, created_at FROM feedback_chats WHERE sender_type = 'user' ORDER BY id DESC LIMIT 1");
if ($latestRes && $lRow = $latestRes->fetch_assoc()) {
    $parsedL = parse_chat_message($lRow['message']);
    $msgContent = !empty($parsedL['clean_msg']) ? $parsedL['clean_msg'] : $lRow['message'];
    if (empty($msgContent) && !empty($lRow['file_name'])) {
        $msgContent = "Attachment: " . $lRow['file_name'];
    }
    $latest_message = [
        'id' => (int)$lRow['id'],
        'session_id' => $lRow['thread_id'],
        'thread_id' => $lRow['thread_id'],
        'user_name' => htmlspecialchars($lRow['sender_name'], ENT_QUOTES, 'UTF-8'),
        'service_type' => htmlspecialchars($parsedL['service_type'], ENT_QUOTES, 'UTF-8'),
        'message' => htmlspecialchars($msgContent, ENT_QUOTES, 'UTF-8'),
        'time' => date('h:i A', strtotime($lRow['created_at'])),
        'time_ago' => format_time_ago($lRow['created_at'])
    ];
}

// 4. Notifications for bell dropdown (top 10 unread user messages)
$notifications = [];
$notifRes = $conn->query("SELECT id, thread_id, sender_name, message, file_name, created_at FROM feedback_chats WHERE sender_type = 'user' AND is_read = 0 ORDER BY id DESC LIMIT 10");
if ($notifRes) {
    while ($nRow = $notifRes->fetch_assoc()) {
        $parsedN = parse_chat_message($nRow['message']);
        $msgSnippet = !empty($parsedN['clean_msg']) ? $parsedN['clean_msg'] : $nRow['message'];
        if (empty($msgSnippet) && !empty($nRow['file_name'])) {
            $msgSnippet = "Attachment: " . $nRow['file_name'];
        }
        $notifications[] = [
            'msg_id' => (int)$nRow['id'],
            'session_id' => $nRow['thread_id'],
            'thread_id' => $nRow['thread_id'],
            'user_name' => htmlspecialchars($nRow['sender_name'], ENT_QUOTES, 'UTF-8'),
            'service_type' => htmlspecialchars($parsedN['service_type'], ENT_QUOTES, 'UTF-8'),
            'message' => htmlspecialchars($msgSnippet, ENT_QUOTES, 'UTF-8'),
            'time' => date('h:i A', strtotime($nRow['created_at'])),
            'time_ago' => format_time_ago($nRow['created_at'])
        ];
    }
}

echo json_encode([
    'status' => 'success',
    'sessions' => $sessions,
    'total_unread' => $total_unread,
    'latest_message' => $latest_message,
    'notifications' => $notifications
]);
