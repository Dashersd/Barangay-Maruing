<?php
// admin/dashboard.php
include 'db_connect.php';

// 1. Tally Households and Estimated Residents across Purok 1 to 9
$total_households = 0;
$total_residents = 0;
$purok_stats = [];

for ($p = 1; $p <= 9; $p++) {
    $table = "sm_purok_{$p}_spots";
    $p_households = 0;
    $p_residents = 0;
    
    $res = $conn->query("SELECT husband_name, spouse_name, children FROM `$table`");
    if ($res) {
        $p_households = $res->num_rows;
        while ($row = $res->fetch_assoc()) {
            if (!empty(trim($row['husband_name'] ?? ''))) $p_residents++;
            if (!empty(trim($row['spouse_name'] ?? ''))) $p_residents++;
            if (!empty($row['children'])) {
                $children = json_decode($row['children'], true);
                if (is_array($children)) {
                    $p_residents += count($children);
                }
            }
        }
    }
    
    $purok_stats[$p] = [
        'households' => $p_households,
        'residents' => $p_residents
    ];
    $total_households += $p_households;
    $total_residents += $p_residents;
}

// 2. Count Active Officials (Barangay + SK)
$total_officials = 0;
$res_off = $conn->query("SELECT COUNT(*) AS total FROM officials WHERE status = 'active' OR status IS NULL");
if ($res_off && $row_off = $res_off->fetch_assoc()) {
    $total_officials += (int)$row_off['total'];
}
$res_sk = $conn->query("SELECT COUNT(*) AS total FROM sk_officials WHERE status = 'active' OR status IS NULL");
if ($res_sk && $row_sk = $res_sk->fetch_assoc()) {
    $total_officials += (int)$row_sk['total'];
}

// 3. Count Feedback and Inquiries (Only count unread messages sent by users/residents)
$unread_feedback = 0;
$total_feedback = 0;
$res_fb = $conn->query("SELECT COUNT(*) AS total, SUM(CASE WHEN is_read = 0 AND sender_type = 'user' THEN 1 ELSE 0 END) AS unread FROM feedback_chats");
if ($res_fb && $row_fb = $res_fb->fetch_assoc()) {
    $total_feedback = (int)$row_fb['total'];
    $unread_feedback = (int)($row_fb['unread'] ?? 0);
}

// 4. Fetch Recent Citizen Inquiries (Top 4 incoming user feedback)
$recent_inquiries = [];
$res_inq = $conn->query("SELECT sender_name, message, is_read, created_at FROM feedback_chats WHERE sender_type = 'user' ORDER BY created_at DESC LIMIT 4");
if ($res_inq) {
    while ($inq = $res_inq->fetch_assoc()) {
        $recent_inquiries[] = $inq;
    }
}

// 5. Fetch Latest Announcements (Top 3)
$recent_announcements = [];
$res_ann = $conn->query("SELECT title, what, when_date, where_place, cover_image, status, created_at FROM announcements ORDER BY created_at DESC LIMIT 3");
if ($res_ann) {
    while ($ann = $res_ann->fetch_assoc()) {
        $recent_announcements[] = $ann;
    }
}

// 6. Fetch Core Officials On Duty (Captain, Secretary, Treasurer, Kagawads)
$core_officials = [];
$res_core = $conn->query("SELECT name, position, photo FROM officials ORDER BY 
    CASE 
        WHEN position LIKE '%Captain%' OR position LIKE '%Punong%' THEN 1 
        WHEN position LIKE '%Secretary%' THEN 2 
        WHEN position LIKE '%Treasurer%' THEN 3 
        ELSE 4 
    END ASC, id ASC LIMIT 4");
if ($res_core) {
    while ($off = $res_core->fetch_assoc()) {
        $core_officials[] = $off;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Barangay Maruing</title>
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
    <link rel="stylesheet" href="Admin CSS/admin.css?v=4">
    <link rel="stylesheet" href="Admin CSS/dashboard.css?v=3">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo">
            <h3>Admin Panel</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
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
            <li>
                <a href="feedback_chat.php">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span>Feedback chat</span>
                    <?php if ($unread_feedback > 0): ?>
                        <span class="sidebar-badge"><?php echo $unread_feedback; ?></span>
                    <?php endif; ?>
                </a>
            </li>
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
                <h2>Dashboard Overview</h2>
            </div>
            <div class="topbar-user">
                <div class="user-info">
                    <span class="user-name">System Admin</span>
                    <span class="user-role">Barangay Maruing</span>
                </div>
                <div class="user-avatar" style="background: #0f766e; color: #fff; font-weight: 700;">
                    A
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="dashboard-content">
            <!-- 4-Column Dynamic KPI Stats Grid -->
            <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px;">
                
                <!-- Card 1: Total Households -->
                <div class="stat-card" style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center; background: #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div class="stat-icon" style="width: 55px; height: 55px; border-radius: 12px; background-color: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-right: 15px; flex-shrink: 0;">
                        <i class="bi bi-house-door-fill"></i>
                    </div>
                    <div class="stat-info">
                        <h4 style="font-size: 0.85rem; font-weight: 600; color: #64748b; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">Households</h4>
                        <h2 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0; line-height: 1;"><?php echo number_format($total_households); ?></h2>
                        <small style="color: #10b981; font-weight: 500; font-size: 0.8rem; display: block; margin-top: 4px;">Across 9 Puroks</small>
                    </div>
                </div>

                <!-- Card 2: Estimated Residents Population -->
                <div class="stat-card" style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center; background: #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div class="stat-icon" style="width: 55px; height: 55px; border-radius: 12px; background-color: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-right: 15px; flex-shrink: 0;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-info">
                        <h4 style="font-size: 0.85rem; font-weight: 600; color: #64748b; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">Residents</h4>
                        <h2 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0; line-height: 1;"><?php echo number_format($total_residents); ?></h2>
                        <small style="color: #3b82f6; font-weight: 500; font-size: 0.8rem; display: block; margin-top: 4px;">Registered Family Members</small>
                    </div>
                </div>

                <!-- Card 3: Officials Count -->
                <div class="stat-card" style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center; background: #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div class="stat-icon" style="width: 55px; height: 55px; border-radius: 12px; background-color: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-right: 15px; flex-shrink: 0;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div class="stat-info">
                        <h4 style="font-size: 0.85rem; font-weight: 600; color: #64748b; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">Leadership</h4>
                        <h2 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0; line-height: 1;"><?php echo number_format($total_officials); ?></h2>
                        <small style="color: #f59e0b; font-weight: 500; font-size: 0.8rem; display: block; margin-top: 4px;">Barangay & SK Council</small>
                    </div>
                </div>

                <!-- Card 4: Feedback / Messages -->
                <div class="stat-card" style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center; background: #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div class="stat-icon" style="width: 55px; height: 55px; border-radius: 12px; background-color: #f5f3ff; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-right: 15px; flex-shrink: 0;">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <div class="stat-info">
                        <h4 style="font-size: 0.85rem; font-weight: 600; color: #64748b; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">Citizen Inquiries</h4>
                        <h2 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0; line-height: 1;"><?php echo number_format($total_feedback); ?></h2>
                        <small style="color: <?php echo $unread_feedback > 0 ? '#ef4444' : '#10b981'; ?>; font-weight: 600; font-size: 0.8rem; display: block; margin-top: 4px;">
                            <?php if ($unread_feedback > 0): ?>
                                <i class="bi bi-exclamation-circle-fill"></i> <?php echo $unread_feedback; ?> Unread Messages
                            <?php else: ?>
                                <i class="bi bi-check-circle-fill"></i> All Inquiries Answered
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Two-Column Main Dashboard Grid -->
            <div class="dashboard-grid">
                
                <!-- LEFT COLUMN: Operations & Analytics -->
                <div class="left-column">
                    
                    <!-- 1. Purok Household Distribution -->
                    <div class="chart-container" style="background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0;">
                                <i class="bi bi-geo-alt-fill" style="color: #0f766e; margin-right: 6px;"></i> Purok Household Distribution
                            </h3>
                            <a href="../spot-map.php" target="_blank" style="color: #0f766e; font-size: 0.85rem; font-weight: 600; text-decoration: none;">
                                Open Full Spot Map <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                        
                        <div class="purok-bar-container">
                            <?php 
                            $max_purok_households = max(array_column($purok_stats, 'households') ?: [1]);
                            if ($max_purok_households <= 0) $max_purok_households = 1;
                            
                            foreach ($purok_stats as $p => $stat): 
                                $pct = round(($stat['households'] / $max_purok_households) * 100);
                            ?>
                                <div class="purok-bar-item">
                                    <div class="purok-bar-header">
                                        <span>
                                            <strong style="color: #1e293b;">Purok <?php echo $p; ?></strong>
                                            <span style="color: #64748b; font-size: 0.8rem; margin-left: 6px;">(<?php echo $stat['households']; ?> <?php echo $stat['households'] == 1 ? 'household' : 'households'; ?> &bull; <?php echo $stat['residents']; ?> residents)</span>
                                        </span>
                                        <div style="display: flex; gap: 8px;">
                                            <a href="resident <?php echo $p; ?>.php" title="View Resident Records" style="font-size: 0.8rem; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; color: #334155;">Residents</a>
                                            <a href="purok <?php echo $p; ?>.php" title="Manage Spot Map Pins" style="font-size: 0.8rem; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; color: #0369a1;">Map Pins</a>
                                        </div>
                                    </div>
                                    <div class="purok-bar-track">
                                        <div class="purok-bar-fill" style="width: <?php echo max(5, $pct); ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- 2. Recent Citizen Inquiries Table -->
                    <div class="chart-container" style="background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0;">
                                <i class="bi bi-chat-left-text-fill" style="color: #7c3aed; margin-right: 6px;"></i> Recent Citizen Inquiries
                            </h3>
                            <a href="feedback_chat.php" style="color: #7c3aed; font-size: 0.85rem; font-weight: 600; text-decoration: none;">
                                View All Inquiries <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <?php if (!empty($recent_inquiries)): ?>
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                                    <thead>
                                        <tr style="color: #64748b; border-bottom: 1px solid #f1f5f9; text-align: left;">
                                            <th style="padding: 10px 8px; font-weight: 600;">Citizen Name</th>
                                            <th style="padding: 10px 8px; font-weight: 600;">Message</th>
                                            <th style="padding: 10px 8px; font-weight: 600;">Date</th>
                                            <th style="padding: 10px 8px; font-weight: 600; text-align: center;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_inquiries as $inq): ?>
                                            <tr style="border-bottom: 1px solid #f8fafc;">
                                                <td style="padding: 12px 8px; font-weight: 600; color: #1e293b; white-space: nowrap;">
                                                    <i class="bi bi-person-circle" style="color: #94a3b8; margin-right: 5px;"></i>
                                                    <?php echo htmlspecialchars($inq['sender_name']); ?>
                                                </td>
                                                <td style="padding: 12px 8px; color: #475569; max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    <?php echo htmlspecialchars($inq['message']); ?>
                                                </td>
                                                <td style="padding: 12px 8px; color: #94a3b8; font-size: 0.8rem; white-space: nowrap;">
                                                    <?php echo date('M d, g:i a', strtotime($inq['created_at'])); ?>
                                                </td>
                                                <td style="padding: 12px 8px; text-align: center; white-space: nowrap;">
                                                    <?php if ($inq['is_read'] == 0): ?>
                                                        <span style="background: #fef2f2; color: #dc2626; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Unread</span>
                                                    <?php else: ?>
                                                        <span style="background: #f0fdf4; color: #16a34a; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 500;">Read</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 30px; color: #94a3b8; font-style: italic;">
                                <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 6px;"></i>
                                No citizen inquiries received yet.
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- 3. Latest Announcements Feed -->
                    <div class="chart-container" style="background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0; margin-bottom: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0;">
                                <i class="bi bi-megaphone-fill" style="color: #d97706; margin-right: 6px;"></i> Recent Announcements & Advisories
                            </h3>
                            <a href="announcements.php" style="color: #d97706; font-size: 0.85rem; font-weight: 600; text-decoration: none;">
                                Manage All <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <?php if (!empty($recent_announcements)): ?>
                            <?php foreach ($recent_announcements as $ann): ?>
                                <div class="announcement-item">
                                    <?php if (!empty($ann['cover_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($ann['cover_image']); ?>" alt="Cover" class="announcement-thumb">
                                    <?php else: ?>
                                        <div class="announcement-thumb">
                                            <i class="bi bi-calendar-event" style="font-size: 1.4rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="announcement-body">
                                        <h4 class="announcement-title" title="<?php echo htmlspecialchars($ann['title']); ?>">
                                            <?php echo htmlspecialchars($ann['title']); ?>
                                        </h4>
                                        <div class="announcement-meta">
                                            <?php if (!empty($ann['when_date'])): ?>
                                                <span><i class="bi bi-calendar3"></i> <?php echo htmlspecialchars($ann['when_date']); ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($ann['where_place'])): ?>
                                                <span><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($ann['where_place']); ?></span>
                                            <?php endif; ?>
                                            <span style="margin-left: auto;">
                                                <span style="background: #ecfdf5; color: #047857; padding: 1px 6px; border-radius: 4px; font-size: 0.72rem; text-transform: uppercase; font-weight: 600;">
                                                    <?php echo htmlspecialchars($ann['status'] ?? 'Published'); ?>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align: center; padding: 25px; color: #94a3b8; font-style: italic;">
                                <i class="bi bi-newspaper" style="font-size: 2rem; display: block; margin-bottom: 6px;"></i>
                                No active announcements posted.
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Quick Actions, Officials, System Status -->
                <div class="right-column">
                    
                    <!-- 1. Quick Action Hub -->
                    <div class="quick-actions" style="background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0 0 16px 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <i class="bi bi-lightning-charge-fill" style="color: #f59e0b; margin-right: 6px;"></i> Quick Actions Hub
                        </h3>
                        <div class="action-buttons" style="display: flex; flex-direction: column; gap: 10px;">
                            <a href="purok 1.php" class="btn-action" style="background: #0f766e; color: #fff; padding: 12px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; font-size: 0.9rem; transition: all 0.2s;">
                                <i class="bi bi-pin-map-fill" style="font-size: 1.2rem;"></i> Add Household Marker
                            </a>
                            <a href="announcements.php" class="btn-action" style="background: #d97706; color: #fff; padding: 12px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; font-size: 0.9rem; transition: all 0.2s;">
                                <i class="bi bi-megaphone-fill" style="font-size: 1.2rem;"></i> Post New Announcement
                            </a>
                            <a href="feedback_chat.php" class="btn-action" style="background: #6d28d9; color: #fff; padding: 12px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; font-size: 0.9rem; transition: all 0.2s;">
                                <i class="bi bi-chat-dots-fill" style="font-size: 1.2rem;"></i> View Citizen Feedback Inbox
                            </a>
                            <a href="../spot-map.php" target="_blank" class="btn-action" style="background: #0284c7; color: #fff; padding: 12px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; font-size: 0.9rem; transition: all 0.2s;">
                                <i class="bi bi-map-fill" style="font-size: 1.2rem;"></i> View Public Interactive Map
                            </a>
                            <a href="barangayofficials.php" class="btn-action" style="background: #334155; color: #fff; padding: 12px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; font-size: 0.9rem; transition: all 0.2s;">
                                <i class="bi bi-people-fill" style="font-size: 1.2rem;"></i> Manage Officials Roster
                            </a>
                        </div>
                    </div>

                    <!-- 2. Core Officials on Duty -->
                    <div class="chart-container" style="background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0; margin-bottom: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0;">
                                <i class="bi bi-shield-check" style="color: #0f766e; margin-right: 6px;"></i> Barangay Leadership
                            </h3>
                            <a href="barangayofficials.php" style="color: #0f766e; font-size: 0.85rem; font-weight: 600; text-decoration: none;">
                                Edit Council <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <?php if (!empty($core_officials)): ?>
                                <?php foreach ($core_officials as $official): ?>
                                    <div style="display: flex; align-items: center; gap: 12px; padding: 10px; border-radius: 8px; background: #f8fafc; border: 1px solid #f1f5f9;">
                                        <?php 
                                        $hasPhoto = !empty($official['photo']) && (strpos($official['photo'], 'http') === 0 || file_exists($official['photo']));
                                        if ($hasPhoto): 
                                        ?>
                                            <img src="<?php echo htmlspecialchars($official['photo']); ?>" alt="Official" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #0f766e;">
                                        <?php else: 
                                            $initials = strtoupper(substr($official['name'] ?? 'O', 0, 2));
                                        ?>
                                            <div style="width: 44px; height: 44px; border-radius: 50%; background: #0f766e; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">
                                                <?php echo htmlspecialchars($initials); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div style="flex: 1; min-width: 0;">
                                            <h5 style="margin: 0; font-size: 0.9rem; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <?php echo htmlspecialchars($official['name']); ?>
                                            </h5>
                                            <small style="color: #64748b; font-size: 0.8rem; font-weight: 500;">
                                                <?php echo htmlspecialchars($official['position']); ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="text-align: center; padding: 15px; color: #94a3b8; font-style: italic;">
                                    No officials data loaded.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>
</body>
</html>
