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
    
    <link rel="stylesheet" href="Admin CSS/admin.css">
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
            <li><a href="#"><i class="bi bi-people-fill"></i> Officials</a></li>
            <li><a href="#"><i class="bi bi-geo-alt-fill"></i> Spot Map</a></li>
            <li><a href="#"><i class="bi bi-image-fill"></i> Gallery</a></li>
            <li><a href="#"><i class="bi bi-telephone-fill"></i> Contact</a></li>
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
            <div class="quick-cards-grid" style="margin-bottom: 30px;">
                <!-- Card 1: Barangay Info -->
                <div class="quick-card card-pink">
                    <div class="quick-card-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div class="quick-card-content">
                        <h3 class="quick-card-title">Barangay Info</h3>
                        <p class="quick-card-desc">Update barangay history, mission, vision, and main image.</p>
                        <a href="about.php" class="quick-card-link">Manage About <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Card 2: Officials -->
                <div class="quick-card card-rose">
                    <div class="quick-card-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="quick-card-content">
                        <h3 class="quick-card-title">Officials</h3>
                        <p class="quick-card-desc">Manage the roster of dedicated barangay officials and staff.</p>
                        <a href="#" class="quick-card-link">Manage Officials <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Card 3: Spot Map -->
                <div class="quick-card card-blue">
                    <div class="quick-card-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="quick-card-content">
                        <h3 class="quick-card-title">Spot Map</h3>
                        <p class="quick-card-desc">Update the geographical territory and landmark information.</p>
                        <a href="#" class="quick-card-link">Manage Map <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Card 4: Contact Us -->
                <div class="quick-card card-purple">
                    <div class="quick-card-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div class="quick-card-content">
                        <h3 class="quick-card-title">Contact Us</h3>
                        <p class="quick-card-desc">Review and respond to inquiries and update contact details.</p>
                        <a href="#" class="quick-card-link">Manage Contacts <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- Left Column -->
                <div class="left-column">
                    
                    <!-- Contact Summary -->
                    <div class="chart-container" style="margin-bottom: 25px;">
                        <h3 class="section-title">Latest Inquiries <small style="float:right; font-size: 0.85rem; font-weight: normal; margin-top:3px;"><a href="#">View All</a></small></h3>
                        <table class="table-inquiries">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Maria Santos</strong></td>
                                    <td>Barangay Clearance Inquiry</td>
                                    <td>Today, 9:30 AM</td>
                                    <td><a href="#" class="btn-sm">Read</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Juan Dela Cruz</strong></td>
                                    <td>Street Light Repair</td>
                                    <td>Yesterday</td>
                                    <td><a href="#" class="btn-sm">Read</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Anonymous</strong></td>
                                    <td>Noise Complaint</td>
                                    <td>Oct 12</td>
                                    <td><a href="#" class="btn-sm">Read</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

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


