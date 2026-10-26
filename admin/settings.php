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

    // 3. Update Admin Profile (Full Name & Email)
    if ($action === 'update_admin_profile') {
        $full_name = trim($_POST['admin_name'] ?? '');
        $email = trim($_POST['admin_email'] ?? '');

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
                $up_stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
                $up_stmt->bind_param("ssi", $full_name, $email, $admin_user['id']);
                if ($up_stmt->execute()) {
                    logActivity($conn, 'Admin Profile Updated', "Admin details updated: $full_name ($email)");
                    $_SESSION['admin_name'] = $full_name;
                    $_SESSION['admin_email'] = $email;
                    header("Location: settings.php?msg=profile_updated&tab=security");
                    exit;
                } else {
                    $error_msg = "Failed to update admin profile: " . $conn->error;
                }
                $up_stmt->close();
            }
            $check_stmt->close();
        }
    }

    // 4. Change Admin Password
    if ($action === 'change_password') {
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
        } elseif (empty($new_password) || empty($confirm_password)) {
            $error_msg = "Please enter and confirm your new password.";
        } elseif ($new_password !== $confirm_password) {
            $error_msg = "New password and confirmation do not match.";
        } elseif (strlen($new_password) < 6) {
            $error_msg = "New password must be at least 6 characters long.";
        } else {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $up_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $up_stmt->bind_param("si", $new_hash, $admin_user['id']);
            if ($up_stmt->execute()) {
                logActivity($conn, 'Security Update', 'Admin account password was changed for: ' . $admin_user['email']);
                header("Location: settings.php?msg=password_updated&tab=security");
                exit;
            } else {
                $error_msg = "Failed to update password in database: " . $conn->error;
            }
            $up_stmt->close();
        }
    }

    // 5. Create New Admin Account
    if ($action === 'create_admin_account') {
        $new_name = trim($_POST['new_admin_name'] ?? '');
        $new_email = trim($_POST['new_admin_email'] ?? '');
        $new_pass = $_POST['new_admin_password'] ?? '';

        if (empty($new_name) || empty($new_email) || empty($new_pass)) {
            $error_msg = "All fields are required to create a new administrator account.";
        } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $error_msg = "Please enter a valid email address.";
        } elseif (strlen($new_pass) < 6) {
            $error_msg = "Password must be at least 6 characters long.";
        } else {
            // Check if email already exists
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $check_stmt->bind_param("s", $new_email);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                $error_msg = "An account with this email address already exists.";
            } else {
                $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $ins_stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role, status) VALUES (?, ?, ?, 'admin', 'active')");
                $ins_stmt->bind_param("sss", $new_name, $new_email, $hashed);
                if ($ins_stmt->execute()) {
                    logActivity($conn, 'Account Created', "New Administrator account created: $new_name ($new_email)");
                    header("Location: settings.php?msg=admin_created&tab=security");
                    exit;
                } else {
                    $error_msg = "Failed to create administrator account: " . $conn->error;
                }
                $ins_stmt->close();
            }
            $check_stmt->close();
        }
    }

    // 6. Clear Activity Logs
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
if ($msg === 'contacts_saved') $success_msg = "Contact details and emergency hotlines updated successfully!";
if ($msg === 'profile_updated') $success_msg = "Admin profile details (name and email) updated successfully!";
if ($msg === 'password_updated') $success_msg = "Admin password updated successfully! Please use your new password next time you log in.";
if ($msg === 'admin_created') $success_msg = "New administrator account created successfully! It can now be used to log in.";
if ($msg === 'logs_cleared') $success_msg = "System activity log history has been reset.";

// Determine active tab
$active_tab = $_GET['tab'] ?? 'general';
$allowed_tabs = ['general', 'contacts', 'security', 'backup', 'logs'];
if (!in_array($active_tab, $allowed_tabs)) {
    $active_tab = 'general';
}

// Fetch Current Settings Record
$info_res = $conn->query("SELECT * FROM barangay_information WHERE id = 1");
$info = ($info_res && $info_res->num_rows > 0) ? $info_res->fetch_assoc() : [];

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

// Fetch All Administrator Accounts
$all_admins = [];
$all_admins_res = $conn->query("SELECT id, full_name, email, role, status, created_at FROM users WHERE role = 'admin' ORDER BY id ASC");
if ($all_admins_res) {
    while ($arow = $all_admins_res->fetch_assoc()) {
        $all_admins[] = $arow;
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
    <link rel="stylesheet" href="Admin CSS/settings.css?v=3">
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

                    <!-- Admin Profile & Email Form -->
                    <div style="margin-bottom: 30px;">
                        <h4 style="color: #0f172a; margin: 0 0 8px 0; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-person-gear text-success"></i> Administrator Name & Login Email
                        </h4>
                        <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 18px;">
                            Updating this email changes the administrator account login credentials for this system.
                        </p>

                        <form action="settings.php" method="POST">
                            <input type="hidden" name="action" value="update_admin_profile">

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

                            <button type="submit" class="btn-save-settings">
                                <i class="bi bi-envelope-check-fill"></i> Save Profile & Email
                            </button>
                        </form>
                    </div>

                    <hr style="border: none; border-top: 1px dashed #cbd5e1; margin: 28px 0;">

                    <!-- Password Change Form -->
                    <h4 style="color: #0f172a; margin: 0 0 8px 0; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-key-fill text-warning"></i> Set New Account Password
                    </h4>
                    <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 18px;">
                        Directly set a new login password for your administrator account (<strong style="color: #093d25;"><?= htmlspecialchars($admin['email']) ?></strong>). Minimum of 6 characters.
                    </p>
                    <form action="settings.php" method="POST">
                        <input type="hidden" name="action" value="change_password">

                        <div class="form-row">
                            <div class="form-group">
                                <label>New Password <span class="required">*</span></label>
                                <div class="password-input-group">
                                    <input type="password" id="new_password" name="new_password" class="form-control" required minlength="6" placeholder="Enter new password">
                                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('new_password', this)" aria-label="Toggle password visibility" title="Show/Hide Password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password <span class="required">*</span></label>
                                <div class="password-input-group">
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="6" placeholder="Re-type new password">
                                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('confirm_password', this)" aria-label="Toggle password visibility" title="Show/Hide Password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-save-settings">
                            <i class="bi bi-shield-lock-fill"></i> Save New Password
                        </button>
                    </form>

                    <hr style="border: none; border-top: 1px dashed #cbd5e1; margin: 30px 0;">

                    <!-- Create New Administrator Account Section -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
                        <h4 style="color: #0f172a; margin: 0 0 8px 0; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-person-plus-fill text-success"></i> Create New Administrator Account
                        </h4>
                        <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 18px;">
                            Add another authorized administrator account with its own login email and password to access the system.
                        </p>

                        <form action="settings.php" method="POST">
                            <input type="hidden" name="action" value="create_admin_account">

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Administrator Full Name <span class="required">*</span></label>
                                    <input type="text" name="new_admin_name" class="form-control" placeholder="e.g. Maria Santos" required>
                                </div>
                                <div class="form-group">
                                    <label>Login Email Address <span class="required">*</span></label>
                                    <input type="email" name="new_admin_email" class="form-control" placeholder="admin2@barangaymaruing.com" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Account Password <span class="required">*</span></label>
                                <div class="password-input-group">
                                    <input type="password" id="new_admin_password" name="new_admin_password" class="form-control" placeholder="At least 6 characters" minlength="6" required>
                                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('new_admin_password', this)" aria-label="Toggle password visibility" title="Show/Hide Password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn-save-settings" style="background-color: #093d25;">
                                <i class="bi bi-person-check-fill"></i> Create Administrator Account
                            </button>
                        </form>
                    </div>

                    <!-- Registered Administrator Accounts List -->
                    <h4 style="color: #0f172a; margin: 0 0 14px 0; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-people-fill text-primary"></i> Registered Administrator Accounts (<?= count($all_admins) ?>)
                    </h4>
                    <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 10px;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
                            <thead>
                                <tr style="background: #f1f5f9; color: #475569; border-bottom: 1px solid #e2e8f0;">
                                    <th style="padding: 10px 14px;">Full Name</th>
                                    <th style="padding: 10px 14px;">Login Email</th>
                                    <th style="padding: 10px 14px;">Role</th>
                                    <th style="padding: 10px 14px;">Status</th>
                                    <th style="padding: 10px 14px;">Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_admins as $adm): ?>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 14px; font-weight: 600; color: #0f172a;">
                                            <?= htmlspecialchars($adm['full_name']) ?>
                                            <?php if ($adm['id'] == ($admin['id'] ?? null)): ?>
                                                <span class="badge" style="background: #dcfce7; color: #166534; font-size: 0.72rem; padding: 2px 8px; border-radius: 12px; margin-left: 6px;">Active Account</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 10px 14px; color: #334155;"><?= htmlspecialchars($adm['email']) ?></td>
                                        <td style="padding: 10px 14px;"><span class="badge" style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 12px; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;"><?= htmlspecialchars($adm['role']) ?></span></td>
                                        <td style="padding: 10px 14px;">
                                            <span class="badge" style="background: <?= ($adm['status'] ?? 'active') === 'active' ? '#dcfce7' : '#fee2e2' ?>; color: <?= ($adm['status'] ?? 'active') === 'active' ? '#166534' : '#b91c1c' ?>; padding: 3px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                                <?= ucfirst(htmlspecialchars($adm['status'] ?? 'active')) ?>
                                            </span>
                                        </td>
                                        <td style="padding: 10px 14px; color: #64748b;"><?= !empty($adm['created_at']) ? date('M d, Y', strtotime($adm['created_at'])) : 'N/A' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
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
