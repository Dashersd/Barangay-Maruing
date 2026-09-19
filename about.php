<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Learn about Barangay Maruing's vision, mission, and rich history.">
    <title>About Us | Barangay Maruing Information System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=2">
    <link rel="stylesheet" href="public/assets/css/about.css?v=2">
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
                        <a class="nav-link active" href="about.php">About</a>
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

    <!-- About Hero Section -->
    <section class="about-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead">About</span>
                    <span class="hero-title-main">BARANGAY MARUING</span>
                </h1>
                <p class="hero-lead">Dedicated to honest, transparent public service and cultivating a thriving, united community for all residents.</p>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="about-us-section">
        <div class="container about-us-container">
            <!-- Left Image -->
            <div class="about-us-image-wrapper">
                <img src="public/image/barangay-maruing-cover.png" alt="Barangay Maruing Landscape" class="about-us-img">
            </div>
            
            <!-- Middle Content -->
            <div class="about-us-content">
                <div class="about-us-header">
                    <span class="about-us-subtitle">ABOUT US</span>
                    <div class="about-us-line"></div>
                </div>
                <h2 class="about-us-title">Our Barangay</h2>
                <p class="about-us-text">Barangay Maruing is one of the duly recognized barangays in the Municipality of Maramag, Lanao del Sur. It is home to hardworking and God-fearing residents who value unity, cooperation, and community development.</p>
                <p class="about-us-text">Our barangay is governed by a dedicated set of officials who work hand in hand with the community to ensure peace and order, deliver essential services, and promote sustainable growth for all.</p>
            </div>
            
            <!-- Right List -->
            <div class="about-us-features">
                <!-- Feature 1 -->
                <div class="feature-item">
                    <div class="feature-icon icon-location">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="feature-text-content">
                        <h4 class="feature-title">LOCATION</h4>
                        <p class="feature-desc">Barangay Maruing, Municipality of Lapuyan, Zamboanga del Sur</p>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="feature-item">
                    <div class="feature-icon icon-population">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="feature-text-content">
                        <h4 class="feature-title">POPULATION</h4>
                        <p class="feature-desc">A growing community of hardworking and united families.</p>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="feature-item">
                    <div class="feature-icon icon-established">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div class="feature-text-content">
                        <h4 class="feature-title">ESTABLISHED</h4>
                        <p class="feature-desc">A proud barangay with a rich history and a strong sense of community.</p>
                    </div>
                </div>
                <!-- Feature 4 -->
                <div class="feature-item">
                    <div class="feature-icon icon-focus">
                        <i class="bi bi-flower1"></i>
                    </div>
                    <div class="feature-text-content">
                        <h4 class="feature-title">FOCUS</h4>
                        <p class="feature-desc">Peace and order, basic services, community development, and a better future for every Maruingueno.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="about-main-section">
        <div class="about-container">
            
            <!-- Vision & Mission Grid -->
            <div class="vm-grid">
                <!-- Vision Card -->
                <div class="vm-card vision-card">
                    <div class="vm-header">
                        <div class=""vm-icon-badge>
                            <i class="bi bi-eye-fill"></i>
                        </div>
                        <div>
                            <h2 class="vm-title">Vision</h2>
                            <span class="vm-subtitle">Our Aspiration</span>
                        </div>
                    </div>
                    <div class="about-paragraph-wrapper">
                        <p class="about-paragraph"></p>
                    </div>
                </div>

                <!-- Mission Card -->
                <div class="vm-card mission-card">
                    <div class="vm-header">
                        <div class="vm-icon-badge">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <div>
                            <h2 class="vm-title">Mission</h2>
                            <span class="vm-subtitle">Our Commitment</span>
                        </div>
                    </div>
                    <div class="about-paragraph-wrapper">
                        <p class="about-paragraph"></p>
                    </div>
                </div>
            </div>


        </div>
    </main>

    <!-- Custom JavaScript -->
    <script src="public/assets/js/main.js"></script>
</body>
</html>
