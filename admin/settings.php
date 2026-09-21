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
    <link rel="stylesheet" href="Admin CSS/settings.css">
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
            <li><a href="#"><i class="bi bi-geo-alt-fill"></i> Spot Map</a></li>
            <li><a href="mediagallery.php"><i class="bi bi-image-fill"></i> Media Gallery</a></li>
            <li><a href="services.php"><i class="bi bi-card-list"></i> Services</a></li>
            <li><a href="announcements.php"><i class="bi bi-megaphone-fill"></i> Announcements</a></li>
            <li><a href="#"><i class="bi bi-telephone-fill"></i> Contact</a></li>
            <li><a href="settings.php" class="active"><i class="bi bi-gear-fill"></i> Settings</a></li>
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
                <h2>Admin Settings</h2>
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
            
            <div class="settings-grid">
                <!-- Left Column: System Configuration -->
                <div class="settings-left">
                    <div class="settings-card">
                        <h4><i class="bi bi-sliders"></i> System Configuration</h4>
                        <hr>
                        
                        <form action="#" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Barangay Name</label>
                                <input type="text" class="form-control" value="Brgy Tabon">
                            </div>
                            
                            <div class="form-group">
                                <label>Official Logo</label>
                                <input type="file" class="form-control-file">
                                <img src="../public/image/Maruing Logo 2.png" alt="Logo" class="settings-logo-preview">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Contact Email</label>
                                    <input type="email" class="form-control" value="contact@brgytabon.gov.ph">
                                </div>
                                <div class="form-group">
                                    <label>Contact Phone</label>
                                    <input type="text" class="form-control" value="0912-345-6789">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Time on Duty</label>
                                <input type="text" class="form-control" value="Mon - Fri, 8:00 AM to 5:00 PM">
                            </div>
                            
                            <button type="submit" class="btn-success">Save Configuration</button>
                        </form>
                    </div>
                </div>
                
                <!-- Right Column: Security & Backups -->
                <div class="settings-right">
                    <div class="settings-card">
                        <h4><i class="bi bi-shield-check"></i> Security & Backups</h4>
                        <hr>
                        
                        <h5 class="section-title">Change Password</h5>
                        <form action="#" method="POST">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" class="form-control">
                            </div>
                            <button type="submit" class="btn-success">Update Password</button>
                        </form>
                        
                        <hr class="mt-4 mb-4">
                        
                        <h5 class="section-title">Data Management</h5>
                        <button class="btn-dark"><i class="bi bi-download"></i> Download Database Backup</button>
                        
                        <hr class="mt-4 mb-4">
                        
                        <h5 class="section-title">Recent Security Logs</h5>
                        <div class="security-logs">
                            <div class="log-item">[2026-09-19 19:30:12] Successful login from 192.168.1.5</div>
                            <div class="log-item">[2026-09-19 14:15:00] Admin updated Community Members</div>
                            <div class="log-item text-danger">[2026-09-18 22:45:11] Failed login attempt from 10.0.0.44</div>
                            <div class="log-item">[2026-09-18 10:05:00] Database backup generated</div>
                            <div class="log-item">[2026-09-17 09:00:15] Successful login from 192.168.1.5</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>


