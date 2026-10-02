<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Legends - legends file | Barangay Maruing</title>
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
    <link rel="stylesheet" href="Admin CSS/legends file.css">
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
            <h2>Households</h2>
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
            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
            <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 15px 20px; margin-bottom: 20px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div><i class="bi bi-trash-fill" style="margin-right: 8px;"></i> Record was successfully removed!</div>
                <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #991b1b; cursor: pointer; font-size: 1.2rem; line-height: 1;">&times;</button>
            </div>
            <?php endif; ?>
            <?php if (isset($_GET['edited']) && $_GET['edited'] == 1): ?>
            <div style="background-color: #e0f2fe; border-left: 4px solid #0284c7; color: #0369a1; padding: 15px 20px; margin-bottom: 20px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div><i class="bi bi-pencil-square" style="margin-right: 8px;"></i> Record was successfully updated!</div>
                <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #0369a1; cursor: pointer; font-size: 1.2rem; line-height: 1;">&times;</button>
            </div>
            <?php endif; ?>
        <div class="purok-header">
            <h2>Legends Records</h2>
        </div>
        
        <div class="records-card" style="background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-top: 20px;">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="records-table" style="width: 100%; border-collapse: collapse; min-width: 800px;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #334155; font-size: 0.95rem;">House Image</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #334155; font-size: 0.95rem;">House Number</th>
                            <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #334155; font-size: 0.95rem;">Husband Name</th>
                            <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #334155; font-size: 0.95rem;">Spouse Name</th>
                            <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #334155; font-size: 0.95rem;">Date Added</th>
                            <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #334155; font-size: 0.95rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="records-tbody">
                            <?php
                            $json_file = 'data/legend.json';
                            $records = [];
                            if (file_exists($json_file)) {
                                $json_data = file_get_contents($json_file);
                                $decoded = json_decode($json_data, true);
                                if (is_array($decoded)) {
                                    $records = $decoded;
                                }
                            }
                            
                            if (empty($records)):
                            ?>
                                <tr>
                                    <td colspan="6" style="padding: 30px; text-align: center; color: #64748b; font-style: italic;">No records found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($records as $record): ?>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 15px 20px; color: #475569;">
                                            <?php if (!empty($record['house_image'])): ?>
                                                <img src="<?php echo htmlspecialchars($record['house_image']); ?>" alt="House" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0;">
                                            <?php else: ?>
                                                <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">No image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 15px 20px; color: #475569;"><?php echo htmlspecialchars($record['house_number'] ?? ''); ?></td>
                                        <td style="padding: 15px 20px; color: #475569;"><?php echo htmlspecialchars($record['husband_name'] ?? ''); ?></td>
                                        <td style="padding: 15px 20px; color: #475569;"><?php echo htmlspecialchars($record['spouse_name'] ?? ''); ?></td>
                                        <td style="padding: 15px 20px; color: #475569;"><?php echo htmlspecialchars($record['date_added'] ?? ''); ?></td>
                                        <td style="padding: 15px 20px; text-align: center;">
                                            <a href="edit_record.php?target_file=legend&id=<?php echo htmlspecialchars($record['id'] ?? ''); ?>" style="background: #0f766e; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; margin-right: 5px; text-decoration: none; display: inline-block;"><i class="bi bi-pencil-square"></i> Edit</a>
                                            <form action="delete_record.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to completely remove this record?');">
                                                <input type="hidden" name="target_file" value="legend">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id'] ?? ''); ?>">
                                                <button type="submit" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;"><i class="bi bi-trash"></i> Remove</button>
                                            </form>
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



