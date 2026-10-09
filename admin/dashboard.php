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
    
    <link rel="stylesheet" href="Admin CSS/admin.css?v=2">
    <link rel="stylesheet" href="Admin CSS/dashboard.css">
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
                <h2>Dashboard Overview</h2>
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
            <!-- Navigation Summary Section -->
            <div class="stats-grid" style="margin-bottom: 30px;">
                <!-- Card 1: Total Households -->
                <div class="stat-card" style="border: 1px solid #e5e7eb; border-radius: 8px;">
                    <div class="stat-icon" style="border-radius: 50%; background-color: #e8f5ee; color: #137547;">
                        <i class="bi bi-house-door-fill"></i>
                    </div>
                    <div class="stat-info">
                        <h4 style="font-size: 0.95rem; font-weight: 500; color: #4b5563; margin-bottom: 5px;">Total<br>Households</h4>
                        <h2 style="font-size: 2.2rem; font-weight: 800; color: #1f2937; margin: 0; line-height: 1;">320</h2>
                    </div>
                </div>

                <!-- Card 2: Total Puroks -->
                <div class="stat-card" style="border: 1px solid #e5e7eb; border-radius: 8px;">
                    <div class="stat-icon" style="border-radius: 50%; background-color: #e8f5ee; color: #137547;">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="stat-info">
                        <h4 style="font-size: 0.95rem; font-weight: 500; color: #4b5563; margin-bottom: 5px;">Total Puroks</h4>
                        <h2 style="font-size: 2.2rem; font-weight: 800; color: #1f2937; margin: 0; line-height: 1;">7</h2>
                    </div>
                </div>

                <!-- Card 3: Barangay Officials -->
                <div class="stat-card" style="border: 1px solid #e5e7eb; border-radius: 8px;">
                    <div class="stat-icon" style="border-radius: 50%; background-color: #e8f5ee; color: #137547;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div class="stat-info">
                        <h4 style="font-size: 0.95rem; font-weight: 500; color: #4b5563; margin-bottom: 5px;">Barangay<br>Officials</h4>
                        <h2 style="font-size: 2.2rem; font-weight: 800; color: #1f2937; margin: 0; line-height: 1;">12</h2>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- Left Column -->
                <div class="left-column">
                    
                    <!-- Gallery Summary -->
                    <div class="chart-container" style="margin-bottom: 25px;">
                        <h3 class="section-title">Gallery Highlights <small style="float:right; font-size: 0.85rem; font-weight: normal; margin-top:3px;"><a href="#">Manage Photos</a></small></h3>
                        <div class="gallery-grid">
                            <div class="gallery-thumb"><i class="bi bi-image"></i></div>
                            <div class="gallery-thumb"><i class="bi bi-image"></i></div>
                            <div class="gallery-thumb"><i class="bi bi-image"></i></div>
                            <div class="gallery-thumb"><i class="bi bi-image"></i></div>
                        </div>
                    </div>

                    <!-- Officials Summary -->
                    <div class="chart-container" style="margin-bottom: 0;">
                        <h3 class="section-title">Core Officials On Duty <small style="float:right; font-size: 0.85rem; font-weight: normal; margin-top:3px;"><a href="#">Manage Roster</a></small></h3>
                        <div class="officials-row">
                            <div class="official-card">
                                <div class="official-avatar">JD</div>
                                <div class="official-info">
                                    <p>Hon. Juan Dela Cruz</p>
                                    <small>Punong Barangay</small>
                                </div>
                            </div>
                            <div class="official-card">
                                <div class="official-avatar">MS</div>
                                <div class="official-info">
                                    <p>Maria Santos</p>
                                    <small>Barangay Secretary</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>


