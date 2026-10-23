<?php
// admin/about.php
require_once 'db_connect.php';

$success_msg = '';
$error_msg = '';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $history = trim($_POST['history'] ?? '');
    $vision = trim($_POST['vision'] ?? '');
    $mission = trim($_POST['mission'] ?? '');

    // Get current image path
    $current_image = null;
    $res = $conn->query("SELECT image_path FROM about_content WHERE id = 1");
    if ($res && $row = $res->fetch_assoc()) {
        $current_image = $row['image_path'];
    }

    $image_path = $current_image;

    // Check if new image was uploaded
    if (isset($_FILES['about_image']) && $_FILES['about_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['about_image']['tmp_name'];
        $file_name = $_FILES['about_image']['name'];
        $file_size = $_FILES['about_image']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($ext, $allowed_exts)) {
            // Verify real MIME type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file_tmp);
            finfo_close($finfo);
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

            if (in_array($mime, $allowed_mimes)) {
                $upload_dir = 'uploads/about/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $clean_name = 'about_' . time() . '_' . uniqid() . '.' . $ext;
                $target_path = $upload_dir . $clean_name;

                if (move_uploaded_file($file_tmp, $target_path)) {
                    // Delete previous custom uploaded image file if present in uploads/about/
                    if (!empty($current_image) && strpos($current_image, 'uploads/about/') !== false && file_exists($current_image)) {
                        @unlink($current_image);
                    }
                    $image_path = $target_path;
                } else {
                    $error_msg = "Failed to save the uploaded image.";
                }
            } else {
                $error_msg = "Invalid image file format detected.";
            }
        } else {
            $error_msg = "Only JPG, PNG, WEBP, and GIF images are allowed.";
        }
    }

    if (empty($error_msg)) {
        // Ensure record 1 exists or update it
        $check = $conn->query("SELECT id FROM about_content WHERE id = 1");
        if ($check && $check->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE about_content SET history = ?, vision = ?, mission = ?, image_path = ? WHERE id = 1");
            $stmt->bind_param("ssss", $history, $vision, $mission, $image_path);
            if ($stmt->execute()) {
                header("Location: about.php?msg=updated");
                exit();
            } else {
                $error_msg = "Error updating content: " . $conn->error;
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO about_content (id, history, vision, mission, image_path) VALUES (1, ?, ?, ?, ?)");
            $stmt->bind_param("ssss", $history, $vision, $mission, $image_path);
            if ($stmt->execute()) {
                header("Location: about.php?msg=updated");
                exit();
            } else {
                $error_msg = "Error inserting content: " . $conn->error;
            }
            $stmt->close();
        }
    }
}

// Check GET status alerts
if (isset($_GET['msg']) && $_GET['msg'] === 'updated') {
    $success_msg = "About Us content has been successfully updated!";
}

// Fetch current active content
$about = [
    'history' => '',
    'vision' => '',
    'mission' => '',
    'image_path' => 'public/image/Barangay Maruing Community Hall.png'
];

$res = $conn->query("SELECT * FROM about_content WHERE id = 1 LIMIT 1");
if ($res && $row = $res->fetch_assoc()) {
    $about = $row;
}

// Compute image source path for preview in admin directory
$preview_src = '';
if (!empty($about['image_path'])) {
    if (strpos($about['image_path'], 'public/') === 0) {
        $preview_src = '../' . $about['image_path'];
    } else {
        $preview_src = $about['image_path'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage About Us | Barangay Maruing</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/assets/css/style.css">
    <link rel="stylesheet" href="Admin CSS/admin.css?v=2">
    <link rel="stylesheet" href="Admin CSS/about.css?v=2">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo">
            <h3>Admin Panel</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
            <li><a href="about.php" class="active"><i class="bi bi-info-circle-fill"></i> About</a></li>
            <li class="has-submenu">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-people-fill"></i> Officials
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="barangayofficials.php">Barangay Officials</a></li>
                    <li><a href="SKofficials.php">SK Officials</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-geo-alt-fill"></i> Spot Map
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="legend.php">Legend</a></li>
                    <li><a href="purok 1.php">Purok 1</a></li>
                    <li><a href="purok 2.php">Purok 2</a></li>
                    <li><a href="purok 3.php">Purok 3</a></li>
                    <li><a href="purok 4.php">Purok 4</a></li>
                    <li><a href="purok 5.php">Purok 5</a></li>
                    <li><a href="purok 6.php">Purok 6</a></li>
                    <li><a href="purok 7.php">Purok 7</a></li>
                    <li><a href="purok 8.php">Purok 8</a></li>
                    <li><a href="purok 9.php">Purok 9</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-house-door-fill"></i> Households
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="legends file.php">Legends File</a></li>
                    <li><a href="resident 1.php">Resident 1</a></li>
                    <li><a href="resident 2.php">Resident 2</a></li>
                    <li><a href="resident 3.php">Resident 3</a></li>
                    <li><a href="resident 4.php">Resident 4</a></li>
                    <li><a href="resident 5.php">Resident 5</a></li>
                    <li><a href="resident 6.php">Resident 6</a></li>
                    <li><a href="resident 7.php">Resident 7</a></li>
                    <li><a href="resident 8.php">Resident 8</a></li>
                    <li><a href="resident 9.php">Resident 9</a></li>
                </ul>
            </li>
            <li><a href="mediagallery.php"><i class="bi bi-image-fill"></i> Media Gallery</a></li>
            <li><a href="services.php"><i class="bi bi-card-list"></i> Services</a></li>
            <li><a href="announcements.php"><i class="bi bi-megaphone-fill"></i> Announcements</a></li>
            <li><a href="#"><i class="bi bi-telephone-fill"></i> Contact</a></li>
            <li><a href="feedback_chat.php"><i class="bi bi-chat-dots-fill"></i> Feedback chat</a></li>
            <li><a href="settings.php"><i class="bi bi-gear-fill"></i> Settings</a></li>
            <li><a href="login.php" class="logout-link"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-title">
                <h2>Manage About Us</h2>
            </div>
            <div class="topbar-user">
                <div class="user-info">
                    <span class="user-name">System Admin</span>
                    <span class="user-role">Administrator</span>
                </div>
                <div class="user-avatar">
                    A
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="dashboard-content">
            <div class="page-header">
                <h3>About Us Content</h3>
                <p>Update the information displayed on the public About Us, Vision, and Mission pages.</p>
            </div>

            <!-- Notification Messages -->
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success" id="successAlert">
                    <div class="alert-content">
                        <i class="bi bi-check-circle-fill"></i>
                        <span><?= htmlspecialchars($success_msg) ?></span>
                    </div>
                    <button type="button" class="alert-close" onclick="document.getElementById('successAlert').remove();">&times;</button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger" id="errorAlert">
                    <div class="alert-content">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span><?= htmlspecialchars($error_msg) ?></span>
                    </div>
                    <button type="button" class="alert-close" onclick="document.getElementById('errorAlert').remove();">&times;</button>
                </div>
            <?php endif; ?>
            
            <form action="about.php" method="POST" enctype="multipart/form-data">
                <div class="about-grid">
                    <!-- Left Side: Image -->
                    <div class="left-col">
                        <div class="form-card h-100">
                            <label class="form-label" for="about_image">Barangay Hall / About Image</label>
                            <div class="image-dropzone">
                                <div class="dropzone-inner">
                                    <div class="image-preview-wrapper" id="previewContainer">
                                        <span class="preview-badge" id="previewBadge">Current Image</span>
                                        <img src="<?= htmlspecialchars($preview_src) ?>" alt="Barangay Hall Image Preview" id="aboutPreviewImg">
                                    </div>
                                    <input type="file" id="about_image" name="about_image" class="file-input-centered" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewAboutImage(this)">
                                    <p class="dropzone-help-text">Click the button above to upload a new JPG, PNG, or WEBP image.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Text Fields -->
                    <div class="right-col">
                        <div class="form-card mb-30">
                            <div class="form-group mb-0">
                                <label class="form-label" for="history">Barangay History</label>
                                <textarea id="history" name="history" class="form-control" rows="8" placeholder="Enter barangay history here..."><?= htmlspecialchars($about['history'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="form-card mb-30">
                            <div class="form-group mb-0">
                                <label class="form-label" for="vision">Vision</label>
                                <textarea id="vision" name="vision" class="form-control" rows="5" placeholder="Enter barangay vision here..."><?= htmlspecialchars($about['vision'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="form-card">
                            <div class="form-group mb-0">
                                <label class="form-label" for="mission">Mission</label>
                                <textarea id="mission" name="mission" class="form-control" rows="5" placeholder="Enter barangay mission here..."><?= htmlspecialchars($about['mission'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary"><i class="bi bi-save"></i> Save Changes</button>
                </div>
            </form>
        </main>
    </div>

    <!-- Client-side script for instant image preview -->
    <script>
        function previewAboutImage(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.getElementById('aboutPreviewImg');
                    const previewBadge = document.getElementById('previewBadge');
                    if (previewImg) {
                        previewImg.src = e.target.result;
                    }
                    if (previewBadge) {
                        previewBadge.textContent = 'New Image Selected';
                        previewBadge.style.backgroundColor = '#15803d';
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
