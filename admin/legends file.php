<?php
// admin/legends file.php - Legends Records File
require_once 'db_connect.php';

// Fetch all legends
$legends = [];
$res = $conn->query("SELECT * FROM sm_legends ORDER BY display_order ASC, id ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $legends[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legends Records | Barangay Maruing Admin</title>
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
    <link rel="stylesheet" href="Admin CSS/legend.css?v=3">
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
            <li class="has-submenu open">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-house-door-fill"></i> Households
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="legends file.php" class="active">Legends File</a></li>
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
            <li><a href="logout.php" class="logout-link" style="color: #ef4444 !important;"><i class="bi bi-box-arrow-left" style="color: #ef4444 !important;"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-title">
                <h2>Spot Map Legend Records</h2>
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
            <div class="legend-header">
                <div class="legend-header-title">
                    <h2>Map Legends File</h2>
                    <p>Overview of all landmark icons and legend markers registered for the Spot Map</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="legend.php" class="btn-submit-legend" style="width: auto; text-decoration: none; padding: 10px 18px; font-size: 0.9rem;">
                        <i class="bi bi-plus-circle"></i> Add New Legend
                    </a>
                    <a href="../spot-map.php" target="_blank" class="btn-view-map">
                        <i class="bi bi-map-fill"></i> View Public Map
                    </a>
                </div>
            </div>
            
            <div class="legend-card" style="margin-top: 20px;">
                <div style="overflow-x: auto;">
                    <table class="legends-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">Order</th>
                                <th style="width: 60px;">Icon</th>
                                <th>Legend Name</th>
                                <th>Icon Class / Path</th>
                                <th>Color</th>
                                <th style="width: 100px;">Status</th>
                                <th style="text-align: center; width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($legends)): ?>
                                <tr>
                                    <td colspan="7" style="padding: 40px; text-align: center; color: #64748b; font-style: italic;">
                                        No legends recorded yet. <a href="legend.php" style="color: #093d25; font-weight: 600;">Click here to add one.</a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($legends as $leg): 
                                    $icon = $leg['icon_path'] ?? 'bi-geo-alt-fill';
                                    $color = $leg['color_hex'] ?? '#6366f1';
                                    $is_img = (preg_match('/\.(png|jpe?g|gif|svg|webp)$/i', $icon) || strpos($icon, '/') !== false);
                                ?>
                                    <tr>
                                        <td style="font-weight: 700; color: #94a3b8; font-size: 0.9rem;">
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
                                            <strong style="color: #093d25; font-size: 0.95rem;"><?= htmlspecialchars($leg['name']) ?></strong>
                                        </td>
                                        <td style="color: #64748b; font-family: monospace; font-size: 0.85rem;">
                                            <?= htmlspecialchars($icon) ?>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                <span style="display: inline-block; width: 14px; height: 14px; border-radius: 50%; background: <?= htmlspecialchars($color) ?>; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                                                <span style="font-size: 0.82rem; font-family: monospace; color: #475569;"><?= htmlspecialchars($color) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-status <?= ($leg['is_active'] == 1) ? 'active' : 'inactive' ?>">
                                                <?= ($leg['is_active'] == 1) ? 'Active' : 'Hidden' ?>
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="legend.php" class="btn-action-sm btn-action-edit" title="Manage on Legend Page">
                                                <i class="bi bi-pencil-square"></i> Manage
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
