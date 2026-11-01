<?php
session_start();
date_default_timezone_set('Asia/Manila');
unset($_SESSION['chat_user_name']);
if (!isset($_SESSION['chat_thread_id'])) {
    $_SESSION['chat_thread_id'] = uniqid('inquiry_');
}

require_once 'admin/db_connect.php';

// Fetch Hero Background Slides for homepage slideshow
$hero_slides = [];
$hero_speed = 5;
if (isset($conn) && !$conn->connect_error) {
    // Check speed setting
    $speed_res = $conn->query("SELECT hero_slideshow_speed FROM barangay_information LIMIT 1");
    if ($speed_res && $s_row = $speed_res->fetch_assoc()) {
        if (!empty($s_row['hero_slideshow_speed'])) {
            $hero_speed = intval($s_row['hero_slideshow_speed']);
            if (!in_array($hero_speed, [3, 5])) $hero_speed = 5;
        }
    }

    // Check custom uploaded hero backgrounds
    $h_check = $conn->query("SHOW TABLES LIKE 'hero_backgrounds'");
    if ($h_check && $h_check->num_rows > 0) {
        $h_res = $conn->query("SELECT * FROM hero_backgrounds WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
        if ($h_res) {
            while ($h_row = $h_res->fetch_assoc()) {
                if (file_exists($h_row['image_path'])) {
                    $hero_slides[] = $h_row['image_path'];
                }
            }
        }
    }
}
// Default fallback if no custom slides uploaded
if (empty($hero_slides)) {
    $hero_slides[] = 'public/image/Maruing.png';
}

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

// Fetch active services for the homepage showcase
$home_services = [];
if (isset($conn) && !$conn->connect_error) {
    $svc_res = $conn->query("SELECT * FROM services WHERE status = 'active' ORDER BY display_order ASC, id ASC LIMIT 4");
    if ($svc_res) {
        while ($row = $svc_res->fetch_assoc()) {
            $home_services[] = $row;
        }
    }
}

// Fetch About section details (history and image) synchronized with about.php
$about_default_image = "public/image/Barangay Maruing Community Hall.png";
$about_default_history = "Maruing once under the jurisdiction of Margosatubig, before it was declared as a Municipality in 1964 during the time of late Diosdado Macapagal and then reverted into Barangay in 1965. According to the Subanen leaders of Elders, the term Maruing came from two[2] Subanen words \"MARU\" which means bad odor and \"DUWING\" a Subanen word a term for gigantic wild boar having fangs that grew outrares from his mouth because of the incredible strength. According to the story, a Subanen hunter went to the forest looking for wild animals. Using his spear the hunter was able to hit a wild boar or the DUWING but unluckily failed to catch it. Thus, the hunter kept on looking the animal until found a very nice place where two rivers meet creating a big river before it proceeds to the sea. Because he did not find the wild boar but instead the beautiful place, he went home and reported to his co-villagers about a beautiful place where they could establish their community because of its strategic location and abundance of water.\n\nAfter few days, the hunter and his community decided to visit the said beautiful place and there they observed \"MARU\" or bad odor where they believed to be the \"DUWING\" that escaped from hunting. Since they do not know the name of the place, they conbined the two words of MARU and DUWING and they called the place Marwing. It was only changed to MARUING during the time of Barangay Captain Lorenzo Bugao.\n\nBarangay Maruing is the home of the Subanen, one of the 18 major tribes of the Lumad in Mindanao. The first Subanen families who inhabited the place were the families of Mangangot, Balabad, Bugao and Hupa and the first chinesse families were Pula, Wasing and Costan whom later killed by bandits. From the Muslim tribes, it was the families of Manopod who went and settle first in Maruing.\n\nBased on the record, of the Municipal Planning Development Office of Lapuyan, Zamboanga del Sur, Maruing was created into Barangay on January 17,1958 by Virtue of Republic Act 226 from from being a Sitio of once Barangay Lapuyan, which was under the Municipality of Margosatubig. Even before the formal creation of Barangay Maruing, the American Government had already appointed a Teniente del Barrio or Chief Executive aside from the traditional political set-up of Datu System, which is headed by Datu Sapalao. In fact, there were times that Datu Sapalao was also appointed as Teniente del Barrio, particularly Datu Sapalao Consing Bugao in 1930's and Datu Sapalao Mandag Humoa in 1940's.\n\nThe second Teniente del Barrio or Barrio at Large after Maruing was fully recognized as a Barangay was Tere Mangangot who served from 1958-1961. His councillors include Ajerol Arip, John Banagan, Tumandio Lesayao, Tumindeg Tahupis, and Tumanghis Antay. During his term, they constructed a bridged at the center of Barangay and they actively participated the clean and green Revolution Program of the National Government. In 1961, Timuay Tumindeg Tahupis, member of the Tribal Leaders of Maruing succeeded Tere Mangangot until 1964. He continued the projects of his predecessor and led in developing the sports activities and competitions with other municipalities.";

$about_data = null;
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $about_res = $conn->query("SELECT * FROM about_content WHERE id = 1 LIMIT 1");
    if ($about_res && $row = $about_res->fetch_assoc()) {
        $about_data = $row;
    }
}

$about_history_text = !empty($about_data['history']) ? $about_data['history'] : $about_default_history;
$about_paragraphs = preg_split('/(\r\n|\n|\r){2,}/', trim($about_history_text));
$about_preview = !empty($about_paragraphs[0]) ? trim($about_paragraphs[0]) : $about_history_text;
if (count($about_paragraphs) > 1 && mb_strlen($about_preview) < 250) {
    $about_preview .= "\n\n" . trim($about_paragraphs[1]);
}
$about_image_src = $about_default_image;
if (!empty($about_data['image_path'])) {
    if (strpos($about_data['image_path'], 'uploads/about/') === 0) {
        $about_image_src = 'admin/' . $about_data['image_path'];
    } elseif (strpos($about_data['image_path'], 'public/') === 0) {
        $about_image_src = $about_data['image_path'];
    } else {
        $about_image_src = $about_data['image_path'];
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
    
    <!-- Google Fonts: Montserrat, Dancing Script & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Playfair+Display:ital,wght@1,600;1,700;1,800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS (Pure CSS Responsive) -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="public/assets/css/gallery.css?v=6">
    <link rel="stylesheet" href="public/assets/css/announcements.css?v=2">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Hero Background Video & Styles (Directly embedded to bypass browser CSS cache) -->
    <style>
    .hero-section {
        position: relative !important;
        overflow: hidden !important;
        min-height: 500px !important;
        min-height: calc(100vh - 75px) !important; /* Fullscreen viewport height below navbar */
        display: flex !important;
        align-items: center !important;
        color: #ffffff !important;
        padding: 110px 0 140px !important;
        padding: 80px 0 100px !important;
        background: #020c06 !important;
    }

    .hero-video-container {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        z-index: 0 !important;
        overflow: hidden !important;
        pointer-events: none !important;
        background-color: #020c06 !important;
    }

    .hero-bg-video {
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        width: 100% !important;
        height: 100% !important;
        min-width: 100% !important;
        min-height: 100% !important;
        width: auto !important;
        height: auto !important;
        transform: translate(-50%, -50%) !important;
        object-fit: cover !important;
        object-position: center center !important;
        z-index: 0 !important;
    }

    /* Dark High-Contrast Gradient Overlay for Crisp Text Visibility */
    .hero-overlay {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: linear-gradient(90deg, rgba(2, 14, 8, 0.94) 0%, rgba(9, 61, 37, 0.85) 45%, rgba(0, 0, 0, 0.65) 100%) !important;
        z-index: 2 !important;
        pointer-events: none !important;
    }

    .hero-container {
        position: relative !important;
        z-index: 3 !important;
    }

    .hero-scroll-down {
        position: absolute;
        bottom: 22px;
        left: 50%;
        transform: translateX(-50%);
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.35rem;
        z-index: 4;
        text-decoration: none;
        animation: heroBounce 2s infinite;
        transition: color 0.2s ease;
    }
    .hero-scroll-down:hover {
        color: #ffffff;
    }
    @keyframes heroBounce {
        0%, 20%, 50%, 80%, 100% { transform: translate(-50%, 0); }
        40% { transform: translate(-50%, -8px); }
        60% { transform: translate(-50%, -4px); }
    }

    @media (max-width: 768px) {
        .hero-section {
            min-height: calc(100svh - 64px) !important;
            padding: 48px 0 72px !important;
            align-items: center !important;
        }
        /* Vertical gradient reads better with centered mobile text */
        .hero-overlay {
            background: linear-gradient(180deg, rgba(2, 14, 8, 0.88) 0%, rgba(9, 61, 37, 0.82) 55%, rgba(0, 0, 0, 0.75) 100%) !important;
        }
        .hero-scroll-down {
            bottom: 14px;
            font-size: 1.15rem;
        }
    }

    /* Hero Live Timestamp Widget */
    .hero-timestamp {
        margin-bottom: 2rem;
        max-width: 680px;
    }

    .hero-pst-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        background: rgba(9, 61, 37, 0.45);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.28);
        border-radius: 50px;
        padding: 0.55rem 1.35rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        max-width: 100%;
        flex-wrap: wrap;
    }

    .hero-pst-pill .pst-clock {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        font-size: 1.05rem;
        font-weight: 600;
        color: #ffffff;
        letter-spacing: 0.3px;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
    }

    .hero-pst-pill .pst-date {
        color: #ffffff;
        font-weight: 600;
    }

    .hero-pst-pill .pst-sep {
        color: var(--accent-color, #fbbf24);
        opacity: 0.85;
        font-weight: 700;
    }

    .hero-pst-pill .pst-time {
        color: #fef08a;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }

    @media (max-width: 768px) {
        .hero-timestamp {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .hero-pst-pill {
            border-radius: 14px;
            padding: 0.5rem 0.9rem;
            gap: 0.4rem;
            justify-content: center;
        }
        .hero-pst-pill .pst-clock {
            font-size: 0.85rem;
            flex-wrap: wrap;
            justify-content: center;
            row-gap: 2px;
        }
    }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top ">
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

    <main>
        <!-- Hero Section -->
        <section class="hero-section" id="hero-section">
            <!-- Background Video (Autoplay, Muted, Loop) -->
            <div class="hero-video-container">
                <video class="hero-bg-video" autoplay muted loop playsinline preload="auto" poster="public/image/Maruing.png">
                    <source src="Video/Homepage.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            <!-- Dark High-Contrast Overlay for Text Visibility -->
            <div class="hero-overlay"></div>

            <div class="container hero-container">
                <div class="hero-content">
                    <h1 class="hero-title" data-aos="fade-up">
                        <span class="hero-script-lead">Welcome to</span>
                        <span class="hero-title-main">BARANGAY MARUING</span>
                    </h1>
                    <!-- Real-time Philippine Standard Time (PST) Timestamp -->
                    <div class="hero-timestamp" data-aos="fade-up" data-aos-delay="100">
                        <div class="hero-pst-pill">
                            <i class="bi bi-clock-history" style="color: var(--accent-color, #fbbf24); font-size: 1.05rem;"></i>
                            <span class="pst-clock" id="heroLiveTimestamp">
                                <span class="pst-date"><?= date('l, F j, Y') ?></span>
                                <span class="pst-sep">•</span>
                                <span class="pst-time"><?= date('h:i:s A') ?></span>
                            </span>
                        </div>
                    </div>
                    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="200">
                        <a href="about.php" class="btn-hero-primary">Explore Information <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Fullscreen Video Scroll Indicator -->
            <a href="#officials-section" class="hero-scroll-down" title="Explore Content">
                <i class="bi bi-chevron-double-down"></i>
            </a>
        </section>

        <section class="landing-officials-section" id="officials-section" style="padding: 5rem 0; background-color: var(--background-color, #f8faf9);">
            <div class="container">
                <div class="section-header" style="text-align: center; margin-bottom: 4rem;" data-aos="fade-up">
                    <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary-color, #137547); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.8rem;">Barangay Officials</span>
                    <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--primary-dark, #093d25); margin-bottom: 1rem;">Meet Our <span style="color: var(--primary-color, #137547);">Leaders</span></h2>
                    <div style="width: 50px; height: 3px; background-color: var(--primary-color, #137547); margin: 0 auto 1.5rem;"></div>
                    <p style="font-size: 1.1rem; color: var(--muted-text-color, #6b7280);">The dedicated officials working to serve the people of Barangay Maruing.</p>
                </div>
                
                <div class="officials-top-wrapper">
                    <div class="captain-spotlight-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="captain-spotlight-img-col">
                            <?php if (!empty($captain_photo)): ?>
                                <img src="<?= htmlspecialchars($captain_photo) ?>" alt="<?= htmlspecialchars($captain_name) ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($captain_name) ?>&background=093d25&color=ffffff&size=350'">
                            <?php else: ?>
                                <div style="width: 100%; height: 320px; background: #e8f5ee; display: flex; align-items: center; justify-content: center; border-radius: 18px;">
                                    <span style="font-size: 4.5rem; font-weight: 300; color: #093d25; font-family: sans-serif;">PB</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="captain-spotlight-info-col">
                            <span class="captain-spotlight-eyebrow">BARANGAY CAPTAIN</span>
                            <h3 class="captain-spotlight-title"><?= htmlspecialchars(!empty($captain_name) && $captain_name !== 'Name' ? $captain_name : 'Barangay Captain') ?></h3>
                            <p class="captain-spotlight-quote">"<?= htmlspecialchars($captain_desc) ?>"</p>
                            <div class="captain-spotlight-action">
                                <a href="officials.php" class="btn btn-captain-action">View Barangay Officials &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>

        <!-- About Section -->
        <section class="about-landing-section">
            <div class="container about-landing-container">
                <div class="about-landing-image" data-aos="fade-right">
                    <img src="<?= htmlspecialchars($about_image_src) ?>" alt="Barangay Maruing History" onerror="this.src='public/image/Barangay Maruing Community Hall.png'">
                </div>
                <div class="about-landing-content" data-aos="fade-left">
                    <h2 class="about-landing-title">About Barangay Maruing</h2>
                    <div class="about-landing-text-scrollable">
                        <?php 
                        $paragraphs = preg_split('/(\r\n|\n|\r){2,}/', trim($about_history_text));
                        foreach ($paragraphs as $para):
                            $clean_para = trim($para);
                            if (!empty($clean_para)):
                        ?>
                            <p class="about-landing-desc"><?= nl2br(htmlspecialchars($clean_para)) ?></p>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
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
                    <?php if (!empty($home_services)): ?>
                        <?php foreach ($home_services as $s_idx => $svc): 
                            $svcIcon = !empty($svc['icon']) ? htmlspecialchars($svc['icon']) : 'bi-file-earmark-text';
                            $btnText = !empty($svc['button_text']) ? htmlspecialchars($svc['button_text']) : 'Request Document';
                            $fee = !empty($svc['fee']) ? htmlspecialchars($svc['fee']) : 'Free';
                            $procTime = !empty($svc['processing_time']) ? htmlspecialchars($svc['processing_time']) : 'Same Day';
                        ?>
                            <div class="service-card" data-aos="zoom-in" data-aos-delay="<?= ($s_idx * 100) ?>" style="background: white; border-radius: 20px; padding: 2.5rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem;">
                                        <i class="bi <?= $svcIcon ?>" style="font-size: 1.8rem; color: var(--primary-color, #10b981);"></i>
                                    </div>

                                    <h4 style="margin-bottom: 0.6rem; font-weight: 700; font-size: 1.15rem;">
                                        <a href="services.php#service-<?= $svc['id'] ?>" style="color: var(--primary-dark, #1e293b); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#137547'" onmouseout="this.style.color='#1e293b'">
                                            <?= htmlspecialchars($svc['name']) ?>
                                        </a>
                                    </h4>

                                    <div style="display: flex; gap: 6px; justify-content: center; margin-bottom: 1rem; flex-wrap: wrap;">
                                        <span style="background: #e8f5ee; color: #137547; font-size: 0.75rem; font-weight: 600; padding: 3px 8px; border-radius: 20px;">
                                            <i class="bi bi-cash-stack"></i> <?= $fee ?>
                                        </span>
                                        <span style="background: #f1f5f9; color: #475569; font-size: 0.75rem; font-weight: 600; padding: 3px 8px; border-radius: 20px;">
                                            <i class="bi bi-clock"></i> <?= $procTime ?>
                                        </span>
                                    </div>

                                    <p style="color: var(--muted-text-color, #64748b); font-size: 0.92rem; line-height: 1.6; margin: 0;"><?= htmlspecialchars(mb_strimwidth($svc['description'], 0, 110, '...')) ?></p>
                                </div>
                                <div style="margin-top: 1.5rem;">
                                    <a href="services.php#service-<?= $svc['id'] ?>" style="color: var(--primary-color, #10b981); font-weight: 700; font-size: 0.88rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: gap 0.2s;">
                                        <?= $btnText ?> <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
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
                    <?php endif; ?>

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

                                <h3 class="announcement-card-title">
                                    <a href="announcements.php#announcement-<?= $ann['id'] ?>" class="announcement-title-link" title="Click to view full announcement">
                                        <?= htmlspecialchars($ann['title']) ?>
                                    </a>
                                </h3>
                                
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

                                <div class="announcement-card-action" style="margin-top: auto; padding-top: 1.25rem;">
                                    <a href="announcements.php#announcement-<?= $ann['id'] ?>" class="announcement-read-more">
                                        Read Full Announcement <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
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
                    <img src="public/image/Maruing Map/Aerial View.png" alt="Spot Map of Barangay Maruing" style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
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
    
                <!-- Right Column: Inquiry / Contact Form -->
                <div class="contact-form-col" data-aos="fade-left" id="inquiry-widget-container">
                    
                    <!-- Inquiry Input Form -->
                    <div id="inquiry-form-card" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:2rem; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                        <h2 style="font-size:1.5rem; font-weight:700; color:#093d25; margin-bottom:0.4rem;">Send Us an Inquiry</h2>
                        <p style="color:#64748b; font-size:0.88rem; line-height:1.5; margin-bottom:1.5rem;">
                            Have concerns, questions, or document requests? Fill out the form below. The Barangay Administration will review your message and <strong>reply directly to your Gmail / email address</strong>.
                        </p>

                        <form id="resident-inquiry-form">
                            <div class="form-group" style="margin-bottom:1.1rem;">
                                <label style="display:block; font-weight:600; font-size:0.86rem; color:#1e293b; margin-bottom:0.35rem;">Your Name <span style="color:#ef4444;">*</span></label>
                                <input type="text" id="inquiry-name" class="form-control" placeholder="Enter your full name" required style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem;">
                            </div>
                            <div class="form-group" style="margin-bottom:1.1rem;">
                                <label style="display:block; font-weight:600; font-size:0.86rem; color:#1e293b; margin-bottom:0.35rem;">Gmail / Email Address <span style="color:#ef4444;">*</span></label>
                                <input type="email" id="inquiry-email" class="form-control" placeholder="e.g. yourname@gmail.com" required style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem;">
                                <small style="display:block; color:#059669; font-size:0.78rem; margin-top:0.3rem;"><i class="bi bi-info-circle-fill"></i> Admin will send their official reply to this Gmail address.</small>
                            </div>
                            <div class="form-group" style="margin-bottom:1.1rem;">
                                <label style="display:block; font-weight:600; font-size:0.86rem; color:#1e293b; margin-bottom:0.35rem;">Service Type / Subject <span style="color:#ef4444;">*</span></label>
                                <select id="inquiry-service-type" class="form-control" required style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem;">
                                    <option value="" disabled selected>Select service or category</option>
                                    <option value="Barangay Clearance">Barangay Clearance</option>
                                    <option value="Certificate of Indigency">Certificate of Indigency</option>
                                    <option value="Business Clearance">Business Clearance</option>
                                    <option value="Certificate of Residency">Certificate of Residency</option>
                                    <option value="General Inquiry / Feedback">General Inquiry / Feedback</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom:1.4rem;">
                                <label style="display:block; font-weight:600; font-size:0.86rem; color:#1e293b; margin-bottom:0.35rem;">Your Message / Request Details <span style="color:#ef4444;">*</span></label>
                                <textarea id="inquiry-message" class="form-control" placeholder="Write your questions, requirements, or concern details here..." rows="4" required style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem; resize:vertical;"></textarea>
                            </div>
                            <button type="submit" id="btn-submit-inquiry" class="btn-submit" style="width:100%; padding:0.85rem; border-radius:8px; font-weight:700; font-size:0.95rem; display:flex; align-items:center; justify-content:center; gap:8px;">
                                <i class="bi bi-send-fill" id="submit-icon"></i>
                                <span id="submit-label">Send Inquiry</span>
                            </button>
                        </form>
                    </div>

                    <!-- Clean Confirmation State (After Sending) -->
                    <div id="inquiry-success-card" style="display:none; text-align:center; padding:2.5rem 1.75rem; background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04);">
                        <div style="width:68px; height:68px; border-radius:50%; background:#dcfce7; color:#16a34a; font-size:2.2rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem auto;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h3 style="font-size:1.35rem; color:#093d25; font-weight:700; margin-bottom:0.5rem;">Inquiry Sent Successfully!</h3>
                        <p style="color:#475569; font-size:0.92rem; line-height:1.6; max-width:440px; margin:0 auto 1.25rem auto;">
                            Thank you, <strong id="success-user-name">Resident</strong>! Your inquiry has been verified and received by Barangay Maruing. Our officers will review your message and reply directly to your Gmail address at:
                            <br><span style="display:inline-block; margin-top:8px; background:#eff6ff; color:#1d4ed8; font-weight:700; padding:4px 14px; border-radius:20px; border:1px solid #bfdbfe;" id="success-user-email">email@gmail.com</span>
                        </p>
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 16px; margin-bottom:1.5rem; font-size:0.84rem; color:#64748b; line-height:1.45;">
                            <i class="bi bi-envelope-check-fill" style="color:#ea4335; margin-right:5px;"></i>
                            Please check your Gmail inbox and Spam/Junk folder for our official response.
                        </div>
                        <button type="button" id="btn-send-another" class="btn-submit" style="width:auto; padding:0.75rem 1.8rem; margin:0 auto; display:inline-flex; align-items:center; gap:8px;">
                            <i class="bi bi-pencil-square"></i> Send Another Inquiry
                        </button>
                    </div>

                </div>
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
      // Background Hero Slideshow (Only active when 2 or more background images are present)
      function initHeroBackgroundSlideshow() {
          const heroSection = document.getElementById('hero-section');
          if (!heroSection) return;
          const slides = Array.from(heroSection.querySelectorAll('.hero-bg-slide'));
          if (!slides || slides.length <= 1) return; // 1 or 0 slides: static background, no slideshow needed

          const speedSeconds = parseInt(heroSection.getAttribute('data-speed'), 10) || 5;
          const intervalMs = Math.max(2, speedSeconds) * 1000;
          let currentIdx = 0;

          // Set initial stacking: first slide fully visible on top
          slides.forEach(function(s, i) {
              s.style.opacity = (i === 0) ? '1' : '0';
              s.style.zIndex = (i === 0) ? '1' : '0';
          });

          setInterval(function() {
              const prevIdx = currentIdx;
              currentIdx = (currentIdx + 1) % slides.length;

              const prevSlide = slides[prevIdx];
              const nextSlide = slides[currentIdx];

              // Place next slide on top (z-index 2) and fade it in
              nextSlide.style.zIndex = '2';
              nextSlide.style.opacity = '1';

              // Previous slide remains solid at z-index 1 underneath while next slide fades in
              prevSlide.style.zIndex = '1';

              // After crossfade completes (1.3s), reset previous slide to transparent
              setTimeout(function() {
                  prevSlide.style.opacity = '0';
                  prevSlide.style.zIndex = '0';
                  nextSlide.style.zIndex = '1';
              }, 1300);
          }, intervalMs);
      }

      // Real-time Philippine Standard Time (PST) Live Clock
      function initHeroLiveTimestamp() {
          const clockEl = document.getElementById('heroLiveTimestamp');
          if (!clockEl) return;

          function updateClock() {
              const now = new Date();
              const dateOptions = {
                  timeZone: 'Asia/Manila',
                  weekday: 'long',
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric'
              };
              const timeOptions = {
                  timeZone: 'Asia/Manila',
                  hour: '2-digit',
                  minute: '2-digit',
                  second: '2-digit',
                  hour12: true
              };

              const dateStr = new Intl.DateTimeFormat('en-US', dateOptions).format(now);
              const timeStr = new Intl.DateTimeFormat('en-US', timeOptions).format(now);

              clockEl.innerHTML = '<span class="pst-date">' + dateStr + '</span> <span class="pst-sep">•</span> <span class="pst-time">' + timeStr + '</span>';
          }

          updateClock();
          setInterval(updateClock, 1000);
      }

      if (document.readyState === 'loading') {
          document.addEventListener('DOMContentLoaded', function() {
              initHeroBackgroundSlideshow();
              initHeroLiveTimestamp();
          });
      } else {
          initHeroBackgroundSlideshow();
          initHeroLiveTimestamp();
      }

      document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
          duration: 800, 
          once: true,
          offset: 100
        });
        initHomeGalleryItems();
      });
    </script>
    <!-- Exact Pop-Up OTP Modal (Barangay Maruing Color Scheme) -->
    <div class="gmail-otp-modal-backdrop" id="gmail-otp-modal-backdrop" style="display:none !important;">
        <div class="gmail-otp-modal-box">
            <!-- Close 'X' Button -->
            <button type="button" class="gmail-otp-close-btn" id="btn-modal-otp-close" aria-label="Close">&times;</button>

            <!-- Shield Icon in Circle (Civic Emerald Mint) -->
            <div class="gmail-otp-shield-icon-circle">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="#137547">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                </svg>
            </div>

            <!-- Italic Serif Title in Brand Deep Green -->
            <h2 class="gmail-otp-title">Gmail OTP Verification</h2>

            <!-- Subtitle Description -->
            <p class="gmail-otp-desc">
                We sent a 6-digit verification OTP to <strong id="modal-otp-target-email">user@gmail.com</strong>.
                Please check your Inbox or Spam folder, then enter the OTP below to confirm your request:
            </p>

            <!-- 6 Individual Input Boxes -->
            <div class="gmail-otp-boxes-group" id="gmail-otp-boxes-group">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="0" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="1" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="2" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="3" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="4" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="5" inputmode="numeric" pattern="[0-9]*">
            </div>

            <!-- Expiration & Resend Pill Bar -->
            <div class="gmail-otp-pill-bar">
                <span class="gmail-otp-pill-timer">
                    <i class="bi bi-clock-history"></i> Expires in: <strong id="modal-otp-timer-text">03:00</strong>
                </span>
                <button type="button" class="gmail-otp-pill-resend-btn" id="btn-modal-resend-otp" disabled>
                    <i class="bi bi-arrow-clockwise"></i> Resend (<span id="modal-resend-sec-text">30s</span>)
                </button>
            </div>

            <!-- Full-Width Brand Emerald Verification Button -->
            <button type="button" class="gmail-otp-submit-btn" id="btn-modal-submit-verify">
                <i class="bi bi-patch-check-fill" id="modal-submit-verify-icon"></i>
                <span id="modal-submit-verify-label">Verify &amp; Submit Request</span>
            </button>

            <!-- Error Message Alert -->
            <div id="modal-otp-error-alert" class="gmail-otp-error-alert" style="display:none;"></div>
        </div>
    </div>

    <!-- Pop-Up Modal Styling (Barangay Maruing Color Palette) -->
    <style>
        .gmail-otp-modal-backdrop {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(9, 61, 37, 0.72) !important; /* Brand dark forest overlay */
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            z-index: 9999999 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            animation: otpBackdropFadeIn 0.2s ease-out;
        }

        @keyframes otpBackdropFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .gmail-otp-modal-box {
            position: relative !important;
            background: #ffffff !important;
            border-radius: 28px !important;
            padding: 38px 32px 34px 32px !important;
            max-width: 440px !important;
            width: 100% !important;
            box-shadow: 0 25px 50px -12px rgba(9, 61, 37, 0.35), 0 0 0 1px rgba(19, 117, 71, 0.12) !important;
            text-align: center !important;
            animation: otpModalScaleIn 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 10000000 !important;
        }

        @keyframes otpModalScaleIn {
            from { transform: scale(0.92); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .gmail-otp-close-btn {
            position: absolute !important;
            top: 20px !important;
            right: 20px !important;
            width: 34px !important;
            height: 34px !important;
            border-radius: 50% !important;
            background: #f1f5f9 !important;
            color: #64748b !important;
            border: 1px solid #e2e8f0 !important;
            cursor: pointer !important;
            font-size: 17px !important;
            line-height: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease !important;
        }

        .gmail-otp-close-btn:hover {
            background: #e8f5ee !important;
            color: #093d25 !important;
            border-color: #a7f3d0 !important;
        }

        .gmail-otp-shield-icon-circle {
            width: 72px !important;
            height: 72px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #e8f5ee 0%, #d1fae5 100%) !important;
            border: 1.5px solid #a7f3d0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 auto 16px auto !important;
            box-shadow: 0 6px 14px rgba(19, 117, 71, 0.15) !important;
        }

        .gmail-otp-title {
            font-family: 'Playfair Display', Georgia, serif !important;
            font-style: italic !important;
            font-weight: 700 !important;
            font-size: 29px !important;
            color: #093d25 !important; /* Brand dark green */
            margin: 0 0 10px 0 !important;
            letter-spacing: -0.3px !important;
        }

        .gmail-otp-desc {
            font-size: 13.5px !important;
            line-height: 1.55 !important;
            color: #475569 !important;
            margin: 0 0 24px 0 !important;
        }

        .gmail-otp-desc strong {
            color: #093d25 !important;
            font-weight: 700 !important;
            background: #e8f5ee !important;
            padding: 2px 8px !important;
            border-radius: 6px !important;
            border: 1px solid #d1fae5 !important;
            word-break: break-all !important;
            display: inline-block !important;
        }

        .gmail-otp-boxes-group {
            display: flex !important;
            justify-content: center !important;
            gap: 10px !important;
            margin-bottom: 22px !important;
        }

        .gmail-otp-digit-box {
            width: 48px !important;
            height: 56px !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 12px !important;
            text-align: center !important;
            font-size: 24px !important;
            font-weight: 800 !important;
            color: #093d25 !important; /* Emerald digit */
            background: #ffffff !important;
            outline: none !important;
            transition: all 0.15s ease !important;
            -webkit-appearance: none !important;
            margin: 0 !important;
            font-family: 'Courier New', monospace !important;
        }

        .gmail-otp-digit-box:focus {
            border-color: #137547 !important;
            box-shadow: 0 0 0 3px rgba(19, 117, 71, 0.22) !important;
            background: #f0fdf4 !important;
        }

        .gmail-otp-pill-bar {
            background: #f7faf8 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 10px 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            margin-bottom: 22px !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
        }

        .gmail-otp-pill-timer {
            color: #093d25 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
        }

        .gmail-otp-pill-timer i {
            color: #137547 !important;
            font-size: 1rem !important;
        }

        .gmail-otp-pill-resend-btn {
            background: none !important;
            border: none !important;
            color: #94a3b8 !important;
            font-weight: 600 !important;
            font-size: 13.5px !important;
            cursor: not-allowed !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            transition: all 0.2s ease !important;
            padding: 0 !important;
        }

        .gmail-otp-pill-resend-btn.active {
            color: #137547 !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            text-decoration: underline !important;
        }

        .gmail-otp-pill-resend-btn.active:hover {
            color: #093d25 !important;
        }

        .gmail-otp-submit-btn {
            width: 100% !important;
            padding: 14px 22px !important;
            border-radius: 30px !important;
            background: linear-gradient(135deg, #137547 0%, #093d25 100%) !important; /* Brand Emerald Gradient */
            color: #ffffff !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            letter-spacing: 0.3px !important;
            border: none !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            box-shadow: 0 6px 18px rgba(19, 117, 71, 0.32) !important;
            transition: all 0.2s ease !important;
        }

        .gmail-otp-submit-btn #modal-submit-verify-icon {
            color: #ffc107 !important; /* Gold badge check */
            font-size: 1.15rem !important;
        }

        .gmail-otp-submit-btn:hover {
            background: linear-gradient(135deg, #0e5936 0%, #062b1a 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 24px rgba(9, 61, 37, 0.45) !important;
        }

        .gmail-otp-submit-btn:disabled {
            opacity: 0.65 !important;
            cursor: not-allowed !important;
            transform: none !important;
        }

        .gmail-otp-error-alert {
            color: #dc2626 !important;
            background: #fef2f2 !important;
            border: 1px solid #fecaca !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            margin-top: 14px !important;
        }

        /* Mobile Screen Adjustments for OTP Modal */
        @media (max-width: 480px) {
            .gmail-otp-modal-box {
                padding: 28px 16px 24px 16px !important;
                border-radius: 20px !important;
                max-width: calc(100vw - 24px) !important;
            }
            .gmail-otp-boxes-group {
                gap: 6px !important;
                margin-bottom: 16px !important;
            }
            .gmail-otp-digit-box {
                width: 40px !important;
                height: 48px !important;
                font-size: 20px !important;
                border-radius: 8px !important;
            }
            .gmail-otp-title {
                font-size: 22px !important;
            }
            .gmail-otp-desc {
                font-size: 12.5px !important;
                margin-bottom: 16px !important;
            }
            .gmail-otp-pill-bar {
                font-size: 12px !important;
                padding: 8px 12px !important;
                margin-bottom: 16px !important;
            }
            .gmail-otp-submit-btn {
                padding: 12px 16px !important;
                font-size: 13.5px !important;
            }
        }
        @media (max-width: 360px) {
            .gmail-otp-modal-box {
                padding: 22px 12px 20px 12px !important;
            }
            .gmail-otp-boxes-group {
                gap: 4px !important;
            }
            .gmail-otp-digit-box {
                width: 35px !important;
                height: 44px !important;
                font-size: 17px !important;
            }
            .gmail-otp-title {
                font-size: 20px !important;
            }
        }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const inquiryForm = document.getElementById('resident-inquiry-form');
        const formCard = document.getElementById('inquiry-form-card');
        const successCard = document.getElementById('inquiry-success-card');
        const submitBtn = document.getElementById('btn-submit-inquiry');
        const submitIcon = document.getElementById('submit-icon');
        const submitLabel = document.getElementById('submit-label');
        const btnSendAnother = document.getElementById('btn-send-another');

        // Modal Elements
        const modalBackdrop = document.getElementById('gmail-otp-modal-backdrop');
        const modalCloseBtn = document.getElementById('btn-modal-otp-close');
        const modalEmailDisplay = document.getElementById('modal-otp-target-email');
        const otpBoxes = document.querySelectorAll('.gmail-otp-digit-box');
        const modalTimerText = document.getElementById('modal-otp-timer-text');
        const modalResendBtn = document.getElementById('btn-modal-resend-otp');
        const modalVerifyBtn = document.getElementById('btn-modal-submit-verify');
        const modalVerifyIcon = document.getElementById('modal-submit-verify-icon');
        const modalVerifyLabel = document.getElementById('modal-submit-verify-label');
        const modalErrorAlert = document.getElementById('modal-otp-error-alert');

        let pendingInquiryData = null;
        let expiryTimerInterval = null;
        let resendTimerInterval = null;
        let expirySecondsLeft = 180;
        let resendSecondsLeft = 30;

        // Open Pop-Up Modal
        function openOtpModal(email) {
            modalEmailDisplay.textContent = email;
            modalErrorAlert.style.display = 'none';
            otpBoxes.forEach(box => box.value = '');
            modalBackdrop.style.setProperty('display', 'flex', 'important');
            setTimeout(() => {
                if (otpBoxes[0]) otpBoxes[0].focus();
            }, 100);

            startExpiryCountdown(180);
            startResendCountdown(30);
        }

        // Close Pop-Up Modal
        function closeOtpModal() {
            modalBackdrop.style.setProperty('display', 'none', 'important');
            clearInterval(expiryTimerInterval);
            clearInterval(resendTimerInterval);
        }

        modalCloseBtn.addEventListener('click', closeOtpModal);
        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) closeOtpModal();
        });

        // 180-second Expiration Countdown
        function startExpiryCountdown(sec) {
            clearInterval(expiryTimerInterval);
            expirySecondsLeft = sec;
            function updateExpiry() {
                const m = String(Math.floor(expirySecondsLeft / 60)).padStart(2, '0');
                const s = String(expirySecondsLeft % 60).padStart(2, '0');
                modalTimerText.textContent = `${m}:${s}`;
                if (expirySecondsLeft <= 0) {
                    clearInterval(expiryTimerInterval);
                    modalTimerText.textContent = 'Expired';
                    showOtpError('Your OTP code has expired. Please click "Resend" to get a new code.');
                } else {
                    expirySecondsLeft--;
                }
            }
            updateExpiry();
            expiryTimerInterval = setInterval(updateExpiry, 1000);
        }

        // 30-second Resend Timer
        function startResendCountdown(sec) {
            clearInterval(resendTimerInterval);
            resendSecondsLeft = sec;
            modalResendBtn.disabled = true;
            modalResendBtn.classList.remove('active');
            modalResendBtn.innerHTML = `<i class="bi bi-arrow-clockwise"></i> Resend (<span id="modal-resend-sec-text">${resendSecondsLeft}s</span>)`;

            resendTimerInterval = setInterval(() => {
                resendSecondsLeft--;
                const span = document.getElementById('modal-resend-sec-text');
                if (span) span.textContent = `${resendSecondsLeft}s`;

                if (resendSecondsLeft <= 0) {
                    clearInterval(resendTimerInterval);
                    modalResendBtn.disabled = false;
                    modalResendBtn.classList.add('active');
                    modalResendBtn.innerHTML = `<i class="bi bi-arrow-clockwise"></i> Resend Code`;
                }
            }, 1000);
        }

        function showOtpError(msg) {
            modalErrorAlert.textContent = msg;
            modalErrorAlert.style.display = 'block';
        }

        // 6-Digit Box Navigation (Auto-advance & Backspace)
        otpBoxes.forEach((box, index) => {
            box.addEventListener('input', (e) => {
                const val = box.value.replace(/[^0-9]/g, '');
                box.value = val ? val.charAt(0) : '';

                if (box.value && index < otpBoxes.length - 1) {
                    otpBoxes[index + 1].focus();
                }
            });

            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !box.value && index > 0) {
                    otpBoxes[index - 1].focus();
                }
                if (e.key === 'Enter') {
                    e.preventDefault();
                    modalVerifyBtn.click();
                }
            });

            box.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                const digits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);
                if (digits.length > 0) {
                    digits.split('').forEach((d, i) => {
                        if (otpBoxes[i]) otpBoxes[i].value = d;
                    });
                    const focusIdx = Math.min(digits.length, otpBoxes.length - 1);
                    otpBoxes[focusIdx].focus();
                }
            });
        });

        // Send OTP Request to API
        function dispatchOtp() {
            if (!pendingInquiryData) return;

            submitBtn.disabled = true;
            submitIcon.className = 'bi bi-arrow-repeat';
            submitIcon.style.animation = 'spin 1s linear infinite';
            submitLabel.textContent = 'Sending OTP to Gmail...';
            modalErrorAlert.style.display = 'none';

            const formData = new URLSearchParams();
            formData.append('fullName', pendingInquiryData.name);
            formData.append('email', pendingInquiryData.email);
            formData.append('service_type', pendingInquiryData.serviceType);
            formData.append('message', pendingInquiryData.message);

            fetch('api/send_otp.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res && res.status === 'success') {
                    openOtpModal(pendingInquiryData.email);
                    if (res.dev_otp) {
                        console.log('DEV OTP Fallback:', res.dev_otp);
                    }
                } else {
                    alert(res.message || 'Could not send verification code to your Gmail.');
                }
            })
            .catch(() => {
                alert('Network connection error. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitIcon.className = 'bi bi-send-fill';
                submitIcon.style.animation = 'none';
                submitLabel.textContent = 'Send Inquiry';
            });
        }

        // 1. Submit Inquiry Form -> Triggers Pop-Up OTP
        if (inquiryForm) {
            inquiryForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const name = document.getElementById('inquiry-name').value.trim();
                const email = document.getElementById('inquiry-email').value.trim();
                const serviceSelect = document.getElementById('inquiry-service-type');
                const serviceType = serviceSelect.options[serviceSelect.selectedIndex].text;
                const message = document.getElementById('inquiry-message').value.trim();

                if (!name || !email || !message) return;

                pendingInquiryData = { name, email, serviceType, message };
                dispatchOtp();
            });
        }

        // 2. Resend Code
        modalResendBtn.addEventListener('click', () => {
            if (modalResendBtn.disabled) return;
            dispatchOtp();
        });

        // 3. Verify OTP & Submit Request
        modalVerifyBtn.addEventListener('click', () => {
            let enteredCode = '';
            otpBoxes.forEach(box => enteredCode += box.value.trim());

            if (enteredCode.length !== 6) {
                showOtpError('Please enter all 6 digits of your OTP code.');
                return;
            }

            modalVerifyBtn.disabled = true;
            modalVerifyIcon.className = 'bi bi-arrow-repeat';
            modalVerifyIcon.style.animation = 'spin 1s linear infinite';
            modalVerifyLabel.textContent = 'Verifying...';
            modalErrorAlert.style.display = 'none';

            const formData = new URLSearchParams();
            formData.append('email', pendingInquiryData.email);
            formData.append('otp_code', enteredCode);

            fetch('api/verify_otp.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res && res.status === 'success') {
                    closeOtpModal();
                    document.getElementById('success-user-name').textContent = pendingInquiryData.name;
                    document.getElementById('success-user-email').textContent = pendingInquiryData.email;
                    formCard.style.display = 'none';
                    successCard.style.display = 'block';
                    inquiryForm.reset();
                    pendingInquiryData = null;
                } else {
                    showOtpError(res.message || 'Invalid verification code. Please check your Gmail.');
                    otpBoxes.forEach(b => b.value = '');
                    otpBoxes[0].focus();
                }
            })
            .catch(() => {
                showOtpError('Verification request failed. Please check your internet connection.');
            })
            .finally(() => {
                modalVerifyBtn.disabled = false;
                modalVerifyIcon.className = 'bi bi-check-circle-fill';
                modalVerifyIcon.style.animation = 'none';
                modalVerifyLabel.textContent = 'Verify & Submit Request';
            });
        });

        // Send Another Inquiry Button
        if (btnSendAnother) {
            btnSendAnother.addEventListener('click', function() {
                inquiryForm.reset();
                successCard.style.display = 'none';
                formCard.style.display = 'block';
                document.getElementById('inquiry-name').focus();
            });
        }
    });
    </script>
</body>

</html>



