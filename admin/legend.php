<?php
// admin/legend.php - Manage Spot Map Legends with Draggable Aerial View Marker & Landmark Image Viewer
require_once 'db_connect.php';

$success_msg = '';
$error_msg = '';

// Helper to keep JSON file synchronized with MySQL table
function syncLegendJson($conn) {
    $res = $conn->query("SELECT * FROM sm_legends ORDER BY display_order ASC, id ASC");
    $rows = [];
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $rows[] = $r;
        }
    }
    $target_dir = __DIR__ . '/data';
    if (!is_dir($target_dir)) {
        @mkdir($target_dir, 0777, true);
    }
    @file_put_contents($target_dir . '/legend.json', json_encode($rows, JSON_PRETTY_PRINT));
}

// Handle Form Submissions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $action = $_POST['action'] ?? '';

    // 1. CREATE LEGEND & MARKER
    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $color_hex = trim($_POST['color_hex'] ?? '#6366f1');
        $display_order = (int)($_POST['display_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
        $top_position = isset($_POST['top_position']) ? (float)$_POST['top_position'] : 50.00;
        $left_position = isset($_POST['left_position']) ? (float)$_POST['left_position'] : 50.00;
        $marker_width = isset($_POST['marker_width']) ? (int)$_POST['marker_width'] : 40;
        $marker_height = isset($_POST['marker_height']) ? (int)$_POST['marker_height'] : 40;
        $icon_type = $_POST['icon_type'] ?? 'preset';

        $icon_path = 'bi-geo-alt-fill';

        // Check for marker icon file upload
        $uploaded_marker = null;
        if (isset($_FILES['marker_image']) && $_FILES['marker_image']['error'] === UPLOAD_ERR_OK) {
            $uploaded_marker = $_FILES['marker_image'];
        } elseif (isset($_FILES['icon_image']) && $_FILES['icon_image']['error'] === UPLOAD_ERR_OK) {
            $uploaded_marker = $_FILES['icon_image'];
        }

        if ($icon_type === 'upload' && $uploaded_marker) {
            $upload_dir = 'uploads/legends/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_ext = strtolower(pathinfo($uploaded_marker['name'], PATHINFO_EXTENSION));
            $file_name = time() . '_legend_' . preg_replace("/[^a-zA-Z0-9_-]/", "", pathinfo($uploaded_marker['name'], PATHINFO_FILENAME)) . '.' . $file_ext;
            $dest = $upload_dir . $file_name;
            if (move_uploaded_file($uploaded_marker['tmp_name'], $dest)) {
                $icon_path = $dest;
            }
        } elseif ($icon_type === 'bootstrap') {
            $icon_path = trim($_POST['icon_bootstrap'] ?? 'bi-geo-alt-fill');
        } else {
            $icon_path = trim($_POST['icon_preset'] ?? 'bi-building-fill');
        }

        // Check for Landmark Actual Photo (viewed on click)
        $landmark_image_path = null;
        $uploaded_photo = null;
        if (isset($_FILES['landmark_image']) && $_FILES['landmark_image']['error'] === UPLOAD_ERR_OK) {
            $uploaded_photo = $_FILES['landmark_image'];
        } elseif (isset($_FILES['house_image']) && $_FILES['house_image']['error'] === UPLOAD_ERR_OK) {
            $uploaded_photo = $_FILES['house_image'];
        }

        if ($uploaded_photo) {
            $photo_dir = 'uploads/landmarks/';
            if (!is_dir($photo_dir)) {
                mkdir($photo_dir, 0777, true);
            }
            $p_ext = strtolower(pathinfo($uploaded_photo['name'], PATHINFO_EXTENSION));
            $p_name = time() . '_landmark_' . preg_replace("/[^a-zA-Z0-9_-]/", "", pathinfo($uploaded_photo['name'], PATHINFO_FILENAME)) . '.' . $p_ext;
            $p_dest = $photo_dir . $p_name;
            if (move_uploaded_file($uploaded_photo['tmp_name'], $p_dest)) {
                $landmark_image_path = $p_dest;
            }
        }

        if (!empty($name)) {
            $stmt = $conn->prepare("INSERT INTO sm_legends (name, icon_path, color_hex, is_active, display_order, top_position, left_position, marker_width, marker_height, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiiddiis", $name, $icon_path, $color_hex, $is_active, $display_order, $top_position, $left_position, $marker_width, $marker_height, $landmark_image_path);
            
            if ($stmt->execute()) {
                $new_legend_id = $conn->insert_id;
                
                // Also mirror into sm_legend_spots for cross-table compatibility
                $stmt_spot = $conn->prepare("INSERT INTO sm_legend_spots (legend_id, title, top_position, left_position, marker_width, marker_height, marker_image, house_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt_spot) {
                    $stmt_spot->bind_param("isddiiss", $new_legend_id, $name, $top_position, $left_position, $marker_width, $marker_height, $icon_path, $landmark_image_path);
                    $stmt_spot->execute();
                    $stmt_spot->close();
                }

                syncLegendJson($conn);
                header("Location: legend.php?msg=created");
                exit();
            } else {
                $error_msg = "Database error: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error_msg = "Legend name cannot be empty.";
        }
    }

    // 2. UPDATE LEGEND
    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $color_hex = trim($_POST['color_hex'] ?? '#6366f1');
        $display_order = (int)($_POST['display_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
        $top_position = isset($_POST['top_position']) ? (float)$_POST['top_position'] : 50.00;
        $left_position = isset($_POST['left_position']) ? (float)$_POST['left_position'] : 50.00;
        $marker_width = isset($_POST['marker_width']) ? (int)$_POST['marker_width'] : 40;
        $marker_height = isset($_POST['marker_height']) ? (int)$_POST['marker_height'] : 40;
        $icon_type = $_POST['icon_type'] ?? 'keep';

        if ($id > 0 && !empty($name)) {
            $new_icon_path = null;

            $uploaded_marker = null;
            if (isset($_FILES['marker_image']) && $_FILES['marker_image']['error'] === UPLOAD_ERR_OK) {
                $uploaded_marker = $_FILES['marker_image'];
            } elseif (isset($_FILES['icon_image']) && $_FILES['icon_image']['error'] === UPLOAD_ERR_OK) {
                $uploaded_marker = $_FILES['icon_image'];
            }

            if ($icon_type === 'upload' && $uploaded_marker) {
                $upload_dir = 'uploads/legends/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $file_ext = strtolower(pathinfo($uploaded_marker['name'], PATHINFO_EXTENSION));
                $file_name = time() . '_legend_' . preg_replace("/[^a-zA-Z0-9_-]/", "", pathinfo($uploaded_marker['name'], PATHINFO_FILENAME)) . '.' . $file_ext;
                $dest = $upload_dir . $file_name;
                if (move_uploaded_file($uploaded_marker['tmp_name'], $dest)) {
                    $new_icon_path = $dest;
                }
            } elseif ($icon_type === 'bootstrap') {
                $new_icon_path = trim($_POST['icon_bootstrap'] ?? '');
            } elseif ($icon_type === 'preset') {
                $new_icon_path = trim($_POST['icon_preset'] ?? '');
            }

            // Check if new landmark photo was uploaded
            $new_landmark_image = null;
            $uploaded_photo = null;
            if (isset($_FILES['landmark_image']) && $_FILES['landmark_image']['error'] === UPLOAD_ERR_OK) {
                $uploaded_photo = $_FILES['landmark_image'];
            } elseif (isset($_FILES['house_image']) && $_FILES['house_image']['error'] === UPLOAD_ERR_OK) {
                $uploaded_photo = $_FILES['house_image'];
            }

            if ($uploaded_photo) {
                $photo_dir = 'uploads/landmarks/';
                if (!is_dir($photo_dir)) {
                    mkdir($photo_dir, 0777, true);
                }
                $p_ext = strtolower(pathinfo($uploaded_photo['name'], PATHINFO_EXTENSION));
                $p_name = time() . '_landmark_' . preg_replace("/[^a-zA-Z0-9_-]/", "", pathinfo($uploaded_photo['name'], PATHINFO_FILENAME)) . '.' . $p_ext;
                $p_dest = $photo_dir . $p_name;
                if (move_uploaded_file($uploaded_photo['tmp_name'], $p_dest)) {
                    $new_landmark_image = $p_dest;
                }
            }

            // Build dynamic update query
            $updates = ["name = ?", "color_hex = ?", "is_active = ?", "display_order = ?", "top_position = ?", "left_position = ?", "marker_width = ?", "marker_height = ?"];
            $types = "ssiiddii";
            $params = [$name, $color_hex, $is_active, $display_order, $top_position, $left_position, $marker_width, $marker_height];

            if ($new_icon_path !== null && $new_icon_path !== '') {
                $updates[] = "icon_path = ?";
                $types .= "s";
                $params[] = $new_icon_path;
            }

            if ($new_landmark_image !== null) {
                $updates[] = "image_path = ?";
                $types .= "s";
                $params[] = $new_landmark_image;
            }

            $types .= "i";
            $params[] = $id;

            $sql = "UPDATE sm_legends SET " . implode(", ", $updates) . " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                syncLegendJson($conn);
                header("Location: legend.php?msg=updated");
                exit();
            } else {
                $error_msg = "Database error: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error_msg = "Invalid legend ID or missing name.";
        }
    }

    // 3. DELETE LEGEND
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM sm_legends WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $conn->query("DELETE FROM sm_legend_spots WHERE legend_id = $id");
                syncLegendJson($conn);
                header("Location: legend.php?msg=deleted");
                exit();
            } else {
                $error_msg = "Database error: " . $conn->error;
            }
            $stmt->close();
        }
    }

    // 4. TOGGLE STATUS
    if ($action === 'toggle_status') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE sm_legends SET is_active = IF(is_active = 1, 0, 1) WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                syncLegendJson($conn);
                header("Location: legend.php?msg=status_updated");
                exit();
            } else {
                $error_msg = "Database error: " . $conn->error;
            }
            $stmt->close();
        }
    }
}

// Fetch all legends
$legends = [];
$res = $conn->query("SELECT * FROM sm_legends ORDER BY display_order ASC, id ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $legends[] = $row;
    }
}

// Next display order
$next_order = count($legends) + 1;

// Flash messages
$msg = $_GET['msg'] ?? '';
if ($msg === 'created') $success_msg = "Legend item, pin, and landmark photo successfully saved to the map!";
if ($msg === 'updated') $success_msg = "Legend item successfully updated!";
if ($msg === 'deleted') $success_msg = "Legend item successfully removed.";
if ($msg === 'status_updated') $success_msg = "Legend status updated successfully!";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Map Legends | Barangay Maruing Admin</title>
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
    <link rel="stylesheet" href="Admin CSS/legend.css?v=6">
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
            <li class="has-submenu open">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-geo-alt-fill"></i> Spot Map
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="legend.php" class="active">Legend</a></li>
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
            <li><a href="login.php" class="logout-link"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-title">
                <h2>Manage Map Legends & Aerial Pins</h2>
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
        <main class="dashboard-content" style="padding: 30px;">
            <?php if (!empty($success_msg)): ?>
            <div style="background-color: #d1fae5; border-left: 4px solid #10b981; color: #065f46; padding: 15px 20px; margin-bottom: 25px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div><i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i> <?= htmlspecialchars($success_msg) ?></div>
                <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #065f46; cursor: pointer; font-size: 1.2rem;">&times;</button>
            </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
            <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 15px 20px; margin-bottom: 25px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div><i class="bi bi-exclamation-triangle-fill" style="margin-right: 8px;"></i> <?= htmlspecialchars($error_msg) ?></div>
                <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #991b1b; cursor: pointer; font-size: 1.2rem;">&times;</button>
            </div>
            <?php endif; ?>

            <!-- Header Description -->
            <div class="legend-header">
                <div class="legend-header-title">
                    <h2>Aerial Spot Map & Legend Placement</h2>
                    <p>Select an icon, upload the landmark photo, and <strong>drag the icon on the Aerial Map</strong> to save its exact coordinates. Clicking the icon will view its photo!</p>
                </div>
                <a href="../spot-map.php" target="_blank" class="btn-view-map">
                    <i class="bi bi-map-fill"></i> View Public Spot Map
                </a>
            </div>

            <!-- 1. Aerial Map with Draggable Pin -->
            <div class="purok-map-container" id="aerialMapContainer">
                <div class="map-banner-overlay">
                    <i class="bi bi-pin-map-fill" style="color: #ffc107; font-size: 1rem;"></i>
                    <span>Aerial Map View &bull; Drag the pulsing marker or click anywhere to position. Click saved pins to view landmark photo!</span>
                </div>

                <img src="../public/image/Maruing Map/Aerial View.png" alt="Barangay Maruing Aerial View" class="spot-map-img" id="aerialMapImg">

                <!-- Saved Existing Legend Markers on Map -->
                <?php foreach ($legends as $leg): 
                    $icon = $leg['icon_path'] ?? 'bi-geo-alt-fill';
                    $color = $leg['color_hex'] ?? '#6366f1';
                    $top = !empty($leg['top_position']) ? (float)$leg['top_position'] : 50.00;
                    $left = !empty($leg['left_position']) ? (float)$leg['left_position'] : 50.00;
                    $w = !empty($leg['marker_width']) ? (int)$leg['marker_width'] : 40;
                    $h = !empty($leg['marker_height']) ? (int)$leg['marker_height'] : 40;
                    $is_img = (preg_match('/\.(png|jpe?g|gif|svg|webp)$/i', $icon) || strpos($icon, '/') !== false);
                    $photo = !empty($leg['image_path']) ? $leg['image_path'] : '';
                    if (!empty($photo) && strpos($photo, 'uploads/') === 0) {
                        $photo_display = $photo;
                    } elseif (!empty($photo) && strpos($photo, 'public/') === 0) {
                        $photo_display = '../' . $photo;
                    } else {
                        $photo_display = $photo;
                    }
                ?>
                    <div class="saved-legend-marker" id="savedMarker_<?= $leg['id'] ?>" style="top: <?= $top ?>%; left: <?= $left ?>%;" title="Click to view photo: <?= htmlspecialchars($leg['name']) ?>" onclick="viewLandmarkImage('<?= htmlspecialchars($leg['name']) ?>', '<?= htmlspecialchars($photo_display) ?>', '<?= htmlspecialchars($icon) ?>', '<?= htmlspecialchars($color) ?>')">
                        <div class="saved-marker-label">
                            <?= htmlspecialchars($leg['name']) ?>
                            <?php if (!empty($photo)): ?>
                                <i class="bi bi-image" style="color: #0284c7; margin-left: 2px;"></i>
                            <?php endif; ?>
                        </div>
                        <?php if ($is_img): ?>
                            <img src="<?= htmlspecialchars((strpos($icon, 'uploads/') === 0 ? '' : 'uploads/legends/') . basename($icon)) ?>" alt="Icon" style="width: <?= $w ?>px; height: <?= $h ?>px; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));">
                        <?php else: 
                            $bi_cls = strpos($icon, 'bi-') === 0 ? $icon : ('bi-' . ltrim($icon, 'bi '));
                        ?>
                            <div class="saved-marker-pin" style="background: <?= htmlspecialchars($color) ?>; width: <?= $w ?>px; height: <?= $h ?>px;">
                                <i class="bi <?= htmlspecialchars($bi_cls) ?>"></i>
                            </div>
                            <div class="saved-marker-pointer" style="border-top-color: <?= htmlspecialchars($color) ?>;"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Active Draggable Marker Being Created / Positioned -->
                <div class="draggable-legend-marker" id="activeDraggableMarker" style="top: 50.00%; left: 50.00%;" title="Drag me to set location on map">
                    <div class="draggable-legend-label" id="draggableMarkerLabel">New Landmark</div>
                    <div class="draggable-legend-pin" id="draggableMarkerPin" style="background: #6366f1; width: 42px; height: 42px;">
                        <div class="draggable-pulse-ring"></div>
                        <i class="bi bi-building-fill" id="draggableMarkerIcon"></i>
                        <img id="draggableMarkerImg" src="" style="width: 26px; height: 26px; object-fit: contain; display: none;" alt="Marker">
                    </div>
                    <div class="draggable-legend-pointer" id="draggableMarkerPointer" style="border-top-color: #6366f1;"></div>
                </div>
            </div>

            <!-- 2. Form & Table Section -->
            <div class="legend-layout-grid">
                
                <!-- Left: Add Legend & Marker Form -->
                <div class="legend-card">
                    <div class="legend-card-header">
                        <h3><i class="bi bi-plus-circle-fill" style="color: #ffc107;"></i> Add Legend & Map Pin</h3>
                    </div>

                    <form action="legend.php" method="POST" enctype="multipart/form-data" id="addLegendForm">
                        <input type="hidden" name="action" value="create">
                        <input type="hidden" name="icon_type" id="addIconType" value="preset">
                        <input type="hidden" name="icon_preset" id="addIconPreset" value="bi-building-fill">

                        <!-- Legend Name -->
                        <div class="form-group">
                            <label for="addLegendName">Legend / Landmark Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" id="addLegendName" name="name" class="form-control" placeholder="e.g., Barangay Hall, Evacuation Center, Daycare" required autocomplete="off">
                            <small style="color: #64748b; font-size: 0.8rem; display: block; margin-top: 4px;">This title will be posted in the Spot Map Legend and marker tooltip.</small>
                        </div>

                        <!-- Landmark Photo (Viewed when icon is clicked) -->
                        <div class="form-group" style="background: #f8fafc; border: 1.5px dashed #0284c7; padding: 14px; border-radius: 10px;">
                            <label for="addLandmarkImage" style="color: #0369a1; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                                <i class="bi bi-camera-fill" style="font-size: 1.1rem;"></i> Landmark Photo (Shown when icon is clicked)
                            </label>
                            <input type="file" id="addLandmarkImage" name="landmark_image" class="form-control file-input" accept="image/*">
                            <small style="color: #0369a1; font-size: 0.8rem; display: block; margin-top: 4px;">
                                Upload the actual photo of this place (e.g., Barangay Hall building). When a user clicks this icon on the map or legend, this image will pop up!
                            </small>
                            <div id="addPhotoPreviewContainer" style="display: none; margin-top: 10px; align-items: center; gap: 10px;">
                                <img id="addPhotoPreviewImg" src="" alt="Photo Preview" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 2px solid #0284c7;">
                                <span style="font-size: 0.85rem; color: #0284c7; font-weight: 600;"><i class="bi bi-check2-circle"></i> Photo ready to attach!</span>
                            </div>
                        </div>

                        <!-- Icon Color -->
                        <div class="form-group">
                            <label>Icon & Pin Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" id="addColorNative" name="color_hex" value="#6366f1" class="color-input-native" title="Choose custom color">
                                <input type="text" id="addColorHexText" class="form-control" value="#6366f1" style="max-width: 120px; font-family: monospace; font-weight: 600;" placeholder="#6366f1">
                            </div>
                            <!-- Quick Palette Swatches -->
                            <div class="color-preset-chips" id="addColorSwatches">
                                <button type="button" class="color-swatch-btn active" data-color="#6366f1" style="background: #6366f1;" title="Indigo (Barangay Hall)"></button>
                                <button type="button" class="color-swatch-btn" data-color="#10b981" style="background: #10b981;" title="Emerald (School)"></button>
                                <button type="button" class="color-swatch-btn" data-color="#f59e0b" style="background: #f59e0b;" title="Amber (Court)"></button>
                                <button type="button" class="color-swatch-btn" data-color="#e11d48" style="background: #e11d48;" title="Rose (Health Center)"></button>
                                <button type="button" class="color-swatch-btn" data-color="#94a3b8" style="background: #94a3b8;" title="Slate (Other Landmarks)"></button>
                                <button type="button" class="color-swatch-btn" data-color="#0284c7" style="background: #0284c7;" title="Sky Blue"></button>
                                <button type="button" class="color-swatch-btn" data-color="#8b5cf6" style="background: #8b5cf6;" title="Purple"></button>
                                <button type="button" class="color-swatch-btn" data-color="#093d25" style="background: #093d25;" title="Barangay Green"></button>
                                <button type="button" class="color-swatch-btn" data-color="#ea580c" style="background: #ea580c;" title="Orange"></button>
                                <button type="button" class="color-swatch-btn" data-color="#0d9488" style="background: #0d9488;" title="Teal"></button>
                            </div>
                        </div>

                        <!-- Icon Selection Mode -->
                        <div class="form-group">
                            <label>Icon Selection</label>
                            <div class="icon-type-tabs">
                                <button type="button" class="icon-tab-btn active" data-type="preset"><i class="bi bi-grid-fill"></i> Presets</button>
                                <button type="button" class="icon-tab-btn" data-type="bootstrap"><i class="bi bi-code-slash"></i> Custom BI Class</button>
                                <button type="button" class="icon-tab-btn" data-type="upload"><i class="bi bi-cloud-arrow-up-fill"></i> Upload Image</button>
                            </div>

                            <!-- Panel 1: Presets -->
                            <div id="panelPreset" class="icon-tab-panel">
                                <div class="preset-icons-grid" id="addPresetGrid">
                                    <div class="preset-icon-chip selected" data-icon="bi-building-fill" title="Barangay Hall / City Hall">
                                        <i class="bi bi-building-fill"></i>
                                        <span>Hall</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-book-half" title="School / Education">
                                        <i class="bi bi-book-half"></i>
                                        <span>School</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-dribbble" title="Basketball Court / Gym">
                                        <i class="bi bi-dribbble"></i>
                                        <span>Court</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-heart-pulse-fill" title="Health Center / Clinic">
                                        <i class="bi bi-heart-pulse-fill"></i>
                                        <span>Health</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-geo-fill" title="General Landmark / Pin">
                                        <i class="bi bi-geo-fill"></i>
                                        <span>Pin</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-house-heart-fill" title="Household / Residence">
                                        <i class="bi bi-house-heart-fill"></i>
                                        <span>House</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-hospital" title="Hospital / Medical">
                                        <i class="bi bi-hospital"></i>
                                        <span>Hospital</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-shop" title="Market / Sari-Sari Store">
                                        <i class="bi bi-shop"></i>
                                        <span>Store</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-bell-fill" title="Chapel / Church">
                                        <i class="bi bi-bell-fill"></i>
                                        <span>Chapel</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-shield-check" title="Barangay Tanod / Police Outpost">
                                        <i class="bi bi-shield-check"></i>
                                        <span>Security</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-water" title="Water Source / Spring">
                                        <i class="bi bi-water"></i>
                                        <span>Water</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-tree-fill" title="Park / Farm / Forest">
                                        <i class="bi bi-tree-fill"></i>
                                        <span>Park</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-mortarboard-fill" title="Daycare / College">
                                        <i class="bi bi-mortarboard-fill"></i>
                                        <span>Daycare</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-flag-fill" title="Plaza / Landmark">
                                        <i class="bi bi-flag-fill"></i>
                                        <span>Plaza</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-lightning-charge-fill" title="Power / Substation">
                                        <i class="bi bi-lightning-charge-fill"></i>
                                        <span>Power</span>
                                    </div>
                                    <div class="preset-icon-chip" data-icon="bi-pin-map-fill" title="Evacuation Center">
                                        <i class="bi bi-pin-map-fill"></i>
                                        <span>Evacuation</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel 2: Custom Bootstrap Icon -->
                            <div id="panelBootstrap" class="icon-tab-panel" style="display: none;">
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <input type="text" id="addIconBootstrap" name="icon_bootstrap" class="form-control" placeholder="e.g., bi-hospital, bi-shop, bi-cross" value="bi-building-fill">
                                    <div id="addBootstrapPreviewIcon" style="width: 42px; height: 42px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #6366f1; flex-shrink: 0;">
                                        <i class="bi bi-building-fill"></i>
                                    </div>
                                </div>
                                <small style="color: #64748b; font-size: 0.8rem; display: block; margin-top: 4px;">Enter any Bootstrap Icons class (e.g. <code>bi-hospital</code>).</small>
                            </div>

                            <!-- Panel 3: Upload Image -->
                            <div id="panelUpload" class="icon-tab-panel" style="display: none;">
                                <input type="file" id="addIconImage" name="marker_image" class="form-control file-input" accept="image/png, image/jpeg, image/svg+xml, image/webp">
                                <small style="color: #64748b; font-size: 0.8rem; display: block; margin-top: 4px;">Upload transparent PNG, SVG, or marker icon (approx. 40x40px recommended).</small>
                            </div>
                        </div>

                        <!-- Map Coordinates (Auto-updated when dragging pin above) -->
                        <div class="row">
                            <div class="col form-group">
                                <label for="topPositionInput"><i class="bi bi-arrows-vertical"></i> Top Position (%)</label>
                                <input type="number" step="0.01" id="topPositionInput" name="top_position" value="50.00" class="form-control" style="font-weight: 600; color: #093d25;">
                            </div>
                            <div class="col form-group">
                                <label for="leftPositionInput"><i class="bi bi-arrows-horizontal"></i> Left Position (%)</label>
                                <input type="number" step="0.01" id="leftPositionInput" name="left_position" value="50.00" class="form-control" style="font-weight: 600; color: #093d25;">
                            </div>
                        </div>

                        <!-- Marker Dimensions -->
                        <div class="row">
                            <div class="col form-group">
                                <label for="markerWidthInput">Marker Width (px)</label>
                                <input type="number" id="markerWidthInput" name="marker_width" value="40" class="form-control">
                            </div>
                            <div class="col form-group">
                                <label for="markerHeightInput">Marker Height (px)</label>
                                <input type="number" id="markerHeightInput" name="marker_height" value="40" class="form-control">
                            </div>
                        </div>

                        <!-- 2-Col: Display Order & Status -->
                        <div class="row">
                            <div class="col form-group">
                                <label for="addDisplayOrder">Display Order</label>
                                <input type="number" id="addDisplayOrder" name="display_order" class="form-control" value="<?= $next_order ?>" min="1">
                            </div>
                            <div class="col form-group">
                                <label for="addIsActive">Status</label>
                                <select id="addIsActive" name="is_active" class="form-control">
                                    <option value="1">Active (Shown on Map & Legend)</option>
                                    <option value="0">Inactive (Hidden)</option>
                                </select>
                            </div>
                        </div>

                        <!-- LIVE PREVIEW BOX (Matching the Spot Map Card) -->
                        <div class="live-preview-box">
                            <div class="live-preview-box-title">
                                <i class="bi bi-eye-fill" style="color: #093d25;"></i> Live Spot Map Legend Card Preview
                            </div>
                            <div class="preview-legend-card">
                                <h4>
                                    <i class="bi bi-map" style="color: #ffc107; font-size: 1.3rem;"></i> Map Legend
                                </h4>
                                <div class="preview-legend-item" id="previewLegendItem">
                                    <i class="bi bi-building-fill" id="livePreviewIcon" style="color: #6366f1;"></i>
                                    <img id="livePreviewImg" src="" style="display: none;" alt="Preview">
                                    <span id="livePreviewName">Barangay Hall</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit-legend">
                            <i class="bi bi-save"></i> Save Legend & Pin to Map
                        </button>
                    </form>
                </div>

                <!-- Right: Existing Legends List -->
                <div class="legend-card">
                    <div class="legend-card-header">
                        <h3><i class="bi bi-layers-fill" style="color: #093d25;"></i> Active Map Legends & Pins (<?= count($legends) ?>)</h3>
                    </div>

                    <?php if (empty($legends)): ?>
                        <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                            <i class="bi bi-map" style="font-size: 3rem; display: block; margin-bottom: 10px; color: #cbd5e1;"></i>
                            <h4 style="color: #475569; margin-bottom: 5px;">No Legend Items Added Yet</h4>
                            <p style="font-size: 0.9rem;">Fill in the form on the left to add your first legend to the Spot Map.</p>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="legends-table">
                                <thead>
                                    <tr>
                                        <th style="width: 45px;">#</th>
                                        <th style="width: 55px;">Icon</th>
                                        <th>Photo</th>
                                        <th>Legend Name</th>
                                        <th>Position (%)</th>
                                        <th style="width: 80px;">Status</th>
                                        <th style="text-align: center; width: 160px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($legends as $leg): 
                                        $icon = $leg['icon_path'] ?? 'bi-geo-alt-fill';
                                        $color = $leg['color_hex'] ?? '#6366f1';
                                        $top = !empty($leg['top_position']) ? (float)$leg['top_position'] : 50.00;
                                        $left = !empty($leg['left_position']) ? (float)$leg['left_position'] : 50.00;
                                        $is_img = (preg_match('/\.(png|jpe?g|gif|svg|webp)$/i', $icon) || strpos($icon, '/') !== false);
                                        $photo = !empty($leg['image_path']) ? $leg['image_path'] : '';
                                        if (!empty($photo) && strpos($photo, 'uploads/') === 0) {
                                            $photo_display = $photo;
                                        } elseif (!empty($photo) && strpos($photo, 'public/') === 0) {
                                            $photo_display = '../' . $photo;
                                        } else {
                                            $photo_display = $photo;
                                        }
                                    ?>
                                    <tr id="rowLegend_<?= $leg['id'] ?>">
                                        <td style="font-weight: 700; color: #94a3b8; font-size: 0.85rem;">
                                            <?= (int)($leg['display_order'] ?? 0) ?>
                                        </td>
                                        <td>
                                            <div class="legend-badge-icon">
                                                <?php if ($is_img): ?>
                                                    <img src="<?= htmlspecialchars((strpos($icon, 'uploads/') === 0 ? '' : 'uploads/legends/') . basename($icon)) ?>" alt="Icon" style="width: 22px; height: 22px; object-fit: contain;">
                                                <?php else: 
                                                    $bi_class = strpos($icon, 'bi-') === 0 ? $icon : ('bi-' . ltrim($icon, 'bi '));
                                                ?>
                                                    <i class="bi <?= htmlspecialchars($bi_class) ?>" style="color: <?= htmlspecialchars($color) ?>;"></i>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($photo)): ?>
                                                <img src="<?= htmlspecialchars($photo_display) ?>" alt="Photo" style="width: 44px; height: 44px; border-radius: 8px; object-fit: cover; border: 1.5px solid #cbd5e1; cursor: pointer;" title="Click to view full photo" onclick="viewLandmarkImage('<?= htmlspecialchars($leg['name']) ?>', '<?= htmlspecialchars($photo_display) ?>', '<?= htmlspecialchars($icon) ?>', '<?= htmlspecialchars($color) ?>')">
                                            <?php else: ?>
                                                <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic;">No photo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong style="color: #1e293b;"><?= htmlspecialchars($leg['name']) ?></strong>
                                            <div style="font-size: 0.78rem; color: #64748b; font-family: monospace;">
                                                <?= htmlspecialchars($icon) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="font-size: 0.82rem; font-family: monospace; color: #0284c7; background: #e0f2fe; padding: 2px 6px; border-radius: 4px;">
                                                <?= number_format($top, 1) ?>%, <?= number_format($left, 1) ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-status <?= ($leg['is_active'] == 1) ? 'active' : 'inactive' ?>">
                                                <?= ($leg['is_active'] == 1) ? 'Active' : 'Hidden' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons" style="justify-content: center;">
                                                <!-- Locate on Map Button -->
                                                <button type="button" class="btn-action-sm btn-action-toggle" onclick="highlightMapPin(<?= $leg['id'] ?>, <?= $top ?>, <?= $left ?>)" title="View on map above">
                                                    <i class="bi bi-geo-alt"></i>
                                                </button>

                                                <!-- Edit Button -->
                                                <button type="button" class="btn-action-sm btn-action-edit" onclick='openEditModal(<?= json_encode($leg, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Edit legend & position">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- Toggle Status -->
                                                <form action="legend.php" method="POST" style="margin: 0; display: inline;">
                                                    <input type="hidden" name="action" value="toggle_status">
                                                    <input type="hidden" name="id" value="<?= (int)$leg['id'] ?>">
                                                    <button type="submit" class="btn-action-sm btn-action-toggle" title="Toggle visibility on map">
                                                        <i class="bi bi-eye<?= ($leg['is_active'] == 1) ? '-slash' : '' ?>"></i>
                                                    </button>
                                                </form>

                                                <!-- Delete -->
                                                <form action="legend.php" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Are you sure you want to delete this legend and map marker?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= (int)$leg['id'] ?>">
                                                    <button type="submit" class="btn-action-sm btn-action-delete" title="Delete legend item">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>

    <!-- Edit Legend Modal -->
    <div id="editLegendModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="bi bi-pencil-square"></i> Edit Legend & Position</h3>
                <button type="button" class="modal-close-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form action="legend.php" method="POST" enctype="multipart/form-data" id="editLegendForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" id="editLegendId">
                    <input type="hidden" name="icon_type" id="editIconType" value="preset">
                    <input type="hidden" name="icon_preset" id="editIconPreset" value="bi-building-fill">

                    <div class="form-group">
                        <label for="editLegendName">Legend Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="editLegendName" name="name" class="form-control" required>
                    </div>

                    <!-- Edit Landmark Actual Photo -->
                    <div class="form-group" style="background: #f8fafc; border: 1.5px dashed #0284c7; padding: 12px; border-radius: 8px;">
                        <label for="editLandmarkImage" style="color: #0369a1; font-weight: 700;"><i class="bi bi-camera-fill"></i> Change Landmark Photo (Actual Image)</label>
                        <input type="file" id="editLandmarkImage" name="landmark_image" class="form-control file-input" accept="image/*">
                        <div id="editPhotoCurrentContainer" style="display: none; margin-top: 8px; align-items: center; gap: 10px;">
                            <img id="editPhotoCurrentImg" src="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;">
                            <span style="font-size: 0.8rem; color: #475569;">Current attached photo (select a new file above to replace it).</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Icon & Pin Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" id="editColorNative" name="color_hex" value="#6366f1" class="color-input-native">
                            <input type="text" id="editColorHexText" class="form-control" value="#6366f1" style="max-width: 120px; font-family: monospace; font-weight: 600;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Icon Selection</label>
                        <div class="icon-type-tabs">
                            <button type="button" class="icon-tab-btn active" id="editTabPreset" onclick="setEditIconTab('preset')"><i class="bi bi-grid-fill"></i> Presets</button>
                            <button type="button" class="icon-tab-btn" id="editTabBootstrap" onclick="setEditIconTab('bootstrap')"><i class="bi bi-code-slash"></i> BI Class</button>
                            <button type="button" class="icon-tab-btn" id="editTabUpload" onclick="setEditIconTab('upload')"><i class="bi bi-cloud-arrow-up-fill"></i> New File</button>
                        </div>

                        <div id="editPanelPreset" class="icon-tab-panel">
                            <div class="preset-icons-grid" id="editPresetGrid">
                                <div class="preset-icon-chip" data-icon="bi-building-fill"><i class="bi bi-building-fill"></i><span>Hall</span></div>
                                <div class="preset-icon-chip" data-icon="bi-book-half"><i class="bi bi-book-half"></i><span>School</span></div>
                                <div class="preset-icon-chip" data-icon="bi-dribbble"><i class="bi bi-dribbble"></i><span>Court</span></div>
                                <div class="preset-icon-chip" data-icon="bi-heart-pulse-fill"><i class="bi bi-heart-pulse-fill"></i><span>Health</span></div>
                                <div class="preset-icon-chip" data-icon="bi-geo-fill"><i class="bi bi-geo-fill"></i><span>Pin</span></div>
                                <div class="preset-icon-chip" data-icon="bi-house-heart-fill"><i class="bi bi-house-heart-fill"></i><span>House</span></div>
                                <div class="preset-icon-chip" data-icon="bi-hospital"><i class="bi bi-hospital"></i><span>Hospital</span></div>
                                <div class="preset-icon-chip" data-icon="bi-shop"><i class="bi bi-shop"></i><span>Store</span></div>
                                <div class="preset-icon-chip" data-icon="bi-bell-fill"><i class="bi bi-bell-fill"></i><span>Chapel</span></div>
                                <div class="preset-icon-chip" data-icon="bi-shield-check"><i class="bi bi-shield-check"></i><span>Security</span></div>
                                <div class="preset-icon-chip" data-icon="bi-water"><i class="bi bi-water"></i><span>Water</span></div>
                                <div class="preset-icon-chip" data-icon="bi-tree-fill"><i class="bi bi-tree-fill"></i><span>Park</span></div>
                                <div class="preset-icon-chip" data-icon="bi-mortarboard-fill"><i class="bi bi-mortarboard-fill"></i><span>Daycare</span></div>
                                <div class="preset-icon-chip" data-icon="bi-flag-fill"><i class="bi bi-flag-fill"></i><span>Plaza</span></div>
                                <div class="preset-icon-chip" data-icon="bi-pin-map-fill"><i class="bi bi-pin-map-fill"></i><span>Evacuation</span></div>
                            </div>
                        </div>

                        <div id="editPanelBootstrap" class="icon-tab-panel" style="display: none;">
                            <input type="text" id="editIconBootstrap" name="icon_bootstrap" class="form-control" placeholder="e.g. bi-hospital">
                        </div>

                        <div id="editPanelUpload" class="icon-tab-panel" style="display: none;">
                            <input type="file" id="editIconImage" name="marker_image" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <!-- Map Coordinates in Edit Modal -->
                    <div class="row">
                        <div class="col form-group">
                            <label for="editTopPosition">Top Position (%)</label>
                            <input type="number" step="0.01" id="editTopPosition" name="top_position" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label for="editLeftPosition">Left Position (%)</label>
                            <input type="number" step="0.01" id="editLeftPosition" name="left_position" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col form-group">
                            <label for="editMarkerWidth">Marker Width (px)</label>
                            <input type="number" id="editMarkerWidth" name="marker_width" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label for="editMarkerHeight">Marker Height (px)</label>
                            <input type="number" id="editMarkerHeight" name="marker_height" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col form-group">
                            <label for="editDisplayOrder">Display Order</label>
                            <input type="number" id="editDisplayOrder" name="display_order" class="form-control" min="1">
                        </div>
                        <div class="col form-group">
                            <label for="editIsActive">Status</label>
                            <select id="editIsActive" name="is_active" class="form-control">
                                <option value="1">Active (Shown on Map & Legend)</option>
                                <option value="0">Inactive (Hidden)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Landmark Photo Preview Lightbox Modal -->
    <div id="landmarkPhotoModal" class="modal-overlay" style="display: none; align-items: center; justify-content: center; z-index: 10000; padding: 20px;">
        <div style="background: #ffffff; border-radius: 20px; width: 95%; max-width: 780px; box-shadow: 0 20px 60px rgba(0,0,0,0.35); overflow: hidden; position: relative; animation: modalFadeIn 0.25s ease-out;">
            <div style="background: #093d25; color: white; padding: 20px 28px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div id="photoModalIconBadge" style="width: 42px; height: 42px; border-radius: 50%; background: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #ffffff; border: 2px solid rgba(255,255,255,0.4); flex-shrink: 0;">
                        <i class="bi bi-building-fill" id="photoModalIcon"></i>
                    </div>
                    <div>
                        <h4 id="photoModalTitle" style="margin: 0; font-size: 1.35rem; font-weight: 700; color: #ffffff;">Barangay Hall</h4>
                        <span style="font-size: 0.85rem; color: #ffc107; font-weight: 600;">Barangay Maruing Landmark</span>
                    </div>
                </div>
                <button type="button" onclick="closeLandmarkPhotoModal()" style="background: none; border: none; font-size: 2rem; color: rgba(255,255,255,0.85); cursor: pointer; line-height: 1;">&times;</button>
            </div>
            
            <div style="padding: 24px; text-align: center; background: #0f172a;">
                <img id="photoModalImg" src="" alt="Landmark Photo" style="width: 100%; max-height: 520px; object-fit: contain; border-radius: 12px; box-shadow: 0 6px 25px rgba(0,0,0,0.5); display: none;">
                <div id="photoModalEmpty" style="padding: 60px 20px; color: #94a3b8; display: none;">
                    <i class="bi bi-camera-slash" style="font-size: 4rem; display: block; margin-bottom: 12px; color: #64748b;"></i>
                    <p style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #cbd5e1;">No actual photo uploaded for this landmark yet.</p>
                </div>
            </div>

            <div style="padding: 16px 28px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.88rem; color: #64748b;"><i class="bi bi-info-circle"></i> Displays when residents click this marker on Spot Map</span>
                <button type="button" onclick="closeLandmarkPhotoModal()" style="background: #e2e8f0; color: #334155; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; cursor: pointer;">Close</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="Admin JS/admin.js"></script>
    <script>
        // DOM Elements
        const mapContainer = document.getElementById('aerialMapContainer');
        const activeMarker = document.getElementById('activeDraggableMarker');
        const markerLabel = document.getElementById('draggableMarkerLabel');
        const markerPin = document.getElementById('draggableMarkerPin');
        const markerIcon = document.getElementById('draggableMarkerIcon');
        const markerImg = document.getElementById('draggableMarkerImg');
        const markerPointer = document.getElementById('draggableMarkerPointer');

        const topInput = document.getElementById('topPositionInput');
        const leftInput = document.getElementById('leftPositionInput');
        const widthInput = document.getElementById('markerWidthInput');
        const heightInput = document.getElementById('markerHeightInput');

        const addLegendName = document.getElementById('addLegendName');
        const addColorNative = document.getElementById('addColorNative');
        const addColorHexText = document.getElementById('addColorHexText');
        const livePreviewName = document.getElementById('livePreviewName');
        const livePreviewIcon = document.getElementById('livePreviewIcon');
        const livePreviewImg = document.getElementById('livePreviewImg');
        const addIconType = document.getElementById('addIconType');
        const addIconPreset = document.getElementById('addIconPreset');
        const addIconBootstrap = document.getElementById('addIconBootstrap');
        const addBootstrapPreviewIcon = document.getElementById('addBootstrapPreviewIcon');
        const addIconImage = document.getElementById('addIconImage');
        const addLandmarkImage = document.getElementById('addLandmarkImage');
        const addPhotoPreviewContainer = document.getElementById('addPhotoPreviewContainer');
        const addPhotoPreviewImg = document.getElementById('addPhotoPreviewImg');

        // Preview uploaded landmark photo in form
        if (addLandmarkImage) {
            addLandmarkImage.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        addPhotoPreviewImg.src = evt.target.result;
                        addPhotoPreviewContainer.style.display = 'flex';
                    };
                    reader.readAsDataURL(file);
                } else {
                    addPhotoPreviewContainer.style.display = 'none';
                }
            });
        }

        // Update live preview & active draggable marker visuals
        function updateLiveVisuals() {
            const nameVal = addLegendName.value.trim();
            const displayName = nameVal || 'New Landmark';
            
            // 1. Text Labels
            markerLabel.textContent = displayName;
            livePreviewName.textContent = nameVal || 'Barangay Hall';

            // 2. Colors
            const colorVal = addColorNative.value;
            markerPin.style.background = colorVal;
            markerPointer.style.borderTopColor = colorVal;
            livePreviewIcon.style.color = colorVal;

            // 3. Icons
            const currentType = addIconType.value;
            if (currentType === 'preset') {
                markerIcon.style.display = 'inline-block';
                markerImg.style.display = 'none';
                livePreviewIcon.style.display = 'inline-block';
                livePreviewImg.style.display = 'none';

                const iconClass = addIconPreset.value || 'bi-building-fill';
                markerIcon.className = 'bi ' + iconClass;
                livePreviewIcon.className = 'bi ' + iconClass;
            } else if (currentType === 'bootstrap') {
                markerIcon.style.display = 'inline-block';
                markerImg.style.display = 'none';
                livePreviewIcon.style.display = 'inline-block';
                livePreviewImg.style.display = 'none';

                let bClass = addIconBootstrap.value.trim() || 'bi-geo-alt-fill';
                if (!bClass.startsWith('bi-') && !bClass.startsWith('bi ')) {
                    bClass = 'bi-' + bClass;
                }
                markerIcon.className = 'bi ' + bClass;
                livePreviewIcon.className = 'bi ' + bClass;
                if (addBootstrapPreviewIcon) {
                    addBootstrapPreviewIcon.innerHTML = '<i class="bi ' + bClass + '"></i>';
                    addBootstrapPreviewIcon.style.color = colorVal;
                }
            } else if (currentType === 'upload') {
                if (addIconImage.files && addIconImage.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        markerImg.src = e.target.result;
                        markerImg.style.display = 'inline-block';
                        markerIcon.style.display = 'none';

                        livePreviewImg.src = e.target.result;
                        livePreviewImg.style.display = 'inline-block';
                        livePreviewIcon.style.display = 'none';
                    };
                    reader.readAsDataURL(addIconImage.files[0]);
                }
            }

            // 4. Dimensions
            const w = widthInput ? parseInt(widthInput.value) || 40 : 40;
            const h = heightInput ? parseInt(heightInput.value) || 40 : 40;
            markerPin.style.width = w + 'px';
            markerPin.style.height = h + 'px';
        }

        // Set Marker Position on Map
        function setMarkerPosition(top, left) {
            top = Math.max(2, Math.min(98, parseFloat(top) || 50));
            left = Math.max(2, Math.min(98, parseFloat(left) || 50));

            activeMarker.style.top = top + '%';
            activeMarker.style.left = left + '%';

            if (topInput) topInput.value = top.toFixed(2);
            if (leftInput) leftInput.value = left.toFixed(2);
        }

        // Draggable Marker Interaction
        let isDragging = false;

        activeMarker.addEventListener('mousedown', function(e) {
            isDragging = true;
            activeMarker.style.cursor = 'grabbing';
            e.preventDefault();
            e.stopPropagation();
        });

        activeMarker.addEventListener('touchstart', function(e) {
            isDragging = true;
            e.stopPropagation();
        }, { passive: true });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;

            const rect = mapContainer.getBoundingClientRect();
            let x = e.clientX - rect.left;
            let y = e.clientY - rect.top;

            x = Math.max(0, Math.min(x, rect.width));
            y = Math.max(0, Math.min(y, rect.height));

            const leftPct = (x / rect.width) * 100;
            const topPct = (y / rect.height) * 100;

            setMarkerPosition(topPct, leftPct);
        });

        document.addEventListener('touchmove', function(e) {
            if (!isDragging || !e.touches[0]) return;

            const rect = mapContainer.getBoundingClientRect();
            let x = e.touches[0].clientX - rect.left;
            let y = e.touches[0].clientY - rect.top;

            x = Math.max(0, Math.min(x, rect.width));
            y = Math.max(0, Math.min(y, rect.height));

            const leftPct = (x / rect.width) * 100;
            const topPct = (y / rect.height) * 100;

            setMarkerPosition(topPct, leftPct);
        }, { passive: true });

        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                activeMarker.style.cursor = 'grab';
            }
        });

        document.addEventListener('touchend', function() {
            isDragging = false;
        });

        // Clicking on Map moves marker to clicked spot
        mapContainer.addEventListener('click', function(e) {
            if (e.target.closest('.saved-legend-marker') || e.target.closest('.map-banner-overlay') || e.target.closest('#activeDraggableMarker')) {
                return;
            }
            const rect = mapContainer.getBoundingClientRect();
            let x = e.clientX - rect.left;
            let y = e.clientY - rect.top;

            const leftPct = (x / rect.width) * 100;
            const topPct = (y / rect.height) * 100;

            setMarkerPosition(topPct, leftPct);
        });

        // Manual coordinate changes
        if (topInput) topInput.addEventListener('input', () => setMarkerPosition(topInput.value, leftInput.value));
        if (leftInput) leftInput.addEventListener('input', () => setMarkerPosition(topInput.value, leftInput.value));
        if (widthInput) widthInput.addEventListener('input', updateLiveVisuals);
        if (heightInput) heightInput.addEventListener('input', updateLiveVisuals);

        // Inputs for live text / color
        addLegendName.addEventListener('input', updateLiveVisuals);

        addColorNative.addEventListener('input', function() {
            addColorHexText.value = this.value;
            updateLiveVisuals();
        });

        addColorHexText.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                addColorNative.value = this.value;
                updateLiveVisuals();
            }
        });

        // Color Swatches
        document.querySelectorAll('#addColorSwatches .color-swatch-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('#addColorSwatches .color-swatch-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const col = this.getAttribute('data-color');
                addColorNative.value = col;
                addColorHexText.value = col;
                updateLiveVisuals();
            });
        });

        // Icon tab switcher
        document.querySelectorAll('.icon-tab-btn').forEach(tab => {
            tab.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                if (!type) return;

                document.querySelectorAll('.icon-type-tabs .icon-tab-btn').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                addIconType.value = type;

                document.getElementById('panelPreset').style.display = (type === 'preset') ? 'block' : 'none';
                document.getElementById('panelBootstrap').style.display = (type === 'bootstrap') ? 'block' : 'none';
                document.getElementById('panelUpload').style.display = (type === 'upload') ? 'block' : 'none';

                updateLiveVisuals();
            });
        });

        // Preset icon selection
        document.querySelectorAll('#addPresetGrid .preset-icon-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                document.querySelectorAll('#addPresetGrid .preset-icon-chip').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                const icon = this.getAttribute('data-icon');
                addIconPreset.value = icon;
                updateLiveVisuals();
            });
        });

        addIconBootstrap.addEventListener('input', updateLiveVisuals);
        addIconImage.addEventListener('change', updateLiveVisuals);

        // Highlight marker on map when Locate clicked
        function highlightMapPin(id, top, left) {
            const saved = document.getElementById('savedMarker_' + id);
            mapContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (saved) {
                saved.style.transform = 'translate(-50%, -120%) scale(1.4)';
                saved.style.zIndex = '1200';
                setTimeout(() => {
                    saved.style.transform = '';
                    saved.style.zIndex = '';
                }, 1800);
            }
        }

        // View Landmark Photo Modal
        const photoModal = document.getElementById('landmarkPhotoModal');
        const photoModalTitle = document.getElementById('photoModalTitle');
        const photoModalImg = document.getElementById('photoModalImg');
        const photoModalEmpty = document.getElementById('photoModalEmpty');
        const photoModalIcon = document.getElementById('photoModalIcon');
        const photoModalIconBadge = document.getElementById('photoModalIconBadge');

        function viewLandmarkImage(name, imageSrc, iconClass, colorHex) {
            photoModalTitle.textContent = name;
            photoModalIconBadge.style.background = colorHex || '#6366f1';
            
            if (iconClass && (iconClass.startsWith('bi-') || iconClass.startsWith('bi '))) {
                photoModalIcon.className = 'bi ' + (iconClass.startsWith('bi-') ? iconClass : 'bi-' + iconClass.replace('bi ', ''));
                photoModalIcon.style.display = 'inline-block';
            } else {
                photoModalIcon.className = 'bi bi-geo-alt-fill';
            }

            if (imageSrc && imageSrc.trim() !== '') {
                photoModalImg.src = imageSrc;
                photoModalImg.style.display = 'block';
                photoModalEmpty.style.display = 'none';
            } else {
                photoModalImg.style.display = 'none';
                photoModalEmpty.style.display = 'block';
            }

            photoModal.style.display = 'flex';
        }

        function closeLandmarkPhotoModal() {
            photoModal.style.display = 'none';
        }

        // Edit Modal Logic
        const editModal = document.getElementById('editLegendModal');
        const editLegendId = document.getElementById('editLegendId');
        const editLegendName = document.getElementById('editLegendName');
        const editColorNative = document.getElementById('editColorNative');
        const editColorHexText = document.getElementById('editColorHexText');
        const editIconType = document.getElementById('editIconType');
        const editIconPreset = document.getElementById('editIconPreset');
        const editIconBootstrap = document.getElementById('editIconBootstrap');
        const editTopPosition = document.getElementById('editTopPosition');
        const editLeftPosition = document.getElementById('editLeftPosition');
        const editMarkerWidth = document.getElementById('editMarkerWidth');
        const editMarkerHeight = document.getElementById('editMarkerHeight');
        const editDisplayOrder = document.getElementById('editDisplayOrder');
        const editIsActive = document.getElementById('editIsActive');
        const editPhotoCurrentContainer = document.getElementById('editPhotoCurrentContainer');
        const editPhotoCurrentImg = document.getElementById('editPhotoCurrentImg');

        function openEditModal(legend) {
            editLegendId.value = legend.id;
            editLegendName.value = legend.name;
            editColorNative.value = legend.color_hex || '#6366f1';
            editColorHexText.value = legend.color_hex || '#6366f1';
            editTopPosition.value = parseFloat(legend.top_position || 50.00).toFixed(2);
            editLeftPosition.value = parseFloat(legend.left_position || 50.00).toFixed(2);
            editMarkerWidth.value = legend.marker_width || 40;
            editMarkerHeight.value = legend.marker_height || 40;
            editDisplayOrder.value = legend.display_order || 1;
            editIsActive.value = legend.is_active;

            if (legend.image_path) {
                let p = legend.image_path;
                if (p.startsWith('public/')) p = '../' + p;
                editPhotoCurrentImg.src = p;
                editPhotoCurrentContainer.style.display = 'flex';
            } else {
                editPhotoCurrentContainer.style.display = 'none';
            }

            const icon = legend.icon_path || '';
            const isImg = /\.(png|jpe?g|gif|svg|webp)$/i.test(icon) || icon.includes('/');

            if (isImg) {
                setEditIconTab('upload');
            } else {
                let matched = false;
                document.querySelectorAll('#editPresetGrid .preset-icon-chip').forEach(c => {
                    if (c.getAttribute('data-icon') === icon) {
                        c.classList.add('selected');
                        matched = true;
                    } else {
                        c.classList.remove('selected');
                    }
                });
                if (matched) {
                    setEditIconTab('preset');
                    editIconPreset.value = icon;
                } else {
                    setEditIconTab('bootstrap');
                    editIconBootstrap.value = icon;
                }
            }

            editModal.style.display = 'flex';
        }

        function closeEditModal() {
            editModal.style.display = 'none';
        }

        function setEditIconTab(type) {
            editIconType.value = type;
            document.getElementById('editTabPreset').classList.toggle('active', type === 'preset');
            document.getElementById('editTabBootstrap').classList.toggle('active', type === 'bootstrap');
            document.getElementById('editTabUpload').classList.toggle('active', type === 'upload');

            document.getElementById('editPanelPreset').style.display = (type === 'preset') ? 'block' : 'none';
            document.getElementById('editPanelBootstrap').style.display = (type === 'bootstrap') ? 'block' : 'none';
            document.getElementById('editPanelUpload').style.display = (type === 'upload') ? 'block' : 'none';
        }

        document.querySelectorAll('#editPresetGrid .preset-icon-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                document.querySelectorAll('#editPresetGrid .preset-icon-chip').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                editIconPreset.value = this.getAttribute('data-icon');
            });
        });

        editColorNative.addEventListener('input', function() {
            editColorHexText.value = this.value;
        });

        editColorHexText.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                editColorNative.value = this.value;
            }
        });

        window.onclick = function(e) {
            if (e.target === editModal) {
                closeEditModal();
            }
            if (e.target === photoModal) {
                closeLandmarkPhotoModal();
            }
        };

        // Initialize state
        updateLiveVisuals();
        setMarkerPosition(50.00, 50.00);
    </script>
</body>
</html>
