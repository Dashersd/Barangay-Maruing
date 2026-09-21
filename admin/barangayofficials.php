<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Barangay Officials | Barangay Maruing</title>
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
    <link rel="stylesheet" href="Admin CSS/barangayofficials.css">
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
                    <li><a href="barangayofficials.php" class="active">Barangay Officials</a></li>
                    <li><a href="SKofficials.php">SK Officials</a></li>
                </ul>
            </li>
            <li><a href="#"><i class="bi bi-geo-alt-fill"></i> Spot Map</a></li>
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
                <h2>Manage Barangay Officials</h2>
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
                <h3>Barangay Officials Content</h3>
                <p>Update the information displayed on the public Barangay Officials page.</p>
            </div>
            
            <div class="org-chart-pyramid">
                <!-- Row 1: Captain -->
                <div class="org-row row-1">
                    <div class="org-card">
                        <div class="org-photo-container">
                            <img src="https://via.placeholder.com/150/e2e8f0/64748b?text=Photo" alt="Barangay Captain" class="org-photo">
                            <div class="org-name-badge">Name</div>
                        </div>
                        <div class="org-role">BARANGAY CAPTAIN</div>
                        <div class="org-actions">
                            <button class="btn-edit-official"><i class="bi bi-pencil-fill"></i> Edit</button>
                        </div>
                    </div>
                </div>

                <!-- Row 2: 2 Kagawads -->
                <div class="org-row row-2">
                    <div class="org-card">
                        <div class="org-photo-container">
                            <img src="https://via.placeholder.com/150/e2e8f0/64748b?text=Photo" alt="Barangay Kagawad" class="org-photo">
                            <div class="org-name-badge">Name</div>
                        </div>
                        <div class="org-role">BARANGAY KAGAWAD</div>
                        <div class="org-actions">
                            <button class="btn-edit-official"><i class="bi bi-pencil-fill"></i> Edit</button>
                        </div>
                    </div>
                    <div class="org-card">
                        <div class="org-photo-container">
                            <img src="https://via.placeholder.com/150/e2e8f0/64748b?text=Photo" alt="Barangay Kagawad" class="org-photo">
                            <div class="org-name-badge">Name</div>
                        </div>
                        <div class="org-role">BARANGAY KAGAWAD</div>
                        <div class="org-actions">
                            <button class="btn-edit-official"><i class="bi bi-pencil-fill"></i> Edit</button>
                        </div>
                    </div>
                </div>

                <!-- Row 3: 5 Kagawads -->
                <div class="org-row row-3">
                    <div class="org-card">
                        <div class="org-photo-container">
                            <img src="https://via.placeholder.com/150/e2e8f0/64748b?text=Photo" alt="Barangay Kagawad" class="org-photo">
                            <div class="org-name-badge">Name</div>
                        </div>
                        <div class="org-role">BARANGAY KAGAWAD</div>
                        <div class="org-actions">
                            <button class="btn-edit-official"><i class="bi bi-pencil-fill"></i> Edit</button>
                        </div>
                    </div>
                    <div class="org-card">
                        <div class="org-photo-container">
                            <img src="https://via.placeholder.com/150/e2e8f0/64748b?text=Photo" alt="Barangay Kagawad" class="org-photo">
                            <div class="org-name-badge">Name</div>
                        </div>
                        <div class="org-role">BARANGAY KAGAWAD</div>
                        <div class="org-actions">
                            <button class="btn-edit-official"><i class="bi bi-pencil-fill"></i> Edit</button>
                        </div>
                    </div>
                    <div class="org-card">
                        <div class="org-photo-container">
                            <img src="https://via.placeholder.com/150/e2e8f0/64748b?text=Photo" alt="Barangay Kagawad" class="org-photo">
                            <div class="org-name-badge">Name</div>
                        </div>
                        <div class="org-role">BARANGAY KAGAWAD</div>
                        <div class="org-actions">
                            <button class="btn-edit-official"><i class="bi bi-pencil-fill"></i> Edit</button>
                        </div>
                    </div>
                    <div class="org-card">
                        <div class="org-photo-container">
                            <img src="https://via.placeholder.com/150/e2e8f0/64748b?text=Photo" alt="Barangay Kagawad" class="org-photo">
                            <div class="org-name-badge">Name</div>
                        </div>
                        <div class="org-role">BARANGAY KAGAWAD</div>
                        <div class="org-actions">
                            <button class="btn-edit-official"><i class="bi bi-pencil-fill"></i> Edit</button>
                        </div>
                    </div>
                    <div class="org-card">
                        <div class="org-photo-container">
                            <img src="https://via.placeholder.com/150/e2e8f0/64748b?text=Photo" alt="Barangay Kagawad" class="org-photo">
                            <div class="org-name-badge">Name</div>
                        </div>
                        <div class="org-role">BARANGAY KAGAWAD</div>
                        <div class="org-actions">
                            <button class="btn-edit-official"><i class="bi bi-pencil-fill"></i> Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>


