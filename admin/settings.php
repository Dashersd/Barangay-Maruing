<?php
// admin/settings.php - Admin Control & Configuration Center
require_once 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================================
// 1. AUTO-MIGRATION / TABLE REPAIR ROUTINES
// =========================================================================

// Ensure activity_logs table exists
$conn->query("CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `action` VARCHAR(255) NOT NULL,
  `details` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// Helper to add column safely if it doesn't exist
function ensureColumnExists($conn, $table, $column, $definition) {
    $check = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    if ($check && $check->num_rows === 0) {
        $conn->query("ALTER TABLE `$table` ADD `$column` $definition");
    }
}

// Check & expand barangay_information table columns
ensureColumnExists($conn, 'barangay_information', 'slogan', "VARCHAR(255) DEFAULT 'Serbisyong Tapat, Para sa Lahat'");
ensureColumnExists($conn, 'barangay_information', 'office_hours', "VARCHAR(150) DEFAULT 'Monday - Friday, 8:00 AM - 5:00 PM'");
ensureColumnExists($conn, 'barangay_information', 'facebook_url', "VARCHAR(255) DEFAULT 'https://www.facebook.com/'");
ensureColumnExists($conn, 'barangay_information', 'police_hotline', "VARCHAR(50) DEFAULT '0998-598-7241'");
ensureColumnExists($conn, 'barangay_information', 'fire_hotline', "VARCHAR(50) DEFAULT '0917-123-4567'");
ensureColumnExists($conn, 'barangay_information', 'health_hotline', "VARCHAR(50) DEFAULT '0920-987-6543'");
ensureColumnExists($conn, 'barangay_information', 'tanod_hotline', "VARCHAR(50) DEFAULT '0931-234-5678'");
ensureColumnExists($conn, 'barangay_information', 'logo_path', "VARCHAR(255) DEFAULT '../public/image/Maruing Logo 2.png'");
ensureColumnExists($conn, 'barangay_information', 'hero_slideshow_speed', "INT DEFAULT 5");

// Ensure hero_backgrounds table exists
$conn->query("CREATE TABLE IF NOT EXISTS `hero_backgrounds` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `image_path` VARCHAR(255) NOT NULL,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// Ensure at least 1 record exists in barangay_information
$info_check = $conn->query("SELECT * FROM barangay_information LIMIT 1");
if ($info_check && $info_check->num_rows === 0) {
    $conn->query("INSERT INTO barangay_information 
        (barangay_name, municipality, province, description, address, contact_number, email, slogan, office_hours, logo_path) 
        VALUES 
        ('Barangay Maruing', 'Lapuyan', 'Zamboanga del Sur', 'Official Web-Based Information System of Barangay Maruing.', 'Barangay Hall, Maruing, Lapuyan, Zamboanga del Sur', '0912-345-6789', 'info@barangaymaruing.gov.ph', 'Serbisyong Tapat, Para sa Lahat', 'Monday - Friday, 8:00 AM - 5:00 PM', '../public/image/Maruing Logo 2.png')");
}

// Helper to log system activity
function logActivity($conn, $action, $details = '') {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $stmt = $conn->prepare("INSERT INTO activity_logs (action, details, ip_address) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $action, $details, $ip);
        $stmt->execute();
        $stmt->close();
    }
}

// Seed initial logs if completely empty
$logs_count = $conn->query("SELECT COUNT(*) FROM activity_logs")->fetch_row()[0] ?? 0;
if ($logs_count == 0) {
    logActivity($conn, 'System Initialized', 'Settings module and activity log database initialized');
    logActivity($conn, 'Admin Login', 'Administrator signed into session');
}

// =========================================================================
// 2. HANDLE POST FORM ACTIONS
// =========================================================================
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // 1. Save General Barangay Profile
    if ($action === 'save_general') {
        $barangay_name = trim($_POST['barangay_name'] ?? 'Barangay Maruing');
        $municipality = trim($_POST['municipality'] ?? 'Lapuyan');
        $province = trim($_POST['province'] ?? 'Zamboanga del Sur');
        $slogan = trim($_POST['slogan'] ?? '');
        $office_hours = trim($_POST['office_hours'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Handle Logo Upload if present
        $logo_update_sql = "";
        $logo_path = null;
        if (isset($_FILES['official_logo']) && $_FILES['official_logo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/branding/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = strtolower(pathinfo($_FILES['official_logo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'])) {
                $filename = 'logo_' . time() . '.' . $ext;
                $target = $upload_dir . $filename;
                if (move_uploaded_file($_FILES['official_logo']['tmp_name'], $target)) {
                    $logo_path = $target;
                }
            }
        }

        if ($logo_path) {
            $stmt = $conn->prepare("UPDATE barangay_information SET barangay_name = ?, municipality = ?, province = ?, slogan = ?, office_hours = ?, address = ?, description = ?, logo_path = ? WHERE id = 1");
            $stmt->bind_param("ssssssss", $barangay_name, $municipality, $province, $slogan, $office_hours, $address, $description, $logo_path);
        } else {
            $stmt = $conn->prepare("UPDATE barangay_information SET barangay_name = ?, municipality = ?, province = ?, slogan = ?, office_hours = ?, address = ?, description = ? WHERE id = 1");
            $stmt->bind_param("sssssss", $barangay_name, $municipality, $province, $slogan, $office_hours, $address, $description);
        }

        if ($stmt && $stmt->execute()) {
            logActivity($conn, 'Profile Updated', 'Barangay General Information and Branding saved');
            header("Location: settings.php?msg=general_saved&tab=general");
            exit;
        } else {
            $error_msg = "Failed to update barangay information.";
        }
    }

    // 1.1 Save / Upload Hero Background Images & Slideshow Speed
    if ($action === 'upload_hero_bg') {
        $speed = isset($_POST['hero_slideshow_speed']) ? intval($_POST['hero_slideshow_speed']) : 5;
        if (!in_array($speed, [3, 5])) $speed = 5;

        $conn->query("UPDATE barangay_information SET hero_slideshow_speed = $speed WHERE id = 1");

        $upload_dir = '../uploads/hero/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $uploaded_count = 0;
        if (isset($_FILES['hero_images']) && !empty($_FILES['hero_images']['name'][0])) {
            $total_files = count($_FILES['hero_images']['name']);
            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['hero_images']['error'][$i] === UPLOAD_ERR_OK) {
                    $orig_name = $_FILES['hero_images']['name'][$i];
                    $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
                    if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
                        $filename = 'hero_' . time() . '_' . uniqid() . '.' . $ext;
                        $target = $upload_dir . $filename;
                        if (move_uploaded_file($_FILES['hero_images']['tmp_name'][$i], $target)) {
                            $db_path = 'uploads/hero/' . $filename;
                            $stmt = $conn->prepare("INSERT INTO hero_backgrounds (image_path) VALUES (?)");
                            $stmt->bind_param("s", $db_path);
                            $stmt->execute();
                            $stmt->close();
                            $uploaded_count++;
                        }
                    }
                }
            }
        }

        if ($uploaded_count > 0) {
            logActivity($conn, 'Hero Slides Uploaded', "$uploaded_count background image(s) uploaded for hero section ($speed-second interval).");
            header("Location: settings.php?msg=hero_uploaded&count=$uploaded_count&tab=hero_bg");
            exit;
        } else {
            logActivity($conn, 'Hero Slideshow Settings Updated', "Slideshow interval set to $speed seconds.");
            header("Location: settings.php?msg=hero_speed_saved&tab=hero_bg");
            exit;
        }
    }

    // 1.2 Delete Hero Background Image
    if ($action === 'delete_hero_bg') {
        $hero_id = intval($_POST['hero_id'] ?? 0);
        if ($hero_id > 0) {
            $stmt = $conn->prepare("SELECT image_path FROM hero_backgrounds WHERE id = ?");
            $stmt->bind_param("i", $hero_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $row = $res->fetch_assoc()) {
                $file = '../' . $row['image_path'];
                if (file_exists($file)) {
                    @unlink($file);
                }
                $del_stmt = $conn->prepare("DELETE FROM hero_backgrounds WHERE id = ?");
                $del_stmt->bind_param("i", $hero_id);
                $del_stmt->execute();
                $del_stmt->close();
                logActivity($conn, 'Hero Slide Deleted', "Deleted hero background ID $hero_id");
            }
            $stmt->close();
        }
        header("Location: settings.php?msg=hero_deleted&tab=hero_bg");
        exit;
    }

    // 2. Save Contact Hotlines & Emergency Desk
    if ($action === 'save_contacts') {
        $email = trim($_POST['email'] ?? '');
        $contact_number = trim($_POST['contact_number'] ?? '');
        $police_hotline = trim($_POST['police_hotline'] ?? '');
        $fire_hotline = trim($_POST['fire_hotline'] ?? '');
        $health_hotline = trim($_POST['health_hotline'] ?? '');
        $tanod_hotline = trim($_POST['tanod_hotline'] ?? '');
        $facebook_url = trim($_POST['facebook_url'] ?? '');

        $stmt = $conn->prepare("UPDATE barangay_information SET email = ?, contact_number = ?, police_hotline = ?, fire_hotline = ?, health_hotline = ?, tanod_hotline = ?, facebook_url = ? WHERE id = 1");
        $stmt->bind_param("sssssss", $email, $contact_number, $police_hotline, $fire_hotline, $health_hotline, $tanod_hotline, $facebook_url);
        
        if ($stmt && $stmt->execute()) {
            logActivity($conn, 'Contacts Updated', 'Emergency Hotlines and contact details updated');
            header("Location: settings.php?msg=contacts_saved&tab=contacts");
            exit;
        } else {
            $error_msg = "Failed to update contact hotlines.";
        }
    }

    // 3. Update Admin Profile & Security Credentials
    if ($action === 'update_admin_security' || $action === 'update_admin_profile' || $action === 'change_password') {
        $full_name = trim($_POST['admin_name'] ?? '');
        $email = trim($_POST['admin_email'] ?? '');
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Fetch current admin user
        $admin_id = $_SESSION['admin_id'] ?? null;
        if ($admin_id) {
            $u_stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'admin' LIMIT 1");
            $u_stmt->bind_param("i", $admin_id);
            $u_stmt->execute();
            $admin_user = $u_stmt->get_result()->fetch_assoc();
            $u_stmt->close();
        } else {
            $user_res = $conn->query("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
            $admin_user = $user_res ? $user_res->fetch_assoc() : null;
        }

        if (!$admin_user) {
            $error_msg = "Admin account not found in database.";
        } elseif (empty($full_name) || empty($email)) {
            $error_msg = "Admin name and email address are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_msg = "Please enter a valid email address.";
        } else {
            // Check if email is already taken by another user
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $check_stmt->bind_param("si", $email, $admin_user['id']);
            $check_stmt->execute();
            $check_res = $check_stmt->get_result();
            if ($check_res && $check_res->num_rows > 0) {
                $error_msg = "This email address is already in use by another account.";
            } else {
                $password_updated = false;
                if (!empty($new_password) || !empty($confirm_password)) {
                    if (empty($new_password) || empty($confirm_password)) {
                        $error_msg = "Please enter and confirm your new password.";
                    } elseif ($new_password !== $confirm_password) {
                        $error_msg = "New password and confirmation do not match.";
                    } elseif (strlen($new_password) < 6) {
                        $error_msg = "New password must be at least 6 characters long.";
                    } else {
                        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        $up_stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, password = ? WHERE id = ?");
                        $up_stmt->bind_param("sssi", $full_name, $email, $new_hash, $admin_user['id']);
                        if ($up_stmt->execute()) {
                            $password_updated = true;
                        } else {
                            $error_msg = "Failed to update account credentials: " . $conn->error;
                        }
                        $up_stmt->close();
                    }
                } else {
                    $up_stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
                    $up_stmt->bind_param("ssi", $full_name, $email, $admin_user['id']);
                    if (!$up_stmt->execute()) {
                        $error_msg = "Failed to update admin profile: " . $conn->error;
                    }
                    $up_stmt->close();
                }

                if (empty($error_msg)) {
                    $log_detail = "Admin details updated: $full_name ($email)" . ($password_updated ? ' (password changed)' : '');
                    logActivity($conn, 'Admin Profile Updated', $log_detail);
                    $_SESSION['admin_name'] = $full_name;
                    $_SESSION['admin_email'] = $email;
                    $msg_key = $password_updated ? 'security_saved_with_password' : 'security_saved';
                    header("Location: settings.php?msg={$msg_key}&tab=security");
                    exit;
                }
            }
            $check_stmt->close();
        }
    }

    // 5. Clear Activity Logs
    if ($action === 'clear_logs') {
        $conn->query("TRUNCATE TABLE activity_logs");
        logActivity($conn, 'Logs Cleared', 'Admin reset the system activity logs');
        header("Location: settings.php?msg=logs_cleared&tab=logs");
        exit;
    }
}

// Flash notification parsing
$msg = $_GET['msg'] ?? '';
if ($msg === 'general_saved') $success_msg = "Barangay profile and general configuration updated successfully!";
if ($msg === 'hero_uploaded') $success_msg = (isset($_GET['count']) ? intval($_GET['count']) : '') . " Hero background image(s) uploaded successfully!";
if ($msg === 'hero_speed_saved') $success_msg = "Hero slideshow settings updated successfully!";
if ($msg === 'hero_deleted') $success_msg = "Hero background image removed successfully!";
if ($msg === 'contacts_saved') $success_msg = "Contact details and emergency hotlines updated successfully!";
if ($msg === 'security_saved') $success_msg = "Administrator credentials updated successfully!";
if ($msg === 'security_saved_with_password') $success_msg = "Administrator profile and password updated successfully! Please use your new password next time you log in.";
if ($msg === 'profile_updated') $success_msg = "Admin profile details (name and email) updated successfully!";
if ($msg === 'password_updated') $success_msg = "Admin password updated successfully! Please use your new password next time you log in.";
if ($msg === 'logs_cleared') $success_msg = "System activity log history has been reset.";

// Determine active tab
$active_tab = $_GET['tab'] ?? 'general';
$allowed_tabs = ['general', 'hero_bg', 'contacts', 'security', 'backup', 'logs'];
if (!in_array($active_tab, $allowed_tabs)) {
    $active_tab = 'general';
}

// Fetch Current Settings Record
$info_res = $conn->query("SELECT * FROM barangay_information WHERE id = 1");
$info = ($info_res && $info_res->num_rows > 0) ? $info_res->fetch_assoc() : [];

// Fetch Hero Background Slides
$hero_slides = [];
$h_res = $conn->query("SELECT * FROM hero_backgrounds ORDER BY sort_order ASC, id ASC");
if ($h_res) {
    while ($h_row = $h_res->fetch_assoc()) {
        $hero_slides[] = $h_row;
    }
}

// Fetch Admin Profile
$admin = null;
$admin_id = $_SESSION['admin_id'] ?? null;
if ($admin_id) {
    $u_stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'admin' LIMIT 1");
    $u_stmt->bind_param("i", $admin_id);
    $u_stmt->execute();
    $admin_res = $u_stmt->get_result();
    $admin = ($admin_res && $admin_res->num_rows > 0) ? $admin_res->fetch_assoc() : null;
    $u_stmt->close();
}
if (empty($admin)) {
    $admin_res = $conn->query("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
    $admin = ($admin_res && $admin_res->num_rows > 0) ? $admin_res->fetch_assoc() : [
        'full_name' => 'System Admin',
        'email' => 'admin@barangaymaruing.com'
    ];
}

// Fetch Recent Activity Logs
$logs = [];
$logs_res = $conn->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 50");
if ($logs_res) {
    while ($row = $logs_res->fetch_assoc()) {
        $logs[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings | Barangay Maruing</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/assets/css/style.css">
    <link rel="stylesheet" href="Admin CSS/admin.css?v=2">
    <link rel="stylesheet" href="Admin CSS/settings.css?v=<?= time() ?>">
</head>
<body>

    <!-- Sidebar -->
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
            <li><a href="#"><i class="bi bi-telephone-fill"></i> Contact</a></li>
            <li><a href="feedback_chat.php"><i class="bi bi-chat-dots-fill"></i> Feedback chat</a></li>
            <li><a href="settings.php" class="active"><i class="bi bi-gear-fill"></i> Settings</a></li>
            <li><a href="logout.php" class="logout-link" style="color: #ef4444 !important;"><i class="bi bi-box-arrow-left" style="color: #ef4444 !important;"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-title">
                <h2>Admin Settings & System Control</h2>
            </div>
            <div class="topbar-user">
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($admin['full_name']) ?></span>
                    <span class="user-role">Administrator</span>
                </div>
                <div class="user-avatar">A</div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="dashboard-content">

            <!-- Alerts -->
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success" id="alertBox">
                    <div><i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i> <?= htmlspecialchars($success_msg) ?></div>
                    <button type="button" class="alert-close" onclick="document.getElementById('alertBox').remove()">&times;</button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger" id="alertBox">
                    <div><i class="bi bi-exclamation-triangle-fill" style="margin-right: 8px;"></i> <?= htmlspecialchars($error_msg) ?></div>
                    <button type="button" class="alert-close" onclick="document.getElementById('alertBox').remove()">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Header Banner -->
            <div class="settings-header-banner">
                <div>
                    <h2><i class="bi bi-sliders"></i> System Configuration</h2>
                    <p>Manage Barangay Maruing identity, official contacts, security credentials, and database backups.</p>
                </div>
                <a href="export_backup.php" class="btn-backup-download" title="Instantly download complete database backup">
                    <i class="bi bi-cloud-arrow-down-fill"></i> Quick SQL Backup
                </a>
            </div>

            <!-- Tab Navigation Bar -->
            <div class="settings-nav-tabs">
                <button type="button" class="tab-btn <?= $active_tab === 'general' ? 'active' : '' ?>" onclick="switchTab('general', this)">
                    <i class="bi bi-building"></i> Barangay Profile
                </button>
                <button type="button" class="tab-btn <?= $active_tab === 'hero_bg' ? 'active' : '' ?>" onclick="switchTab('hero_bg', this)">
                    <i class="bi bi-images"></i> Hero Background
                </button>
                <button type="button" class="tab-btn <?= $active_tab === 'contacts' ? 'active' : '' ?>" onclick="switchTab('contacts', this)">
                    <i class="bi bi-telephone-outbound"></i> Hotlines & Contacts
                </button>
                <button type="button" class="tab-btn <?= $active_tab === 'security' ? 'active' : '' ?>" onclick="switchTab('security', this)">
                    <i class="bi bi-shield-lock"></i> Security & Password
                </button>
                <button type="button" class="tab-btn <?= $active_tab === 'backup' ? 'active' : '' ?>" onclick="switchTab('backup', this)">
                    <i class="bi bi-database-check"></i> Database & System Health
                </button>
                <button type="button" class="tab-btn <?= $active_tab === 'logs' ? 'active' : '' ?>" onclick="switchTab('logs', this)">
                    <i class="bi bi-clock-history"></i> Activity Logs (<?= count($logs) ?>)
                </button>
            </div>

            <!-- ======================================================== -->
            <!-- TAB 1: GENERAL BARANGAY PROFILE                          -->
            <!-- ======================================================== -->
            <div id="tab-general" class="tab-pane <?= $active_tab === 'general' ? 'active' : '' ?>">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div>
                            <h3 class="settings-card-title"><i class="bi bi-geo-alt-fill text-success"></i> Official Barangay Details</h3>
                            <p class="settings-card-subtitle">Information displayed across the website header, footer, and generated documents.</p>
                        </div>
                    </div>

                    <form action="settings.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="save_general">

                        <div class="form-row-3">
                            <div class="form-group">
                                <label>Barangay Name <span class="required">*</span></label>
                                <input type="text" name="barangay_name" class="form-control" value="<?= htmlspecialchars($info['barangay_name'] ?? 'Barangay Maruing') ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Municipality <span class="required">*</span></label>
                                <input type="text" name="municipality" class="form-control" value="<?= htmlspecialchars($info['municipality'] ?? 'Lapuyan') ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Province <span class="required">*</span></label>
                                <input type="text" name="province" class="form-control" value="<?= htmlspecialchars($info['province'] ?? 'Zamboanga del Sur') ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Official Slogan / Tagline</label>
                                <input type="text" name="slogan" class="form-control" value="<?= htmlspecialchars($info['slogan'] ?? 'Serbisyong Tapat, Para sa Lahat') ?>" placeholder="e.g. Serbisyong Tapat, Para sa Lahat">
                            </div>
                            <div class="form-group">
                                <label>Office Schedule / Time on Duty</label>
                                <input type="text" name="office_hours" class="form-control" value="<?= htmlspecialchars($info['office_hours'] ?? 'Monday - Friday, 8:00 AM - 5:00 PM') ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Official Hall Physical Address</label>
                            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($info['address'] ?? 'Barangay Hall, Maruing, Lapuyan, Zamboanga del Sur') ?>">
                        </div>

                        <div class="form-group">
                            <label>Official Logo / Seal</label>
                            <div class="logo-upload-container">
                                <div class="logo-preview-box">
                                    <img src="<?= htmlspecialchars(!empty($info['logo_path']) ? $info['logo_path'] : '../public/image/Maruing Logo 2.png') ?>" alt="Barangay Logo" id="logoPreviewImg">
                                </div>
                                <div style="flex: 1;">
                                    <input type="file" name="official_logo" accept="image/*" class="form-control" onchange="previewLogo(this)">
                                    <span class="form-hint">Accepted formats: PNG, JPG, WEBP, SVG. Leave empty to keep the existing seal.</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Barangay Mission / Vision Brief Summary</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($info['description'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" class="btn-save-settings">
                            <i class="bi bi-check2-circle"></i> Save Profile Details
                        </button>
                    </form>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- TAB: HERO BACKGROUND & SLIDESHOW                         -->
            <!-- ======================================================== -->
            <div id="tab-hero_bg" class="tab-pane <?= $active_tab === 'hero_bg' ? 'active' : '' ?>">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div>
                            <h3 class="settings-card-title"><i class="bi bi-images text-success"></i> Hero Background & Slideshow</h3>
                            <p class="settings-card-subtitle">Manage the homepage hero background photo. If multiple photos are uploaded, they will automatically smooth crossfade in the background behind the static text.</p>
                        </div>
                    </div>

                    <!-- Status Notification Banner -->
                    <?php 
                    $slide_count = count($hero_slides);
                    $current_speed = intval($info['hero_slideshow_speed'] ?? 5);
                    if (!in_array($current_speed, [3, 5])) $current_speed = 5;
                    ?>
                    <div class="hero-status-pill <?= $slide_count > 1 ? 'status-multi' : ($slide_count === 1 ? 'status-single' : 'status-empty') ?>">
                        <?php if ($slide_count > 1): ?>
                            <i class="bi bi-play-circle-fill"></i> <strong>Smooth Slideshow Active:</strong> <?= $slide_count ?> background images rotating every <?= $current_speed ?> seconds. The hero text remains static and unchanged.
                        <?php elseif ($slide_count === 1): ?>
                            <i class="bi bi-image"></i> <strong>Single Static Background:</strong> 1 custom background photo is currently active on the homepage hero section.
                        <?php else: ?>
                            <i class="bi bi-info-circle-fill"></i> <strong>Default Background:</strong> No custom photos uploaded yet. The homepage is currently displaying the default photo (<code>public/image/Maruing.png</code>).
                        <?php endif; ?>
                    </div>

                    <!-- Upload Form -->
                    <form action="settings.php" method="POST" enctype="multipart/form-data" class="hero-upload-form">
                        <input type="hidden" name="action" value="upload_hero_bg">
                        
                        <div class="form-row">
                            <div class="form-group" style="flex: 2;">
                                <label><i class="bi bi-cloud-arrow-up-fill"></i> Upload Background Photo(s)</label>
                                <input type="file" name="hero_images[]" multiple accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" id="heroImagesInput" onchange="previewHeroUploads(this)">
                                <span class="form-hint">You can select multiple photos at once. Supported formats: PNG, JPG, JPEG, WEBP. High-resolution landscape photos recommended.</span>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label><i class="bi bi-stopwatch"></i> Slideshow Speed</label>
                                <select name="hero_slideshow_speed" class="form-control">
                                    <option value="3" <?= $current_speed === 3 ? 'selected' : '' ?>>3 Seconds (Fast)</option>
                                    <option value="5" <?= $current_speed === 5 ? 'selected' : '' ?>>5 Seconds (Recommended - Smooth)</option>
                                </select>
                                <span class="form-hint">Controls how long each background photo remains before smoothly crossfading to the next.</span>
                            </div>
                        </div>

                        <!-- Client-side Selected Files Preview -->
                        <div id="heroUploadPreview" class="hero-preview-row" style="display: none;"></div>

                        <button type="submit" class="btn-save-settings">
                            <i class="bi bi-upload"></i> Save & Upload Backgrounds
                        </button>
                    </form>

                    <!-- Existing Background Images Gallery -->
                    <div class="hero-gallery-section" style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #e2e8f0;">
                        <h4 class="hero-gallery-title" style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-collection"></i> Active Background Images (<?= $slide_count ?>)
                        </h4>
                        
                        <?php if ($slide_count === 0): ?>
                            <div class="hero-empty-state" style="text-align: center; padding: 30px 20px; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; color: #64748b; max-width: 100%; box-sizing: border-box;">
                                <div class="hero-default-preview" style="width: 100%; max-width: 320px; height: 160px; margin: 0 auto 16px; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #cbd5e1; background: #0f172a;">
                                    <img src="../public/image/Maruing.png" alt="Default Hero Image" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                </div>
                                <p style="margin: 0; font-size: 0.95rem; color: #334155;">
                                    <strong>Currently using the default Barangay Maruing background photo.</strong>
                                </p>
                                <p style="margin: 5px 0 0; font-size: 0.85rem; color: #64748b;">
                                    Upload photos above to customize your homepage hero background or activate a smooth slideshow.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="hero-grid">
                                <?php foreach ($hero_slides as $index => $slide): ?>
                                    <div class="hero-card">
                                        <div class="hero-card-img-wrap" style="position: relative; width: 100%; height: 160px; background: #0f172a; overflow: hidden;">
                                            <img src="../<?= htmlspecialchars($slide['image_path']) ?>" alt="Hero Slide <?= $index + 1 ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                            <span class="hero-card-badge">Slide #<?= $index + 1 ?></span>
                                        </div>
                                        <div class="hero-card-footer">
                                            <span class="hero-card-date"><i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($slide['created_at'])) ?></span>
                                            <form action="settings.php" method="POST" onsubmit="return confirm('Are you sure you want to remove this background image?');" style="margin: 0;">
                                                <input type="hidden" name="action" value="delete_hero_bg">
                                                <input type="hidden" name="hero_id" value="<?= $slide['id'] ?>">
                                                <button type="submit" class="btn-delete-hero" title="Delete this image">
                                                    <i class="bi bi-trash3-fill"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- TAB 2: HOTLINES & EMERGENCY CONTACTS                     -->
            <!-- ======================================================== -->
            <div id="tab-contacts" class="tab-pane <?= $active_tab === 'contacts' ? 'active' : '' ?>">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div>
                            <h3 class="settings-card-title"><i class="bi bi-telephone-plus-fill text-success"></i> Emergency Hotlines & Public Help Desk</h3>
                            <p class="settings-card-subtitle">Numbers publicized on emergency pages, footer, and the contact section.</p>
                        </div>
                    </div>

                    <form action="settings.php" method="POST">
                        <input type="hidden" name="action" value="save_contacts">

                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="bi bi-envelope"></i> Official Barangay Email</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($info['email'] ?? 'contact@barangaymaruing.gov.ph') ?>" required>
                            </div>
                            <div class="form-group">
                                <label><i class="bi bi-telephone"></i> Barangay Hall Main Contact</label>
                                <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($info['contact_number'] ?? '0912-345-6789') ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="bi bi-shield-shaded" style="color: #0284c7;"></i> Police Station Hotline (Lapuyan PNP)</label>
                                <input type="text" name="police_hotline" class="form-control" value="<?= htmlspecialchars($info['police_hotline'] ?? '0998-598-7241') ?>">
                            </div>
                            <div class="form-group">
                                <label><i class="bi bi-fire" style="color: #ef4444;"></i> BFP Fire Station Hotline (Lapuyan Fire Dept)</label>
                                <input type="text" name="fire_hotline" class="form-control" value="<?= htmlspecialchars($info['fire_hotline'] ?? '0917-123-4567') ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="bi bi-heart-pulse-fill" style="color: #10b981;"></i> Health Center / Clinic Ambulance</label>
                                <input type="text" name="health_hotline" class="form-control" value="<?= htmlspecialchars($info['health_hotline'] ?? '0920-987-6543') ?>">
                            </div>
                            <div class="form-group">
                                <label><i class="bi bi-person-badge-fill" style="color: #d97706;"></i> Barangay Tanod / Peace & Order Desk</label>
                                <input type="text" name="tanod_hotline" class="form-control" value="<?= htmlspecialchars($info['tanod_hotline'] ?? '0931-234-5678') ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="bi bi-facebook" style="color: #1877f2;"></i> Official Facebook Page URL</label>
                            <input type="url" name="facebook_url" class="form-control" value="<?= htmlspecialchars($info['facebook_url'] ?? 'https://www.facebook.com/') ?>" placeholder="https://www.facebook.com/BarangayMaruingOfficial">
                        </div>

                        <button type="submit" class="btn-save-settings">
                            <i class="bi bi-check2-circle"></i> Save Hotline Contacts
                        </button>
                    </form>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- TAB 3: ADMIN ACCOUNT & SECURITY                          -->
            <!-- ======================================================== -->
            <div id="tab-security" class="tab-pane <?= $active_tab === 'security' ? 'active' : '' ?>">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div>
                            <h3 class="settings-card-title"><i class="bi bi-shield-lock-fill text-success"></i> Administrator Credentials</h3>
                            <p class="settings-card-subtitle">Manage login passwords and authentication credentials for system access.</p>
                        </div>
                    </div>

                    <!-- Current Account Info -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px 24px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <div style="font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Active Administrator Account</div>
                            <div style="font-size: 1.15rem; font-weight: 700; color: #093d25;"><?= htmlspecialchars($admin['full_name']) ?></div>
                            <div style="font-size: 0.88rem; color: #475569;"><i class="bi bi-envelope"></i> <?= htmlspecialchars($admin['email']) ?></div>
                        </div>
                        <span class="badge" style="background: #dcfce7; color: #166534; padding: 6px 14px; border-radius: 20px; font-weight: 700;">
                            <i class="bi bi-shield-fill-check"></i> Super Administrator
                        </span>
                    </div>

                    <!-- Unified Administrator Credentials & Security Form -->
                    <form action="settings.php" method="POST">
                        <input type="hidden" name="action" value="update_admin_security">

                        <!-- Administrator Name & Login Email -->
                        <div style="margin-bottom: 24px;">
                            <h4 style="color: #0f172a; margin: 0 0 8px 0; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-person-gear text-success"></i> Administrator Name & Login Email
                            </h4>
                            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 18px;">
                                Updating this email changes the administrator account login credentials for this system.
                            </p>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Administrator Full Name <span class="required">*</span></label>
                                    <input type="text" name="admin_name" class="form-control" value="<?= htmlspecialchars($admin['full_name']) ?>" required placeholder="e.g. System Administrator">
                                </div>
                                <div class="form-group">
                                    <label>Login Email Address <span class="required">*</span></label>
                                    <input type="email" name="admin_email" class="form-control" value="<?= htmlspecialchars($admin['email']) ?>" required placeholder="admin@example.com">
                                </div>
                            </div>
                        </div>

                        <hr style="border: none; border-top: 1px dashed #cbd5e1; margin: 24px 0;">

                        <!-- Password Change Section (Optional) -->
                        <div style="margin-bottom: 26px;">
                            <h4 style="color: #0f172a; margin: 0 0 8px 0; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-key-fill text-warning"></i> Set New Account Password <span style="font-size: 0.82rem; font-weight: normal; color: #64748b;">(Optional &mdash; leave blank to keep current)</span>
                            </h4>
                            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 18px;">
                                Directly set a new login password for your administrator account (<strong style="color: #093d25;"><?= htmlspecialchars($admin['email']) ?></strong>). Minimum of 6 characters.
                            </p>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <div class="password-input-group">
                                        <input type="password" id="new_password" name="new_password" class="form-control" minlength="6" placeholder="Enter new password (optional)">
                                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('new_password', this)" aria-label="Toggle password visibility" title="Show/Hide Password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <div class="password-input-group">
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="6" placeholder="Re-type new password">
                                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('confirm_password', this)" aria-label="Toggle password visibility" title="Show/Hide Password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-save-settings">
                            <i class="bi bi-shield-check"></i> Save Account Settings
                        </button>
                    </form>

                </div>
            </div>

            <!-- ======================================================== -->
            <!-- TAB 4: DATABASE & SYSTEM HEALTH                          -->
            <!-- ======================================================== -->
            <div id="tab-backup" class="tab-pane <?= $active_tab === 'backup' ? 'active' : '' ?>">
                <!-- System Health Stats -->
                <div class="system-info-grid">
                    <div class="system-stat-card">
                        <div class="system-stat-icon"><i class="bi bi-filetype-php"></i></div>
                        <div class="system-stat-info">
                            <h5>PHP Version</h5>
                            <span><?= phpversion() ?></span>
                        </div>
                    </div>
                    <div class="system-stat-card">
                        <div class="system-stat-icon"><i class="bi bi-database"></i></div>
                        <div class="system-stat-info">
                            <h5>Database Engine</h5>
                            <span>MySQL <?= $conn->server_info ? strtok($conn->server_info, '-') : '5.7+' ?></span>
                        </div>
                    </div>
                    <div class="system-stat-card">
                        <div class="system-stat-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                        <div class="system-stat-info">
                            <h5>Upload Max Size</h5>
                            <span><?= ini_get('upload_max_filesize') ?></span>
                        </div>
                    </div>
                    <div class="system-stat-card">
                        <div class="system-stat-icon"><i class="bi bi-server"></i></div>
                        <div class="system-stat-info">
                            <h5>Server Environment</h5>
                            <span><?= php_uname('s') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Database Backup Card -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div>
                            <h3 class="settings-card-title"><i class="bi bi-database-down text-success"></i> Full Database Backup</h3>
                            <p class="settings-card-subtitle">Generate an encrypted or raw SQL snapshot of all resident spots, officials, announcements, and content.</p>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border-left: 4px solid #093d25; padding: 18px; border-radius: 6px; margin-bottom: 25px;">
                        <h4 style="margin: 0 0 6px 0; color: #093d25; font-size: 0.95rem;">Why back up your data regularly?</h4>
                        <p style="margin: 0; font-size: 0.88rem; color: #475569; line-height: 1.5;">
                            Downloading SQL backups ensures you never lose household records, map marker coordinates, or published announcements in case of server failure or hardware migrations.
                        </p>
                    </div>

                    <a href="export_backup.php" class="btn-backup-download">
                        <i class="bi bi-download"></i> Download Complete SQL Backup (.sql)
                    </a>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- TAB 5: ACTIVITY LOGS & AUDIT TRAIL                       -->
            <!-- ======================================================== -->
            <div id="tab-logs" class="tab-pane <?= $active_tab === 'logs' ? 'active' : '' ?>">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div>
                            <h3 class="settings-card-title"><i class="bi bi-shield-check text-success"></i> System Security & Audit Trail</h3>
                            <p class="settings-card-subtitle">Chronological record of admin actions, logins, and configurations.</p>
                        </div>
                        <form action="settings.php" method="POST" onsubmit="return confirm('Are you sure you want to clear all activity logs?');">
                            <input type="hidden" name="action" value="clear_logs">
                            <button type="submit" class="btn-secondary" style="color: #ef4444; border-color: #fecaca; background: #fff;">
                                <i class="bi bi-trash"></i> Clear Logs
                            </button>
                        </form>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="logs-table">
                            <thead>
                                <tr>
                                    <th style="width: 170px;">Timestamp</th>
                                    <th style="width: 180px;">Action</th>
                                    <th>Details</th>
                                    <th style="width: 130px;">IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($logs)): ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; color: #94a3b8; padding: 30px;">No activity logs recorded yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td style="color: #64748b; font-family: monospace;">
                                                <?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?>
                                            </td>
                                            <td>
                                                <span class="log-action-tag">
                                                    <?= htmlspecialchars($log['action']) ?>
                                                </span>
                                            </td>
                                            <td style="color: #334155; font-weight: 500;">
                                                <?= htmlspecialchars($log['details'] ?? 'N/A') ?>
                                            </td>
                                            <td style="color: #94a3b8; font-family: monospace;">
                                                <?= htmlspecialchars($log['ip_address'] ?? '127.0.0.1') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Client-side Tab Switcher & Preview Script -->
    <script>
        function switchTab(tabId, btn) {
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(function(pane) {
                pane.classList.remove('active');
            });

            // Deactivate all tab buttons
            document.querySelectorAll('.tab-btn').forEach(function(b) {
                b.classList.remove('active');
            });

            // Activate chosen tab
            const targetPane = document.getElementById('tab-' + tabId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
            if (btn) {
                btn.classList.add('active');
            }

            // Update URL query param without full page reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tabId);
            window.history.replaceState({}, '', url);
        }

        // Live Logo Preview
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('logoPreviewImg');
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Live Hero Uploads Multiple Preview
        function previewHeroUploads(input) {
            const previewContainer = document.getElementById('heroUploadPreview');
            if (!previewContainer) return;
            previewContainer.innerHTML = '';
            if (input.files && input.files.length > 0) {
                previewContainer.style.display = 'flex';
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const thumb = document.createElement('div');
                        thumb.className = 'hero-thumb-preview';
                        thumb.innerHTML = '<img src="' + e.target.result + '" alt="Selected"><span class="thumb-name">' + file.name + '</span>';
                        previewContainer.appendChild(thumb);
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                previewContainer.style.display = 'none';
            }
        }

        // Toggle Password Show/Hide
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;

            const icon = btn.querySelector('i');
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
    </script>
</body>
</html>
