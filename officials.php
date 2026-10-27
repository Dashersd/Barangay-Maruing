<?php
function get_official_photo_src($photo) {
    if (empty($photo) || strpos($photo, 'placeholder') !== false) {
        return '';
    }
    if (strpos($photo, 'http://') === 0 || strpos($photo, 'https://') === 0) {
        return $photo;
    }
    $trimmed = ltrim($photo, '/\\');
    if (strpos($trimmed, 'admin/') === 0) {
        return $trimmed;
    }
    return 'admin/' . $trimmed;
}

// Default officials matching the organization structure
$all_officials = [
    'captain' => [
        'name' => 'Punong Barangay',
        'role' => 'Punong Barangay',
        'photo' => '',
        'message' => 'Presiding officer of the Sangguniang Barangay, leading executive policies, community welfare programs, and peace and order in Barangay Maruing.'
    ],
    'secretary' => [
        'name' => 'Barangay Secretary',
        'role' => 'Barangay Secretary',
        'photo' => ''
    ],
    'treasurer' => [
        'name' => 'Barangay Treasurer',
        'role' => 'Barangay Treasurer',
        'photo' => ''
    ],
    'kagawad_1' => [
        'name' => 'Barangay Kagawad 1',
        'role' => 'Barangay Kagawad',
        'photo' => ''
    ],
    'kagawad_2' => [
        'name' => 'Barangay Kagawad 2',
        'role' => 'Barangay Kagawad',
        'photo' => ''
    ],
    'kagawad_3' => [
        'name' => 'Barangay Kagawad 3',
        'role' => 'Barangay Kagawad',
        'photo' => ''
    ],
    'kagawad_4' => [
        'name' => 'Barangay Kagawad 4',
        'role' => 'Barangay Kagawad',
        'photo' => ''
    ],
    'kagawad_5' => [
        'name' => 'Barangay Kagawad 5',
        'role' => 'Barangay Kagawad',
        'photo' => ''
    ],
    'kagawad_6' => [
        'name' => 'Barangay Kagawad 6',
        'role' => 'Barangay Kagawad',
        'photo' => ''
    ],
    'kagawad_7' => [
        'name' => 'Barangay Kagawad 7',
        'role' => 'Barangay Kagawad',
        'photo' => ''
    ]
];

// 1. Fetch from MySQL Database
@include_once __DIR__ . '/admin/db_connect.php';
$loaded_db = false;
if (isset($conn) && !$conn->connect_error) {
    $res = $conn->query("SELECT * FROM officials");
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $k = !empty($row['official_key']) ? $row['official_key'] : '';
            if (empty($k)) {
                $pos_l = strtolower($row['position']);
                if (strpos($pos_l, 'captain') !== false || strpos($pos_l, 'punong') !== false) $k = 'captain';
                elseif (strpos($pos_l, 'secretary') !== false) $k = 'secretary';
                elseif (strpos($pos_l, 'treasurer') !== false) $k = 'treasurer';
            }
            if (!empty($k) && isset($all_officials[$k])) {
                if (!empty($row['name']) && $row['name'] !== 'Name') {
                    $all_officials[$k]['name'] = $row['name'];
                }
                if (!empty($row['position'])) {
                    $all_officials[$k]['role'] = $row['position'];
                }
                if (!empty($row['photo'])) {
                    $all_officials[$k]['photo'] = get_official_photo_src($row['photo']);
                }
                if ($k === 'captain' && !empty($row['message'])) {
                    $all_officials['captain']['message'] = $row['message'];
                }
                $loaded_db = true;
            }
        }
    }
}

// 2. Fallback and merge with JSON cache
$data_file = __DIR__ . '/admin/data/barangay_officials.json';
if (file_exists($data_file)) {
    $officials_data = json_decode(file_get_contents($data_file), true);
    if (is_array($officials_data)) {
        foreach ($officials_data as $k => $item) {
            if (isset($all_officials[$k])) {
                $cur_name = $all_officials[$k]['name'];
                $is_default_name = ($cur_name === 'Name' || 
                    $cur_name === 'Punong Barangay' || 
                    $cur_name === 'Barangay Secretary' || 
                    $cur_name === 'Barangay Treasurer' || 
                    strpos($cur_name, 'Barangay Kagawad') === 0);
                
                if ($is_default_name && !empty($item['name']) && $item['name'] !== 'Name') {
                    $all_officials[$k]['name'] = $item['name'];
                }
                if (!empty($item['role']) && (empty($all_officials[$k]['role']) || $all_officials[$k]['role'] === 'BARANGAY KAGAWAD')) {
                    $all_officials[$k]['role'] = $item['role'];
                }
                if (empty($all_officials[$k]['photo']) && !empty($item['photo'])) {
                    $all_officials[$k]['photo'] = get_official_photo_src($item['photo']);
                }
                if ($k === 'captain' && !empty($item['message']) && empty($all_officials['captain']['message'])) {
                    $all_officials['captain']['message'] = $item['message'];
                }
            }
        }
    }
}

// Helper function to render each official card
function render_official_card($official, $default_name, $default_role) {
    $name = (!empty($official['name']) && $official['name'] !== 'Name') ? $official['name'] : $default_name;
    $role = !empty($official['role']) ? $official['role'] : $default_role;
    $photo = !empty($official['photo']) ? $official['photo'] : '';
    ?>
    <div class="official-card">
        <div class="official-avatar">
            <?php if (!empty($photo)): ?>
                <img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($name) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            <?php else: ?>
                <i class="bi bi-person"></i>
            <?php endif; ?>
        </div>
        <h3 class="official-name"><?= htmlspecialchars($name) ?></h3>
        <div class="official-role"><?= htmlspecialchars($role) ?></div>
    </div>
    <?php
}
?>
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
    <link rel="stylesheet" href="public/assets/css/style.css?v=4">
    <link rel="stylesheet" href="public/assets/css/officials.css?v=2">
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
                        <a class="nav-link" href="services.php">Services</a>
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

    <!-- Officials Hero Section -->
    <section class="officials-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead" data-aos="fade-down">Barangay</span>
                    <span class="hero-title-main" data-aos="zoom-in" data-aos-delay="100">OFFICIALS</span>
                </h1>
                <p class="hero-lead" data-aos="fade-up" data-aos-delay="200">Meet the dedicated leaders and council members of Barangay Maruing, committed to honest governance and servant leadership.</p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="officials-main-section">
        <div class="officials-container">
            
            <!-- 1. Executive Leadership: Punong Barangay -->
            <section class="captain-section">
                <div class="category-header" data-aos="fade-down">
                    <h2 class="category-title">Executive Leadership</h2>
                    <p class="category-subtitle">Head of the Barangay Government</p>
                    <div class="category-accent-bar"></div>
                </div>

                <div class="captain-wrapper">
                    <div class="captain-card" data-aos="zoom-in">
                        <div class="captain-avatar-col">
                            <div class="captain-avatar">
                                <?php if (!empty($all_officials['captain']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_officials['captain']['photo']) ?>" alt="<?= htmlspecialchars($all_officials['captain']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="bi bi-person-fill"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="captain-info-col">
                            <h3 class="captain-name"><?= htmlspecialchars($all_officials['captain']['name']) ?></h3>
                            <div class="captain-role"><?= htmlspecialchars($all_officials['captain']['role']) ?></div>
                            <p class="captain-desc"><?= nl2br(htmlspecialchars($all_officials['captain']['message'])) ?></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. Sangguniang Barangay (Councilors) -->
            <section class="councilors-section">
                <div class="category-header" data-aos="fade-down">
                    <h2 class="category-title">Sangguniang Barangay Members</h2>
                    <p class="category-subtitle">Barangay Kagawads & Committee Chairpersons</p>
                    <div class="category-accent-bar"></div>
                </div>

                <div class="officials-pyramid">
                    <!-- Row 1: Secretary, Treasurer -->
                    <div class="pyramid-row" data-aos="fade-up">
                        <!-- Barangay Secretary -->
                        <div class="official-card">
                            <div class="official-avatar">
                                <?php if (!empty($all_officials['secretary']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_officials['secretary']['photo']) ?>" alt="<?= htmlspecialchars($all_officials['secretary']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="bi bi-person"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="official-name"><?= htmlspecialchars((!empty($all_officials['secretary']['name']) && $all_officials['secretary']['name'] !== 'Name') ? $all_officials['secretary']['name'] : 'Barangay Secretary') ?></h3>
                            <div class="official-role"><?= htmlspecialchars($all_officials['secretary']['role'] ?? 'Barangay Secretary') ?></div>

                        </div>

                        <!-- Barangay Treasurer -->
                        <div class="official-card">
                            <div class="official-avatar">
                                <?php if (!empty($all_officials['treasurer']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_officials['treasurer']['photo']) ?>" alt="<?= htmlspecialchars($all_officials['treasurer']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="bi bi-person"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="official-name"><?= htmlspecialchars((!empty($all_officials['treasurer']['name']) && $all_officials['treasurer']['name'] !== 'Name') ? $all_officials['treasurer']['name'] : 'Barangay Treasurer') ?></h3>
                            <div class="official-role"><?= htmlspecialchars($all_officials['treasurer']['role'] ?? 'Barangay Treasurer') ?></div>

                        </div>
                    </div>

                    <!-- Row 2: Kagawads 1, 2, 3 -->
                    <div class="pyramid-row" data-aos="fade-up">
                        <!-- Kagawad 1 -->
                        <div class="official-card">
                            <div class="official-avatar">
                                <?php if (!empty($all_officials['kagawad_1']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_officials['kagawad_1']['photo']) ?>" alt="<?= htmlspecialchars($all_officials['kagawad_1']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="bi bi-person"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="official-name"><?= htmlspecialchars((!empty($all_officials['kagawad_1']['name']) && $all_officials['kagawad_1']['name'] !== 'Name') ? $all_officials['kagawad_1']['name'] : 'Barangay Kagawad 1') ?></h3>
                            <div class="official-role"><?= htmlspecialchars($all_officials['kagawad_1']['role'] ?? 'Barangay Kagawad') ?></div>

                        </div>

                        <!-- Kagawad 2 -->
                        <div class="official-card">
                            <div class="official-avatar">
                                <?php if (!empty($all_officials['kagawad_2']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_officials['kagawad_2']['photo']) ?>" alt="<?= htmlspecialchars($all_officials['kagawad_2']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="bi bi-person"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="official-name"><?= htmlspecialchars((!empty($all_officials['kagawad_2']['name']) && $all_officials['kagawad_2']['name'] !== 'Name') ? $all_officials['kagawad_2']['name'] : 'Barangay Kagawad 2') ?></h3>
                            <div class="official-role"><?= htmlspecialchars($all_officials['kagawad_2']['role'] ?? 'Barangay Kagawad') ?></div>

                        </div>

                        <!-- Kagawad 3 -->
                        <div class="official-card">
                            <div class="official-avatar">
                                <?php if (!empty($all_officials['kagawad_3']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_officials['kagawad_3']['photo']) ?>" alt="<?= htmlspecialchars($all_officials['kagawad_3']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="bi bi-person"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="official-name"><?= htmlspecialchars((!empty($all_officials['kagawad_3']['name']) && $all_officials['kagawad_3']['name'] !== 'Name') ? $all_officials['kagawad_3']['name'] : 'Barangay Kagawad 3') ?></h3>
                            <div class="official-role"><?= htmlspecialchars($all_officials['kagawad_3']['role'] ?? 'Barangay Kagawad') ?></div>

                        </div>
                    </div>

                    <!-- Row 3: Kagawads 4, 5, 6, 7 -->
                    <div class="pyramid-row" data-aos="fade-up">
                        <!-- Kagawad 4 -->
                        <div class="official-card">
                            <div class="official-avatar">
                                <?php if (!empty($all_officials['kagawad_4']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_officials['kagawad_4']['photo']) ?>" alt="<?= htmlspecialchars($all_officials['kagawad_4']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="bi bi-person"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="official-name"><?= htmlspecialchars((!empty($all_officials['kagawad_4']['name']) && $all_officials['kagawad_4']['name'] !== 'Name') ? $all_officials['kagawad_4']['name'] : 'Barangay Kagawad 4') ?></h3>
                            <div class="official-role"><?= htmlspecialchars($all_officials['kagawad_4']['role'] ?? 'Barangay Kagawad') ?></div>

                        </div>

                        <!-- Kagawad 5 -->
                        <div class="official-card">
                            <div class="official-avatar">
                                <?php if (!empty($all_officials['kagawad_5']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_officials['kagawad_5']['photo']) ?>" alt="<?= htmlspecialchars($all_officials['kagawad_5']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="bi bi-person"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="official-name"><?= htmlspecialchars((!empty($all_officials['kagawad_5']['name']) && $all_officials['kagawad_5']['name'] !== 'Name') ? $all_officials['kagawad_5']['name'] : 'Barangay Kagawad 5') ?></h3>
                            <div class="official-role"><?= htmlspecialchars($all_officials['kagawad_5']['role'] ?? 'Barangay Kagawad') ?></div>

                        </div>

                        <!-- Kagawad 6 -->
                        <div class="official-card">
                            <div class="official-avatar">
                                <?php if (!empty($all_officials['kagawad_6']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_officials['kagawad_6']['photo']) ?>" alt="<?= htmlspecialchars($all_officials['kagawad_6']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="bi bi-person"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="official-name"><?= htmlspecialchars((!empty($all_officials['kagawad_6']['name']) && $all_officials['kagawad_6']['name'] !== 'Name') ? $all_officials['kagawad_6']['name'] : 'Barangay Kagawad 6') ?></h3>
                            <div class="official-role"><?= htmlspecialchars($all_officials['kagawad_6']['role'] ?? 'Barangay Kagawad') ?></div>

                        </div>

                        <!-- Kagawad 7 -->
                        <div class="official-card">
                            <div class="official-avatar">
                                <?php if (!empty($all_officials['kagawad_7']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_officials['kagawad_7']['photo']) ?>" alt="<?= htmlspecialchars($all_officials['kagawad_7']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="bi bi-person"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="official-name"><?= htmlspecialchars((!empty($all_officials['kagawad_7']['name']) && $all_officials['kagawad_7']['name'] !== 'Name') ? $all_officials['kagawad_7']['name'] : 'Barangay Kagawad 7') ?></h3>
                            <div class="official-role"><?= htmlspecialchars($all_officials['kagawad_7']['role'] ?? 'Barangay Kagawad') ?></div>

                        </div>
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
      });
    </script>
</body>
</html>


