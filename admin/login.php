<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Login to Barangay Maruing Web-Based Information System portal.">
    <title>Admin Login | Barangay Maruing Information System</title>
    
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
    <link rel="stylesheet" href="../public/assets/css/auth.css">
    <style>
        .auth-card-login {
            border-top: 4px solid var(--accent-color, #ffc107);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top">
        <div class="navbar-container">
            <a class="navbar-brand" href="../index.php">
                <img src="../public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo" style="width: 50px; height: 50px; object-fit: contain; margin-right: 10px;">
                <div class="brand-text">
                    <span class="brand-title">Barangay Maruing</span>
                    <span class="brand-tagline">Admin Portal</span>
                </div>
            </a>
            <div class="navbar-menu">
                <ul class="navbar-nav nav-links">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Back to Main Site</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="auth-wrapper">
        <div class="auth-card auth-card-login">
            <div class="auth-header">
                <div class="auth-icon-badge" style="width: 100px; height: 100px;">
                    <img src="../public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <h1 class="auth-title">Admin Login</h1>
                <p class="auth-subtitle">Sign in to manage the Barangay Portal</p>
            </div>

            <form class="auth-form" action="#" method="POST" onsubmit="event.preventDefault(); window.location.href='dashboard.php';">
                <div class="form-group">
                    <label class="form-label" for="login-username">Admin ID / Username</label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-shield-lock input-icon"></i>
                        <input type="text" id="login-username" class="form-control" placeholder="Enter admin username" required autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="login-password">Password</label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" id="login-password" class="form-control" placeholder="Enter admin password" required autocomplete="current-password">
                        <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('login-password', this)" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" id="remember-me">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link" onclick="alert('Please contact the IT Head for admin password resets.'); return false;">Forgot password?</a>
                </div>

                <button type="submit" class="btn-auth-submit" style="background-color: var(--accent-color, #ffc107); color: #1a1a1a;">
                    <i class="bi bi-box-arrow-in-right"></i> Secure Login
                </button>
            </form>
        </div>
    </main>

    <!-- Custom JavaScript -->
    <script src="../public/assets/js/main.js"></script>
</body>
</html>
