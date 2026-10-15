<?php
// admin/SKofficials.php

// -------------------------------------------------------------
// 1. Officials Data Handling & Persistence
// -------------------------------------------------------------
$data_dir = __DIR__ . '/data';
$data_file = $data_dir . '/sk_officials.json';
$uploads_dir = __DIR__ . '/uploads/sk_officials';

function get_sk_admin_photo_src($photo) {
    if (empty($photo) || strpos($photo, 'placeholder') !== false) {
        return 'https://via.placeholder.com/150/e2e8f0/64748b?text=Photo';
    }
    if (strpos($photo, 'http://') === 0 || strpos($photo, 'https://') === 0) {
        return $photo;
    }
    $trimmed = ltrim($photo, '/\\');
    if (strpos($trimmed, 'public/') === 0) {
        return '../' . $trimmed;
    }
    if (strpos($trimmed, 'admin/') === 0) {
        return substr($trimmed, 6);
    }
    return $trimmed;
}

// Default list of SK officials matching the pyramid layout
$default_officials = [
    'chairperson' => [
        'id' => 'chairperson',
        'role' => 'SK CHAIRPERSON',
        'name' => 'Hon. Ryan M. Tubos',
        'photo' => 'public/image/SK Officials/Ryan M. Tubos.jpeg',
        'message' => 'Empowering the youth of Barangay Maruing through active participation in sports, education, and community development.'
    ],
    'secretary' => [
        'id' => 'secretary',
        'role' => 'SK SECRETARY',
        'name' => 'Hon. Rosemer G. Banagan',
        'photo' => 'public/image/SK Officials/Rosmer G. Banagan.jpeg'
    ],
    'treasurer' => [
        'id' => 'treasurer',
        'role' => 'SK TREASURER',
        'name' => 'Hon. Judith G. Tambagel',
        'photo' => 'public/image/SK Officials/Judith G. Tambagel.jpeg'
    ],
    'kagawad_1' => [
        'id' => 'kagawad_1',
        'role' => 'SK KAGAWAD 1',
        'name' => 'Hon. Renzarr L. Roxas',
        'photo' => 'public/image/SK Officials/Renzarr L. Roxas.jpeg'
    ],
    'kagawad_2' => [
        'id' => 'kagawad_2',
        'role' => 'SK KAGAWAD 2',
        'name' => 'Hon. Benie L. Ligao',
        'photo' => 'public/image/SK Officials/Benie L. Ligao.jpeg'
    ],
    'kagawad_3' => [
        'id' => 'kagawad_3',
        'role' => 'SK KAGAWAD 3',
        'name' => 'Hon. Jhon Mark Charton D. Singue',
        'photo' => 'public/image/SK Officials/Jhon Mark Charton D. Singue.jpeg'
    ],
    'kagawad_4' => [
        'id' => 'kagawad_4',
        'role' => 'SK KAGAWAD 4',
        'name' => 'Hon. Mark A. Alpay',
        'photo' => 'public/image/SK Officials/Mark A. Alpay.jpeg'
    ],
    'kagawad_5' => [
        'id' => 'kagawad_5',
        'role' => 'SK KAGAWAD 5',
        'name' => 'Hon. Marlon B. Amplao',
        'photo' => 'public/image/SK Officials/Marlon B. Amplao.jpeg'
    ],
    'kagawad_6' => [
        'id' => 'kagawad_6',
        'role' => 'SK KAGAWAD 6',
        'name' => 'Hon. Verdelyn G. Halilong',
        'photo' => 'public/image/SK Officials/Verdelyn G. Halilong.jpeg'
    ],
    'kagawad_7' => [
        'id' => 'kagawad_7',
        'role' => 'SK KAGAWAD 7',
        'name' => 'Hon. Mc Felco T Bugao',
        'photo' => 'public/image/SK Officials/Mc Felco T. Bugao.jpeg'
    ]
];

// Load existing data: prioritize MySQL database, with JSON as backup/cache
$officials = $default_officials;

@include_once __DIR__ . '/db_connect.php';
$has_db = isset($conn) && !$conn->connect_error;

if ($has_db) {
    try {
        // Ensure sk_officials table exists
        $conn->query("CREATE TABLE IF NOT EXISTS `sk_officials` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `official_key` varchar(50) DEFAULT NULL,
            `name` varchar(100) NOT NULL,
            `position` varchar(100) NOT NULL,
            `photo` varchar(255) DEFAULT NULL,
            `message` text DEFAULT NULL,
            `status` enum('active','inactive') DEFAULT 'active',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // Ensure columns exist
        $col_key = $conn->query("SHOW COLUMNS FROM sk_officials LIKE 'official_key'");
        if ($col_key && $col_key->num_rows === 0) {
            @$conn->query("ALTER TABLE sk_officials ADD COLUMN official_key VARCHAR(50) DEFAULT NULL AFTER id");
        }
        $col_msg = $conn->query("SHOW COLUMNS FROM sk_officials LIKE 'message'");
        if ($col_msg && $col_msg->num_rows === 0) {
            @$conn->query("ALTER TABLE sk_officials ADD COLUMN message TEXT DEFAULT NULL AFTER photo");
        }

        // Check if database has any SK officials records yet
        $count_res = $conn->query("SELECT COUNT(*) AS total FROM sk_officials");
        $row_count = 0;
        if ($count_res) {
            $cnt_row = $count_res->fetch_assoc();
            $row_count = (int)($cnt_row['total'] ?? 0);
        }

        if ($row_count === 0) {
            // First time setup: seed the default SK officials into MySQL
            $ins_stmt = $conn->prepare("INSERT INTO sk_officials (official_key, name, position, photo, message, status) VALUES (?, ?, ?, ?, ?, 'active')");
            if ($ins_stmt) {
                foreach ($default_officials as $k => $d) {
                    $d_msg = ($k === 'chairperson') ? ($d['message'] ?? '') : null;
                    $ins_stmt->bind_param("sssss", $k, $d['name'], $d['role'], $d['photo'], $d_msg);
                    $ins_stmt->execute();
                }
                $ins_stmt->close();
            }
        } else {
            // Load all officials records directly from MySQL
            $db_res = $conn->query("SELECT * FROM sk_officials");
            if ($db_res && $db_res->num_rows > 0) {
                while ($r = $db_res->fetch_assoc()) {
                    $k = !empty($r['official_key']) ? $r['official_key'] : '';
                    if (empty($k)) {
                        foreach ($default_officials as $d_k => $d_v) {
                            if (strcasecmp($d_v['role'], $r['position']) === 0) {
                                $k = $d_k;
                                break;
                            }
                        }
                    }
                    if (!empty($k) && isset($officials[$k])) {
                        $officials[$k]['name'] = $r['name'];
                        $officials[$k]['role'] = (!empty($r['position']) && $r['position'] !== 'SK KAGAWAD') ? $r['position'] : ($default_officials[$k]['role'] ?? $r['position']);
                        if (!empty($r['photo'])) {
                            $officials[$k]['photo'] = $r['photo'];
                        }
                        if ($k === 'chairperson' && isset($r['message'])) {
                            $officials[$k]['message'] = $r['message'];
                        }
                    }
                }
            }

            // Ensure any missing default official keys get inserted into MySQL
            foreach ($default_officials as $k => $d) {
                $check = $conn->query("SELECT id FROM sk_officials WHERE official_key = '$k' LIMIT 1");
                if ($check && $check->num_rows === 0) {
                    $ins = $conn->prepare("INSERT INTO sk_officials (official_key, name, position, photo, message, status) VALUES (?, ?, ?, ?, ?, 'active')");
                    if ($ins) {
                        $d_msg = ($k === 'chairperson') ? ($d['message'] ?? '') : null;
                        $ins->bind_param("sssss", $k, $d['name'], $d['role'], $d['photo'], $d_msg);
                        $ins->execute();
                        $ins->close();
                    }
                }
            }
        }

        // Keep JSON cache in sync with MySQL
        if (!is_dir($data_dir)) {
            @mkdir($data_dir, 0777, true);
        }
        @file_put_contents($data_file, json_encode($officials, JSON_PRETTY_PRINT));
    } catch (Throwable $e) {
        // Fallback to JSON below if DB has any temporary issue
    }
} else {
    // If MySQL connection fails, load from local JSON cache
    if (file_exists($data_file)) {
        $json_content = @file_get_contents($data_file);
        $decoded = json_decode($json_content, true);
        if (is_array($decoded)) {
            foreach ($default_officials as $k => $def) {
                if (isset($decoded[$k]) && is_array($decoded[$k])) {
                    $officials[$k] = array_merge($def, $decoded[$k]);
                }
            }
        }
    }
}

// -------------------------------------------------------------
// 2. Handle POST Request to Edit Official
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_official') {
    $id = isset($_POST['official_id']) ? trim($_POST['official_id']) : '';
    $name = isset($_POST['official_name']) ? trim($_POST['official_name']) : '';
    $role = isset($_POST['official_role']) ? trim($_POST['official_role']) : '';
    $message = isset($_POST['official_message']) ? trim($_POST['official_message']) : '';

    if (array_key_exists($id, $officials)) {
        $photo_path = $officials[$id]['photo'];

        // Handle uploaded photo
        if (isset($_FILES['official_photo']) && $_FILES['official_photo']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir($uploads_dir)) {
                @mkdir($uploads_dir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['official_photo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($ext, $allowed)) {
                $filename = 'sk_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $id) . '_' . time() . '.' . $ext;
                $target_path = $uploads_dir . '/' . $filename;
                if (move_uploaded_file($_FILES['official_photo']['tmp_name'], $target_path)) {
                    $photo_path = 'uploads/sk_officials/' . $filename;
                }
            }
        }

        // Update in-memory official record
        $officials[$id]['name'] = !empty($name) ? $name : 'Name';
        $officials[$id]['role'] = !empty($role) ? $role : $officials[$id]['role'];
        $officials[$id]['photo'] = $photo_path;

        // Message is applicable ONLY to SK Chairperson
        if ($id === 'chairperson') {
            $officials['chairperson']['message'] = $message;
        }

        // 1. Save to JSON file cache
        if (!is_dir($data_dir)) {
            @mkdir($data_dir, 0777, true);
        }
        @file_put_contents($data_file, json_encode($officials, JSON_PRETTY_PRINT));

        // 2. Store directly in MySQL database
        if ($has_db) {
            try {
                $c_name = $officials[$id]['name'];
                $c_role = $officials[$id]['role'];
                $c_photo = $officials[$id]['photo'];
                $c_msg = ($id === 'chairperson') ? ($officials['chairperson']['message'] ?? '') : null;

                // Check if row exists for this official_key
                $stmt = $conn->prepare("SELECT id FROM sk_officials WHERE official_key = ? LIMIT 1");
                $exists = false;
                if ($stmt) {
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if ($res && $res->num_rows > 0) {
                        $row = $res->fetch_assoc();
                        $rec_id = $row['id'];
                        $exists = true;

                        // Update in MySQL
                        $upd = $conn->prepare("UPDATE sk_officials SET name = ?, position = ?, photo = ?, message = ?, status = 'active' WHERE id = ?");
                        if ($upd) {
                            $upd->bind_param("ssssi", $c_name, $c_role, $c_photo, $c_msg, $rec_id);
                            $upd->execute();
                            $upd->close();
                        }
                    }
                    $stmt->close();
                }

                if (!$exists) {
                    // Insert new record
                    $ins = $conn->prepare("INSERT INTO sk_officials (official_key, name, position, photo, message, status) VALUES (?, ?, ?, ?, ?, 'active')");
                    if ($ins) {
                        $ins->bind_param("sssss", $id, $c_name, $c_role, $c_photo, $c_msg);
                        $ins->execute();
                        $ins->close();
                    }
                }
            } catch (Throwable $e) {
                // Log or ignore DB error
            }
        }

        // Check if AJAX request
        $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
                   || isset($_POST['ajax']);

        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'SK Official updated successfully!',
                'official' => [
                    'id' => $id,
                    'name' => $officials[$id]['name'],
                    'role' => $officials[$id]['role'],
                    'photo' => get_sk_admin_photo_src($officials[$id]['photo']),
                    'raw_photo' => $officials[$id]['photo'],
                    'message' => $officials[$id]['message'] ?? ''
                ]
            ]);
            exit;
        } else {
            header("Location: SKofficials.php?updated=1");
            exit;
        }
    } else {
        $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
                   || isset($_POST['ajax']);
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Invalid official identifier provided.'
            ]);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage SK Officials | Barangay Maruing</title>
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
    <link rel="stylesheet" href="Admin CSS/SKofficials.css">
    <link rel="stylesheet" href="Admin CSS/barangayofficials.css?v=4">
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
            <li class="has-submenu open">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-people-fill"></i> Officials
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="barangayofficials.php">Barangay Officials</a></li>
                    <li><a href="SKofficials.php" class="active">SK Officials</a></li>
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
            <li><a href="settings.php"><i class="bi bi-gear-fill"></i> Settings</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="login.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-title">
                <h2>Manage SK Officials</h2>
            </div>
            <div class="topbar-user">
                <div class="user-info">
                    <span class="user-name">System Admin</span>
                    <span class="user-role">Administrator</span>
                </div>
                <div class="user-avatar">
                    A
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="dashboard-content">
            <div class="page-header">
                <h3>Sangguniang Kabataan Content</h3>
                <p>Update the information and photos displayed on the public SK Officials page.</p>
            </div>
            
            <div class="org-chart-pyramid">
                <!-- Row 1: Chairperson -->
                <div class="org-row row-1">
                    <?php 
                        $cp = $officials['chairperson']; 
                        $cp_photo = get_sk_admin_photo_src($cp['photo']);
                    ?>
                    <div class="org-card" id="card-chairperson" 
                         data-id="chairperson" 
                         data-name="<?= htmlspecialchars($cp['name']) ?>" 
                         data-role="<?= htmlspecialchars($cp['role']) ?>" 
                         data-photo="<?= htmlspecialchars($cp_photo) ?>"
                         data-message="<?= htmlspecialchars($cp['message'] ?? '') ?>">
                        <div class="org-photo-container">
                            <img src="<?= htmlspecialchars($cp_photo) ?>" alt="<?= htmlspecialchars($cp['role']) ?>" class="org-photo">
                            <div class="org-name-badge"><?= htmlspecialchars($cp['name']) ?></div>
                        </div>
                        <div class="org-role"><?= htmlspecialchars($cp['role']) ?></div>
                        <div class="org-actions">
                            <button type="button" class="btn-edit-official" onclick="openEditOfficialModal('chairperson')">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Secretary and Treasurer -->
                <div class="org-row row-2">
                    <!-- Secretary -->
                    <?php 
                        $sec = $officials['secretary']; 
                        $sec_photo = get_sk_admin_photo_src($sec['photo']);
                    ?>
                    <div class="org-card" id="card-secretary" 
                         data-id="secretary" 
                         data-name="<?= htmlspecialchars($sec['name']) ?>" 
                         data-role="<?= htmlspecialchars($sec['role']) ?>" 
                         data-photo="<?= htmlspecialchars($sec_photo) ?>">
                        <div class="org-photo-container">
                            <img src="<?= htmlspecialchars($sec_photo) ?>" alt="<?= htmlspecialchars($sec['role']) ?>" class="org-photo">
                            <div class="org-name-badge"><?= htmlspecialchars($sec['name']) ?></div>
                        </div>
                        <div class="org-role"><?= htmlspecialchars($sec['role']) ?></div>
                        <div class="org-actions">
                            <button type="button" class="btn-edit-official" onclick="openEditOfficialModal('secretary')">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>
                        </div>
                    </div>

                    <!-- Treasurer -->
                    <?php 
                        $tr = $officials['treasurer']; 
                        $tr_photo = get_sk_admin_photo_src($tr['photo']);
                    ?>
                    <div class="org-card" id="card-treasurer" 
                         data-id="treasurer" 
                         data-name="<?= htmlspecialchars($tr['name']) ?>" 
                         data-role="<?= htmlspecialchars($tr['role']) ?>" 
                         data-photo="<?= htmlspecialchars($tr_photo) ?>">
                        <div class="org-photo-container">
                            <img src="<?= htmlspecialchars($tr_photo) ?>" alt="<?= htmlspecialchars($tr['role']) ?>" class="org-photo">
                            <div class="org-name-badge"><?= htmlspecialchars($tr['name']) ?></div>
                        </div>
                        <div class="org-role"><?= htmlspecialchars($tr['role']) ?></div>
                        <div class="org-actions">
                            <button type="button" class="btn-edit-official" onclick="openEditOfficialModal('treasurer')">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Kagawads 1 to 3 -->
                <div class="org-row row-3" style="display: flex; justify-content: center; gap: 20px; width: 100%; max-width: 650px;">
                    <?php for ($i = 1; $i <= 3; $i++): 
                        $k_id = 'kagawad_' . $i;
                        $k_item = $officials[$k_id] ?? ($default_officials[$k_id] ?? []);
                        $k_photo = get_sk_admin_photo_src($k_item['photo'] ?? '');
                        $k_role = (!empty($k_item['role']) && $k_item['role'] !== 'SK KAGAWAD') ? $k_item['role'] : ('SK KAGAWAD ' . $i);
                        $k_name = !empty($k_item['name']) ? $k_item['name'] : 'Name';
                    ?>
                    <div class="org-card" id="card-<?= $k_id ?>" 
                         data-id="<?= $k_id ?>" 
                         data-name="<?= htmlspecialchars($k_name) ?>" 
                         data-role="<?= htmlspecialchars($k_role) ?>" 
                         data-photo="<?= htmlspecialchars($k_photo) ?>">
                        <div class="org-photo-container">
                            <img src="<?= htmlspecialchars($k_photo) ?>" alt="<?= htmlspecialchars($k_role) ?>" class="org-photo">
                            <div class="org-name-badge"><?= htmlspecialchars($k_name) ?></div>
                        </div>
                        <div class="org-role"><?= htmlspecialchars($k_role) ?></div>
                        <div class="org-actions">
                            <button type="button" class="btn-edit-official" onclick="openEditOfficialModal('<?= $k_id ?>')">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <!-- Row 4: Kagawads 4 to 7 -->
                <div class="org-row row-4" style="display: flex; justify-content: center; gap: 20px; width: 100%; max-width: 850px;">
                    <?php for ($i = 4; $i <= 7; $i++): 
                        $k_id = 'kagawad_' . $i;
                        $k_item = $officials[$k_id] ?? ($default_officials[$k_id] ?? []);
                        $k_photo = get_sk_admin_photo_src($k_item['photo'] ?? '');
                        $k_role = (!empty($k_item['role']) && $k_item['role'] !== 'SK KAGAWAD') ? $k_item['role'] : ('SK KAGAWAD ' . $i);
                        $k_name = !empty($k_item['name']) ? $k_item['name'] : 'Name';
                    ?>
                    <div class="org-card" id="card-<?= $k_id ?>" 
                         data-id="<?= $k_id ?>" 
                         data-name="<?= htmlspecialchars($k_name) ?>" 
                         data-role="<?= htmlspecialchars($k_role) ?>" 
                         data-photo="<?= htmlspecialchars($k_photo) ?>">
                        <div class="org-photo-container">
                            <img src="<?= htmlspecialchars($k_photo) ?>" alt="<?= htmlspecialchars($k_role) ?>" class="org-photo">
                            <div class="org-name-badge"><?= htmlspecialchars($k_name) ?></div>
                        </div>
                        <div class="org-role"><?= htmlspecialchars($k_role) ?></div>
                        <div class="org-actions">
                            <button type="button" class="btn-edit-official" onclick="openEditOfficialModal('<?= $k_id ?>')">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Official Modal -->
    <div id="editOfficialModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalHeaderTitle">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalHeaderTitle"><i class="bi bi-pencil-square"></i> Edit SK Official</h2>
                <button type="button" class="close-modal" onclick="closeModal('editOfficialModal')" aria-label="Close dialog">&times;</button>
            </div>
            <form action="SKofficials.php" method="POST" id="editOfficialForm" enctype="multipart/form-data">
                <input type="hidden" name="action" value="save_official">
                <input type="hidden" name="official_id" id="modalOfficialId" value="">
                
                <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                    <!-- Photo preview and upload -->
                    <div class="photo-preview-wrap">
                        <img id="modalPreviewImg" src="https://via.placeholder.com/150/e2e8f0/64748b?text=Photo" alt="Preview Photo" class="modal-preview-img">
                        <label for="modalPhotoInput" class="modal-photo-btn">
                            <i class="bi bi-camera-fill"></i> Change Photo
                        </label>
                        <input type="file" id="modalPhotoInput" name="official_photo" accept="image/*" style="display: none;">
                    </div>

                    <!-- Official Name -->
                    <div class="form-group">
                        <label class="form-label" for="modalOfficialName">Official Full Name</label>
                        <input type="text" id="modalOfficialName" name="official_name" class="form-control" required placeholder="e.g., Hon. Juan Dela Cruz">
                    </div>

                    <!-- Role / Position -->
                    <div class="form-group">
                        <label class="form-label" for="modalOfficialRole">Role / Position</label>
                        <input type="text" id="modalOfficialRole" name="official_role" class="form-control" required placeholder="e.g., SK CHAIRPERSON">
                    </div>

                    <!-- Chairperson's Message (Visible ONLY for SK Chairperson) -->
                    <div class="form-group" id="chairpersonMessageGroup" style="display: none;">
                        <label class="form-label" for="modalOfficialMessage">
                            <i class="bi bi-chat-quote-fill"></i> SK Chairperson's Message
                        </label>
                        <textarea id="modalOfficialMessage" name="official_message" class="form-control" rows="4" placeholder="Enter the SK Chairperson's message or youth statement..."></textarea>
                        <small style="color: #64748b; font-size: 0.75rem; margin-top: 5px; display: block;">
                            <i class="bi bi-info-circle"></i> This message is displayed on the public website and homepage.
                        </small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('editOfficialModal')">Cancel</button>
                    <button type="submit" class="btn-submit" id="modalSubmitBtn">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="adminToast" class="admin-toast" style="display: none;">
        <i class="bi bi-check-circle-fill"></i>
        <span id="toastMsg">SK Official updated successfully!</span>
    </div>

    <!-- Modal and Form Scripts -->
    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        function showToast(msg) {
            const toast = document.getElementById('adminToast');
            const toastMsg = document.getElementById('toastMsg');
            if (toast && toastMsg) {
                toastMsg.textContent = msg;
                toast.style.display = 'flex';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3500);
            }
        }

        function openEditOfficialModal(officialId) {
            const card = document.getElementById('card-' + officialId);
            if (!card) return;

            const name = card.dataset.name || '';
            const role = card.dataset.role || '';
            const photo = card.dataset.photo || '';
            const message = card.dataset.message || '';

            document.getElementById('modalOfficialId').value = officialId;
            document.getElementById('modalOfficialName').value = name;
            document.getElementById('modalOfficialRole').value = role;
            document.getElementById('modalPreviewImg').src = photo;
            
            // Only show Message for SK Chairperson
            const cpGroup = document.getElementById('chairpersonMessageGroup');
            const messageInput = document.getElementById('modalOfficialMessage');
            if (officialId === 'chairperson') {
                if (cpGroup) cpGroup.style.display = 'flex';
                if (messageInput) messageInput.value = message;
            } else {
                if (cpGroup) cpGroup.style.display = 'none';
                if (messageInput) messageInput.value = '';
            }

            // Reset file input
            const fileInput = document.getElementById('modalPhotoInput');
            if (fileInput) fileInput.value = '';

            // Reset save button state
            const submitBtn = document.getElementById('modalSubmitBtn');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Save Changes';
            }

            openModal('editOfficialModal');
            setTimeout(() => {
                document.getElementById('modalOfficialName').focus();
            }, 100);
        }

        // Live preview for uploaded photo
        document.getElementById('modalPhotoInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    document.getElementById('modalPreviewImg').src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Close modal when clicking outside or pressing Escape
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal('editOfficialModal');
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal('editOfficialModal');
            }
        });

        // Robust Form Submission
        document.getElementById('editOfficialForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = document.getElementById('modalSubmitBtn');
            const originalBtnHtml = '<i class="bi bi-check-lg"></i> Save Changes';
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

            const formData = new FormData(form);
            formData.append('ajax', '1');

            fetch('SKofficials.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                const text = await response.text();
                let data = null;
                try {
                    data = JSON.parse(text);
                } catch (parseErr) {
                    console.warn('Response was not pure JSON, fallback to native submit');
                    HTMLFormElement.prototype.submit.call(form);
                    return;
                }

                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;

                if (data && data.success) {
                    const off = data.official;
                    const card = document.getElementById('card-' + off.id);
                    if (card) {
                        // Update dataset attributes
                        card.dataset.name = off.name;
                        card.dataset.role = off.role;
                        card.dataset.photo = off.photo;
                        if (off.id === 'chairperson' && typeof off.message !== 'undefined') {
                            card.dataset.message = off.message;
                        }

                        // Update DOM elements on the card
                        const photoEl = card.querySelector('.org-photo');
                        if (photoEl) {
                            const separator = off.photo.includes('?') ? '&' : '?';
                            photoEl.src = off.photo + separator + 't=' + new Date().getTime();
                        }
                        
                        const nameEl = card.querySelector('.org-name-badge');
                        if (nameEl) {
                            nameEl.textContent = off.name;
                        }

                        const roleEl = card.querySelector('.org-role');
                        if (roleEl) {
                            roleEl.textContent = off.role;
                        }
                    }

                    closeModal('editOfficialModal');
                    showToast(data.message || 'SK Official updated successfully!');
                } else {
                    alert('Failed to update: ' + (data && data.message ? data.message : 'Please check your inputs and try again.'));
                }
            })
            .catch(err => {
                console.error('Fetch error encountered, fallback to native form submission:', err);
                HTMLFormElement.prototype.submit.call(form);
            });
        });
    </script>
</body>
</html>
