<?php
// admin/export_backup.php - Database Backup Generator
require_once 'db_connect.php';

// Set headers for file download
$backup_name = 'barangay_maruing_backup_' . date('Y-m-d_His') . '.sql';
header('Content-Type: application/sql; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $backup_name . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Log this action to activity_logs if table exists
$ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$log_stmt = $conn->prepare("INSERT INTO activity_logs (action, details, ip_address) VALUES ('Database Backup', 'Downloaded complete SQL backup: " . $backup_name . "', ?)");
if ($log_stmt) {
    $log_stmt->bind_param("s", $ip);
    $log_stmt->execute();
    $log_stmt->close();
}

$output = "-- ============================================================\n";
$output .= "-- Barangay Maruing Web-Based Information System Database Dump\n";
$output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$output .= "-- Host: " . $servername . " | Database: " . $dbname . "\n";
$output .= "-- ============================================================\n\n";
$output .= "SET FOREIGN_KEY_CHECKS=0;\n";
$output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
$output .= "START TRANSACTION;\n";
$output .= "SET time_zone = \"+00:00\";\n\n";

echo $output;

// Fetch all tables
$tables = [];
$res = $conn->query("SHOW TABLES");
if ($res) {
    while ($row = $res->fetch_row()) {
        $tables[] = $row[0];
    }
}

foreach ($tables as $table) {
    echo "\n-- ------------------------------------------------------------\n";
    echo "-- Table structure for `$table`\n";
    echo "-- ------------------------------------------------------------\n";
    echo "DROP TABLE IF EXISTS `$table`;\n";

    $create_res = $conn->query("SHOW CREATE TABLE `$table`");
    if ($create_res && $create_row = $create_res->fetch_row()) {
        echo $create_row[1] . ";\n\n";
    }

    // Dump Data
    $data_res = $conn->query("SELECT * FROM `$table`");
    if ($data_res && $data_res->num_rows > 0) {
        echo "-- Dumping data for table `$table`\n";
        
        $fields_count = $data_res->field_count;
        $fields_info = $data_res->fetch_fields();

        while ($row = $data_res->fetch_assoc()) {
            $values = [];
            foreach ($fields_info as $field) {
                $val = $row[$field->name];
                if (is_null($val)) {
                    $values[] = "NULL";
                } else {
                    $values[] = "'" . $conn->real_escape_string($val) . "'";
                }
            }
            echo "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
        }
        echo "\n";
    }
}

echo "SET FOREIGN_KEY_CHECKS=1;\n";
echo "COMMIT;\n";
exit();
