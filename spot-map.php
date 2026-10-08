<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Barangay Maruing Spot Map">
    <title>Spot Map | Barangay Maruing Information System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=9">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
                        <a class="nav-link active" href="spot-map.php">Spot Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Services</a>
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

    <main class="py-5 mt-4" style="width: 96%; max-width: 1400px; margin: 0 auto;">

        <div class="landing-spot-map-container" style="display: grid; grid-template-columns: 360px 1fr; gap: 3rem; align-items: start;">
            <div class="spot-map-content" style="display: flex; flex-direction: column; gap: 2rem; padding: 0; position: sticky; top: 110px;">
                
                <!-- Container 1: Map Legend -->
                <div class="spot-map-card" data-aos="fade-right" style="background: #ffffff; padding: 2.5rem 2rem; border-radius: 20px; border: none; box-shadow: 0 4px 25px rgba(0,0,0,0.04);">
                    <h3 style="font-family: Georgia, 'Times New Roman', serif; font-weight: 700; font-size: 1.5rem; color: #093d25; display: flex; align-items: center; gap: 12px; margin-bottom: 2rem;">
                        <i class="bi bi-map" style="color: #ffc107; font-size: 1.6rem;"></i> Map Legend
                    </h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem 1rem;">
                        <div style="display: flex; align-items: flex-start; gap: 10px; font-weight: 500; color: #64748b; font-size: 0.95rem;">
                            <i class="bi bi-building-fill" style="color: #6366f1; font-size: 1.25rem;"></i>
                            <span style="line-height: 1.2;">Barangay<br>Hall</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; font-weight: 500; color: #64748b; font-size: 0.95rem;">
                            <i class="bi bi-book-half" style="color: #10b981; font-size: 1.25rem;"></i>
                            <span style="line-height: 1.2;">School</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; font-weight: 500; color: #64748b; font-size: 0.95rem;">
                            <i class="bi bi-dribbble" style="color: #f59e0b; font-size: 1.25rem;"></i>
                            <span style="line-height: 1.2;">Court</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; font-weight: 500; color: #64748b; font-size: 0.95rem;">
                            <i class="bi bi-heart-pulse-fill" style="color: #e11d48; font-size: 1.25rem;"></i>
                            <span style="line-height: 1.2;">Health Center</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; font-weight: 500; color: #64748b; font-size: 0.95rem; grid-column: span 2;">
                            <i class="bi bi-geo-fill" style="color: #94a3b8; font-size: 1.25rem;"></i>
                            <span style="line-height: 1.2;">Other Landmarks</span>
                        </div>
                    </div>
                </div>

                <!-- Container 2: 9 Puroks of Maruing -->
                <div class="spot-map-card" data-aos="fade-right" data-aos-delay="150" style="background: #ffffff; padding: 2.5rem 2rem; border-radius: 20px; border: none; box-shadow: 0 4px 25px rgba(0,0,0,0.04);">
                    <h3 style="font-family: Georgia, 'Times New Roman', serif; font-weight: 700; font-size: 1.45rem; color: #093d25; display: flex; align-items: center; gap: 12px; margin-bottom: 2rem;">
                        <i class="bi bi-geo-alt-fill" style="color: #f59e0b; font-size: 1.6rem;"></i> 9 Puroks of Maruing
                    </h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; width: 100%;">
                        <?php 
                        $purokImages = [
                            1 => 'public/image/Purok/Purok 1.jpg?v=2',
                            2 => 'public/image/Purok/Purok 2.jpg?v=2',
                            3 => 'public/image/Purok/Purok 3.jpg?v=2',
                            4 => 'public/image/Purok/Purok 4.jpg?v=2',
                            5 => 'public/image/Purok/Purok 5.jpg?v=2',
                            6 => 'public/image/Purok/Purok 6.jpg?v=2',
                            7 => 'public/image/Purok/Purok 7.jpg?v=2',
                            8 => 'public/image/Purok/Purok 8.jpg?v=2',
                            9 => 'public/image/Purok/Purok 9.jpg?v=2'
                        ];
                        for($i=1; $i<=9; $i++): 
                            $hasImg = isset($purokImages[$i]);
                            $imgSrc = $hasImg ? $purokImages[$i] : '';
                        ?>
                            <button type="button" 
                                    class="purok-chip-btn <?php echo $hasImg ? 'has-image' : ''; ?>" 
                                    data-purok="<?php echo $i; ?>" 
                                    <?php if($hasImg): ?>data-img="<?php echo $imgSrc; ?>"<?php endif; ?>
                                    <?php if(!$hasImg): ?>disabled style="opacity: 0.55; cursor: not-allowed; background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; border-radius: 25px; padding: 0.6rem 0; font-weight: 700; font-size: 0.85rem; width: 100%; text-align: center;" title="Map coming soon"<?php else: ?>style="background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; border-radius: 25px; padding: 0.6rem 0; font-weight: 700; font-size: 0.85rem; width: 100%; text-align: center;" title="Click to view Purok <?php echo $i; ?> map"<?php endif; ?>>
                                Purok <?php echo $i; ?>
                            </button>
                        <?php endfor; ?>

                    </div>
                </div>
            </div>
            
            <div class="spot-map-visual-wrapper" data-aos="fade-left" style="display: flex; flex-direction: column; width: 100%; margin-top: 3.5rem;">
                <!-- Back to Overview Button (Positioned at the Top) -->
                <div id="back-overview-wrapper" style="display: none; justify-content: flex-start; margin-bottom: 14px;">
                    <button type="button" id="btn-back-overview" class="btn-back-overview" title="Back to Regional Overview Map">
                        <i class="bi bi-arrow-left"></i> Overview Map
                    </button>
                </div>

                <div class="spot-map-visual">
                    <!-- Interactive Red Pin on Maruing -->
                    <div class="map-pin-wrapper" id="maruing-pin-btn" role="button" tabindex="0" title="Click to view detailed map of Barangay Maruing" style="display: none;">
                        <div class="pin-pulse"></div>
                        <div class="pin-marker">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="pin-tooltip">Click to View Maruing</div>
                    </div>

                    <img id="spot-map-img" src="public/image/Maruing Map/Philippines.png" alt="Spot Map of Barangay Maruing" style="width: 100%; height: 850px !important; max-height: none !important; object-fit: fill !important; border-radius: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); cursor: pointer;">
                    
                    <!-- Dynamic Markers Container -->
                    <div id="map-markers-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 500;"></div>
                </div>
            </div>
        </div>
        
        <!-- Household Details Modal -->
        <div id="household-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
            <div style="background: white; border-radius: 16px; padding: 30px; width: 90%; max-width: 400px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); position: relative; animation: slideInUp 0.3s ease-out;">
                <button type="button" id="close-modal-btn" style="position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer;">&times;</button>
                <div style="text-align: center; margin-bottom: 20px;">
                    <h3 style="color: #093d25; font-weight: 700; margin-bottom: 5px;">Household Details</h3>
                    <p style="color: #64748b; font-size: 0.9rem; margin: 0;">Barangay Maruing</p>
                </div>
                <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                    <img id="modal-house-img" src="" alt="House Image" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #f1f5f9; display: none;">
                    <div id="modal-no-img" style="width: 120px; height: 120px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 2rem;"><i class="bi bi-house-door-fill"></i></div>
                </div>
                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #64748b; font-size: 0.9rem;">House Number</span>
                        <strong id="modal-house-no" style="color: #334155;">-</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #64748b; font-size: 0.9rem;">Husband/Head</span>
                        <strong id="modal-husband" style="color: #334155;">-</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b; font-size: 0.9rem;">Spouse Name</span>
                        <strong id="modal-spouse" style="color: #334155;">-</strong>
                    </div>
                </div>
            </div>
        </div>
        <style>
            @keyframes slideInUp {
                from { transform: translateY(30px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
        </style>
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

    <!-- Spot Map Interactive Pin & Image Switcher -->
    <script src="public/assets/js/spotmap.js?v=1"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
          duration: 800, 
          once: true,
          offset: 100
        });
      });
    </script>
</body>
</html>


