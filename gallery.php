<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View the gallery of Barangay Maruing.">
    <title>Gallery | Barangay Maruing Information System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=1789796772">
    <link rel="stylesheet" href="public/assets/css/gallery.css?v=1">
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
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="officials.php">Officials <i class="bi bi-chevron-down" style="font-size: 0.75rem; margin-left: 2px;"></i></a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="officials.php">Barangay Officials</a></li>
                            <li><a class="dropdown-item" href="SKofficial.php">SK Officials</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" onclick="return false;">Spot Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn-admin" href="admin/login.php">Admin Login</a>
                    </li>
                </ul>
                <ul class="navbar-nav nav-auth">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn-register" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Gallery Hero Section -->
    <section class="gallery-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead">Our</span>
                    <span class="hero-title-main">GALLERY</span>
                </h1>
                <p class="hero-lead">Explore the beauty, events, and community life of Barangay Maruing through our photo gallery.</p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="gallery-main-section">
        <div class="container gallery-container">
            
            <div class="gallery-grid">
                <!-- Gallery Item 1 -->
                <div class="gallery-item">
                    <img src="public/image/barangay-maruing-cover.png" alt="Barangay Maruing" class="gallery-img">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-content">
                            <i class="bi bi-search"></i>
                            <span class="gallery-caption">Barangay Hall</span>
                        </div>
                    </div>
                </div>
                <!-- Gallery Item 2 -->
                <div class="gallery-item">
                    <img src="public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo" class="gallery-img logo-img">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-content">
                            <i class="bi bi-search"></i>
                            <span class="gallery-caption">Official Logo</span>
                        </div>
                    </div>
                </div>
                <!-- Gallery Item 3 (Placeholder) -->
                <div class="gallery-item placeholder-item">
                    <div class="placeholder-content">
                        <i class="bi bi-image"></i>
                        <span>More photos coming soon</span>
                    </div>
                </div>
                <!-- Gallery Item 4 (Placeholder) -->
                <div class="gallery-item placeholder-item">
                    <div class="placeholder-content">
                        <i class="bi bi-image"></i>
                        <span>More photos coming soon</span>
                    </div>
                </div>
                <!-- Gallery Item 5 (Placeholder) -->
                <div class="gallery-item placeholder-item">
                    <div class="placeholder-content">
                        <i class="bi bi-image"></i>
                        <span>More photos coming soon</span>
                    </div>
                </div>
                <!-- Gallery Item 6 (Placeholder) -->
                <div class="gallery-item placeholder-item">
                    <div class="placeholder-content">
                        <i class="bi bi-image"></i>
                        <span>More photos coming soon</span>
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
                <!-- Column 5 -->
                <div class="col-md-2 footer-links">
                    <a href="login.php">LOGIN</a>
                    <a href="register.php">REGISTER</a>
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
