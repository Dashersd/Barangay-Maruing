<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../admin/db_connect.php';

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

// 1. Fetch all distinct thread_ids ordered by latest activity
$threads_query = "
    SELECT 
        c.thread_id,
        MAX(c.created_at) as last_activity,
        MAX(c.id) as max_id
    FROM feedback_chats c
    GROUP BY c.thread_id
    ORDER BY last_activity DESC
";

$result = $conn->query($threads_query);
$sessions = [];

if ($result && $result->num_rows > 0) {
    while ($tRow = $result->fetch_assoc()) {
        $tid = $tRow['thread_id'];

        // Get resident's name and service from earliest user message
        $user_name = 'Resident';
        $service_type = 'General Inquiry';

        $uStmt = $conn->prepare("SELECT sender_name, message FROM feedback_chats WHERE thread_id = ? AND sender_type = 'user' ORDER BY id ASC LIMIT 1");
        if ($uStmt) {
            $uStmt->bind_param("s", $tid);
            $uStmt->execute();
            $uRes = $uStmt->get_result();
            if ($uRow = $uRes->fetch_assoc()) {
                if (!empty($uRow['sender_name'])) {
                    $user_name = $uRow['sender_name'];
                }
                $parsed = parse_chat_message($uRow['message']);
                if ($parsed['service_type'] !== 'General Inquiry') {
                    $service_type = $parsed['service_type'];
                }
            }
            $uStmt->close();
        }

        // Get last message in this thread
        $last_msg = '';
        $time_str = '';
        $last_msg_id = (int)$tRow['max_id'];
        $last_sender = 'user';

        $lStmt = $conn->prepare("SELECT id, sender_type, message, created_at FROM feedback_chats WHERE thread_id = ? ORDER BY id DESC LIMIT 1");
        if ($lStmt) {
            $lStmt->bind_param("s", $tid);
            $lStmt->execute();
            $lRes = $lStmt->get_result();
            if ($lRow = $lRes->fetch_assoc()) {
                $last_sender = $lRow['sender_type'];
                $parsedLast = parse_chat_message($lRow['message']);
                $last_msg = !empty($parsedLast['clean_msg']) ? $parsedLast['clean_msg'] : $lRow['message'];
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
            'user_name' => htmlspecialchars($user_name),
            'service_type' => htmlspecialchars($service_type),
            'created_at' => $tRow['last_activity'],
            'last_msg' => htmlspecialchars($last_msg),
            'time' => $time_str,
            'last_msg_id' => $last_msg_id,
            'last_sender' => $last_sender,
            'unread_count' => $unread_count,
            'last_activity' => $tRow['last_activity']
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
$latestRes = $conn->query("SELECT id, thread_id, sender_name, message, created_at FROM feedback_chats WHERE sender_type = 'user' ORDER BY id DESC LIMIT 1");
if ($latestRes && $lRow = $latestRes->fetch_assoc()) {
    $parsedL = parse_chat_message($lRow['message']);
    $latest_message = [
        'id' => (int)$lRow['id'],
        'session_id' => $lRow['thread_id'],
        'user_name' => htmlspecialchars($lRow['sender_name']),
        'service_type' => htmlspecialchars($parsedL['service_type']),
        'message' => htmlspecialchars($parsedL['clean_msg']),
        'time' => date('h:i A', strtotime($lRow['created_at']))
    ];
}

// 4. Notifications for bell dropdown (top 10 unread user messages)
$notifications = [];
$notifRes = $conn->query("SELECT id, thread_id, sender_name, message, created_at FROM feedback_chats WHERE sender_type = 'user' AND is_read = 0 ORDER BY id DESC LIMIT 10");
if ($notifRes) {
    while ($nRow = $notifRes->fetch_assoc()) {
        $parsedN = parse_chat_message($nRow['message']);
        $notifications[] = [
            'msg_id' => (int)$nRow['id'],
            'session_id' => $nRow['thread_id'],
            'user_name' => htmlspecialchars($nRow['sender_name']),
            'service_type' => htmlspecialchars($parsedN['service_type']),
            'message' => htmlspecialchars($parsedN['clean_msg']),
            'time' => date('h:i A', strtotime($nRow['created_at']))
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
?>
