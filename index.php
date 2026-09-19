<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Official Web-Based Information System for Barangay Maruing. Access announcements, services, barangay officials, and community updates online.">
    <title>Home | Barangay Maruing Information System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS (Pure CSS Responsive) -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=1789796772">
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top ">
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
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="officials.php">Officials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" onclick="return false;">Spot Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
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

    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container hero-container">
                <div class="hero-content">
                    <h1 class="hero-title">
                        <span class="hero-script-lead">Welcome to</span>
                        <span class="hero-title-main">BARANGAY MARUING</span>
                    </h1>
                    <p class="hero-lead">Your trusted source for barangay governance, community information, and public service.</p>
                    <div class="hero-buttons">
                        <a href="about.php" class="btn-hero-primary">Explore Information <i
                                class="bi bi-arrow-right"></i></a>
                        <a href="officials.php" class="btn-hero-outline">Meet Officials <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-landing-section">
            <div class="container about-landing-container">
                <div class="about-landing-image">
                    <img src="public/image/barangay-maruing-cover.png" alt="Barangay Maruing History">
                </div>
                <div class="about-landing-content">
                    <h2 class="about-landing-title">About Barangay Maruing</h2>
                    <p class="about-landing-desc">
                        Discover the rich history and vibrant community of Barangay Maruing. Our barangay is dedicated to providing honest and dedicated service to all its residents, fostering growth, unity, and a better quality of life for everyone. 
                    </p>
                    <a href="about.php#about-us-section" class="btn-learn-more">
                        Learn more <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Officials Landing Section -->
        <section class="landing-officials-section">
            <div class="container">
                <div class="landing-officials-header">
                    <h2 class="landing-officials-title">Barangay Officials</h2>
                    <p class="landing-officials-subtitle">Dedicated leaders of Barangay Maruing</p>
                </div>
                
                <div class="landing-captain-card">
                    <div class="landing-captain-image">
                        <!-- Placeholder image for the captain -->
                        <img src="public/image/barangay-maruing-cover.png" alt="Barangay Captain">
                    </div>
                    <div class="landing-captain-info">
                        <div class="landing-captain-role">
                            BARANGAY CAPTAIN
                        </div>
                        <h3 class="landing-captain-name">Hon. Punong Barangay</h3>
                        <p class="landing-captain-desc">
                            Presiding officer of the Sangguniang Barangay, leading executive policies, community welfare programs, and ensuring peace and order within Barangay Maruing.
                        </p>

                        <a href="officials.php" class="btn-view-officials">
                            View All Officials <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Spot Map Landing Section -->
        <section class="landing-spot-map-section">
            <div class="container">
                <div class="landing-spot-map-header" style="text-align: center; margin-bottom: 3rem;">
                    <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 1rem; text-align: center;">Spot Map</h2>
                    <p style="font-size: 1.1rem; color: var(--muted-text-color); text-align: center; margin-left: auto; margin-right: auto;">Geographical overview and key locations in Barangay Maruing</p>
                </div>
                
                <div class="landing-spot-map-container">
                <div class="spot-map-content">
                    <div class="spot-map-badge">
                        <i class="bi bi-geo-alt-fill"></i> EXPLORE
                    </div>
                    <h2 class="spot-map-title">Barangay Spot Map</h2>
                    <p class="spot-map-desc">
                        Navigate our community with ease. View important landmarks, territorial boundaries, purok zones, and key public facilities throughout Barangay Maruing.
                    </p>
                    <a href="javascript:void(0)" class="btn-view-map" style="pointer-events: none; opacity: 0.7;" title="Coming soon">
                        View Full Map <i class="bi bi-map-fill"></i>
                    </a>

                    <div style="margin-top: 3.5rem; width: 100%;">
                        <h5 style="font-size: 1.1rem; font-weight: 700; color: var(--primary-dark); margin-bottom: 1.2rem; text-transform: uppercase; letter-spacing: 1px;">The 9 Puroks</h5>
                        <div class="purok-chips">
                            <?php for($i = 1; $i <= 9; $i++): ?>
                            <div class="purok-chip">
                                <i class="bi bi-geo-alt"></i> Purok <?php echo $i; ?>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <div class="spot-map-visual">
                    <iframe 
                        src="https://maps.google.com/maps?q=Barangay%20Maruing,%20Philippines&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            </div>
        </section>

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
