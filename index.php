<?php
session_start();
// Reset chat after 1 day (86400 seconds)
if (isset($_SESSION['chat_start_time']) && (time() - $_SESSION['chat_start_time'] > 86400)) {
    unset($_SESSION['chat_thread_id']);
    unset($_SESSION['chat_user_name']);
    unset($_SESSION['chat_start_time']);
}

if (!isset($_SESSION['chat_thread_id'])) {
    $_SESSION['chat_thread_id'] = uniqid('thread_');
    $_SESSION['chat_start_time'] = time();
}
if (!isset($_SESSION['chat_user_name'])) {
    $_SESSION['chat_user_name'] = '';
}

require_once 'admin/db_connect.php';

// Fetch latest gallery media items for the homepage showcase
$home_gallery_items = [];
$gallery_sql = "SELECT m.*, f.name as folder_name 
                FROM gallery_media m 
                JOIN gallery_folders f ON m.folder_id = f.id 
                ORDER BY m.id DESC 
                LIMIT 6";
$gallery_res = $conn->query($gallery_sql);
if ($gallery_res) {
    while ($row = $gallery_res->fetch_assoc()) {
        if (file_exists('admin/' . $row['file_path'])) {
            $home_gallery_items[] = $row;
        }
    }
}

// Format date badge for announcements
if (!function_exists('format_announcement_badge')) {
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
}

// Fetch latest published announcements for the homepage showcase
$home_announcements = [];
if (isset($conn) && !$conn->connect_error) {
    $ann_res = $conn->query("SELECT * FROM announcements WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    if ($ann_res) {
        while ($row = $ann_res->fetch_assoc()) {
            $home_announcements[] = $row;
        }
    }
}

// Fetch Barangay Captain details for the Meet Our Leaders section
$captain_name = 'Hon. Punong Barangay';
$captain_role = 'Barangay Captain';
$captain_photo = '';
$captain_desc = 'To our residents, partners, and every member of our community, thank you for your trust, cooperation, and unwavering support. At Barangay Maruing, we remain committed to delivering honest and transparent public service that promotes safety, unity, and progress for all. Together, let us continue building a barangay where every family feels heard, respected, and supported.';

function resolve_leader_photo($photo) {
    if (empty($photo) || strpos($photo, 'placeholder') !== false) return '';
    if (strpos($photo, 'http://') === 0 || strpos($photo, 'https://') === 0) return $photo;
    $trimmed = ltrim($photo, '/\\');
    if (strpos($trimmed, 'public/') === 0) return $trimmed;
    if (strpos($trimmed, 'admin/') === 0) return $trimmed;
    return 'admin/' . $trimmed;
}

$captain_found = false;
if (isset($conn) && !$conn->connect_error) {
    $c_stmt = $conn->query("SELECT * FROM officials WHERE official_key = 'captain' OR position LIKE '%Captain%' OR position LIKE '%Punong Barangay%' LIMIT 1");
    if ($c_stmt && $c_stmt->num_rows > 0) {
        $c_row = $c_stmt->fetch_assoc();
        if (!empty($c_row['name']) && $c_row['name'] !== 'Name') {
            $captain_name = $c_row['name'];
        }
        if (!empty($c_row['position'])) {
            $captain_role = $c_row['position'];
        }
        if (!empty($c_row['photo'])) {
            $captain_photo = resolve_leader_photo($c_row['photo']);
        }
        if (!empty($c_row['message'])) {
            $captain_desc = $c_row['message'];
        }
        $captain_found = true;
    }
}

if (!$captain_found || empty($captain_photo)) {
    $json_officials_file = __DIR__ . '/admin/data/barangay_officials.json';
    if (file_exists($json_officials_file)) {
        $j_data = json_decode(file_get_contents($json_officials_file), true);
        $j_cap = $j_data['captain'] ?? [];
        if (!$captain_found) {
            if (!empty($j_cap['name']) && $j_cap['name'] !== 'Name') {
                $captain_name = $j_cap['name'];
            }
            if (!empty($j_cap['role'])) {
                $captain_role = $j_cap['role'];
            }
            if (!empty($j_cap['message'])) {
                $captain_desc = $j_cap['message'];
            }
        }
        if (empty($captain_photo) && !empty($j_cap['photo'])) {
            $captain_photo = resolve_leader_photo($j_cap['photo']);
        }
    }
}

// Fetch SK Chairman details for the Meet Our Leaders section
$sk_chair_name = 'Hon. Ryan M. Tubos';
$sk_chair_role = 'Sangguniang Kabataan Chairman';
$sk_chair_photo = 'public/image/SK Officials/Ryan M. Tubos.jpeg';
$sk_chair_desc = 'Empowering the youth of Barangay Maruing through active participation in sports, education, and community development.';

$sk_chair_found = false;
if (isset($conn) && !$conn->connect_error) {
    $sk_stmt = $conn->query("SELECT * FROM sk_officials WHERE official_key = 'chairperson' OR position LIKE '%Chairman%' OR position LIKE '%Chairperson%' LIMIT 1");
    if ($sk_stmt && $sk_stmt->num_rows > 0) {
        $sk_row = $sk_stmt->fetch_assoc();
        if (!empty($sk_row['name']) && $sk_row['name'] !== 'Name') {
            $sk_chair_name = $sk_row['name'];
        }
        if (!empty($sk_row['position'])) {
            $sk_chair_role = $sk_row['position'];
        }
        if (!empty($sk_row['photo'])) {
            $sk_chair_photo = resolve_leader_photo($sk_row['photo']);
        }
        if (!empty($sk_row['message'])) {
            $sk_chair_desc = $sk_row['message'];
        }
        $sk_chair_found = true;
    }
}

if (!$sk_chair_found || empty($sk_chair_photo)) {
    $json_sk_file = __DIR__ . '/admin/data/sk_officials.json';
    if (file_exists($json_sk_file)) {
        $j_sk_data = json_decode(file_get_contents($json_sk_file), true);
        $j_sk_chair = $j_sk_data['chairperson'] ?? [];
        if (!$sk_chair_found) {
            if (!empty($j_sk_chair['name']) && $j_sk_chair['name'] !== 'Name') {
                $sk_chair_name = $j_sk_chair['name'];
            }
            if (!empty($j_sk_chair['role'])) {
                $sk_chair_role = $j_sk_chair['role'];
            }
            if (!empty($j_sk_chair['message'])) {
                $sk_chair_desc = $j_sk_chair['message'];
            }
        }
        if (empty($sk_chair_photo) && !empty($j_sk_chair['photo'])) {
            $sk_chair_photo = resolve_leader_photo($j_sk_chair['photo']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Official Web-Based Information System for Barangay Maruing. Access announcements, services, barangay officials, and community updates online.">
    <title>Home | Barangay Maruing Information System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS (Pure CSS Responsive) -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=6">
    <link rel="stylesheet" href="public/assets/css/gallery.css?v=6">
    <link rel="stylesheet" href="public/assets/css/announcements.css?v=2">
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
                        <a class="nav-link active" href="index.php">Home</a>
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

    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container hero-container">
                <div class="hero-content">
                    <h1 class="hero-title" data-aos="fade-up">
                        <span class="hero-script-lead">Welcome to</span>
                        <span class="hero-title-main">BARANGAY MARUING</span>
                    </h1>
                    <p class="hero-lead" data-aos="fade-up" data-aos-delay="100">Your trusted source for barangay governance, community information, and public service.</p>
                    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="200">
                        <a href="about.php" class="btn-hero-primary">Explore Information <i
                                class="bi bi-arrow-right"></i></a>
                        <a href="officials.php" class="btn-hero-outline">Meet Officials <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="landing-officials-section" id="officials-section" style="padding: 5rem 0; background-color: var(--background-color, #f8faf9);">
            <div class="container">
                <div class="section-header" style="text-align: center; margin-bottom: 4rem;" data-aos="fade-up">
                    <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary-color, #137547); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.8rem;">Barangay Officials</span>
                    <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--primary-dark, #093d25); margin-bottom: 1rem;">Meet Our <span style="color: var(--primary-color, #137547);">Leaders</span></h2>
                    <div style="width: 50px; height: 3px; background-color: var(--primary-color, #137547); margin: 0 auto 1.5rem;"></div>
                    <p style="font-size: 1.1rem; color: var(--muted-text-color, #6b7280);">The dedicated officials working to serve the people of Barangay Maruing.</p>
                </div>
                
                <div class="officials-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; max-width: 900px; margin: 0 auto;">
                    
                    <!-- Official Card 1 -->
                    <div class="official-card" data-aos="fade-up" data-aos-delay="100" style="background: white; border-radius: 20px; padding: 3rem 2rem; box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0,0,0,0.05)); border: 1px solid var(--border-color, #e5e7eb); text-align: center; transition: transform 0.3s ease;">
                        <div style="position: relative; width: 140px; height: 140px; margin: 0 auto 2.5rem; background-color: var(--primary-light, #e8f5ee); border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 8px rgba(19, 117, 71, 0.05);">
                            <?php if (!empty($captain_photo)): ?>
                                <img src="<?= htmlspecialchars($captain_photo) ?>" alt="<?= htmlspecialchars($captain_name) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                            <?php else: ?>
                                <span style="font-size: 3.5rem; font-weight: 300; color: var(--primary-dark, #093d25); font-family: sans-serif;">PB</span>
                            <?php endif; ?>
                            <div style="position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); background-color: var(--primary-color, #137547); color: white; padding: 0.5rem 1.2rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; white-space: nowrap; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                Punong Barangay
                            </div>
                        </div>
                        <h4 style="color: var(--primary-dark, #093d25); margin-bottom: 0.5rem; font-weight: 800; font-size: 1.3rem;"><?= htmlspecialchars($captain_name) ?></h4>
                        <p style="color: var(--primary-color, #137547); font-size: 0.95rem; font-weight: 600; margin-bottom: 1.5rem;"><?= htmlspecialchars($captain_role) ?></p>
                        <p style="color: var(--muted-text-color, #6b7280); font-size: 0.95rem; font-style: italic; line-height: 1.6;">"<?= htmlspecialchars($captain_desc) ?>"</p>
                        <a href="officials.php" class="btn-officials-outline" style="display: inline-block; margin-top: 1.5rem; padding: 0.6rem 1.5rem; background-color: white; border: 2px solid var(--primary-color, #137547); color: var(--primary-color, #137547) !important; border-radius: 8px; font-weight: 700; text-decoration: none; transition: all 0.3s ease;">
                            View Barangay Officials <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Official Card 2 -->
                    <div class="official-card" data-aos="fade-up" data-aos-delay="200" style="background: white; border-radius: 20px; padding: 3rem 2rem; box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0,0,0,0.05)); border: 1px solid var(--border-color, #e5e7eb); text-align: center; transition: transform 0.3s ease;">
                        <div style="position: relative; width: 140px; height: 140px; margin: 0 auto 2.5rem; background-color: rgba(255, 193, 7, 0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 8px rgba(255, 193, 7, 0.05);">
                            <?php if (!empty($sk_chair_photo)): ?>
                                <img src="<?= htmlspecialchars($sk_chair_photo) ?>" alt="<?= htmlspecialchars($sk_chair_name) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                            <?php else: ?>
                                <span style="font-size: 3.5rem; font-weight: 300; color: var(--primary-dark, #093d25); font-family: sans-serif;">SK</span>
                            <?php endif; ?>
                            <div style="position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); background-color: var(--primary-color, #137547); color: white; padding: 0.5rem 1.2rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; white-space: nowrap; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                SK Chairman
                            </div>
                        </div>
                        <h4 style="color: var(--primary-dark, #093d25); margin-bottom: 0.5rem; font-weight: 800; font-size: 1.3rem;"><?= htmlspecialchars($sk_chair_name) ?></h4>
                        <p style="color: var(--primary-color, #137547); font-size: 0.95rem; font-weight: 600; margin-bottom: 1.5rem;"><?= htmlspecialchars($sk_chair_role) ?></p>
                        <p style="color: var(--muted-text-color, #6b7280); font-size: 0.95rem; font-style: italic; line-height: 1.6;">"<?= htmlspecialchars($sk_chair_desc) ?>"</p>
                        <a href="SKofficial.php" class="btn-officials-outline" style="display: inline-block; margin-top: 1.5rem; padding: 0.6rem 1.5rem; background-color: white; border: 2px solid var(--primary-color, #137547); color: var(--primary-color, #137547) !important; border-radius: 8px; font-weight: 700; text-decoration: none; transition: all 0.3s ease;">
                            View SK Officials <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>


            </div>
        </section>

        <!-- About Section -->
        <section class="about-landing-section">
            <div class="container about-landing-container">
                <div class="about-landing-image" data-aos="fade-right">
                    <img src="public/image/Barangay Maruing Community Hall.png" alt="Barangay Maruing History">
                </div>
                <div class="about-landing-content" data-aos="fade-left">
                    <h2 class="about-landing-title">About Barangay Maruing</h2>
                    <p class="about-landing-desc">
                        Discover the rich history and vibrant community of Barangay Maruing. Our barangay is dedicated to providing honest and dedicated service to all its residents, fostering growth, unity, and a better quality of life for everyone. 
                    </p>
                    <a href="about.php#about-us-section" class="btn-learn-more">
                        Learn more <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
        <!-- Services Section -->
        <section id="services-section" class="services-section" style="padding: 5rem 0;">
            <div class="container">
                <div class="section-header" style="text-align: center; margin-bottom: 4rem;" data-aos="fade-up">
                    <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary-color, #10b981); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.8rem;">What We Offer</span>
                    <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--primary-dark, #1e293b); margin-bottom: 1rem;">Barangay <span style="color: var(--primary-color, #10b981);">Services</span></h2>
                    <div style="width: 50px; height: 3px; background-color: var(--primary-color, #10b981); margin: 0 auto 1.5rem;"></div>
                    <p style="font-size: 1.1rem; color: var(--muted-text-color, #64748b);">Accessible and efficient services dedicated to the welfare of our residents.</p>
                </div>
                <div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2rem;">
                    
                    <!-- Card 1 -->
                    <div class="service-card" data-aos="zoom-in" data-aos-delay="100" style="background: white; border-radius: 20px; padding: 2.5rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                        <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i class="bi bi-file-earmark-text" style="font-size: 1.8rem; color: var(--primary-color, #10b981);"></i>
                        </div>
                        <h4 style="color: var(--primary-dark, #1e293b); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">Document Issuance</h4>
                        <p style="color: var(--muted-text-color, #64748b); font-size: 0.95rem; line-height: 1.6;">Get your Barangay Clearance, Certificate of Indigency, and other important documents with ease.</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="service-card" data-aos="zoom-in" data-aos-delay="100" style="background: white; border-radius: 20px; padding: 2.5rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                        <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i class="bi bi-activity" style="font-size: 2rem; color: var(--primary-color, #10b981);"></i>
                        </div>
                        <h4 style="color: var(--primary-dark, #1e293b); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">Health Center</h4>
                        <p style="color: var(--muted-text-color, #64748b); font-size: 0.95rem; line-height: 1.6;">Free consultations, vaccinations, and maternal care services for every Maruing resident.</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="service-card" data-aos="zoom-in" data-aos-delay="100" style="background: white; border-radius: 20px; padding: 2.5rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                        <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i class="bi bi-shield-check" style="font-size: 1.8rem; color: var(--primary-color, #10b981);"></i>
                        </div>
                        <h4 style="color: var(--primary-dark, #1e293b); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">Peace & Order</h4>
                        <p style="color: var(--muted-text-color, #64748b); font-size: 0.95rem; line-height: 1.6;">File a blotter or request assistance from our active Barangay Tanods who patrol 24/7.</p>
                    </div>

                    <!-- Card 4 -->
                    <div class="service-card" data-aos="zoom-in" data-aos-delay="100" style="background: white; border-radius: 20px; padding: 2.5rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                        <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i class="bi bi-people" style="font-size: 1.8rem; color: var(--primary-color, #10b981);"></i>
                        </div>
                        <h4 style="color: var(--primary-dark, #1e293b); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">Community Programs</h4>
                        <p style="color: var(--muted-text-color, #64748b); font-size: 0.95rem; line-height: 1.6;">Participate in livelihood seminars, youth sports leagues, and regular clean-up drives.</p>
                    </div>

                </div>
                <div style="text-align: center; margin-top: 3rem;">
                    <a href="services.php" class="btn-learn-more">View All Services <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </section>

        <!-- Announcements Section -->
        <section id="announcements-section" class="announcements-section" style="padding: 5rem 0; background-color: var(--background-color, #f8faf9);">
            <div class="container">
                <div class="section-header" style="text-align: center; margin-bottom: 4rem;" data-aos="fade-up">
                    <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary-color, #137547); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.8rem;">Stay Updated</span>
                    <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--primary-dark, #093d25); margin-bottom: 1rem;">Latest <span style="color: var(--primary-color, #137547);">Announcements</span></h2>
                    <div style="width: 50px; height: 3px; background-color: var(--primary-color, #137547); margin: 0 auto 1.5rem;"></div>
                    <p style="font-size: 1.1rem; color: var(--muted-text-color, #6b7280);">Keep track of upcoming events, meetings, and important notices in our barangay.</p>
                </div>
                <div class="announcements-grid <?= (count($home_announcements) === 1) ? 'single-announcement' : '' ?>">
                    <?php if (empty($home_announcements)): ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: white; border-radius: 16px; border: 1px dashed #cbd5e1; width: 100%; max-width: 600px; margin: 0 auto;">
                            <i class="bi bi-megaphone" style="font-size: 3rem; color: #94a3b8; display: block; margin-bottom: 15px;"></i>
                            <h3 style="color: #334155; margin-bottom: 8px;">No Announcements Yet</h3>
                            <p style="color: #64748b; margin: 0;">Stay tuned! New community updates and notices will be posted here soon.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($home_announcements as $idx => $ann): ?>
                            <div class="announcement-card-v2" data-aos="fade-up" data-aos-delay="<?= ($idx % 3) * 100 ?>">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.2rem;">
                                    <div class="announcement-date-badge" style="margin-bottom: 0;">
                                        <?= htmlspecialchars(format_announcement_badge($ann['when_date'], $ann['created_at'])) ?>
                                    </div>
                                    <span style="font-size: 0.8rem; color: #94a3b8;"><i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($ann['created_at'])) ?></span>
                                </div>

                                <?php if (!empty($ann['cover_image']) && file_exists('admin/' . $ann['cover_image'])): ?>
                                    <div class="announcement-image-wrapper" onclick="openAnnouncementLightbox('admin/<?= htmlspecialchars($ann['cover_image'], ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($ann['title']), ENT_QUOTES) ?>')" title="Click to view full image">
                                        <img class="announcement-img-backdrop" src="admin/<?= htmlspecialchars($ann['cover_image']) ?>" alt="" aria-hidden="true">
                                        <img class="announcement-img-main" src="admin/<?= htmlspecialchars($ann['cover_image']) ?>" alt="<?= htmlspecialchars($ann['title']) ?>">
                                        <span class="announcement-img-badge"><i class="bi bi-arrows-fullscreen"></i> View Full Image</span>
                                    </div>
                                <?php endif; ?>

                                <h3 class="announcement-card-title"><?= htmlspecialchars($ann['title']) ?></h3>
                                
                                <div class="announcement-details">
                                    <?php if (!empty($ann['what'])): ?>
                                        <p><strong>What:</strong> <?= htmlspecialchars($ann['what']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($ann['when_date'])): ?>
                                        <p><strong>When:</strong> <?= htmlspecialchars($ann['when_date']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($ann['where_place'])): ?>
                                        <p><strong>Where:</strong> <?= htmlspecialchars($ann['where_place']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($ann['who'])): ?>
                                        <p><strong>Who:</strong> <?= htmlspecialchars($ann['who']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($ann['why'])): ?>
                                        <p><strong>Why:</strong> <?= htmlspecialchars($ann['why']) ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($ann['content'])): ?>
                                    <div class="announcement-divider"></div>
                                    <div class="announcement-description">
                                        <p><?= nl2br(htmlspecialchars($ann['content'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div style="text-align: center; margin-top: 3rem;">
                    <a href="announcements.php" class="btn-learn-more">View All Announcements <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </section>

        <!-- Spot Map Landing Section -->
        <section class="landing-spot-map-section">
            <div class="container">
                <div class="landing-spot-map-header" style="text-align: center; margin-bottom: 3rem;">
                    <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 1rem; text-align: center;">Spot Map</h2>
                    <p style="font-size: 1.1rem; color: var(--muted-text-color); text-align: center; margin-left: auto; margin-right: auto;">Geographical overview and key locations in Barangay Maruing</p>
                </div>
                
                <div class="landing-spot-map-container">
                <div class="spot-map-content" data-aos="fade-right">
                    <div class="spot-map-badge">
                        <i class="bi bi-geo-alt-fill"></i> EXPLORE
                    </div>
                    <h2 class="spot-map-title">Barangay Spot Map</h2>
                    <p class="spot-map-desc">
                        Navigate our community with ease. View important landmarks, territorial boundaries, purok zones, and key public facilities throughout Barangay Maruing.
                    </p>
                    <a href="spot-map.php" class="btn-view-map">
                        View Full Map <i class="bi bi-map-fill"></i>
                    </a>

                </div>
                <div class="spot-map-visual" data-aos="fade-left">
                    <!-- <iframe 
                        src="https://maps.google.com/maps?q=Barangay%20Maruing,%20Philippines&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe> -->
                    <img src="public/image/Maruing Map/download.png" alt="Spot Map of Barangay Maruing" style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                </div>
            </div>
            </div>
        </section>

    </main>

    <!-- Gallery Section -->

    <section class="gallery-section-wrapper">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 4rem;" data-aos="fade-up">
                <h2 style="font-size: 2.8rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem; font-family: 'Montserrat', sans-serif;">
                    Our <span style="color: var(--primary-color); position: relative;">Gallery<span style="position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 70%; height: 4px; background-color: var(--primary-color); border-radius: 2px;"></span></span>
                </h2>
                <p style="font-size: 1.1rem; color: #64748b; max-width: 800px; margin: 2.5rem auto 0; line-height: 1.6; font-family: 'Montserrat', sans-serif;">
                    Take a look at the latest events, activities, and programs organized for the residents<br>of Barangay Maruing.
                </p>
            </div>
            
            <div class="gallery-grid" id="homeGalleryGrid">
                <?php if (!empty($home_gallery_items)): ?>
                    <?php foreach ($home_gallery_items as $index => $item): 
                        $formatted_date = date('M d, Y', strtotime($item['created_at']));
                        $formatted_time = date('h:i A', strtotime($item['created_at']));
                        $full_timestamp = $formatted_date . ' • ' . $formatted_time;
                    ?>
                        <div class="gallery-item" 
                             data-aos="fade-up" 
                             data-aos-delay="<?= ($index % 6) * 100 ?>" 
                             data-src="admin/<?= htmlspecialchars($item['file_path']) ?>" 
                             data-caption="<?= htmlspecialchars($item['caption'] ?? $item['folder_name']) ?>" 
                             data-category="<?= htmlspecialchars($item['folder_name']) ?>"
                             data-timestamp="<?= htmlspecialchars($full_timestamp) ?>"
                             onclick="openHomeLightboxByIndex(<?= $index ?>)">
                            <img src="admin/<?= htmlspecialchars($item['file_path']) ?>" alt="<?= htmlspecialchars($item['caption'] ?? 'Barangay Maruing Gallery') ?>" class="gallery-img" loading="lazy">
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
                    <div class="gallery-item" 
                         data-aos="fade-up" 
                         data-aos-delay="0" 
                         data-src="public/image/Maruing.png" 
                         data-caption="Barangay Hall" 
                         data-category="Barangay Center"
                         data-timestamp="Oct 13, 2026 • 10:00 AM"
                         onclick="openHomeLightbox('public/image/Maruing.png', 'Barangay Hall', 'Barangay Center', 'Oct 13, 2026 • 10:00 AM')">
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
                    <div class="gallery-item" 
                         data-aos="fade-up" 
                         data-aos-delay="100" 
                         data-src="public/image/Maruing Logo 2.png" 
                         data-caption="Official Logo" 
                         data-category="Official"
                         data-timestamp="Oct 13, 2026 • 10:00 AM"
                         onclick="openHomeLightbox('public/image/Maruing Logo 2.png', 'Official Logo', 'Official', 'Oct 13, 2026 • 10:00 AM')">
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
                <?php endif; ?>
            </div>
            
            <!-- View Galleries Button -->
            <div style="text-align: center; margin-top: 3.5rem;">
                <a href="gallery.php" class="btn-learn-more">View Galleries <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->

    <section class="contact-form-section">
        <div class="container">
            <div class="contact-form-wrapper">
                
                <!-- Left Column: Contact Info -->
                <div class="contact-info-col" data-aos="fade-right">
                    <h2>Get in touch</h2>
                    <p style="color: #64748b; margin-bottom: 2.5rem; line-height: 1.6;">Reach out to our barangay office for public inquiries, government assistance, and community concerns.</p>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Head Office</h4>
                            <p>Barangay Maruing Hall<br>Maruing, Municipality</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Email Us</h4>
                            <p>support@maruing.gov.ph<br>info@maruing.gov.ph</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item" style="margin-bottom: 2.5rem;">
                        <div class="contact-info-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Call Us</h4>
                            <p>Phone: 09XX-XXX-XXXX<br>Tanod: 09XX-XXX-XXXX</p>
                        </div>
                    </div>
    
                </div>
    
                <!-- Right Column: Chat Interface -->
                <div class="contact-form-col" data-aos="fade-left" id="chat-widget-container">
                    <?php $has_chat = !empty($_SESSION['chat_user_name']); ?>
                    <div id="start-chat-container" style="<?php echo $has_chat ? 'display:none;' : ''; ?>">
                        <h2>Start a Live Chat</h2>
                        <form id="start-chat-form">
                            <div class="form-group">
                                <label>Your Name</label>
                                <input type="text" id="chat-name" class="form-control" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group">
                                <label>Service Type</label>
                                <select id="chat-service-type" class="form-control" required>
                                    <option value="" disabled selected>Select service</option>
                                    <option value="Barangay Clearance">Barangay Clearance</option>
                                    <option value="Certificate of Indigency">Certificate of Indigency</option>
                                    <option value="Business Clearance">Business Clearance</option>
                                    <option value="Certificate of Residency">Certificate of Residency</option>
                                    <option value="Other Inquiry">Other Inquiry</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Initial Message</label>
                                <textarea id="chat-initial-message" class="form-control" placeholder="How can we help you?" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn-submit">Start Chat</button>
                        </form>
                    </div>

                    <div id="live-chat-container" style="<?php echo $has_chat ? '' : 'display:none;'; ?>">
                        <h2>Live Chat Support</h2>
                        <div id="live-chat-box" style="border: 1px solid #e2e8f0; border-radius: 10px; height: 400px; display: flex; flex-direction: column; background: #fff;">
                            <div id="chat-messages" style="flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; background: #f8faf9;">
                                <!-- Messages will be loaded here via AJAX -->
                            </div>
                            <div style="padding: 15px; border-top: 1px solid #e2e8f0; display: flex; gap: 10px;">
                                <input type="text" id="chat-reply-input" class="form-control" placeholder="Type a message..." style="margin-bottom:0;">
                                <button id="chat-reply-btn" class="btn-submit" style="width: auto; padding: 0.8rem 1.5rem; margin-top:0;">Send</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                const threadId = "<?php echo $_SESSION['chat_thread_id']; ?>";
                let currentUserName = "<?php echo $_SESSION['chat_user_name'] ?? ''; ?>";

                document.addEventListener('DOMContentLoaded', () => {
                    const startForm = document.getElementById('start-chat-form');
                    
                    function bindChatEvents(name) {
                        currentUserName = name;
                        fetchMessages();
                        setInterval(fetchMessages, 3000);

                        const replyBtn = document.getElementById('chat-reply-btn');
                        const replyInput = document.getElementById('chat-reply-input');
                        if (replyBtn && replyInput) {
                            replyBtn.addEventListener('click', () => {
                                if (replyInput.value.trim() !== '') {
                                    sendChatMessage(currentUserName, replyInput.value.trim(), () => {
                                        replyInput.value = '';
                                        fetchMessages();
                                    });
                                }
                            });
                            
                            replyInput.addEventListener('keypress', function(e) {
                                if (e.key === 'Enter') {
                                    e.preventDefault();
                                    replyBtn.click();
                                }
                            });
                        }
                    }

                    if (startForm) {
                        startForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const name = document.getElementById('chat-name').value;
                            const serviceTypeSelect = document.getElementById('chat-service-type');
                            const serviceType = serviceTypeSelect.options[serviceTypeSelect.selectedIndex].text;
                            const rawMessage = document.getElementById('chat-initial-message').value;
                            const message = `[Service: ${serviceType}]\n\n${rawMessage}`;

                            fetch('set_chat_session.php', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                                body: 'name=' + encodeURIComponent(name)
                            }).then(() => {
                                sendChatMessage(name, message, () => {
                                    document.getElementById('start-chat-container').style.display = 'none';
                                    document.getElementById('live-chat-container').style.display = 'block';
                                    bindChatEvents(name);
                                });
                            });
                        });
                    }

                    if (currentUserName && document.getElementById('live-chat-container').style.display !== 'none') {
                        bindChatEvents(currentUserName);
                    }
                });

                function sendChatMessage(senderName, message, callback) {
                    const formData = new URLSearchParams();
                    formData.append('thread_id', threadId);
                    formData.append('sender_name', senderName);
                    formData.append('sender_type', 'user');
                    formData.append('message', message);

                    fetch('api/chat_send.php', {
                        method: 'POST',
                        body: formData
                    }).then(r => r.json()).then(res => {
                        if (res.status === 'success' && callback) callback();
                    });
                }

                function fetchMessages() {
                    fetch(`api/chat_fetch.php?thread_id=${threadId}&reader_type=user`)
                        .then(r => r.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const container = document.getElementById('chat-messages');
                                container.innerHTML = '';
                                data.messages.forEach(msg => {
                                    const isUser = msg.sender_type === 'user';
                                    const msgDiv = document.createElement('div');
                                    msgDiv.style.maxWidth = '75%';
                                    msgDiv.style.padding = '10px 15px';
                                    msgDiv.style.borderRadius = '10px';
                                    msgDiv.style.fontSize = '0.9rem';
                                    
                                    if (isUser) {
                                        msgDiv.style.alignSelf = 'flex-end';
                                        msgDiv.style.backgroundColor = '#137547';
                                        msgDiv.style.color = '#fff';
                                        msgDiv.style.borderBottomRightRadius = '0';
                                    } else {
                                        msgDiv.style.alignSelf = 'flex-start';
                                        msgDiv.style.backgroundColor = '#e2e8f0';
                                        msgDiv.style.color = '#1e293b';
                                        msgDiv.style.borderBottomLeftRadius = '0';
                                    }
                                    
                                    msgDiv.innerHTML = `<strong>${msg.sender_name}</strong><br>${msg.message}<div style="font-size:0.7rem; margin-top:5px; opacity:0.8; text-align:right;">${msg.created_at}</div>`;
                                    container.appendChild(msgDiv);
                                });
                                container.scrollTop = container.scrollHeight;
                            }
                        });
                }
                </script>
                
            </div>
        </div>
    </section>

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

    <!-- Interactive Lightbox Modal for Homepage -->
    <div id="homeLightbox" class="public-lightbox" onclick="closeHomeLightbox()">
        <!-- Close Button -->
        <button type="button" class="public-lightbox-close" title="Close (Esc)" onclick="closeHomeLightbox()">&times;</button>
        
        <!-- Previous & Next Controls -->
        <button type="button" class="lightbox-arrow lightbox-prev" title="Previous Image" onclick="prevHomeLightbox(event)">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button type="button" class="lightbox-arrow lightbox-next" title="Next Image" onclick="nextHomeLightbox(event)">
            <i class="bi bi-chevron-right"></i>
        </button>

        <div class="public-lightbox-container" onclick="event.stopPropagation()">
            <div class="lightbox-image-wrapper">
                <img src="" alt="Photo" id="homeLightboxImg" class="public-lightbox-img">
            </div>
            <div class="public-lightbox-caption-wrap">
                <div class="lightbox-info-row">
                    <span id="homeLightboxCategory" class="public-lightbox-cat"></span>
                    <span id="homeLightboxTimestamp" class="public-lightbox-timestamp"></span>
                    <span id="homeLightboxCounter" class="public-lightbox-counter"></span>
                </div>
                <h4 id="homeLightboxCaption"></h4>
            </div>
        </div>
    </div>

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
      let homeVisibleItems = [];
      let homeLightboxIndex = 0;

      function initHomeGalleryItems() {
          homeVisibleItems = [];
          const allItems = document.querySelectorAll('#homeGalleryGrid .gallery-item[data-src]');
          allItems.forEach(item => {
              homeVisibleItems.push({
                  src: item.getAttribute('data-src'),
                  caption: item.getAttribute('data-caption') || '',
                  category: item.getAttribute('data-category') || '',
                  timestamp: item.getAttribute('data-timestamp') || ''
              });
          });
      }

      function openHomeLightboxByIndex(index) {
          if (!homeVisibleItems.length) initHomeGalleryItems();
          const item = homeVisibleItems[index];
          if (item) {
              homeLightboxIndex = index;
              renderHomeLightboxContent(item.src, item.caption, item.category, item.timestamp);
          }
      }

      function openHomeLightbox(src, caption, category, timestamp) {
          renderHomeLightboxContent(src, caption, category, timestamp);
      }

      function renderHomeLightboxContent(src, caption, category, timestamp) {
          const lb = document.getElementById('homeLightbox');
          const img = document.getElementById('homeLightboxImg');
          const cap = document.getElementById('homeLightboxCaption');
          const cat = document.getElementById('homeLightboxCategory');
          const timeEl = document.getElementById('homeLightboxTimestamp');
          const counter = document.getElementById('homeLightboxCounter');

          if (lb && img) {
              img.src = src;
              cap.textContent = caption || '';
              cat.innerHTML = category ? '<i class="bi bi-folder-fill"></i> ' + category : '';
              if (timeEl) {
                  timeEl.innerHTML = timestamp ? '<i class="bi bi-clock-history"></i> ' + timestamp : '';
              }
              if (homeVisibleItems.length > 0) {
                  counter.textContent = (homeLightboxIndex + 1) + ' / ' + homeVisibleItems.length;
              } else {
                  counter.textContent = '';
              }
              lb.style.display = 'flex';
              document.body.style.overflow = 'hidden';
          }
      }

      function prevHomeLightbox(e) {
          if (e) e.stopPropagation();
          if (homeVisibleItems.length <= 1) return;
          homeLightboxIndex = (homeLightboxIndex - 1 + homeVisibleItems.length) % homeVisibleItems.length;
          const item = homeVisibleItems[homeLightboxIndex];
          renderHomeLightboxContent(item.src, item.caption, item.category, item.timestamp);
      }

      function nextHomeLightbox(e) {
          if (e) e.stopPropagation();
          if (homeVisibleItems.length <= 1) return;
          homeLightboxIndex = (homeLightboxIndex + 1) % homeVisibleItems.length;
          const item = homeVisibleItems[homeLightboxIndex];
          renderHomeLightboxContent(item.src, item.caption, item.category, item.timestamp);
      }

      function closeHomeLightbox() {
          const lb = document.getElementById('homeLightbox');
          if (lb) {
              lb.style.display = 'none';
              document.body.style.overflow = '';
          }
      }

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
          const lb = document.getElementById('homeLightbox');
          if (lb && lb.style.display === 'flex') {
              if (e.key === 'Escape') closeHomeLightbox();
              else if (e.key === 'ArrowLeft') prevHomeLightbox();
              else if (e.key === 'ArrowRight') nextHomeLightbox();
          }
          if (e.key === 'Escape') {
              closeAnnouncementLightbox();
          }
      });
      document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
          duration: 800, 
          once: true,
          offset: 100
        });
        initHomeGalleryItems();
      });
    </script>
</body>

</html>



