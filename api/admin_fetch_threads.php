<?php
require_once '../admin/db_connect.php';

header('Content-Type: application/json');

// Fetch latest message for each thread
$query = "
    SELECT c1.thread_id, c1.sender_name, c1.message, c1.created_at, c1.is_read, c1.sender_type
    FROM feedback_chats c1
    INNER JOIN (
        SELECT thread_id, MAX(created_at) as max_created_at
        FROM feedback_chats
        GROUP BY thread_id
    ) c2 ON c1.thread_id = c2.thread_id AND c1.created_at = c2.max_created_at
    ORDER BY c1.created_at DESC
";

$result = $conn->query($query);

$threads = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $threads[] = [
            'thread_id' => $row['thread_id'],
            // Get user's name (we check sender_type; if admin sent last, we need original user's name)
            // But we can just use the latest message's info, though ideally we want the user's name always.
            // Let's do a subquery or just fetch the first user name.
            'last_message' => htmlspecialchars($row['message']),
            'created_at' => date('h:i A', strtotime($row['created_at'])),
            'is_read' => $row['is_read'],
            'sender_type' => $row['sender_type']
        ];
    }
}

// Better logic to get the correct user name per thread:
foreach ($threads as &$t) {
    $thread_id = $t['thread_id'];
    $name_stmt = $conn->prepare("SELECT sender_name FROM feedback_chats WHERE thread_id = ? AND sender_type = 'user' LIMIT 1");
    $name_stmt->bind_param("s", $thread_id);
    $name_stmt->execute();
    $res = $name_stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $t['sender_name'] = htmlspecialchars($row['sender_name']);
    } else {
        $t['sender_name'] = "Anonymous";
    }
    $name_stmt->close();
}

echo json_encode(['status' => 'success', 'threads' => $threads]);
?>
