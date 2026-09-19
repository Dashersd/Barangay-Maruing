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
    <link rel="stylesheet" href="public/assets/css/style.css">
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

        <!-- Quick Access Section -->
        <section class="quick-access-section">
            <div class="container">
                <div class="quick-cards-grid">
                    <!-- Card 1: Barangay Info -->
                    <div class="quick-card card-pink">
                        <div class="quick-card-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div class="quick-card-content">
                            <h3 class="quick-card-title">Barangay Info</h3>
                            <p class="quick-card-desc">Learn more about our barangay's history, mission, and community.
                            </p>
                            <a href="about.php" class="quick-card-link">View Details <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>

                    <!-- Card 2: Officials -->
                    <div class="quick-card card-rose">
                        <div class="quick-card-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="quick-card-content">
                            <h3 class="quick-card-title">Officials</h3>
                            <p class="quick-card-desc">Meet the dedicated officials serving our community with commitment.</p>
                            <a href="officials.php" class="quick-card-link">View Officials <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>

                    <!-- Card 3: Spot Map -->
                    <div class="quick-card card-blue">
                        <div class="quick-card-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="quick-card-content">
                            <h3 class="quick-card-title">Spot Map</h3>
                            <p class="quick-card-desc">Explore the geographical territory, puroks, and landmarks of Barangay Maruing.</p>
                            <a href="spot-map.php" class="quick-card-link">Explore Map <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>

                    <!-- Card 4: Contact Us -->
                    <div class="quick-card card-purple">
                        <div class="quick-card-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="quick-card-content">
                            <h3 class="quick-card-title">Contact Us</h3>
                            <p class="quick-card-desc">Reach out to our barangay office for inquiries, assistance, and support.</p>
                            <a href="contact.php" class="quick-card-link">Get In Touch <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Custom JavaScript -->
    <script src="public/assets/js/main.js"></script>
</body>

</html>
