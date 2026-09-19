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
    <link rel="stylesheet" href="public/assets/css/style.css?v=2">
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
                    <li class="nav-item">
                        <a class="nav-link" href="officials.php">Officials</a>
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

    <!-- Custom JavaScript -->
    <script src="public/assets/js/main.js"></script>
</body>
</html>
