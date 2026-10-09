<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Legends - Purok 2 | Barangay Maruing</title>
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
    <link rel="stylesheet" href="Admin CSS/purok 2.css">
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
                    <li><a href="legend.php">Legend</a></li>
                    <li><a href="purok 1.php">Purok 1</a></li>
                    <li><a href="purok 2.php" class="active">Purok 2</a></li>
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
                <h2>Manage Legends</h2>
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
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div style="background-color: #d1fae5; border-left: 4px solid #10b981; color: #065f46; padding: 15px 20px; margin-bottom: 20px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div><i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i> Successfully saved the marker to the map!</div>
                <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #065f46; cursor: pointer; font-size: 1.2rem; line-height: 1;">&times;</button>
            </div>
            <?php endif; ?>
            <div class="purok-header">
                <h2>Add House to Map</h2>
                <p>Upload a marker, add members, and drag the icon to save to the map</p>
            </div>
            
                        <div class="purok-map-container" style="position: relative;">
                <img src="../public/image/Purok/Purok 2.jpg" alt="Spot Map" class="spot-map-img" style="width: 100%; display: block;">
                <?php
                include 'db_connect.php';
                
                $sql = "SELECT * FROM sm_purok_2_spots";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($marker = $result->fetch_assoc()) {
                        $imgSrc = !empty($marker['marker_image']) ? $marker['marker_image'] : '';
                        if ($imgSrc) {
                            echo '<img src="' . htmlspecialchars($imgSrc) . '" style="position: absolute; top: ' . $marker['top_position'] . '%; left: ' . $marker['left_position'] . '%; width: ' . $marker['marker_width'] . 'px; height: ' . $marker['marker_height'] . 'px; transform: translate(-50%, -100%); z-index: 500;" title="' . htmlspecialchars($marker['husband_name']) . '">';
                        }
                    }
                }
                ?>
            </div>

            <div class="purok-form-container">
                <form action="save_record.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="target_file" value="purok2">
                    <div class="form-group">
                        <label>House Number</label>
                        <input type="text" name="house_number" placeholder="e.g. 123" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Household Name</label>
                        <div class="row">
                            <div class="col">
                                <input type="text" name="husband_name" placeholder="Name of the Husband (e.g. Juan Dela Cruz)" class="form-control">
                            </div>
                            <div class="col">
                                <input type="text" name="spouse_name" placeholder="Name of the Spouse (Maiden) (e.g. Maria Santos)" class="form-control">
                            </div>
                        </div>
                    </div>

                                        <div class="form-group">
                        <label>House Image (Actual Photo)</label>
                        <input type="file" name="house_image" class="form-control file-input">
                    </div>

                    <div class="form-group">
                        <label>Marker Image (Icon shown on map)</label>
                        <input type="file" name="marker_image" class="form-control file-input">
                    </div>

                    <div class="row">
                        <div class="col form-group">
                            <label>Marker Width (px)</label>
                            <input type="number" name="marker_width" value="40" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label>Marker Height (px)</label>
                            <input type="number" name="marker_height" value="40" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col form-group">
                            <label>Top position (%)</label>
                            <input type="number" step="0.01" name="top_position" value="50.00" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label>Left position (%)</label>
                            <input type="number" step="0.01" name="left_position" value="50.00" class="form-control">
                        </div>
                    </div>

                    <button type="submit" class="btn-save"><i class="bi bi-save"></i> Save to Map</button>
                </form>
            </div>
        </main>
    </div>
    <script src="Admin JS/admin.js"></script>
</body>
</html>


