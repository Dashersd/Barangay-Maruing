<?php
function get_sk_public_photo_src($photo) {
    if (empty($photo) || strpos($photo, 'placeholder') !== false) {
        return '';
    }
    if (strpos($photo, 'http://') === 0 || strpos($photo, 'https://') === 0) {
        return $photo;
    }
    $trimmed = ltrim($photo, '/\\');
    if (strpos($trimmed, 'public/') === 0) {
        return $trimmed;
    }
    if (strpos($trimmed, 'admin/') === 0) {
        return $trimmed;
    }
    return 'admin/' . $trimmed;
}

// Default SK officials data
$all_sk_officials = [
    'chairperson' => [
        'name' => 'Hon. Ryan M. Tubos',
        'role' => 'SK Chairperson',
        'photo' => 'public/image/SK Officials/Ryan M. Tubos.jpeg',
        'message' => 'Presiding officer of the Sangguniang Kabataan, leading executive policies, community welfare programs, and youth development in Barangay Maruing.'
    ],
    'secretary' => [
        'name' => 'Hon. Rosemer G. Banagan',
        'role' => 'SK Secretary',
        'photo' => 'public/image/SK Officials/Rosmer G. Banagan.jpeg'
    ],
    'treasurer' => [
        'name' => 'Hon. Judith G. Tambagel',
        'role' => 'SK Treasurer',
        'photo' => 'public/image/SK Officials/Judith G. Tambagel.jpeg'
    ],
    'kagawad_1' => [
        'name' => 'Hon. Renzarr L. Roxas',
        'role' => 'SK Kagawad',
        'photo' => 'public/image/SK Officials/Renzarr L. Roxas.jpeg'
    ],
    'kagawad_2' => [
        'name' => 'Hon. Benie L. Ligao',
        'role' => 'SK Kagawad',
        'photo' => 'public/image/SK Officials/Benie L. Ligao.jpeg'
    ],
    'kagawad_3' => [
        'name' => 'Hon. Jhon Mark Charton D. Singue',
        'role' => 'SK Kagawad',
        'photo' => 'public/image/SK Officials/Jhon Mark Charton D. Singue.jpeg'
    ],
    'kagawad_4' => [
        'name' => 'Hon. Mark A. Alpay',
        'role' => 'SK Kagawad',
        'photo' => 'public/image/SK Officials/Mark A. Alpay.jpeg'
    ],
    'kagawad_5' => [
        'name' => 'Hon. Marlon B. Amplao',
        'role' => 'SK Kagawad',
        'photo' => 'public/image/SK Officials/Marlon B. Amplao.jpeg'
    ],
    'kagawad_6' => [
        'name' => 'Hon. Verdelyn G. Halilong',
        'role' => 'SK Kagawad',
        'photo' => 'public/image/SK Officials/Verdelyn G. Halilong.jpeg'
    ],
    'kagawad_7' => [
        'name' => 'Hon. Mc Felco T Bugao',
        'role' => 'SK Kagawad',
        'photo' => 'public/image/SK Officials/Mc Felco T. Bugao.jpeg'
    ]
];

// 1. Fetch from MySQL Database
@include_once __DIR__ . '/admin/db_connect.php';
if (isset($conn) && !$conn->connect_error) {
    $res = $conn->query("SELECT * FROM sk_officials");
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $k = !empty($row['official_key']) ? $row['official_key'] : '';
            if (empty($k)) {
                $pos_l = strtolower($row['position']);
                if (strpos($pos_l, 'chair') !== false) $k = 'chairperson';
                elseif (strpos($pos_l, 'secretary') !== false) $k = 'secretary';
                elseif (strpos($pos_l, 'treasurer') !== false) $k = 'treasurer';
            }
            if (!empty($k) && isset($all_sk_officials[$k])) {
                if (!empty($row['name']) && $row['name'] !== 'Name') {
                    $all_sk_officials[$k]['name'] = $row['name'];
                }
                if (!empty($row['position'])) {
                    $all_sk_officials[$k]['role'] = $row['position'];
                }
                if (!empty($row['photo'])) {
                    $all_sk_officials[$k]['photo'] = get_sk_public_photo_src($row['photo']);
                }
                if ($k === 'chairperson' && !empty($row['message'])) {
                    $all_sk_officials['chairperson']['message'] = $row['message'];
                }
            }
        }
    }
}

// 2. Fallback and merge with JSON cache
$sk_json_file = __DIR__ . '/admin/data/sk_officials.json';
if (file_exists($sk_json_file)) {
    $sk_json_data = json_decode(file_get_contents($sk_json_file), true);
    if (is_array($sk_json_data)) {
        foreach ($sk_json_data as $k => $item) {
            if (isset($all_sk_officials[$k])) {
                if (!empty($item['name']) && $item['name'] !== 'Name' && ($all_sk_officials[$k]['name'] === 'Hon. Ryan M. Tubos' || empty($all_sk_officials[$k]['name']) || $all_sk_officials[$k]['name'] === 'Name')) {
                    $all_sk_officials[$k]['name'] = $item['name'];
                }
                if (!empty($item['role'])) {
                    $all_sk_officials[$k]['role'] = $item['role'];
                }
                if (!empty($item['photo'])) {
                    $all_sk_officials[$k]['photo'] = get_sk_public_photo_src($item['photo']);
                }
                if ($k === 'chairperson' && !empty($item['message'])) {
                    $all_sk_officials['chairperson']['message'] = $item['message'];
                }
            }
        }
    }
}

// Helper to render public official card
function render_sk_official_card($official, $default_name, $default_role) {
    $name = (!empty($official['name']) && $official['name'] !== 'Name') ? $official['name'] : $default_name;
    $role = !empty($official['role']) ? $official['role'] : $default_role;
    $photo = !empty($official['photo']) ? $official['photo'] : '';
    ?>
    <div class="official-card">
        <div class="official-avatar">
            <?php if (!empty($photo)): ?>
                <img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($name) ?>" style="width: 100%; height: 100%; object-fit: cover;">
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
    <link rel="stylesheet" href="public/assets/css/SKofficial.css?v=2">
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

    <!-- Officials Hero Section -->
    <section class="officials-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead" data-aos="fade-down">Sangguniang Kabataan</span>
                    <span class="hero-title-main" data-aos="zoom-in" data-aos-delay="100">OFFICIALS</span>
                </h1>
                <p class="hero-lead" data-aos="fade-up" data-aos-delay="200">Meet the dedicated leaders and council members of Barangay Maruing, committed to honest governance and servant leadership.</p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="officials-main-section">
        <div class="officials-container">
            
            <!-- 1. Executive Leadership: SK Chairperson -->
            <section class="captain-section">
                <div class="category-header" data-aos="fade-down">
                    <h2 class="category-title">Executive Leadership</h2>
                    <p class="category-subtitle">Head of the Sangguniang Kabataan Officials</p>
                    <div class="category-accent-bar"></div>
                </div>

                <div class="captain-wrapper">
                    <div class="captain-card" data-aos="zoom-in">
                        <div class="captain-avatar-col">
                            <div class="captain-avatar">
                                <?php if (!empty($all_sk_officials['chairperson']['photo'])): ?>
                                    <img src="<?= htmlspecialchars($all_sk_officials['chairperson']['photo']) ?>" alt="<?= htmlspecialchars($all_sk_officials['chairperson']['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="bi bi-person" style="font-size: 5rem; color: #a0aec0;"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="captain-info-col">
                            <h3 class="captain-name"><?= htmlspecialchars($all_sk_officials['chairperson']['name']) ?></h3>
                            <div class="captain-role"><?= htmlspecialchars($all_sk_officials['chairperson']['role']) ?></div>
                            <p class="captain-desc"><?= nl2br(htmlspecialchars($all_sk_officials['chairperson']['message'] ?? 'Presiding officer of the Sangguniang Kabataan, leading executive policies, community welfare programs, and youth development in Barangay Maruing.')) ?></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. Sangguniang Kabataan Members -->
            <section class="councilors-section">
                <div class="category-header" data-aos="fade-down">
                    <h2 class="category-title">Sangguniang Kabataan Members</h2>
                    <p class="category-subtitle">SK Kagawads & Committee Chairpersons</p>
                    <div class="category-accent-bar"></div>
                </div>

                <div class="officials-pyramid">
                    <!-- Row 1: Secretary, Treasurer -->
                    <div class="pyramid-row" data-aos="fade-up">
                        <?php render_sk_official_card($all_sk_officials['secretary'], 'Hon. Rosemer G. Banagan', 'SK Secretary'); ?>
                        <?php render_sk_official_card($all_sk_officials['treasurer'], 'Hon. Judith G. Tambagel', 'SK Treasurer'); ?>
                    </div>

                    <!-- Row 2: Kagawads 1, 2, 3 -->
                    <div class="pyramid-row" data-aos="fade-up">
                        <?php render_sk_official_card($all_sk_officials['kagawad_1'], 'Hon. Renzarr L. Roxas', 'SK Kagawad'); ?>
                        <?php render_sk_official_card($all_sk_officials['kagawad_2'], 'Hon. Benie L. Ligao', 'SK Kagawad'); ?>
                        <?php render_sk_official_card($all_sk_officials['kagawad_3'], 'Hon. Jhon Mark Charton D. Singue', 'SK Kagawad'); ?>
                    </div>

                    <!-- Row 3: Kagawads 4, 5, 6, 7 -->
                    <div class="pyramid-row" data-aos="fade-up">
                        <?php render_sk_official_card($all_sk_officials['kagawad_4'], 'Hon. Mark A. Alpay', 'SK Kagawad'); ?>
                        <?php render_sk_official_card($all_sk_officials['kagawad_5'], 'Hon. Marlon B. Amplao', 'SK Kagawad'); ?>
                        <?php render_sk_official_card($all_sk_officials['kagawad_6'], 'Hon. Verdelyn G. Halilong', 'SK Kagawad'); ?>
                        <?php render_sk_official_card($all_sk_officials['kagawad_7'], 'Hon. Mc Felco T Bugao', 'SK Kagawad'); ?>
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
