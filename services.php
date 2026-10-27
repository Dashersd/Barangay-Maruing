<?php
require_once 'admin/db_connect.php';

// Fetch active services ordered by display_order ASC
$services = [];
$res = $conn->query("SELECT * FROM services WHERE status = 'active' ORDER BY display_order ASC, id ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $services[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Services offered by Barangay Maruing. Access clearances, certificates, and community assistance online.">
    <title>Services | Barangay Maruing Information System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=4">
    <link rel="stylesheet" href="public/assets/css/services.css?v=2">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .service-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .service-card-body {
            flex-grow: 1;
        }
        .service-requirements-preview {
            background: #f1f8f4;
            border-left: 3px solid #137547;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #334155;
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .service-requirements-preview strong {
            display: block;
            color: #093d25;
            margin-bottom: 3px;
        }

        /* Detail Modal Styles */
        .service-detail-modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .service-detail-dialog {
            background: white;
            border-radius: 16px;
            max-width: 540px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
            animation: modalFadeIn 0.3s ease-out;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .service-detail-header {
            background: linear-gradient(135deg, #093d25, #137547);
            color: white;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .service-detail-header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .service-detail-header .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: background 0.2s;
        }
        .service-detail-header .close-btn:hover {
            background: rgba(255, 255, 255, 0.35);
        }
        .service-detail-body {
            padding: 24px;
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .service-detail-footer {
            padding: 16px 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        .btn-modal-contact {
            background: #137547;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }
        .btn-modal-contact:hover {
            background: #093d25;
            color: white;
        }
        .btn-modal-close {
            background: transparent;
            color: #64748b;
            border: 1px solid #cbd5e1;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-modal-close:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top">
        <div class="navbar-container">
            <div class="navbar-brand">
                <img src="public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo" style="width: 50px; height: 50px; object-fit: contain; margin-right: 8px;">
                <img src="public/image/Lapuyan Logo.png" alt="Municipality of Lapuyan Logo" style="width: 50px; height: 50px; object-fit: contain; margin-right: 12px;">
                <div class="brand-text">
                    <span class="brand-title">Barangay Maruing</span>
                    <span class="brand-tagline">Serbisyong Tapat, Para sa Lahat</span>
                </div>
            </div>
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
                        <a class="nav-link active" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="announcements.php">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="spot-map.php">Spot Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
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

    <!-- Services Hero Section -->
    <section class="services-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead" data-aos="fade-down">Our</span>
                    <span class="hero-title-main" data-aos="zoom-in" data-aos-delay="100">SERVICES</span>
                </h1>
                <p class="hero-lead" data-aos="fade-up" data-aos-delay="200">Access essential barangay services, permits, and clearances conveniently online.</p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="services-main-section">
        <div class="container services-container">
            
            <?php if (empty($services)): ?>
                <!-- Empty State -->
                <div style="text-align: center; padding: 4rem 1rem; color: #64748b;" data-aos="fade-up">
                    <i class="bi bi-folder-x" style="font-size: 3.5rem; color: #137547; display: block; margin-bottom: 1rem;"></i>
                    <h3 style="color: #093d25; margin-bottom: 0.5rem; font-weight: 700; font-size: 1.5rem;">No Services Listed</h3>
                    <p style="max-width: 520px; margin: 0 auto; line-height: 1.6;">Our barangay service catalog is currently being updated. Please check back shortly or visit the Barangay Hall for direct inquiries.</p>
                </div>
            <?php else: ?>
                <!-- Dynamic Services Grid -->
                <div class="services-grid">
                    <?php 
                    $delay = 0;
                    foreach ($services as $service): 
                        $iconClass = !empty($service['icon']) ? htmlspecialchars($service['icon']) : 'bi-file-earmark-text';
                        $btnText = !empty($service['button_text']) ? htmlspecialchars($service['button_text']) : 'Request Document';
                        $btnUrl = !empty($service['button_url']) ? htmlspecialchars($service['button_url']) : 'contact.php';
                        $fee = !empty($service['fee']) ? htmlspecialchars($service['fee']) : 'Free';
                        $procTime = !empty($service['processing_time']) ? htmlspecialchars($service['processing_time']) : 'Same Day';
                        
                        $serviceJson = htmlspecialchars(json_encode([
                            'name' => $service['name'],
                            'description' => $service['description'],
                            'requirements' => $service['requirements'],
                            'fee' => $fee,
                            'processing_time' => $procTime,
                            'button_text' => $btnText,
                            'button_url' => $btnUrl,
                            'icon' => $iconClass
                        ]), ENT_QUOTES, 'UTF-8');
                    ?>
                        <div id="service-<?= $service['id'] ?>" class="service-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>" data-service='<?= $serviceJson ?>'>
                            <div class="service-card-body">
                                <div class="service-icon">
                                    <i class="bi <?= $iconClass ?>"></i>
                                </div>
                                <h3 class="service-title"><?= htmlspecialchars($service['name']) ?></h3>

                                <div style="display: flex; gap: 8px; justify-content: center; margin-bottom: 1.2rem; flex-wrap: wrap;">
                                    <span style="background: #e8f5ee; color: #137547; font-size: 0.8rem; font-weight: 600; padding: 4px 10px; border-radius: 20px;">
                                        <i class="bi bi-cash-stack"></i> <?= $fee ?>
                                    </span>
                                    <span style="background: #f1f5f9; color: #475569; font-size: 0.8rem; font-weight: 600; padding: 4px 10px; border-radius: 20px;">
                                        <i class="bi bi-clock"></i> <?= $procTime ?>
                                    </span>
                                </div>

                                <p class="service-description"><?= nl2br(htmlspecialchars($service['description'])) ?></p>
                                
                                <?php if (!empty($service['requirements'])): ?>
                                    <div class="service-requirements-preview">
                                        <strong><i class="bi bi-card-checklist"></i> Requirements:</strong>
                                        <?= nl2br(htmlspecialchars($service['requirements'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="service-card-action">
                                <button type="button" class="btn-service" style="cursor: pointer; background: transparent;" onclick='openServiceModal(<?= $serviceJson ?>)'>
                                    <?= $btnText ?> <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    <?php 
                        $delay = ($delay + 100) % 400;
                    endforeach; 
                    ?>
                </div>
            <?php endif; ?>
            
        </div>
    </main>

    <!-- Service Detail & Instructions Modal -->
    <div id="serviceDetailModal" class="service-detail-modal">
        <div class="service-detail-dialog">
            <div class="service-detail-header">
                <h3>
                    <i id="modalServiceIcon" class="bi bi-file-earmark-text"></i>
                    <span id="modalServiceName">Service Details</span>
                </h3>
                <button type="button" class="close-btn" onclick="closeServiceModal()">&times;</button>
            </div>
            <div class="service-detail-body">
                <div style="display: flex; gap: 10px; margin-bottom: 1.2rem; flex-wrap: wrap;">
                    <span style="background: #e8f5ee; color: #137547; font-size: 0.85rem; font-weight: 600; padding: 5px 12px; border-radius: 20px;">
                        <i class="bi bi-cash-stack"></i> Fee: <strong id="modalServiceFee">Free</strong>
                    </span>
                    <span style="background: #f1f5f9; color: #475569; font-size: 0.85rem; font-weight: 600; padding: 5px 12px; border-radius: 20px;">
                        <i class="bi bi-clock"></i> Processing: <strong id="modalServiceTime">Same Day</strong>
                    </span>
                </div>

                <div style="margin-bottom: 1.2rem;">
                    <h5 style="color: #093d25; font-weight: 700; margin-bottom: 6px; font-size: 1rem;">Description</h5>
                    <p id="modalServiceDesc" style="margin: 0;"></p>
                </div>
                
                <div id="modalRequirementsSection" style="margin-bottom: 1.2rem;">
                    <h5 style="color: #093d25; font-weight: 700; margin-bottom: 6px; font-size: 1rem;">Prerequisites & Requirements</h5>
                    <div id="modalServiceReqs" style="background: #f8fafc; border-left: 3px solid #137547; padding: 12px 14px; border-radius: 6px; font-size: 0.9rem;"></div>
                </div>

                <div style="background: #e8f5ee; border-radius: 8px; padding: 12px 16px; display: flex; gap: 10px; align-items: flex-start;">
                    <i class="bi bi-info-circle-fill" style="color: #137547; font-size: 1.2rem; margin-top: 2px;"></i>
                    <p style="margin: 0; font-size: 0.85rem; color: #093d25;">
                        To complete your request, please prepare the requirements above and visit the Barangay Hall during business hours (Monday to Friday, 8:00 AM - 5:00 PM), or send an inquiry via our contact page.
                    </p>
                </div>
            </div>
            <div class="service-detail-footer">
                <button type="button" class="btn-modal-close" onclick="closeServiceModal()">Close</button>
                <a id="modalContactBtn" href="contact.php" class="btn-modal-contact">
                    Inquire Online <i class="bi bi-send-fill"></i>
                </a>
            </div>
        </div>
    </div>

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
                <div class="col-md-2 col-6 footer-links">
                    <a href="index.php">HOME</a>
                    <a href="about.php">ABOUT US</a>
                    <a href="vision.php">VISION</a>
                </div>
                <!-- Column 3 -->
                <div class="col-md-3 col-6 footer-links">
                    <a href="mission.php">MISSION</a>
                    <a href="officials.php">BARANGAY OFFICIALS</a>
                    <a href="SKofficial.php">SK OFFICIALS</a>
                </div>
                <!-- Column 4 -->
                <div class="col-md-3 col-6 footer-links">
                    <a href="spot-map.php">SPOT MAP</a>
                    <a href="gallery.php">GALLERY</a>
                    <a href="contact.php">CONTACT US</a>
                </div>
            </div>
            
            <hr class="footer-divider">
            
            <div class="footer-bottom">
                <p class="footer-copy">&copy; Copyright. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom JavaScript -->
    <script src="public/assets/js/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800, 
                once: true,
                offset: 100
            });

            // Smooth scroll and open modal if navigated directly from homepage
            const hash = window.location.hash;
            if (hash && hash.startsWith('#service-')) {
                const targetCard = document.querySelector(hash);
                if (targetCard) {
                    setTimeout(function() {
                        targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 200);
                    const svcDataStr = targetCard.getAttribute('data-service');
                    if (svcDataStr && (window.location.search.includes('open=true') || window.location.hash)) {
                        try {
                            const svcData = JSON.parse(svcDataStr);
                            setTimeout(function() {
                                openServiceModal(svcData);
                            }, 400);
                        } catch (e) {}
                    }
                }
            }
        });

        function openServiceModal(data) {
            document.getElementById('modalServiceName').textContent = data.name;
            document.getElementById('modalServiceIcon').className = 'bi ' + (data.icon || 'bi-file-earmark-text');
            document.getElementById('modalServiceDesc').textContent = data.description || 'No description available.';
            document.getElementById('modalServiceFee').textContent = data.fee || 'Free';
            document.getElementById('modalServiceTime').textContent = data.processing_time || 'Same Day';
            
            const reqSection = document.getElementById('modalRequirementsSection');
            const reqContent = document.getElementById('modalServiceReqs');
            if (data.requirements && data.requirements.trim() !== '') {
                reqContent.innerHTML = data.requirements.replace(/\n/g, '<br>');
                reqSection.style.display = 'block';
            } else {
                reqSection.style.display = 'none';
            }

            const contactBtn = document.getElementById('modalContactBtn');
            if (contactBtn) {
                const targetUrl = data.button_url && data.button_url.trim() !== '' 
                    ? data.button_url 
                    : ('contact.php?subject=' + encodeURIComponent('Inquiry: ' + data.name));
                contactBtn.href = targetUrl;
                contactBtn.innerHTML = (data.button_text || 'Inquire Online') + ' <i class="bi bi-arrow-right"></i>';
            }

            const modal = document.getElementById('serviceDetailModal');
            if (modal) modal.style.display = 'flex';
        }

        function closeServiceModal() {
            const modal = document.getElementById('serviceDetailModal');
            if (modal) modal.style.display = 'none';
        }

        // Close on backdrop click and Esc key
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('serviceDetailModal');
            if (event.target === modal) {
                closeServiceModal();
            }
        });

        window.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeServiceModal();
            }
        });
    </script>
</body>
</html>
