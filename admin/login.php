<?php
// admin/login.php - Administrator Authentication Portal
require_once 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle Logout action or visit from logout link
if (
    (isset($_GET['action']) && $_GET['action'] === 'logout') || 
    isset($_GET['logout']) || 
    (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && !isset($_GET['msg']))
) {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    if (isset($_COOKIE['admin_remember'])) {
        setcookie('admin_remember', '', time() - 3600, "/");
    }
    session_destroy();
    header("Location: login.php?msg=logged_out");
    exit;
}

// Fetch dynamic Barangay branding from database
$b_info = [
    'barangay_name' => 'Barangay Maruing',
    'municipality' => 'Lapuyan',
    'logo_path' => '../public/image/Maruing Logo 2.png'
];
$info_res = $conn->query("SELECT barangay_name, municipality, logo_path FROM barangay_information LIMIT 1");
if ($info_res && $row = $info_res->fetch_assoc()) {
    $b_info = array_merge($b_info, array_filter($row));
}
$display_logo = !empty($b_info['logo_path']) ? $b_info['logo_path'] : '../public/image/Maruing Logo 2.png';

$error_msg = '';
$success_msg = '';

// Handle Quick Password Reset for local development recovery
if (isset($_GET['action']) && $_GET['action'] === 'reset_default') {
    $default_pass = 'admin123';
    $hash = password_hash($default_pass, PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password = '$hash' WHERE role = 'admin'");
    header("Location: login.php?msg=pass_reset");
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
    $success_msg = "You have been logged out securely.";
}
if (isset($_GET['msg']) && $_GET['msg'] === 'pass_reset') {
    $success_msg = "Administrator password has been reset to: admin123";
}

// Process Login Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_identifier = trim($_POST['login_identifier'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);

    if (empty($login_identifier) || empty($password)) {
        $error_msg = "Please enter your administrator email/username and password.";
    } else {
        // Look up admin by email or full name
        $stmt = $conn->prepare("SELECT * FROM users WHERE (email = ? OR full_name = ?) AND role = 'admin' LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("ss", $login_identifier, $login_identifier);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Check if account is active
                if (isset($user['status']) && $user['status'] === 'inactive') {
                    $error_msg = "This administrator account is currently inactive. Please contact support.";
                } else {
                    $password_valid = false;

                    // Verify password (supports bcrypt hash or legacy plain text)
                    if (password_verify($password, $user['password'])) {
                        $password_valid = true;
                    } elseif ($password === $user['password']) {
                        $password_valid = true;
                        // Auto-upgrade legacy plain text password to strong bcrypt hash
                        $new_hash = password_hash($password, PASSWORD_DEFAULT);
                        $rehash_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                        if ($rehash_stmt) {
                            $rehash_stmt->bind_param("si", $new_hash, $user['id']);
                            $rehash_stmt->execute();
                            $rehash_stmt->close();
                        }
                    }

                    if ($password_valid) {
                        // Establish Admin Session
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = (int)$user['id'];
                        $_SESSION['admin_name'] = $user['full_name'];
                        $_SESSION['admin_email'] = $user['email'];
                        $_SESSION['admin_role'] = $user['role'];

                        // Remember Me cookie handling
                        if ($remember_me) {
                            setcookie('admin_remember', $user['email'], time() + (86400 * 30), "/");
                        } else {
                            if (isset($_COOKIE['admin_remember'])) {
                                setcookie('admin_remember', '', time() - 3600, "/");
                            }
                        }

                        // Log Activity to activity_logs table
                        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                        $log_details = "Admin logged in: " . $user['email'];
                        $log_stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address) VALUES (?, 'Admin Login', ?, ?)");
                        if ($log_stmt) {
                            $log_stmt->bind_param("iss", $user['id'], $log_details, $ip);
                            @$log_stmt->execute();
                            $log_stmt->close();
                        }

                        // Redirect to Admin Dashboard
                        header("Location: dashboard.php");
                        exit;
                    } else {
                        $error_msg = "Incorrect password. If you forgot your password, click 'Forgot password?' below to reset it to: admin123";
                    }
                }
            } else {
                $error_msg = "No administrator account found with that email or username.";
            }
            $stmt->close();
        } else {
            $error_msg = "Database query error: " . $conn->error;
        }
    }
}

// Prefill identifier from cookie or POST
$default_identifier = $_POST['login_identifier'] ?? $_COOKIE['admin_remember'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Login to <?= htmlspecialchars($b_info['barangay_name']) ?> Web-Based Information System portal.">
    <title>Admin Login | <?= htmlspecialchars($b_info['barangay_name']) ?> Information System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= htmlspecialchars($display_logo) ?>">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/assets/css/style.css">
    <link rel="stylesheet" href="../public/assets/css/auth.css">
    <style>
        .auth-card-login {
            border-top: 4px solid var(--accent-color, #ffc107);
        }
        .login-alert {
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 12px;
            line-height: 1.4;
        }
        .login-alert-danger {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
        }
        .login-alert-success {
            background-color: #dcfce7;
            border-left: 4px solid #16a34a;
            color: #15803d;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top">
        <div class="navbar-container">
            <div class="navbar-brand">
                <img src="<?= htmlspecialchars($display_logo) ?>" alt="Barangay Logo" style="width: 50px; height: 50px; object-fit: contain; margin-right: 8px;">
                <img src="../public/image/Lapuyan Logo.png" alt="Municipality of Lapuyan Logo" style="width: 50px; height: 50px; object-fit: contain; margin-right: 12px;">
                <div class="brand-text">
                    <span class="brand-title"><?= htmlspecialchars($b_info['barangay_name']) ?></span>
                    <span class="brand-tagline">Admin Portal</span>
                </div>
            </div>
            <div class="navbar-menu">
                <ul class="navbar-nav nav-links">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Back to Main Site</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="auth-wrapper">
        <div class="auth-card auth-card-login">
            <div class="auth-header">
                <div class="auth-icon-badge" style="width: 100px; height: 100px;">
                    <img src="<?= htmlspecialchars($display_logo) ?>" alt="Barangay Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <h1 class="auth-title">Admin Login</h1>
                <p class="auth-subtitle">Sign in to manage <?= htmlspecialchars($b_info['barangay_name']) ?> Portal</p>
            </div>

            <!-- Flash / Error Alerts -->
            <?php if (!empty($error_msg)): ?>
                <div class="login-alert login-alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.15rem; flex-shrink: 0;"></i>
                    <span><?= htmlspecialchars($error_msg) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_msg)): ?>
                <div class="login-alert login-alert-success" role="alert">
                    <i class="bi bi-check-circle-fill" style="font-size: 1.15rem; flex-shrink: 0;"></i>
                    <span><?= htmlspecialchars($success_msg) ?></span>
                </div>
            <?php endif; ?>

            <form class="auth-form" action="login.php" method="POST">
                <div class="form-group">
                    <label class="form-label" for="login-username">Admin Email or Username</label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-shield-lock input-icon"></i>
                        <input type="text" id="login-username" name="login_identifier" class="form-control" placeholder="admin@barangaymaruing.com" value="<?= htmlspecialchars($default_identifier) ?>" required autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="login-password">Password</label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" id="login-password" name="password" class="form-control" placeholder="Enter admin password" required autocomplete="current-password">
                        <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('login-password', this)" aria-label="Toggle password visibility" title="Show/Hide Password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" id="remember-me" name="remember_me" <?= !empty($_COOKIE['admin_remember']) ? 'checked' : '' ?>>
                        <span>Remember me</span>
                    </label>
                    <a href="login.php?action=reset_default" class="forgot-link" onclick="return confirm('Reset administrator password to: admin123?');">Forgot password?</a>
                </div>

                <button type="submit" class="btn-auth-submit" style="background-color: var(--accent-color, #ffc107); color: #1a1a1a;">
                    <i class="bi bi-box-arrow-in-right"></i> Secure Login
                </button>
            </form>
        </div>
    </main>

    <!-- Custom JavaScript -->
    <script src="../public/assets/js/main.js"></script>
    <script>
        // Inline fallback for password visibility toggle if main.js is delayed
        if (typeof togglePasswordVisibility !== 'function') {
            function togglePasswordVisibility(inputId, buttonEl) {
                const input = document.getElementById(inputId);
                if (!input) return;
                const icon = buttonEl.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) {
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                } else {
                    input.type = 'password';
                    if (icon) {
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                }
            }
        }
    </script>
</body>
</html>
