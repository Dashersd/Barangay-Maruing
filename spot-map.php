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
                <div class="spot-map-card" style="background: #ffffff; padding: 2.5rem 2rem; border-radius: 20px; border: none; box-shadow: 0 4px 25px rgba(0,0,0,0.04);">
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
                <div class="spot-map-card" style="background: #ffffff; padding: 2.5rem 2rem; border-radius: 20px; border: none; box-shadow: 0 4px 25px rgba(0,0,0,0.04);">
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
            
            <div class="spot-map-visual-wrapper" style="display: flex; flex-direction: column; width: 100%; margin-top: 3.5rem;">
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

                    <img id="spot-map-img" src="public/image/Maruing Map/Philippines.jpg" alt="Spot Map of Barangay Maruing" style="width: 100%; height: 100%; min-height: 750px; max-height: 85vh; object-fit: cover; border-radius: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); cursor: pointer;">
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

    <!-- Spot Map Interactive Pin & Image Switcher -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const spotMapImg = document.getElementById('spot-map-img');
        const maruingPin = document.getElementById('maruing-pin-btn');
        const btnBackOverview = document.getElementById('btn-back-overview');
        const backOverviewWrapper = document.getElementById('back-overview-wrapper');
        const btnViewFullImage = document.getElementById('btn-view-full-image');
        
        const overviewImgSrc = 'public/image/Maruing Map/download.png';
        const detailedImgSrc = 'public/image/Maruing Map/map2.png';

        const purokButtons = document.querySelectorAll('.purok-chip-btn.has-image');

        function clearActivePurok() {
            document.querySelectorAll('.purok-chip-btn').forEach(function(btn) {
                btn.classList.remove('active');
            });
        }

        function showDetailedMap() {
            if (!spotMapImg) return;
            spotMapImg.style.opacity = '0.2';
            setTimeout(function() {
                spotMapImg.src = detailedImgSrc;
                spotMapImg.alt = 'Detailed Street Map of Barangay Maruing';
                spotMapImg.style.opacity = '1';
                if (maruingPin) maruingPin.style.display = 'none';
                if (backOverviewWrapper) backOverviewWrapper.style.display = 'flex';
                if (btnViewFullImage) btnViewFullImage.href = detailedImgSrc;
                clearActivePurok();
            }, 180);
        }

        function showOverviewMap() {
            if (!spotMapImg) return;
            spotMapImg.style.opacity = '0.2';
            setTimeout(function() {
                spotMapImg.src = overviewImgSrc;
                spotMapImg.alt = 'Spot Map of Barangay Maruing';
                spotMapImg.style.opacity = '1';
                if (maruingPin) maruingPin.style.display = 'flex';
                if (backOverviewWrapper) backOverviewWrapper.style.display = 'none';
                if (btnViewFullImage) btnViewFullImage.href = overviewImgSrc;
                clearActivePurok();
            }, 180);
        }

        function showPurokMap(imgSrc, purokNum, btnElement) {
            if (!spotMapImg || !imgSrc) return;
            spotMapImg.style.opacity = '0.2';
            setTimeout(function() {
                spotMapImg.src = imgSrc;
                spotMapImg.alt = 'Map of Purok ' + purokNum + ', Barangay Maruing';
                spotMapImg.style.opacity = '1';
                if (maruingPin) maruingPin.style.display = 'none';
                if (backOverviewWrapper) backOverviewWrapper.style.display = 'flex';
                if (btnViewFullImage) btnViewFullImage.href = imgSrc;
                clearActivePurok();
                if (btnElement) btnElement.classList.add('active');
            }, 180);
        }

        purokButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const imgSrc = this.getAttribute('data-img');
                const purokNum = this.getAttribute('data-purok');
                if (imgSrc) {
                    showPurokMap(imgSrc, purokNum, this);
                }
            });
        });

        const mapSequence = [
            'public/image/Maruing Map/Philippines.jpg',
            'public/image/Maruing Map/Zamboanga del sur.jpg',
            'public/image/Maruing Map/Lapuyan.gif',
            'public/image/Maruing Map/download.png'
        ];
        let currentSequenceIndex = 0;

        if (spotMapImg) {
            spotMapImg.addEventListener('click', function() {
                if (currentSequenceIndex < mapSequence.length - 1) {
                    currentSequenceIndex++;
                    spotMapImg.style.opacity = '0.2';
                    setTimeout(function() {
                        spotMapImg.src = mapSequence[currentSequenceIndex];
                        spotMapImg.style.opacity = '1';
                        
                        if (currentSequenceIndex === mapSequence.length - 1) {
                            if (maruingPin) maruingPin.style.display = 'flex';
                            spotMapImg.style.cursor = 'default';
                        }
                    }, 180);
                }
            });
        }
        if (maruingPin) {
            maruingPin.addEventListener('click', showDetailedMap);
            maruingPin.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    showDetailedMap();
                }
            });
        }

        if (btnBackOverview) {
            btnBackOverview.addEventListener('click', showOverviewMap);
        }
    });
    </script>
</body>
</html>


