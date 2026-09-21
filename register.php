<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register for Barangay Maruing Web-Based Information System portal account.">
    <title>Register | Barangay Maruing Information System</title>
    
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
    <link rel="stylesheet" href="public/assets/css/auth.css">
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
                        <a class="btn-register active" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="auth-wrapper">
        <div class="auth-card auth-card-register">
            <div class="auth-header">
                <div class="auth-icon-badge" style="width: 100px; height: 100px;">
                    <img src="public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <h1 class="auth-title">Create an Account</h1>
                <p class="auth-subtitle">Register for the Barangay Maruing Resident Portal</p>
            </div>

            <form class="auth-form" action="#" method="POST" onsubmit="event.preventDefault();">
                <div class="auth-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="reg-firstname">First Name</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" id="reg-firstname" class="form-control" placeholder="e.g. Juan" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="reg-lastname">Last Name</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" id="reg-lastname" class="form-control" placeholder="e.g. Dela Cruz" required>
                        </div>
                    </div>
                </div>

                <div class="auth-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="reg-email">Email Address</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" id="reg-email" class="form-control" placeholder="e.g. juan@email.com" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="reg-phone">Contact Number</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-telephone input-icon"></i>
                            <input type="tel" id="reg-phone" class="form-control" placeholder="09XX-XXX-XXXX" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="reg-purok">Purok / Zone</label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-geo-alt input-icon"></i>
                        <select id="reg-purok" class="form-control" required>
                            <option value="" disabled selected>Select your Purok in Barangay Maruing</option>
                            <option value="purok-1">Purok 1</option>
                            <option value="purok-2">Purok 2</option>
                            <option value="purok-3">Purok 3</option>
                            <option value="purok-4">Purok 4</option>
                            <option value="purok-5">Purok 5</option>
                            <option value="purok-6">Purok 6</option>
                            <option value="purok-7">Purok 7</option>
                        </select>
                    </div>
                </div>

                <div class="auth-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="reg-password">Password</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" id="reg-password" class="form-control" placeholder="Create password" required autocomplete="new-password">
                            <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('reg-password', this)" aria-label="Toggle password visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="reg-confirm-password">Confirm Password</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-shield-check input-icon"></i>
                            <input type="password" id="reg-confirm-password" class="form-control" placeholder="Confirm password" required autocomplete="new-password">
                            <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('reg-confirm-password', this)" aria-label="Toggle password visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" id="terms-agreement" required>
                        <span>I agree to the Barangay Data Privacy & Terms of Service</span>
                    </label>
                </div>

                <button type="submit" class="btn-auth-submit">
                    <i class="bi bi-person-check-fill"></i> Register Account
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="login.php">Sign In</a>
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
