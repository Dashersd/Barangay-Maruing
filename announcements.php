<?php
require_once 'admin/db_connect.php';

function format_announcement_badge($when_date, $created_at) {
    if (!empty($when_date)) {
        $ts = strtotime($when_date);
        if ($ts && $ts > 0) {
            return strtoupper(date('M d', $ts));
        }
    }
    $ts = !empty($created_at) ? strtotime($created_at) : time();
    return strtoupper(date('M d', $ts));
}

$announcements = [];
$res = $conn->query("SELECT * FROM announcements WHERE status = 'published' ORDER BY created_at DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $announcements[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Announcements for Barangay Maruing.">
    <title>Announcements | Barangay Maruing Information System</title>
    
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
    <link rel="stylesheet" href="public/assets/css/announcements.css?v=2">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
                        <a class="nav-link" href="spot-map.php">Spot Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="announcements.php">Announcements</a>
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

    <!-- Announcements Hero Section -->
    <section class="announcements-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead" data-aos="fade-down">Our</span>
                    <span class="hero-title-main" data-aos="zoom-in" data-aos-delay="100">ANNOUNCEMENTS</span>
                </h1>
                <p class="hero-lead" data-aos="fade-up" data-aos-delay="200">Stay informed with the latest news, events, and important updates in Barangay Maruing.</p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="announcements-main-section">
        <div class="container announcements-container">
            
            <div class="announcements-grid <?php echo (count($announcements) === 1) ? 'single-announcement' : ''; ?>">
                <?php if (empty($announcements)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: white; border-radius: 16px; border: 1px dashed #cbd5e1;">
                        <i class="bi bi-megaphone" style="font-size: 3rem; color: #94a3b8; display: block; margin-bottom: 15px;"></i>
                        <h3 style="color: #334155; margin-bottom: 8px;">No Announcements Yet</h3>
                        <p style="color: #64748b; margin: 0;">Stay tuned! New community updates and notices will be posted here soon.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($announcements as $idx => $ann): ?>
                        <div id="announcement-<?php echo $ann['id']; ?>" class="announcement-card-v2" data-aos="fade-up" data-aos-delay="<?php echo ($idx % 3) * 100; ?>">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.2rem;">
                                <div class="announcement-date-badge" style="margin-bottom: 0;">
                                    <?php echo htmlspecialchars(format_announcement_badge($ann['when_date'], $ann['created_at'])); ?>
                                </div>
                                <span style="font-size: 0.8rem; color: #94a3b8;"><i class="bi bi-calendar3"></i> <?php echo date('M d, Y', strtotime($ann['created_at'])); ?></span>
                            </div>

                            <?php if (!empty($ann['cover_image']) && file_exists('admin/' . $ann['cover_image'])): ?>
                                <div class="announcement-image-wrapper" onclick="openAnnouncementLightbox('admin/<?php echo htmlspecialchars($ann['cover_image'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars(addslashes($ann['title']), ENT_QUOTES); ?>')" title="Click to view full image">
                                    <img class="announcement-img-backdrop" src="admin/<?php echo htmlspecialchars($ann['cover_image']); ?>" alt="" aria-hidden="true">
                                    <img class="announcement-img-main" src="admin/<?php echo htmlspecialchars($ann['cover_image']); ?>" alt="<?php echo htmlspecialchars($ann['title']); ?>">
                                    <span class="announcement-img-badge"><i class="bi bi-arrows-fullscreen"></i> View Full Image</span>
                                </div>
                            <?php endif; ?>

                            <h3 class="announcement-card-title"><?php echo htmlspecialchars($ann['title']); ?></h3>
                            
                            <div class="announcement-details">
                                <?php if (!empty($ann['what'])): ?>
                                    <p><strong>What:</strong> <?php echo htmlspecialchars($ann['what']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($ann['when_date'])): ?>
                                    <p><strong>When:</strong> <?php echo htmlspecialchars($ann['when_date']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($ann['where_place'])): ?>
                                    <p><strong>Where:</strong> <?php echo htmlspecialchars($ann['where_place']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($ann['who'])): ?>
                                    <p><strong>Who:</strong> <?php echo htmlspecialchars($ann['who']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($ann['why'])): ?>
                                    <p><strong>Why:</strong> <?php echo htmlspecialchars($ann['why']); ?></p>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($ann['content'])): ?>
                                <div class="announcement-divider"></div>
                                <div class="announcement-description">
                                    <p><?php echo nl2br(htmlspecialchars($ann['content'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                <div class="footer-socials">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-envelope-fill"></i></a>
                </div>
                <p class="footer-copy">&copy; Copyright. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Announcement Fullscreen Lightbox Modal -->
    <div id="announcementLightbox" class="announcement-lightbox" onclick="closeAnnouncementLightbox(event)">
        <button type="button" class="announcement-lightbox-close" onclick="closeAnnouncementLightbox(event)" title="Close (Esc)">&times;</button>
        <div class="announcement-lightbox-content" onclick="event.stopPropagation()">
            <img id="announcementLightboxImg" src="" alt="Full Announcement Image">
            <div id="announcementLightboxTitle" class="announcement-lightbox-title"></div>
        </div>
    </div>

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
      });

      function openAnnouncementLightbox(src, title) {
          var modal = document.getElementById('announcementLightbox');
          var img = document.getElementById('announcementLightboxImg');
          var titleEl = document.getElementById('announcementLightboxTitle');
          if (modal && img) {
              img.src = src;
              if (titleEl) titleEl.textContent = title || '';
              modal.classList.add('active');
              document.body.style.overflow = 'hidden';
          }
      }

      function closeAnnouncementLightbox(e) {
          if (e && e.stopPropagation) e.stopPropagation();
          var modal = document.getElementById('announcementLightbox');
          if (modal) {
              modal.classList.remove('active');
              document.body.style.overflow = '';
          }
      }

      document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
              closeAnnouncementLightbox();
          }
      });
    </script>
</body>
</html>
