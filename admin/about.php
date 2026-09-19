<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage About Us | Barangay Maruing</title>
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
    <link rel="stylesheet" href="Admin CSS/about.css">
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
            <li><a href="about.php" class="active"><i class="bi bi-info-circle-fill"></i> About</a></li>
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
                <h2>Manage About Us</h2>
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
                <h3>About Us Content</h3>
                <p>Update the information displayed on the public About Us page.</p>
            </div>
            
            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="about-grid">
                    <!-- Left Side: Image -->
                    <div class="left-col">
                        <div class="form-card h-100">
                            <label class="form-label" for="about_image">Add Image</label>
                            <div class="image-dropzone">
                                <input type="file" id="about_image" name="about_image" class="file-input-centered" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Text Fields -->
                    <div class="right-col">
                        <div class="form-card mb-30">
                            <div class="form-group mb-0">
                                <label class="form-label" for="history">History</label>
                                <textarea id="history" name="history" class="form-control" rows="6" placeholder="Enter barangay history here..."></textarea>
                            </div>
                        </div>

                        <div class="form-card mb-30">
                            <div class="form-group mb-0">
                                <label class="form-label" for="vision">Vision</label>
                                <textarea id="vision" name="vision" class="form-control" rows="4" placeholder="Enter barangay vision here..."></textarea>
                            </div>
                        </div>

                        <div class="form-card">
                            <div class="form-group mb-0">
                                <label class="form-label" for="mission">Mission</label>
                                <textarea id="mission" name="mission" class="form-control" rows="4" placeholder="Enter barangay mission here..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary"><i class="bi bi-save"></i> Save Changes</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>


