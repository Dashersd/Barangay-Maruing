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
    <link rel="stylesheet" href="public/assets/css/style.css?v=10">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
                        <a class="nav-link active" href="spot-map.php">Spot Map</a>
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

    <main class="py-5 mt-4" style="width: 96%; max-width: 1400px; margin: 0 auto;">

        <div class="landing-spot-map-container" style="display: grid; grid-template-columns: 360px 1fr; gap: 3rem; align-items: start;">
            <div class="spot-map-content" style="display: flex; flex-direction: column; gap: 2rem; padding: 0; position: sticky; top: 110px;">
                
                <!-- Container 1: Map Legend -->
                <div class="spot-map-card" data-aos="fade-right" style="background: #ffffff; padding: 2.5rem 2rem; border-radius: 20px; border: none; box-shadow: 0 4px 25px rgba(0,0,0,0.04);">
                    <h3 style="font-family: Georgia, 'Times New Roman', serif; font-weight: 700; font-size: 1.5rem; color: #093d25; display: flex; align-items: center; gap: 12px; margin-bottom: 2rem;">
                        <i class="bi bi-map" style="color: #ffc107; font-size: 1.6rem;"></i> Map Legend
                    </h3>
                    <?php
                    $map_legends = [];
                    // 1. Fetch from Database
                    if (file_exists('admin/db_connect.php')) {
                        require_once 'admin/db_connect.php';
                        if (isset($conn) && !$conn->connect_error) {
                            $leg_res = $conn->query("SELECT * FROM sm_legends WHERE is_active = 1 ORDER BY display_order ASC, id ASC");
                            if ($leg_res && $leg_res->num_rows > 0) {
                                while ($l_row = $leg_res->fetch_assoc()) {
                                    $map_legends[] = $l_row;
                                }
                            }
                        }
                    }
                    // 2. Fallback to JSON
                    if (empty($map_legends) && file_exists('admin/data/legend.json')) {
                        $json_str = file_get_contents('admin/data/legend.json');
                        $json_arr = json_decode($json_str, true);
                        if (is_array($json_arr)) {
                            foreach ($json_arr as $item) {
                                if (!isset($item['is_active']) || $item['is_active'] == 1) {
                                    $map_legends[] = $item;
                                }
                            }
                        }
                    }
                    // 3. Fallback to defaults if still empty
                    if (empty($map_legends)) {
                        $map_legends = [
                            ['name' => 'Barangay Hall', 'icon_path' => 'bi-building-fill', 'color_hex' => '#6366f1'],
                            ['name' => 'School', 'icon_path' => 'bi-book-half', 'color_hex' => '#10b981'],
                            ['name' => 'Court', 'icon_path' => 'bi-dribbble', 'color_hex' => '#f59e0b'],
                            ['name' => 'Health Center', 'icon_path' => 'bi-heart-pulse-fill', 'color_hex' => '#e11d48'],
                            ['name' => 'Other Landmarks', 'icon_path' => 'bi-geo-fill', 'color_hex' => '#94a3b8']
                        ];
                    }
                    ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem 1rem;">
                        <?php 
                        $total_items = count($map_legends);
                        foreach ($map_legends as $idx => $leg_item): 
                            $name = $leg_item['name'] ?? '';
                            $icon = !empty($leg_item['icon_path']) ? trim($leg_item['icon_path']) : 'bi-geo-alt-fill';
                            $color = !empty($leg_item['color_hex']) ? htmlspecialchars($leg_item['color_hex']) : '#093d25';
                            $photo = !empty($leg_item['image_path']) ? $leg_item['image_path'] : '';
                            if (!empty($photo) && strpos($photo, 'uploads/') === 0) {
                                $photo_url = 'admin/' . $photo;
                            } else {
                                $photo_url = $photo;
                            }
                            $is_last_odd = ($total_items % 2 !== 0 && $idx === $total_items - 1);
                            $col_span = $is_last_odd ? 'grid-column: span 2;' : '';

                            $is_img = (preg_match('/\.(png|jpe?g|gif|svg|webp)$/i', $icon) || strpos($icon, '/') !== false);
                            if ($is_img && strpos($icon, 'admin/') !== 0 && strpos($icon, 'public/') !== 0) {
                                $img_src = 'admin/' . $icon;
                            } else {
                                $img_src = $icon;
                            }
                        ?>
                        <div class="spot-map-legend-item-clickable" 
                             role="button" 
                             tabindex="0"
                             onclick="openLandmarkModal('<?= htmlspecialchars(addslashes($name)) ?>', '<?= htmlspecialchars(addslashes($photo_url)) ?>', '<?= htmlspecialchars(addslashes($icon)) ?>', '<?= htmlspecialchars(addslashes($color)) ?>', '<?= htmlspecialchars(addslashes($leg_item['id'] ?? '')) ?>')"
                             style="display: flex; align-items: flex-start; gap: 10px; font-weight: 500; color: #64748b; font-size: 0.95rem; cursor: pointer; transition: all 0.2s ease; border-radius: 8px; padding: 6px; <?= $col_span ?>" 
                             title="Click to view photo & locate <?= htmlspecialchars($name) ?> on map">
                            <?php if ($is_img): ?>
                                <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($name) ?>" style="width: 22px; height: 22px; object-fit: contain; margin-top: 1px;">
                            <?php else: 
                                $bi_cls = strpos($icon, 'bi-') === 0 ? $icon : ('bi-' . ltrim($icon, 'bi '));
                            ?>
                                <i class="bi <?= htmlspecialchars($bi_cls) ?>" style="color: <?= $color ?>; font-size: 1.25rem; line-height: 1;"></i>
                            <?php endif; ?>
                            <span style="line-height: 1.2; word-break: break-word;"><?= htmlspecialchars($name) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Container 2: 9 Puroks of Maruing -->
                <div class="spot-map-card" data-aos="fade-right" data-aos-delay="150" style="background: #ffffff; padding: 2.5rem 2rem; border-radius: 20px; border: none; box-shadow: 0 4px 25px rgba(0,0,0,0.04);">
                    <h3 style="font-family: Georgia, 'Times New Roman', serif; font-weight: 700; font-size: 1.45rem; color: #093d25; display: flex; align-items: center; gap: 12px; margin-bottom: 2rem;">
                        <i class="bi bi-geo-alt-fill" style="color: #f59e0b; font-size: 1.6rem;"></i> 9 Puroks of Maruing
                    </h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; width: 100%;">
                        <!-- View Aerial Spot Map Button -->
                        <button type="button" id="btn-show-aerial" class="btn-aerial-view" style="grid-column: span 2; background: linear-gradient(135deg, #093d25 0%, #155734 100%); color: #ffffff; border: 1px solid rgba(255, 193, 7, 0.4); border-radius: 25px; padding: 0.7rem 1rem; font-weight: 700; font-size: 0.88rem; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; box-shadow: 0 4px 12px rgba(9, 61, 37, 0.18); transition: all 0.2s ease;" title="View Aerial Spot Map with all 9 Purok Pins">
                            <i class="bi bi-pin-map-fill" style="color: #ffc107; font-size: 1.1rem;"></i> View Aerial Map (9 Puroks)
                        </button>

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
                    <button type="button" id="btn-back-overview" class="btn-back-overview" title="Back to Map Overview">
                        <i class="bi bi-arrow-left"></i> Overview Map
                    </button>
                </div>

                <div class="spot-map-visual">
                    <!-- Interactive Regional Pin on Map -->
                    <div class="map-pin-wrapper" id="maruing-pin-btn" role="button" tabindex="0" title="Click to view Mindanao" style="display: flex; top: 77.5%; left: 61.5%;">
                        <div class="pin-pulse"></div>
                        <div class="pin-marker">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="pin-tooltip" id="pin-tooltip-text">Click to View Mindanao</div>
                    </div>

                    <!-- 9 Purok Pins Layer on Aerial View -->
                    <div id="purok-pins-container" class="purok-pins-layer" style="display: none;">
                        <div class="purok-map-pin" data-purok="1" data-img="public/image/Purok/Purok 1.jpg?v=2" style="top: 15%; left: 36%;" role="button" tabindex="0">
                            <div class="purok-pin-tooltip">Explore Purok 1 Map</div>
                            <div class="purok-pin-badge">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Purok 1</span>
                            </div>
                            <div class="purok-pin-pointer"></div>
                            <div class="purok-pin-dot">
                                <div class="purok-pin-pulse"></div>
                            </div>
                        </div>

                        <div class="purok-map-pin" data-purok="2" data-img="public/image/Purok/Purok 2.jpg?v=2" style="top: 14%; left: 22%;" role="button" tabindex="0">
                            <div class="purok-pin-tooltip">Explore Purok 2 Map</div>
                            <div class="purok-pin-badge">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Purok 2</span>
                            </div>
                            <div class="purok-pin-pointer"></div>
                            <div class="purok-pin-dot">
                                <div class="purok-pin-pulse"></div>
                            </div>
                        </div>

                        <div class="purok-map-pin" data-purok="3" data-img="public/image/Purok/Purok 3.jpg?v=2" style="top: 35%; left: 30%;" role="button" tabindex="0">
                            <div class="purok-pin-tooltip">Explore Purok 3 Map</div>
                            <div class="purok-pin-badge">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Purok 3</span>
                            </div>
                            <div class="purok-pin-pointer"></div>
                            <div class="purok-pin-dot">
                                <div class="purok-pin-pulse"></div>
                            </div>
                        </div>

                        <div class="purok-map-pin" data-purok="4" data-img="public/image/Purok/Purok 4.jpg?v=2" style="top: 50%; left: 38%;" role="button" tabindex="0">
                            <div class="purok-pin-tooltip">Explore Purok 4 Map</div>
                            <div class="purok-pin-badge">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Purok 4</span>
                            </div>
                            <div class="purok-pin-pointer"></div>
                            <div class="purok-pin-dot">
                                <div class="purok-pin-pulse"></div>
                            </div>
                        </div>

                        <div class="purok-map-pin" data-purok="5" data-img="public/image/Purok/Purok 5.jpg?v=2" style="top: 65%; left: 32%;" role="button" tabindex="0">
                            <div class="purok-pin-tooltip">Explore Purok 5 Map</div>
                            <div class="purok-pin-badge">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Purok 5</span>
                            </div>
                            <div class="purok-pin-pointer"></div>
                            <div class="purok-pin-dot">
                                <div class="purok-pin-pulse"></div>
                            </div>
                        </div>

                        <div class="purok-map-pin" data-purok="6" data-img="public/image/Purok/Purok 6.jpg?v=2" style="top: 86%; left: 42%;" role="button" tabindex="0">
                            <div class="purok-pin-tooltip">Explore Purok 6 Map</div>
                            <div class="purok-pin-badge">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Purok 6</span>
                            </div>
                            <div class="purok-pin-pointer"></div>
                            <div class="purok-pin-dot">
                                <div class="purok-pin-pulse"></div>
                            </div>
                        </div>

                        <div class="purok-map-pin" data-purok="7" data-img="public/image/Purok/Purok 7.jpg?v=2" style="top: 61%; left: 63%;" role="button" tabindex="0">
                            <div class="purok-pin-tooltip">Explore Purok 7 Map</div>
                            <div class="purok-pin-badge">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Purok 7</span>
                            </div>
                            <div class="purok-pin-pointer"></div>
                            <div class="purok-pin-dot">
                                <div class="purok-pin-pulse"></div>
                            </div>
                        </div>

                        <div class="purok-map-pin" data-purok="8" data-img="public/image/Purok/Purok 8.jpg?v=2" style="top: 48%; left: 77%;" role="button" tabindex="0">
                            <div class="purok-pin-tooltip">Explore Purok 8 Map</div>
                            <div class="purok-pin-badge">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Purok 8</span>
                            </div>
                            <div class="purok-pin-pointer"></div>
                            <div class="purok-pin-dot">
                                <div class="purok-pin-pulse"></div>
                            </div>
                        </div>

                        <div class="purok-map-pin" data-purok="9" data-img="public/image/Purok/Purok 9.jpg?v=2" style="top: 76%; left: 61%;" role="button" tabindex="0">
                            <div class="purok-pin-tooltip">Explore Purok 9 Map</div>
                            <div class="purok-pin-badge">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Purok 9</span>
                            </div>
                            <div class="purok-pin-pointer"></div>
                            <div class="purok-pin-dot">
                                <div class="purok-pin-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Landmark Pins Layer on Aerial View -->
                    <div id="legend-pins-container" class="legend-pins-layer" style="display: none;">
                        <?php 
                        foreach ($map_legends as $leg_pin): 
                            $l_id = $leg_pin['id'] ?? '';
                            $l_name = $leg_pin['name'] ?? '';
                            $l_icon = !empty($leg_pin['icon_path']) ? trim($leg_pin['icon_path']) : 'bi-geo-alt-fill';
                            $l_color = !empty($leg_pin['color_hex']) ? htmlspecialchars($leg_pin['color_hex']) : '#6366f1';
                            $l_photo = !empty($leg_pin['image_path']) ? $leg_pin['image_path'] : '';
                            if (!empty($l_photo) && strpos($l_photo, 'uploads/') === 0) {
                                $l_photo_url = 'admin/' . $l_photo;
                            } else {
                                $l_photo_url = $l_photo;
                            }
                            $l_top = !empty($leg_pin['top_position']) ? (float)$leg_pin['top_position'] : 50.00;
                            $l_left = !empty($leg_pin['left_position']) ? (float)$leg_pin['left_position'] : 50.00;
                            $l_w = !empty($leg_pin['marker_width']) ? (int)$leg_pin['marker_width'] : 40;
                            $l_h = !empty($leg_pin['marker_height']) ? (int)$leg_pin['marker_height'] : 40;

                            $l_is_img = (preg_match('/\.(png|jpe?g|gif|svg|webp)$/i', $l_icon) || strpos($l_icon, '/') !== false);
                            if ($l_is_img && strpos($l_icon, 'admin/') !== 0 && strpos($l_icon, 'public/') !== 0) {
                                $l_img_src = 'admin/' . $l_icon;
                            } else {
                                $l_img_src = $l_icon;
                            }
                        ?>
                        <div class="legend-map-landmark-pin" 
                             id="legend-pin-<?= htmlspecialchars($l_id) ?>"
                             data-legend-id="<?= htmlspecialchars($l_id) ?>"
                             style="position: absolute; top: <?= $l_top ?>%; left: <?= $l_left ?>%;" 
                             title="Click to view photo: <?= htmlspecialchars($l_name) ?>"
                             onclick="openLandmarkModal('<?= htmlspecialchars(addslashes($l_name)) ?>', '<?= htmlspecialchars(addslashes($l_photo_url)) ?>', '<?= htmlspecialchars(addslashes($l_icon)) ?>', '<?= htmlspecialchars(addslashes($l_color)) ?>', '<?= htmlspecialchars(addslashes($l_id)) ?>')">
                            <div class="legend-pin-label">
                                <span><?= htmlspecialchars($l_name) ?></span>
                                <?php if (!empty($l_photo)): ?>
                                    <i class="bi bi-camera-fill" style="color: #ffc107; font-size: 0.65rem;" title="Has photo"></i>
                                <?php endif; ?>
                            </div>
                            <?php if ($l_is_img): ?>
                                <img src="<?= htmlspecialchars($l_img_src) ?>" alt="<?= htmlspecialchars($l_name) ?>" style="width: <?= $l_w ?>px; height: <?= $l_h ?>px; object-fit: contain; filter: drop-shadow(0 3px 6px rgba(0,0,0,0.4));">
                            <?php else: 
                                $l_bi_cls = strpos($l_icon, 'bi-') === 0 ? $l_icon : ('bi-' . ltrim($l_icon, 'bi '));
                            ?>
                                <div class="legend-pin-circle" style="background: <?= $l_color ?>; width: <?= max(34, min(48, $l_w)) ?>px; height: <?= max(34, min(48, $l_h)) ?>px;">
                                    <i class="bi <?= htmlspecialchars($l_bi_cls) ?>"></i>
                                </div>
                                <div class="legend-pin-pointer" style="border-top-color: <?= $l_color ?>;"></div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <img id="spot-map-img" src="public/image/Maruing Map/Philippines.png" alt="Spot Map of Barangay Maruing" style="width: 100%; height: 850px !important; max-height: none !important; object-fit: fill !important; border-radius: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); cursor: default;">
                    
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
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #64748b; font-size: 0.9rem;">Spouse Name</span>
                        <strong id="modal-spouse" style="color: #334155;">-</strong>
                    </div>
                    <div style="border-top: 1px dashed #e2e8f0; padding-top: 10px; margin-top: 10px;">
                        <span style="color: #64748b; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Children / Dependents</span>
                        <div id="modal-children-list" style="display: flex; flex-direction: column; gap: 4px; max-height: 130px; overflow-y: auto;">
                            <!-- Populated dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Landmark Photo Modal (Viewed when legend or map icon is clicked) -->
        <div id="landmark-photo-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.72); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(6px); padding: 20px;">
            <div style="background: white; border-radius: 20px; width: 95%; max-width: 780px; box-shadow: 0 25px 60px rgba(0,0,0,0.4); overflow: hidden; position: relative; animation: slideInUp 0.3s ease-out;">
                <!-- Header -->
                <div style="background: #093d25; color: white; padding: 20px 28px; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div id="landmark-modal-icon-badge" style="width: 44px; height: 44px; border-radius: 50%; background: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; color: #ffffff; border: 2.5px solid rgba(255,255,255,0.4); flex-shrink: 0; box-shadow: 0 3px 10px rgba(0,0,0,0.25);">
                            <i class="bi bi-building-fill" id="landmark-modal-icon"></i>
                        </div>
                        <div>
                            <h3 id="landmark-modal-title" style="margin: 0; font-size: 1.4rem; font-weight: 700; color: #ffffff; font-family: Georgia, serif; letter-spacing: 0.02em;">Barangay Hall</h3>
                            <span style="font-size: 0.85rem; color: #ffc107; font-weight: 600; letter-spacing: 0.03em;">Barangay Maruing Landmark</span>
                        </div>
                    </div>
                    <button type="button" id="close-landmark-modal-btn" onclick="closeLandmarkModal()" style="background: none; border: none; font-size: 2rem; color: rgba(255,255,255,0.85); cursor: pointer; line-height: 1; transition: color 0.2s ease;" title="Close Modal">&times;</button>
                </div>

                <!-- Photo Display Area -->
                <div style="padding: 24px; background: #0f172a; text-align: center; position: relative;">
                    <img id="landmark-modal-photo" src="" alt="Landmark Photo" style="width: 100%; max-height: 520px; object-fit: contain; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.6); display: none;">
                    <div id="landmark-modal-empty" style="padding: 60px 20px; color: #94a3b8; display: none;">
                        <i class="bi bi-camera-slash" style="font-size: 4rem; display: block; margin-bottom: 14px; color: #64748b;"></i>
                        <p style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #cbd5e1;">No photo has been uploaded for this landmark yet.</p>
                        <small style="color: #94a3b8; font-size: 0.88rem; display: block; margin-top: 6px;">Admin can upload the actual building photo in the Admin Panel.</small>
                    </div>
                </div>

                <!-- Footer -->
                <div style="background: #f8fafc; padding: 16px 28px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; flex-wrap: wrap; gap: 12px;">
                    <span style="font-size: 0.9rem; color: #64748b; font-weight: 500;"><i class="bi bi-geo-alt-fill" style="color: #093d25;"></i> Barangay Maruing Spot Map</span>
                    <div style="display: flex; gap: 10px;">
                        <button type="button" id="btn-modal-locate" onclick="locateAndHighlightCurrentLandmark()" style="background: linear-gradient(135deg, #093d25 0%, #155734 100%); color: #ffffff; border: 1px solid rgba(255, 193, 7, 0.45); padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 7px; box-shadow: 0 3px 10px rgba(9, 61, 37, 0.2); transition: all 0.2s ease;" title="View exact location on Aerial Map">
                            <i class="bi bi-pin-map-fill" style="color: #ffc107;"></i> Show on Aerial Map
                        </button>
                        <button type="button" onclick="closeLandmarkModal()" style="background: #e2e8f0; color: #334155; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.2s ease;">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let currentLandmarkId = null;

            function openLandmarkModal(name, photoUrl, iconClass, colorHex, legendId) {
                currentLandmarkId = legendId || null;
                const modal = document.getElementById('landmark-photo-modal');
                const title = document.getElementById('landmark-modal-title');
                const photo = document.getElementById('landmark-modal-photo');
                const empty = document.getElementById('landmark-modal-empty');
                const badge = document.getElementById('landmark-modal-icon-badge');
                const icon = document.getElementById('landmark-modal-icon');

                if (!modal) return;

                title.textContent = name;
                if (badge) badge.style.background = colorHex || '#6366f1';

                const isImg = (iconClass && (/\.(png|jpe?g|gif|svg|webp)$/i.test(iconClass) || iconClass.indexOf('/') !== -1));
                if (badge) {
                    if (isImg) {
                        const imgSrc = (iconClass.indexOf('admin/') !== 0 && iconClass.indexOf('public/') !== 0) ? ('admin/' + iconClass) : iconClass;
                        badge.innerHTML = '<img src="' + imgSrc + '" style="width: 22px; height: 22px; object-fit: contain;" alt="Icon">';
                    } else {
                        const biCls = (iconClass && (iconClass.startsWith('bi-') || iconClass.startsWith('bi '))) 
                            ? (iconClass.startsWith('bi-') ? iconClass : 'bi-' + iconClass.replace('bi ', ''))
                            : 'bi-geo-alt-fill';
                        badge.innerHTML = '<i class="bi ' + biCls + '" id="landmark-modal-icon"></i>';
                    }
                }

                if (photoUrl && photoUrl.trim() !== '') {
                    photo.src = photoUrl;
                    photo.style.display = 'block';
                    empty.style.display = 'none';
                } else {
                    photo.style.display = 'none';
                    empty.style.display = 'block';
                }

                modal.style.display = 'flex';
            }

            function closeLandmarkModal() {
                const modal = document.getElementById('landmark-photo-modal');
                if (modal) modal.style.display = 'none';
            }

            function locateAndHighlightCurrentLandmark() {
                closeLandmarkModal();
                if (currentLandmarkId && typeof window.locateLandmarkOnMap === 'function') {
                    window.locateLandmarkOnMap(currentLandmarkId);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('landmark-photo-modal');
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) closeLandmarkModal();
                    });
                }
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeLandmarkModal();
                });
            });
        </script>

        <style>
            @keyframes slideInUp {
                from { transform: translateY(30px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            .spot-map-legend-item-clickable:hover {
                background: #f1f5f9;
                transform: translateY(-2px);
                box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            }
            .legend-map-landmark-pin:hover {
                transform: translate(-50%, -108%) scale(1.15) !important;
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

    <script src="public/assets/js/spotmap.js?v=5"></script>
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


