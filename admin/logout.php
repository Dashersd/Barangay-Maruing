<?php
// admin/logout.php - Dedicated Logout Handler
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = [];

// Destroy session cookie if set
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Clear remember-me cookie if desired on explicit logout
if (isset($_COOKIE['admin_remember'])) {
    setcookie('admin_remember', '', time() - 3600, "/");
}

// Destroy the session
session_destroy();

// Redirect directly to login.php
header("Location: login.php?msg=logged_out");
exit;
