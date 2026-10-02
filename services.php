<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Services offered by Barangay Maruing.">
    <title>Services | Barangay Maruing Information System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=4">
    <link rel="stylesheet" href="public/assets/css/services.css?v=1">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top">
        <div class="navbar-container">
            <a class="navbar-brand" href="index.php">
                <img src="public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo" style="width: 50px; height: 50px; object-fit: contain; margin-right: 10px;">
                <div class="brand-text">
                    <span class="brand-title">Barangay Maruing</span>
                    <span class="brand-tagline">Serbisyong Tapat, Para sa Lahat</span>
                </div>
            </a>
            <div class="navbar-menu">
                <ul class="navbar-nav nav-links">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="about.php">About <i class="bi bi-chevron-down" style="font-size: 0.75rem; margin-left: 2px;"></i></a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="about.php#about-us-section">History</a></li>
                            <li><a class="dropdown-item" href="vision.php">Vision</a></li>
                            <li><a class="dropdown-item" href="mission.php">Mission</a></li>
                            <li><a class="dropdown-item" href="officials.php">Barangay Officials</a></li>
                            <li><a class="dropdown-item" href="SKofficial.php">SK Officials</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="spot-map.php">Spot Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="announcements.php">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav nav-auth">
                    <li class="nav-item">
                        <a class="btn-admin" href="admin/login.php">Admin Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Services Hero Section -->
    <section class="services-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead">Our</span>
                    <span class="hero-title-main">SERVICES</span>
                </h1>
                <p class="hero-lead">Access essential barangay services, permits, and clearances conveniently online.</p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="services-main-section">
        <div class="container services-container">
            
            <div class="services-grid">
                
                <!-- Service 1 -->
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h3 class="service-title">Barangay Clearance</h3>
                    <p class="service-description">Request a Barangay Clearance for employment, banking, or other legal purposes. Essential for verifying your residency and good standing.</p>
                    <a href="#" class="btn-service">Request Document <i class="bi bi-arrow-right"></i></a>
                </div>

                <!-- Service 2 -->
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-house-heart"></i>
                    </div>
                    <h3 class="service-title">Certificate of Indigency</h3>
                    <p class="service-description">Obtain a Certificate of Indigency to avail of government assistance, scholarships, and medical support programs.</p>
                    <a href="#" class="btn-service">Request Document <i class="bi bi-arrow-right"></i></a>
                </div>

                <!-- Service 3 -->
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <h3 class="service-title">Business Clearance</h3>
                    <p class="service-description">Required for all new and renewing businesses operating within the barangay jurisdiction prior to Mayor's permit application.</p>
                    <a href="#" class="btn-service">Request Document <i class="bi bi-arrow-right"></i></a>
                </div>

                <!-- Service 4 -->
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h3 class="service-title">Certificate of Residency</h3>
                    <p class="service-description">Proof of your residency within the barangay. Often required for school enrollments, ID applications, and other local transactions.</p>
                    <a href="#" class="btn-service">Request Document <i class="bi bi-arrow-right"></i></a>
                </div>

            </div>
            
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-top row">
                <!-- Column 1: Logo -->
                <div class="col-md-4 footer-brand" style="display: flex; align-items: center; gap: 15px;">
                    <img src="public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo" class="footer-logo-img" style="width: 70px;">
                    <div>
                        <h2 class="footer-logo" style="margin-bottom: 0;">BARANGAY MARUING</h2>
                        <p class="footer-slogan" style="margin-bottom: 0;">SERBISYONG TAPAT, PARA SA LAHAT</p>
                    </div>
                </div>
                <!-- Column 2 -->
                <div class="col-md-2 footer-links">
                    <a href="index.php">HOME</a>
                    <a href="about.php">ABOUT US</a>
                </div>
                <!-- Column 3 -->
                <div class="col-md-2 footer-links">
                    <a href="officials.php">OFFICIALS</a>
                    <a href="spot-map.php">SPOT MAP</a>
                </div>
                <!-- Column 4 -->
                <div class="col-md-2 footer-links">
                    <a href="gallery.php">GALLERY</a>
                    <a href="contact.php">CONTACT US</a>
                </div>
            </div>
            
            <hr class="footer-divider">
            
            <div class="footer-bottom">
                <div class="footer-socials">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-envelope-fill"></i></a>
                </div>
                <p class="footer-copy">&copy; Copyright. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom JavaScript -->
    <script src="public/assets/js/main.js"></script>
</body>
</html>
