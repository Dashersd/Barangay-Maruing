<?php
// admin/mediagallery.php
require_once 'db_connect.php';

// Auto-check / create tables if not present
$conn->query("CREATE TABLE IF NOT EXISTS `gallery_folders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

$conn->query("CREATE TABLE IF NOT EXISTS `gallery_media` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `folder_id` INT NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(255) NULL,
    `file_size` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (`folder_id`),
    CONSTRAINT `fk_gallery_folder` FOREIGN KEY (`folder_id`) REFERENCES `gallery_folders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

$upload_base_dir = 'uploads/gallery/';
if (!is_dir($upload_base_dir)) {
    mkdir($upload_base_dir, 0777, true);
}

// -------------------------------------------------------------
// POST Request Handling
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // 1. Create Folder
    if ($action === 'create_folder') {
        $folder_name = trim($_POST['folder_name'] ?? '');
        $folder_desc = trim($_POST['folder_description'] ?? '');

        if (!empty($folder_name)) {
            $stmt = $conn->prepare("INSERT INTO gallery_folders (name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $folder_name, $folder_desc);
            if ($stmt->execute()) {
                header("Location: mediagallery.php?msg=folder_created");
                exit;
            } else {
                header("Location: mediagallery.php?error=create_failed");
                exit;
            }
        } else {
            header("Location: mediagallery.php?error=empty_folder_name");
            exit;
        }
    }

    // 2. Edit / Rename Folder
    if ($action === 'edit_folder') {
        $folder_id = intval($_POST['folder_id'] ?? 0);
        $folder_name = trim($_POST['folder_name'] ?? '');
        $folder_desc = trim($_POST['folder_description'] ?? '');

        if ($folder_id > 0 && !empty($folder_name)) {
            $stmt = $conn->prepare("UPDATE gallery_folders SET name = ?, description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $folder_name, $folder_desc, $folder_id);
            $stmt->execute();
            header("Location: mediagallery.php?folder_id=" . $folder_id . "&msg=folder_updated");
            exit;
        }
    }

    // 3. Delete Folder
    if ($action === 'delete_folder') {
        $folder_id = intval($_POST['folder_id'] ?? 0);
        if ($folder_id > 0) {
            // Delete physical media files inside this folder
            $stmt_media = $conn->prepare("SELECT file_path FROM gallery_media WHERE folder_id = ?");
            $stmt_media->bind_param("i", $folder_id);
            $stmt_media->execute();
            $res = $stmt_media->get_result();
            while ($row = $res->fetch_assoc()) {
                if (file_exists($row['file_path'])) {
                    @unlink($row['file_path']);
                }
            }

            // Delete folder record (gallery_media will cascade delete)
            $stmt_del = $conn->prepare("DELETE FROM gallery_folders WHERE id = ?");
            $stmt_del->bind_param("i", $folder_id);
            $stmt_del->execute();

            header("Location: mediagallery.php?msg=folder_deleted");
            exit;
        }
    }

    // 4. Upload Images into Folder
    if ($action === 'upload_media') {
        $folder_id = intval($_POST['folder_id'] ?? 0);
        $caption = trim($_POST['caption'] ?? '');

        if ($folder_id > 0 && isset($_FILES['media_files']) && !empty($_FILES['media_files']['name'][0])) {
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $uploaded_count = 0;
            $files = $_FILES['media_files'];
            $file_count = count($files['name']);

            for ($i = 0; $i < $file_count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $orig_name = $files['name'][$i];
                    $file_size = $files['size'][$i];
                    $tmp_name = $files['tmp_name'][$i];

                    $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
                    if (in_array($ext, $allowed_extensions)) {
                        $sanitized = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', pathinfo($orig_name, PATHINFO_FILENAME));
                        $new_filename = time() . '_' . uniqid() . '_' . $sanitized . '.' . $ext;
                        $destination = $upload_base_dir . $new_filename;

                        if (move_uploaded_file($tmp_name, $destination)) {
                            $item_caption = !empty($caption) ? $caption : pathinfo($orig_name, PATHINFO_FILENAME);
                            $stmt_ins = $conn->prepare("INSERT INTO gallery_media (folder_id, file_path, file_name, caption, file_size) VALUES (?, ?, ?, ?, ?)");
                            $stmt_ins->bind_param("isssi", $folder_id, $destination, $new_filename, $item_caption, $file_size);
                            $stmt_ins->execute();
                            $uploaded_count++;
                        }
                    }
                }
            }

            if ($uploaded_count > 0) {
                header("Location: mediagallery.php?folder_id=" . $folder_id . "&msg=uploaded&count=" . $uploaded_count);
                exit;
            } else {
                header("Location: mediagallery.php?folder_id=" . $folder_id . "&error=no_valid_images");
                exit;
            }
        }
    }

    // 5. Delete Single Media Item
    if ($action === 'delete_media') {
        $media_id = intval($_POST['media_id'] ?? 0);
        $folder_id = intval($_POST['folder_id'] ?? 0);

        if ($media_id > 0) {
            $stmt = $conn->prepare("SELECT file_path FROM gallery_media WHERE id = ?");
            $stmt->bind_param("i", $media_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                if (file_exists($row['file_path'])) {
                    @unlink($row['file_path']);
                }
                $del_stmt = $conn->prepare("DELETE FROM gallery_media WHERE id = ?");
                $del_stmt->bind_param("i", $media_id);
                $del_stmt->execute();
            }

            header("Location: mediagallery.php?folder_id=" . $folder_id . "&msg=media_deleted");
            exit;
        }
    }
}

// -------------------------------------------------------------
// View Determination: Root View vs. Inside Folder View
// -------------------------------------------------------------
$current_folder_id = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : 0;
$current_folder = null;
$folder_media = [];

if ($current_folder_id > 0) {
    // Inside Folder View
    $stmt = $conn->prepare("SELECT * FROM gallery_folders WHERE id = ?");
    $stmt->bind_param("i", $current_folder_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $current_folder = $res->fetch_assoc();

    if ($current_folder) {
        $stmt_media = $conn->prepare("SELECT * FROM gallery_media WHERE folder_id = ? ORDER BY id DESC");
        $stmt_media->bind_param("i", $current_folder_id);
        $stmt_media->execute();
        $media_res = $stmt_media->get_result();
        while ($m = $media_res->fetch_assoc()) {
            $folder_media[] = $m;
        }
    } else {
        header("Location: mediagallery.php");
        exit;
    }
} else {
    // Root View: Fetch all folders with photo counts
    $folders_query = "SELECT f.*, 
                             COUNT(m.id) AS total_photos
                      FROM gallery_folders f
                      LEFT JOIN gallery_media m ON f.id = m.folder_id
                      GROUP BY f.id
                      ORDER BY f.created_at ASC";
    $folders_result = $conn->query($folders_query);
}

// Flash messages
$msg = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $current_folder ? htmlspecialchars($current_folder['name']) . " | " : "" ?>Manage Media Gallery | Barangay Maruing</title>
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
    <link rel="stylesheet" href="Admin CSS/mediagallery.css?v=4">
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
            <li><a href="about.php"><i class="bi bi-info-circle-fill"></i> About</a></li>
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
            <li><a href="mediagallery.php" class="active"><i class="bi bi-image-fill"></i> Media Gallery</a></li>
            <li><a href="services.php"><i class="bi bi-card-list"></i> Services</a></li>
            <li><a href="announcements.php"><i class="bi bi-megaphone-fill"></i> Announcements</a></li>
            <li><a href="#"><i class="bi bi-telephone-fill"></i> Contact</a></li>
            <li><a href="feedback_chat.php"><i class="bi bi-chat-dots-fill"></i> Feedback chat</a></li>
            <li><a href="settings.php"><i class="bi bi-gear-fill"></i> Settings</a></li>
            <li><a href="logout.php" class="logout-link" style="color: #ef4444 !important;"><i class="bi bi-box-arrow-left" style="color: #ef4444 !important;"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-title">
                <h2>Manage Media Gallery</h2>
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

            <!-- Alerts Notification -->
            <?php if ($msg): ?>
                <div class="alert-banner alert-success" id="statusAlert">
                    <div class="alert-text">
                        <i class="bi bi-check-circle-fill"></i>
                        <?php 
                            if ($msg === 'folder_created') echo "Folder created successfully!";
                            elseif ($msg === 'folder_updated') echo "Folder details updated successfully!";
                            elseif ($msg === 'folder_deleted') echo "Folder and all its contents were deleted.";
                            elseif ($msg === 'uploaded') echo (isset($_GET['count']) ? intval($_GET['count']) : "") . " Image(s) uploaded successfully!";
                            elseif ($msg === 'media_deleted') echo "Image deleted successfully.";
                            else echo "Action completed successfully.";
                        ?>
                    </div>
                    <button type="button" class="btn-close-alert" onclick="document.getElementById('statusAlert').remove();">&times;</button>
                </div>
            <?php elseif ($error): ?>
                <div class="alert-banner alert-error" id="statusAlert">
                    <div class="alert-text">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <?php 
                            if ($error === 'empty_folder_name') echo "Please provide a valid folder name.";
                            elseif ($error === 'no_valid_images') echo "No valid image files were uploaded.";
                            else echo "An error occurred. Please try again.";
                        ?>
                    </div>
                    <button type="button" class="btn-close-alert" onclick="document.getElementById('statusAlert').remove();">&times;</button>
                </div>
            <?php endif; ?>

            <?php if ($current_folder): ?>
                <!-- ======================================================== -->
                <!-- INSIDE FOLDER VIEW -->
                <!-- ======================================================== -->
                
                <!-- Breadcrumbs & Navigation -->
                <div class="gallery-nav-bar">
                    <div class="breadcrumbs">
                        <a href="mediagallery.php" class="breadcrumb-item"><i class="bi bi-folder-fill"></i> Media Library</a>
                        <span class="breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
                        <span class="breadcrumb-current"><?= htmlspecialchars($current_folder['name']) ?></span>
                    </div>
                    <a href="mediagallery.php" class="btn-back-folders">
                        <i class="bi bi-arrow-left"></i> Back to Folders
                    </a>
                </div>

                <!-- Folder Header Row -->
                <div class="category-header-card folder-detail-header-card">
                    <div class="category-header-info">
                        <div class="folder-title-wrap">
                            <h2 class="category-main-title"><i class="bi bi-folder2-open folder-title-icon"></i> <?= htmlspecialchars($current_folder['name']) ?></h2>
                            <span class="badge-count"><?= count($folder_media) ?> <?= count($folder_media) === 1 ? 'Photo' : 'Photos' ?></span>
                        </div>
                        <?php if (!empty($current_folder['description'])): ?>
                            <p class="category-sub-title"><?= htmlspecialchars($current_folder['description']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="folder-action-buttons">
                        <button type="button" class="btn-secondary-action" onclick="openEditFolderModal(<?= $current_folder['id'] ?>, '<?= htmlspecialchars(addslashes($current_folder['name'])) ?>', '<?= htmlspecialchars(addslashes($current_folder['description'] ?? '')) ?>')">
                            <i class="bi bi-pencil-square"></i> Rename Folder
                        </button>
                        <button type="button" class="btn-danger-action" onclick="confirmDeleteFolder(<?= $current_folder['id'] ?>, '<?= htmlspecialchars(addslashes($current_folder['name'])) ?>')">
                            <i class="bi bi-trash3"></i> Delete Folder
                        </button>
                        <button type="button" class="btn-create-folder" onclick="openModal('uploadMediaModal')">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Upload Images
                        </button>
                    </div>
                </div>

                <!-- Media Grid Container -->
                <div class="media-container-white">
                    <?php if (count($folder_media) > 0): ?>
                        <div class="media-grid">
                            <?php foreach ($folder_media as $media): ?>
                                <div class="media-card">
                                    <div class="media-thumb-wrap" onclick="viewLightbox('<?= htmlspecialchars($media['file_path']) ?>', '<?= htmlspecialchars(addslashes($media['caption'] ?? $media['file_name'])) ?>')">
                                        <img src="<?= htmlspecialchars($media['file_path']) ?>" alt="<?= htmlspecialchars($media['caption'] ?? 'Media') ?>" loading="lazy">
                                        <div class="media-overlay">
                                            <span class="overlay-btn preview-btn" title="View Fullscreen"><i class="bi bi-zoom-in"></i></span>
                                        </div>
                                    </div>
                                    <div class="media-card-footer">
                                        <div class="media-caption" title="<?= htmlspecialchars($media['caption'] ?? $media['file_name']) ?>">
                                            <?= htmlspecialchars($media['caption'] ?? $media['file_name']) ?>
                                        </div>
                                        <div class="media-meta">
                                            <span><?= date('M d, Y', strtotime($media['created_at'])) ?></span>
                                            <button type="button" class="btn-delete-icon" title="Delete Image" onclick="confirmDeleteMedia(<?= $media['id'] ?>, <?= $current_folder['id'] ?>)">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <!-- Empty Folder State -->
                        <div class="empty-state">
                            <div class="empty-icon-box">
                                <i class="bi bi-images"></i>
                            </div>
                            <h3>No photos in this folder yet</h3>
                            <p>Upload photos to categorize them under "<strong><?= htmlspecialchars($current_folder['name']) ?></strong>".</p>
                            <button type="button" class="btn-create-folder" onclick="openModal('uploadMediaModal')">
                                <i class="bi bi-cloud-arrow-up-fill"></i> Upload Images
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <!-- ======================================================== -->
                <!-- ROOT VIEW: FOLDERS / CATEGORIES (MATCHING SCREENSHOT)   -->
                <!-- ======================================================== -->

                <!-- Top Header Card -->
                <div class="category-header-card">
                    <div class="category-header-info">
                        <h2 class="category-main-title">Media Categories & Folders</h2>
                        <p class="category-sub-title">Organize photos into folders (e.g., Meeting, Sports, Community Events).</p>
                    </div>
                    <button class="btn-create-folder" onclick="openModal('createFolderModal')">
                        <i class="bi bi-folder-plus"></i> + Create Folder
                    </button>
                </div>

                <!-- Folders Grid -->
                <?php if ($folders_result && $folders_result->num_rows > 0): ?>
                    <div class="category-cards-grid">
                        <?php while ($f = $folders_result->fetch_assoc()): ?>
                            <div class="category-folder-card">
                                <!-- Top Folder Icon Badge -->
                                <div class="folder-icon-badge">
                                    <i class="bi bi-folder-fill"></i>
                                </div>

                                <!-- Folder Title -->
                                <a href="mediagallery.php?folder_id=<?= $f['id'] ?>" class="folder-card-title-link">
                                    <h3 class="folder-card-title"><?= htmlspecialchars($f['name']) ?></h3>
                                </a>

                                <!-- Folder Description -->
                                <p class="folder-card-desc">
                                    <?= !empty($f['description']) ? htmlspecialchars($f['description']) : 'No description provided' ?>
                                </p>

                                <!-- Dashed Divider -->
                                <div class="card-dashed-divider"></div>

                                <!-- Meta Row: Photo Count & Date -->
                                <div class="folder-card-meta">
                                    <span class="photo-count-pill">
                                        <i class="bi bi-image"></i> <?= $f['total_photos'] ?> <?= $f['total_photos'] == 1 ? 'Photo' : 'Photos' ?>
                                    </span>
                                    <span class="folder-created-date">
                                        <?= date('M d, Y', strtotime($f['created_at'])) ?>
                                    </span>
                                </div>

                                <!-- Card Footer: Open Album & Trash Icon -->
                                <div class="folder-card-footer">
                                    <a href="mediagallery.php?folder_id=<?= $f['id'] ?>" class="btn-open-album">
                                        Open Album <i class="bi bi-chevron-right"></i>
                                    </a>
                                    <button type="button" class="btn-card-trash" title="Delete Folder" onclick="confirmDeleteFolder(<?= $f['id'] ?>, '<?= htmlspecialchars(addslashes($f['name'])) ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <!-- Empty Root State -->
                    <div class="media-container-white">
                        <div class="empty-state">
                            <div class="empty-icon-box">
                                <i class="bi bi-folder-plus"></i>
                            </div>
                            <h3>No folders created yet</h3>
                            <p>Organize your media library by creating category folders like "Meetings", "Sports", or "Events".</p>
                            <button type="button" class="btn-create-folder" onclick="openModal('createFolderModal')">
                                <i class="bi bi-folder-plus"></i> + Create Folder
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </main>
    </div>

    <!-- ======================================================== -->
    <!-- MODALS -->
    <!-- ======================================================== -->

    <!-- 1. Create Folder Modal -->
    <div id="createFolderModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="bi bi-folder-plus text-success"></i> Create New Folder</h2>
                <span class="close-modal" onclick="closeModal('createFolderModal')">&times;</span>
            </div>
            <form action="mediagallery.php" method="POST" id="createFolderForm">
                <input type="hidden" name="action" value="create_folder">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="newFolderName">Folder Name <span class="text-danger">*</span></label>
                        <input type="text" id="newFolderName" name="folder_name" class="form-control" required placeholder="e.g., Foods, Meeting, Sports">
                        <small class="form-hint">Choose a descriptive category or event name.</small>
                    </div>
                    <div class="form-group">
                        <label for="newFolderDesc">Description (Optional)</label>
                        <textarea id="newFolderDesc" name="folder_description" class="form-control" rows="3" placeholder="Briefly describe what photos this folder contains..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('createFolderModal')">Cancel</button>
                    <button type="submit" class="btn-submit"><i class="bi bi-check-lg"></i> Create Folder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Edit / Rename Folder Modal -->
    <div id="editFolderModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="bi bi-pencil-square text-success"></i> Edit Folder Details</h2>
                <span class="close-modal" onclick="closeModal('editFolderModal')">&times;</span>
            </div>
            <form action="mediagallery.php" method="POST" id="editFolderForm">
                <input type="hidden" name="action" value="edit_folder">
                <input type="hidden" name="folder_id" id="editFolderId" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editFolderName">Folder Name <span class="text-danger">*</span></label>
                        <input type="text" id="editFolderName" name="folder_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editFolderDesc">Description</label>
                        <textarea id="editFolderDesc" name="folder_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('editFolderModal')">Cancel</button>
                    <button type="submit" class="btn-submit"><i class="bi bi-check-lg"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. Upload Images Modal (Inside Folder) -->
    <?php if ($current_folder): ?>
    <div id="uploadMediaModal" class="modal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2><i class="bi bi-cloud-arrow-up-fill text-success"></i> Upload Images to "<?= htmlspecialchars($current_folder['name']) ?>"</h2>
                <span class="close-modal" onclick="closeModal('uploadMediaModal')">&times;</span>
            </div>
            <form action="mediagallery.php" method="POST" enctype="multipart/form-data" id="uploadMediaForm">
                <input type="hidden" name="action" value="upload_media">
                <input type="hidden" name="folder_id" value="<?= $current_folder['id'] ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Photos <span class="text-danger">*</span></label>
                        <div class="upload-dropzone" onclick="document.getElementById('mediaFileInput').click()">
                            <i class="bi bi-cloud-arrow-up dropzone-icon"></i>
                            <p class="dropzone-text">Click or drag images here to select files</p>
                            <span class="dropzone-hint">Supports JPG, PNG, WEBP, and GIF (Multiple photos supported)</span>
                            <input type="file" id="mediaFileInput" name="media_files[]" multiple accept="image/*" class="file-input-hidden" required onchange="handleFileSelect(this)">
                        </div>
                        <div id="selectedFilesList" class="selected-files-list"></div>
                    </div>
                    <div class="form-group">
                        <label for="uploadCaption">Common Caption / Title (Optional)</label>
                        <input type="text" id="uploadCaption" name="caption" class="form-control" placeholder="e.g., Opening ceremony, Barangay Hall event...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('uploadMediaModal')">Cancel</button>
                    <button type="submit" class="btn-submit" id="btnSubmitUpload"><i class="bi bi-cloud-arrow-up-fill"></i> Start Upload</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- 4. Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="bi bi-exclamation-triangle-fill text-danger"></i> Confirm Deletion</h2>
                <span class="close-modal" onclick="closeModal('deleteConfirmModal')">&times;</span>
            </div>
            <form action="mediagallery.php" method="POST" id="deleteForm">
                <input type="hidden" name="action" id="deleteAction" value="">
                <input type="hidden" name="folder_id" id="deleteFolderId" value="">
                <input type="hidden" name="media_id" id="deleteMediaId" value="">
                <div class="modal-body">
                    <p id="deleteConfirmMessage" class="delete-msg">Are you sure you want to delete this item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('deleteConfirmModal')">Cancel</button>
                    <button type="submit" class="btn-danger-submit"><i class="bi bi-trash3"></i> Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. Lightbox Modal -->
    <div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
        <span class="lightbox-close">&times;</span>
        <div class="lightbox-container" onclick="event.stopPropagation()">
            <img src="" alt="Fullscreen preview" id="lightboxImg" class="lightbox-img">
            <div id="lightboxCaption" class="lightbox-caption"></div>
        </div>
    </div>

    <!-- JavaScript Handlers -->
    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        };

        function openEditFolderModal(id, name, desc) {
            document.getElementById('editFolderId').value = id;
            document.getElementById('editFolderName').value = name;
            document.getElementById('editFolderDesc').value = desc;
            openModal('editFolderModal');
        }

        function confirmDeleteFolder(id, name) {
            document.getElementById('deleteAction').value = 'delete_folder';
            document.getElementById('deleteFolderId').value = id;
            document.getElementById('deleteMediaId').value = '';
            document.getElementById('deleteConfirmMessage').innerHTML = 
                'Are you sure you want to delete the folder "<strong>' + name + '</strong>"? <br><span class="text-danger" style="font-size:0.85rem;">All images inside this folder will also be permanently removed.</span>';
            openModal('deleteConfirmModal');
        }

        function confirmDeleteMedia(mediaId, folderId) {
            document.getElementById('deleteAction').value = 'delete_media';
            document.getElementById('deleteFolderId').value = folderId;
            document.getElementById('deleteMediaId').value = mediaId;
            document.getElementById('deleteConfirmMessage').innerHTML = 
                'Are you sure you want to permanently delete this photo?';
            openModal('deleteConfirmModal');
        }

        function handleFileSelect(input) {
            const list = document.getElementById('selectedFilesList');
            if (!list) return;
            list.innerHTML = '';
            if (input.files && input.files.length > 0) {
                const countBadge = document.createElement('div');
                countBadge.className = 'selected-count';
                countBadge.innerHTML = '<i class="bi bi-check2-circle"></i> ' + input.files.length + ' file(s) selected:';
                list.appendChild(countBadge);

                const ul = document.createElement('ul');
                ul.className = 'file-preview-list';
                for (let i = 0; i < Math.min(input.files.length, 5); i++) {
                    const li = document.createElement('li');
                    li.textContent = input.files[i].name + ' (' + (input.files[i].size / 1024).toFixed(1) + ' KB)';
                    ul.appendChild(li);
                }
                if (input.files.length > 5) {
                    const more = document.createElement('li');
                    more.style.fontStyle = 'italic';
                    more.textContent = '...and ' + (input.files.length - 5) + ' more photo(s)';
                    ul.appendChild(more);
                }
                list.appendChild(ul);
            }
        }

        // Lightbox Functions
        function viewLightbox(src, caption) {
            const lightbox = document.getElementById('lightboxModal');
            const img = document.getElementById('lightboxImg');
            const cap = document.getElementById('lightboxCaption');
            if (lightbox && img) {
                img.src = src;
                cap.textContent = caption || '';
                lightbox.style.display = 'flex';
            }
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightboxModal');
            if (lightbox) {
                lightbox.style.display = 'none';
            }
        }

        // Escape key to close modals/lightbox
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
                document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
            }
        });
    </script>
</body>
</html>
