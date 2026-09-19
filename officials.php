<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Meet the Barangay Officials and Council of Barangay Maruing.">
    <title>Officials | Barangay Maruing Information System</title>
    
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
    <link rel="stylesheet" href="public/assets/css/officials.css">
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
                        <a class="nav-link active" href="officials.php">Officials</a>
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

    <!-- Officials Hero Section -->
    <section class="officials-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead">Barangay</span>
                    <span class="hero-title-main">OFFICIALS</span>
                </h1>
                <p class="hero-lead">Meet the dedicated leaders and council members of Barangay Maruing, committed to honest governance and servant leadership.</p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="officials-main-section">
        <div class="officials-container">
            
            <!-- 1. Executive Leadership: Punong Barangay -->
            <section class="captain-section">
                <div class="category-header">
                    <h2 class="category-title">Executive Leadership</h2>
                    <p class="category-subtitle">Head of the Barangay Government</p>
                    <div class="category-accent-bar"></div>
                </div>

                <div class="captain-wrapper">
                    <div class="captain-card">
                        <div class="captain-avatar-col">
                            <div class="captain-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="captain-badge-icon" title="Punong Barangay">
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                        <div class="captain-info-col">
                            <span class="captain-tag"><i class="bi bi-shield-check"></i> Highest Official</span>
                            <h3 class="captain-name">Hon. Punong Barangay</h3>
                            <div class="captain-role">Punong Barangay / Barangay Captain</div>
                            <p class="captain-desc">Presiding officer of the Sangguniang Barangay, leading executive policies, community welfare programs, and peace and order in Barangay Maruing.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. Sangguniang Barangay (Councilors) -->
            <section class="councilors-section">
                <div class="category-header">
                    <h2 class="category-title">Sangguniang Barangay Members</h2>
                    <p class="category-subtitle">Barangay Kagawads & Committee Chairpersons</p>
                    <div class="category-accent-bar"></div>
                </div>

                <div class="officials-grid">
                    <!-- Kagawad 1 -->
                    <div class="official-card">
                        <div class="official-avatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <h3 class="official-name">Hon. Barangay Kagawad 1</h3>
                        <div class="official-role">Barangay Kagawad</div>
                        <div class="official-committee-badge">Committee on Peace & Order</div>
                    </div>

                    <!-- Kagawad 2 -->
                    <div class="official-card">
                        <div class="official-avatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <h3 class="official-name">Hon. Barangay Kagawad 2</h3>
                        <div class="official-role">Barangay Kagawad</div>
                        <div class="official-committee-badge">Committee on Appropriations</div>
                    </div>

                    <!-- Kagawad 3 -->
                    <div class="official-card">
                        <div class="official-avatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <h3 class="official-name">Hon. Barangay Kagawad 3</h3>
                        <div class="official-role">Barangay Kagawad</div>
                        <div class="official-committee-badge">Committee on Health & Sanitation</div>
                    </div>

                    <!-- Kagawad 4 -->
                    <div class="official-card">
                        <div class="official-avatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <h3 class="official-name">Hon. Barangay Kagawad 4</h3>
                        <div class="official-role">Barangay Kagawad</div>
                        <div class="official-committee-badge">Committee on Public Works</div>
                    </div>

                    <!-- Kagawad 5 -->
                    <div class="official-card">
                        <div class="official-avatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <h3 class="official-name">Hon. Barangay Kagawad 5</h3>
                        <div class="official-role">Barangay Kagawad</div>
                        <div class="official-committee-badge">Committee on Education & Culture</div>
                    </div>

                    <!-- Kagawad 6 -->
                    <div class="official-card">
                        <div class="official-avatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <h3 class="official-name">Hon. Barangay Kagawad 6</h3>
                        <div class="official-role">Barangay Kagawad</div>
                        <div class="official-committee-badge">Committee on Agriculture & Environment</div>
                    </div>

                    <!-- Kagawad 7 -->
                    <div class="official-card">
                        <div class="official-avatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <h3 class="official-name">Hon. Barangay Kagawad 7</h3>
                        <div class="official-role">Barangay Kagawad</div>
                        <div class="official-committee-badge">Committee on Social Services & Welfare</div>
                    </div>

                    <!-- SK Chairperson -->
                    <div class="official-card">
                        <div class="official-avatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <h3 class="official-name">Hon. SK Chairperson</h3>
                        <div class="official-role">SK Chairperson / Ex-Officio</div>
                        <div class="official-committee-badge">Committee on Youth & Sports Development</div>
                    </div>
                </div>
            </section>



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
