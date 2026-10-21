<?php
// gallery.php
require_once 'admin/db_connect.php';

// Fetch folders with media counts
$folders = [];
$total_media_count = 0;
$folders_res = $conn->query("SELECT f.*, COUNT(m.id) as media_count FROM gallery_folders f LEFT JOIN gallery_media m ON f.id = m.folder_id GROUP BY f.id ORDER BY f.name ASC");
if ($folders_res) {
    while ($row = $folders_res->fetch_assoc()) {
        $folders[] = $row;
    }
}

// Fetch all media items joined with folder name
$media_items = [];
$media_res = $conn->query("SELECT m.*, f.name as folder_name FROM gallery_media m JOIN gallery_folders f ON m.folder_id = f.id ORDER BY m.id DESC");
if ($media_res) {
    while ($m = $media_res->fetch_assoc()) {
        // Ensure image file exists before displaying
        if (file_exists('admin/' . $m['file_path'])) {
            $media_items[] = $m;
        }
    }
}
$total_media_count = count($media_items);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View the photo gallery of Barangay Maruing. Browse community meetings, sports festivals, projects, and events.">
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
    <link rel="stylesheet" href="public/assets/css/style.css?v=6">
    <link rel="stylesheet" href="public/assets/css/gallery.css?v=6">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
                        <a class="nav-link active" href="gallery.php">Gallery</a>
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

    <!-- Gallery Hero Section -->
    <section class="gallery-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead" data-aos="fade-down">Our</span>
                    <span class="hero-title-main" data-aos="zoom-in" data-aos-delay="100">GALLERY</span>
                </h1>
                <p class="hero-lead" data-aos="fade-up" data-aos-delay="200">
                    Explore the beauty, events, and community life of Barangay Maruing through our photo gallery.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="gallery-main-section">
        <div class="container gallery-container">
            
            <!-- Category Filter Tabs Bar -->
            <div class="gallery-filter-wrapper" data-aos="fade-up">
                <div class="gallery-filter-bar" id="galleryFilterBar">
                    <button class="filter-btn active" data-filter="all">
                        <i class="bi bi-grid-fill"></i> All Photos 
                        <span class="filter-badge"><?= $total_media_count > 0 ? $total_media_count : 2 ?></span>
                    </button>
                    <?php if (!empty($folders)): ?>
                        <?php foreach ($folders as $folder): ?>
                            <button class="filter-btn" data-filter="folder-<?= $folder['id'] ?>">
                                <i class="bi bi-folder2"></i> <?= htmlspecialchars($folder['name']) ?> 
                                <?php if ($folder['media_count'] > 0): ?>
                                    <span class="filter-badge"><?= $folder['media_count'] ?></span>
                                <?php endif; ?>
                            </button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Gallery Photos Grid -->
            <div class="gallery-grid" id="publicGalleryGrid">
                <?php if (!empty($media_items)): ?>
                    <?php foreach ($media_items as $index => $item): 
                        $formatted_date = date('M d, Y', strtotime($item['created_at']));
                        $formatted_time = date('h:i A', strtotime($item['created_at']));
                        $full_timestamp = $formatted_date . ' • ' . $formatted_time;
                    ?>
                        <div class="gallery-item filter-item folder-<?= $item['folder_id'] ?>" 
                             data-aos="zoom-in" 
                             data-aos-delay="<?= ($index % 6) * 80 ?>" 
                             data-src="admin/<?= htmlspecialchars($item['file_path']) ?>" 
                             data-caption="<?= htmlspecialchars($item['caption'] ?? $item['folder_name']) ?>" 
                             data-category="<?= htmlspecialchars($item['folder_name']) ?>"
                             data-timestamp="<?= htmlspecialchars($full_timestamp) ?>"
                             onclick="openPublicLightboxByIndex(<?= $index ?>)">
                            <img src="admin/<?= htmlspecialchars($item['file_path']) ?>" alt="<?= htmlspecialchars($item['caption'] ?? 'Gallery Photo') ?>" class="gallery-img" loading="lazy">
                            <div class="gallery-overlay">
                                <div class="gallery-overlay-content">
                                    <span class="gallery-zoom-icon"><i class="bi bi-arrows-fullscreen"></i></span>
                                    <span class="gallery-caption"><?= htmlspecialchars($item['caption'] ?? $item['folder_name']) ?></span>
                                    <span class="gallery-folder-tag"><i class="bi bi-folder-fill"></i> <?= htmlspecialchars($item['folder_name']) ?></span>
                                    <span class="gallery-timestamp"><i class="bi bi-clock-history"></i> <?= htmlspecialchars($full_timestamp) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default Initial Showcase if no photos uploaded yet -->
                    <div class="gallery-item filter-item folder-default" 
                         data-aos="zoom-in" 
                         data-aos-delay="0" 
                         data-src="public/image/Maruing.png" 
                         data-caption="Barangay Hall" 
                         data-category="Barangay Center"
                         data-timestamp="Oct 13, 2026 • 10:00 AM"
                         onclick="openPublicLightbox('public/image/Maruing.png', 'Barangay Hall', 'Barangay Center', 'Oct 13, 2026 • 10:00 AM')">
                        <img src="public/image/Maruing.png" alt="Barangay Hall" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <span class="gallery-zoom-icon"><i class="bi bi-arrows-fullscreen"></i></span>
                                <span class="gallery-caption">Barangay Hall</span>
                                <span class="gallery-folder-tag"><i class="bi bi-folder-fill"></i> Barangay Center</span>
                                <span class="gallery-timestamp"><i class="bi bi-clock-history"></i> Oct 13, 2026 • 10:00 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-item filter-item folder-default" 
                         data-aos="zoom-in" 
                         data-aos-delay="100" 
                         data-src="public/image/Maruing Logo 2.png" 
                         data-caption="Official Logo" 
                         data-category="Official"
                         data-timestamp="Oct 13, 2026 • 10:00 AM"
                         onclick="openPublicLightbox('public/image/Maruing Logo 2.png', 'Official Logo', 'Official', 'Oct 13, 2026 • 10:00 AM')">
                        <img src="public/image/Maruing Logo 2.png" alt="Official Logo" class="gallery-img logo-img">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <span class="gallery-zoom-icon"><i class="bi bi-arrows-fullscreen"></i></span>
                                <span class="gallery-caption">Official Logo</span>
                                <span class="gallery-folder-tag"><i class="bi bi-folder-fill"></i> Official</span>
                                <span class="gallery-timestamp"><i class="bi bi-clock-history"></i> Oct 13, 2026 • 10:00 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-item placeholder-item filter-item" data-aos="zoom-in" data-aos-delay="200">
                        <div class="placeholder-content">
                            <i class="bi bi-images"></i>
                            <span>More photos coming soon</span>
                        </div>
                    </div>
                    <div class="gallery-item placeholder-item filter-item" data-aos="zoom-in" data-aos-delay="300">
                        <div class="placeholder-content">
                            <i class="bi bi-images"></i>
                            <span>More photos coming soon</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Empty State for filtered categories with 0 photos -->
            <div id="filterEmptyState" class="filter-empty-state" style="display: none;">
                <div class="empty-icon-wrap">
                    <i class="bi bi-folder-x"></i>
                </div>
                <h3>No photos in this category yet</h3>
                <p>There are currently no photos uploaded under this category. Please check back soon or view "All Photos".</p>
                <button type="button" class="btn-reset-filter" onclick="resetToAllFilter()">
                    <i class="bi bi-grid-fill"></i> View All Photos
                </button>
            </div>

        </div>
    </main>

    <!-- Public Interactive Lightbox Modal -->
    <div id="publicLightbox" class="public-lightbox" onclick="closePublicLightbox()">
        <!-- Close Button -->
        <button type="button" class="public-lightbox-close" title="Close (Esc)" onclick="closePublicLightbox()">&times;</button>
        
        <!-- Previous & Next Controls -->
        <button type="button" class="lightbox-arrow lightbox-prev" title="Previous Image" onclick="prevLightbox(event)">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button type="button" class="lightbox-arrow lightbox-next" title="Next Image" onclick="nextLightbox(event)">
            <i class="bi bi-chevron-right"></i>
        </button>

        <div class="public-lightbox-container" onclick="event.stopPropagation()">
            <div class="lightbox-image-wrapper">
                <img src="" alt="Photo" id="publicLightboxImg" class="public-lightbox-img">
            </div>
            <div class="public-lightbox-caption-wrap">
                <div class="lightbox-info-row">
                    <span id="publicLightboxCategory" class="public-lightbox-cat"></span>
                    <span id="publicLightboxTimestamp" class="public-lightbox-timestamp"></span>
                    <span id="publicLightboxCounter" class="public-lightbox-counter"></span>
                </div>
                <h4 id="publicLightboxCaption"></h4>
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
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      let visibleItems = [];
      let currentLightboxIndex = 0;

      document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
          duration: 800, 
          once: true,
          offset: 100
        });

        updateVisibleItems('all');

        // Filter button handlers
        const filterBtns = document.querySelectorAll('.filter-btn');
        const items = document.querySelectorAll('.filter-item');
        const emptyState = document.getElementById('filterEmptyState');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');
                let countVisible = 0;

                items.forEach(item => {
                    if (filter === 'all' || item.classList.contains(filter)) {
                        item.style.display = 'block';
                        countVisible++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (emptyState) {
                    emptyState.style.display = countVisible === 0 ? 'block' : 'none';
                }

                updateVisibleItems(filter);
            });
        });
      });

      function updateVisibleItems(filter) {
          visibleItems = [];
          const allItems = document.querySelectorAll('.gallery-item[data-src]');
          allItems.forEach(item => {
              if (filter === 'all' || item.classList.contains(filter)) {
                  visibleItems.push({
                      src: item.getAttribute('data-src'),
                      caption: item.getAttribute('data-caption') || '',
                      category: item.getAttribute('data-category') || '',
                      timestamp: item.getAttribute('data-timestamp') || ''
                  });
              }
          });
      }

      function resetToAllFilter() {
          const allBtn = document.querySelector('.filter-btn[data-filter="all"]');
          if (allBtn) allBtn.click();
      }

      // Lightbox Functions
      function openPublicLightboxByIndex(index) {
          const item = visibleItems[index];
          if (item) {
              currentLightboxIndex = index;
              renderLightboxContent(item.src, item.caption, item.category, item.timestamp);
          }
      }

      function openPublicLightbox(src, caption, category, timestamp) {
          renderLightboxContent(src, caption, category, timestamp);
      }

      function renderLightboxContent(src, caption, category, timestamp) {
          const lb = document.getElementById('publicLightbox');
          const img = document.getElementById('publicLightboxImg');
          const cap = document.getElementById('publicLightboxCaption');
          const cat = document.getElementById('publicLightboxCategory');
          const timeEl = document.getElementById('publicLightboxTimestamp');
          const counter = document.getElementById('publicLightboxCounter');

          if (lb && img) {
              img.src = src;
              cap.textContent = caption || '';
              cat.innerHTML = category ? '<i class="bi bi-folder-fill"></i> ' + category : '';
              if (timeEl) {
                  timeEl.innerHTML = timestamp ? '<i class="bi bi-clock-history"></i> ' + timestamp : '';
              }
              if (visibleItems.length > 0) {
                  counter.textContent = (currentLightboxIndex + 1) + ' / ' + visibleItems.length;
              } else {
                  counter.textContent = '';
              }
              lb.style.display = 'flex';
              document.body.style.overflow = 'hidden'; // prevent background scrolling
          }
      }

      function prevLightbox(e) {
          if (e) e.stopPropagation();
          if (visibleItems.length <= 1) return;
          currentLightboxIndex = (currentLightboxIndex - 1 + visibleItems.length) % visibleItems.length;
          const item = visibleItems[currentLightboxIndex];
          renderLightboxContent(item.src, item.caption, item.category, item.timestamp);
      }

      function nextLightbox(e) {
          if (e) e.stopPropagation();
          if (visibleItems.length <= 1) return;
          currentLightboxIndex = (currentLightboxIndex + 1) % visibleItems.length;
          const item = visibleItems[currentLightboxIndex];
          renderLightboxContent(item.src, item.caption, item.category, item.timestamp);
      }

      function closePublicLightbox() {
          const lb = document.getElementById('publicLightbox');
          if (lb) {
              lb.style.display = 'none';
              document.body.style.overflow = '';
          }
      }

      document.addEventListener('keydown', function(e) {
          const lb = document.getElementById('publicLightbox');
          if (lb && lb.style.display === 'flex') {
              if (e.key === 'Escape') closePublicLightbox();
              else if (e.key === 'ArrowLeft') prevLightbox();
              else if (e.key === 'ArrowRight') nextLightbox();
          }
      });
    </script>
</body>
</html>
