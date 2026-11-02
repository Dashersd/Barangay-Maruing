<?php
// admin/db_connect.php - Dual-Environment Database Connection (Local XAMPP + InfinityFree Production)

mysqli_report(MYSQLI_REPORT_OFF);

$http_host   = $_SERVER['HTTP_HOST'] ?? '';
$server_addr = $_SERVER['SERVER_ADDR'] ?? '';
$is_cli      = (php_sapi_name() === 'cli');

$is_localhost = (
    $is_cli ||
    $http_host === 'localhost' ||
    strpos($http_host, 'localhost:') === 0 ||
    strpos($http_host, '127.0.0.1') === 0 ||
    $server_addr === '127.0.0.1' ||
    $server_addr === '::1'
);

if ($is_localhost) {
    // 1. Local XAMPP Configuration
    $servername = "127.0.0.1";
    $username   = "root";
    $password   = "";
    $dbname     = "barangay_maruing";
    $port       = 3306;

    $conn = @new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        $conn = @new mysqli("localhost", $username, $password, $dbname, $port);
    }
} else {
    // 2. InfinityFree Production Configuration (barangaymaruing.infinityfreeapp.com)
    $servername = getenv('DB_HOST') ?: "sql208.infinityfree.com";
    $username   = getenv('DB_USER') ?: "if0_43319778";
    $password   = getenv('DB_PASS') ?: "yBxuFOPGrAq0FR";
    $dbname     = getenv('DB_NAME') ?: "if0_43319778_barangay_maruing";
    $port       = 3306;

    // Connect to MySQL server and auto-resolve exact if0_43319778_XXX database name
    $conn = @new mysqli($servername, $username, $password, "", $port);

    if ($conn && !$conn->connect_error) {
        $db_selected = @$conn->select_db($dbname);

        if (!$db_selected) {
            $res = @$conn->query("SHOW DATABASES LIKE 'if0_43319778_%'");
            if ($res && $res->num_rows > 0) {
                $user_dbs = [];
                while ($row = $res->fetch_row()) {
                    $user_dbs[] = $row[0];
                }

                // Prefer any database with 'maruing' in its name
                $preferred_db = null;
                foreach ($user_dbs as $candidate) {
                    if (stripos($candidate, 'maruing') !== false) {
                        $preferred_db = $candidate;
                        break;
                    }
                }

                // Next prefer any database other than tabon_db
                if (!$preferred_db) {
                    foreach ($user_dbs as $candidate) {
                        if (stripos($candidate, 'tabon') === false) {
                            $preferred_db = $candidate;
                            break;
                        }
                    }
                }

                // Fallback to the first available database under the account
                if (!$preferred_db && !empty($user_dbs)) {
                    $preferred_db = $user_dbs[0];
                }

                if ($preferred_db) {
                    $dbname = $preferred_db;
                    $db_selected = @$conn->select_db($dbname);
                }
            }
        }

        // Auto-initialize Barangay Maruing schema & seed data if sm_purok_1_spots does not exist yet
        if ($db_selected) {
            $chk = @$conn->query("SHOW TABLES LIKE 'sm_purok_1_spots'");
            if ($chk && $chk->num_rows === 0) {
                $sql_file = dirname(__DIR__) . '/Database/barangay_maruing.sql';
                if (file_exists($sql_file)) {
                    $sql_contents = file_get_contents($sql_file);
                    if (!empty($sql_contents)) {
                        @$conn->multi_query($sql_contents);
                        while (@$conn->more_results() && @$conn->next_result()) {
                            if ($r = @$conn->store_result()) {
                                $r->free();
                            }
                        }
                    }
                }
            }
        }
    }
}

// Check connection
if (!$conn || $conn->connect_error) {
    $err_msg = $conn ? $conn->connect_error : "Unable to initialize MySQLi";
    if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $err_msg]);
        exit();
    }
    die("Database connection failed: " . htmlspecialchars($err_msg));
}

// Set charset to ensure proper encoding
$conn->set_charset("utf8mb4");
?>
