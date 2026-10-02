<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Announcements for Barangay Maruing.">
    <title>Announcements | Barangay Maruing Information System</title>
    
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
    <link rel="stylesheet" href="public/assets/css/announcements.css?v=1">
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
                        <a class="nav-link" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="announcements.php">Announcements</a>
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

    <!-- Announcements Hero Section -->
    <section class="announcements-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead">Our</span>
                    <span class="hero-title-main">ANNOUNCEMENTS</span>
                </h1>
                <p class="hero-lead">Stay informed with the latest news, events, and important updates in Barangay Maruing.</p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="announcements-main-section">
        <div class="container announcements-container">
            
            <div class="announcements-grid">
                <!-- Announcement 1 -->
                <div class="announcement-card-v2">
                    <div class="announcement-date-badge">OCT 15</div>
                    <h3 class="announcement-card-title">General Assembly Meeting</h3>
                    
                    <div class="announcement-details">
                        <p><strong>What:</strong> Bi-annual General Assembly Meeting</p>
                        <p><strong>When:</strong> October 15, 2025 at 9:00 AM</p>
                        <p><strong>Why:</strong> To discuss upcoming infrastructure projects and budget allocations.</p>
                        <p><strong>Who:</strong> All residents of Barangay San Jose</p>
                    </div>

                    <div class="announcement-divider"></div>

                    <div class="announcement-description">
                        <p>We highly encourage at least one representative per household to attend this important assembly. Your voice and feedback are essential as we plan for the community's future developments.</p>
                    </div>
                </div>

                <!-- Announcement 2 -->
                <div class="announcement-card-v2">
                    <div class="announcement-date-badge">OCT 22</div>
                    <h3 class="announcement-card-title">Free Medical & Dental Mission</h3>
                    
                    <div class="announcement-details">
                        <p><strong>What:</strong> Free Medical and Dental Checkups</p>
                        <p><strong>When:</strong> October 22, 2025 starting at 8:00 AM</p>
                        <p><strong>Why:</strong> To provide accessible healthcare services to those in need.</p>
                        <p><strong>Who:</strong> Priority for Senior Citizens and Children</p>
                    </div>

                    <div class="announcement-divider"></div>

                    <div class="announcement-description">
                        <p>In partnership with the City Health Office and volunteer doctors, we will be providing free checkups, basic tooth extractions, and essential medicines. Please bring your valid ID and PhilHealth card if available.</p>
                    </div>
                </div>

                <!-- Announcement 3 -->
                <div class="announcement-card-v2">
                    <div class="announcement-date-badge">NOV 01</div>
                    <h3 class="announcement-card-title">Undas 2025 Traffic Advisory</h3>
                    
                    <div class="announcement-details">
                        <p><strong>What:</strong> Temporary Road Closures and Rerouting</p>
                        <p><strong>When:</strong> November 01, 2025 (All Day)</p>
                        <p><strong>Why:</strong> To manage the expected high volume of traffic heading to the public cemetery.</p>
                        <p><strong>Who:</strong> All Motorists and Residents</p>
                    </div>

                    <div class="announcement-divider"></div>

                    <div class="announcement-description">
                        <p>Please be advised that Main Avenue will be strictly one-way leading towards the cemetery. Expect heavy traffic. Barangay Tanods and City Traffic Enforcers will be deployed to guide motorists.</p>
                    </div>
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
