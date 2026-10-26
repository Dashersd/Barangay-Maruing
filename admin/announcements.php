<?php
// admin/announcements.php
require_once 'db_connect.php';

$success_msg = '';
$error_msg = '';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $title = trim($_POST['announcementTitle'] ?? '');
        $what = trim($_POST['announcementWhat'] ?? '');
        $when_date = trim($_POST['announcementWhen'] ?? '');
        $where_place = trim($_POST['announcementWhere'] ?? '');
        $who = trim($_POST['announcementWho'] ?? '');
        $why = trim($_POST['announcementWhy'] ?? '');
        $content = trim($_POST['announcementContent'] ?? '');
        $status = in_array($_POST['announcementStatus'] ?? '', ['published', 'draft']) ? $_POST['announcementStatus'] : 'published';

        $cover_image_path = null;
        if (isset($_FILES['announcementImage']) && $_FILES['announcementImage']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/announcements/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = pathinfo($_FILES['announcementImage']['name'], PATHINFO_EXTENSION);
            $clean_name = time() . '_' . uniqid() . '.' . preg_replace("/[^a-zA-Z0-9]/", "", $ext);
            $target = $upload_dir . $clean_name;
            if (move_uploaded_file($_FILES['announcementImage']['tmp_name'], $target)) {
                $cover_image_path = $target;
            }
        }

        if (!empty($title)) {
            $stmt = $conn->prepare("INSERT INTO announcements (title, what, when_date, where_place, who, why, content, cover_image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $title, $what, $when_date, $where_place, $who, $why, $content, $cover_image_path, $status);
            if ($stmt->execute()) {
                header("Location: announcements.php?msg=created");
                exit();
            } else {
                $error_msg = "Error creating announcement: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error_msg = "Title is required.";
        }
    }

    if ($action === 'update') {
        $id = (int)($_POST['announcementId'] ?? 0);
        $title = trim($_POST['announcementTitle'] ?? '');
        $what = trim($_POST['announcementWhat'] ?? '');
        $when_date = trim($_POST['announcementWhen'] ?? '');
        $where_place = trim($_POST['announcementWhere'] ?? '');
        $who = trim($_POST['announcementWho'] ?? '');
        $why = trim($_POST['announcementWhy'] ?? '');
        $content = trim($_POST['announcementContent'] ?? '');
        $status = in_array($_POST['announcementStatus'] ?? '', ['published', 'draft']) ? $_POST['announcementStatus'] : 'published';

        if ($id > 0 && !empty($title)) {
            $current_stmt = $conn->prepare("SELECT cover_image FROM announcements WHERE id = ?");
            $current_stmt->bind_param("i", $id);
            $current_stmt->execute();
            $res = $current_stmt->get_result();
            $current_record = $res->fetch_assoc();
            $current_stmt->close();

            $cover_image_path = $current_record['cover_image'] ?? null;

            if (isset($_FILES['announcementImage']) && $_FILES['announcementImage']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/announcements/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $ext = pathinfo($_FILES['announcementImage']['name'], PATHINFO_EXTENSION);
                $clean_name = time() . '_' . uniqid() . '.' . preg_replace("/[^a-zA-Z0-9]/", "", $ext);
                $target = $upload_dir . $clean_name;
                if (move_uploaded_file($_FILES['announcementImage']['tmp_name'], $target)) {
                    if (!empty($cover_image_path) && file_exists($cover_image_path)) {
                        @unlink($cover_image_path);
                    }
                    $cover_image_path = $target;
                }
            }

            $stmt = $conn->prepare("UPDATE announcements SET title = ?, what = ?, when_date = ?, where_place = ?, who = ?, why = ?, content = ?, cover_image = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sssssssssi", $title, $what, $when_date, $where_place, $who, $why, $content, $cover_image_path, $status, $id);
            if ($stmt->execute()) {
                header("Location: announcements.php?msg=updated");
                exit();
            } else {
                $error_msg = "Error updating announcement: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error_msg = "Invalid data or missing title.";
        }
    }

    if ($action === 'delete') {
        $id = (int)($_POST['announcementId'] ?? 0);
        if ($id > 0) {
            $current_stmt = $conn->prepare("SELECT cover_image FROM announcements WHERE id = ?");
            $current_stmt->bind_param("i", $id);
            $current_stmt->execute();
            $res = $current_stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                if (!empty($row['cover_image']) && file_exists($row['cover_image'])) {
                    @unlink($row['cover_image']);
                }
            }
            $current_stmt->close();

            $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            header("Location: announcements.php?msg=deleted");
            exit();
        }
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') $success_msg = "Announcement successfully published!";
    if ($_GET['msg'] === 'updated') $success_msg = "Announcement successfully updated!";
    if ($_GET['msg'] === 'deleted') $success_msg = "Announcement successfully removed!";
}

// Fetch all announcements
$announcements = [];
$result = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements | Barangay Maruing</title>
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
    <link rel="stylesheet" href="Admin CSS/announcements.css?v=2">
    <style>
        .badge.published {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge.draft {
            background-color: #fef3c7;
            color: #92400e;
        }
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .alert-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
    </style>
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
            <li><a href="mediagallery.php"><i class="bi bi-image-fill"></i> Media Gallery</a></li>
            <li><a href="services.php"><i class="bi bi-card-list"></i> Services</a></li>
            <li><a href="announcements.php" class="active"><i class="bi bi-megaphone-fill"></i> Announcements</a></li>
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
                <h2>Manage Announcements</h2>
            </div>
            <div class="topbar-user">
                <div class="user-info">
                    <span class="user-name">System Admin</span>
                    <span class="user-role">Administrator</span>
                </div>
                <div class="user-avatar">A</div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="dashboard-content">
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($success_msg); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <div class="admin-header-row">
                <h2>Announcements & Updates</h2>
                <button class="btn-action btn-add" onclick="openModal('addAnnouncementModal')"><i class="bi bi-plus-lg"></i> Add New Announcement</button>
            </div>
            
            <div class="admin-container">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Cover</th>
                                <th>Title & Summary</th>
                                <th>Schedule / When</th>
                                <th>Target Audience</th>
                                <th>Status</th>
                                <th>Date Added</th>
                                <th style="text-align: center; width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($announcements)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #64748b; padding: 40px; font-style: italic;">
                                        No announcements found. Click "+ Add New Announcement" to post your first update.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($announcements as $ann): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($ann['cover_image']) && file_exists($ann['cover_image'])): ?>
                                                <img src="<?php echo htmlspecialchars($ann['cover_image']); ?>" alt="Cover" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">
                                            <?php else: ?>
                                                <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 1.2rem;">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong style="color: #1e293b; font-size: 1rem; display: block; margin-bottom: 4px;">
                                                <?php echo htmlspecialchars($ann['title']); ?>
                                            </strong>
                                            <?php if (!empty($ann['what'])): ?>
                                                <small style="color: #64748b; display: block;"><i class="bi bi-tag-fill" style="font-size: 0.75rem;"></i> <?php echo htmlspecialchars($ann['what']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span style="color: #334155; font-weight: 500;">
                                                <?php echo htmlspecialchars($ann['when_date'] ?: 'Not specified'); ?>
                                            </span>
                                            <?php if (!empty($ann['where_place'])): ?>
                                                <small style="color: #64748b; display: block;"><i class="bi bi-geo-alt-fill" style="font-size: 0.75rem;"></i> <?php echo htmlspecialchars($ann['where_place']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td style="color: #475569;">
                                            <?php echo htmlspecialchars($ann['who'] ?: 'General Public'); ?>
                                        </td>
                                        <td>
                                            <?php if ($ann['status'] === 'published'): ?>
                                                <span class="badge published"><i class="bi bi-eye-fill"></i> Published</span>
                                            <?php else: ?>
                                                <span class="badge draft"><i class="bi bi-eye-slash-fill"></i> Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="color: #64748b; font-size: 0.85rem;">
                                            <?php echo date('M d, Y', strtotime($ann['created_at'])); ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn-sm btn-edit" onclick='openEditModal(<?php echo json_encode($ann, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <form action="" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="announcementId" value="<?php echo (int)$ann['id']; ?>">
                                                <button type="submit" class="btn-sm btn-delete">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Announcement Modal -->
    <div id="addAnnouncementModal" class="modal">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-header">
                <h2>Add New Announcement</h2>
                <span class="close-modal" onclick="closeModal('addAnnouncementModal')">&times;</span>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto; padding: 20px;">
                <form action="" method="POST" id="addAnnouncementForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="create">

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="addTitle" style="font-weight: 600; display: block; margin-bottom: 6px;">Announcement Title *</label>
                        <input type="text" id="addTitle" name="announcementTitle" class="form-control" required placeholder="e.g., General Assembly Meeting" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="addImage" style="font-weight: 600; display: block; margin-bottom: 6px;">Cover Image (Optional)</label>
                        <input type="file" id="addImage" name="announcementImage" class="form-control" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        <small style="color: #64748b; font-size: 0.8rem;">Upload a banner/poster for this announcement.</small>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label for="addWhat" style="font-weight: 600; display: block; margin-bottom: 6px;">What (Short summary)</label>
                            <input type="text" id="addWhat" name="announcementWhat" class="form-control" placeholder="e.g., Free Medical & Dental Mission" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                        <div class="form-group">
                            <label for="addWhen" style="font-weight: 600; display: block; margin-bottom: 6px;">When (Date & Time)</label>
                            <input type="text" id="addWhen" name="announcementWhen" class="form-control" placeholder="e.g., October 22, 2026, 8:00 AM" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label for="addWhere" style="font-weight: 600; display: block; margin-bottom: 6px;">Where (Location / Venue)</label>
                            <input type="text" id="addWhere" name="announcementWhere" class="form-control" placeholder="e.g., Barangay Gymnasium" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                        <div class="form-group">
                            <label for="addWho" style="font-weight: 600; display: block; margin-bottom: 6px;">Who (Target Audience)</label>
                            <input type="text" id="addWho" name="announcementWho" class="form-control" placeholder="e.g., All residents of Barangay Maruing" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="addWhy" style="font-weight: 600; display: block; margin-bottom: 6px;">Why (Purpose / Objective)</label>
                        <textarea id="addWhy" name="announcementWhy" class="form-control" rows="2" placeholder="e.g., To discuss upcoming infrastructure projects and community budget." style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="addContent" style="font-weight: 600; display: block; margin-bottom: 6px;">Full Description / Message</label>
                        <textarea id="addContent" name="announcementContent" class="form-control" rows="4" placeholder="Detailed announcement content, instructions, requirements, or reminders..." style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"></textarea>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="addStatus" style="font-weight: 600; display: block; margin-bottom: 6px;">Status</label>
                        <select id="addStatus" name="announcementStatus" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            <option value="published">Published (Visible on Website)</option>
                            <option value="draft">Draft (Hidden)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn-cancel" onclick="closeModal('addAnnouncementModal')" style="padding: 10px 18px; border: 1px solid #cbd5e1; background: white; border-radius: 6px; cursor: pointer;">Cancel</button>
                <button type="submit" form="addAnnouncementForm" class="btn-action" style="padding: 10px 20px; border-radius: 6px; cursor: pointer;">Publish Announcement</button>
            </div>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div id="editAnnouncementModal" class="modal">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-header">
                <h2>Edit Announcement</h2>
                <span class="close-modal" onclick="closeModal('editAnnouncementModal')">&times;</span>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto; padding: 20px;">
                <form action="" method="POST" id="editAnnouncementForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" id="editId" name="announcementId" value="">

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editTitle" style="font-weight: 600; display: block; margin-bottom: 6px;">Announcement Title *</label>
                        <input type="text" id="editTitle" name="announcementTitle" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 6px;">Current Cover Image</label>
                        <div id="editImagePreview" style="margin-bottom: 8px;"></div>
                        <label for="editImage" style="font-weight: 500; font-size: 0.85rem; color: #64748b; display: block; margin-bottom: 4px;">Replace Cover Image (Optional)</label>
                        <input type="file" id="editImage" name="announcementImage" class="form-control" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label for="editWhat" style="font-weight: 600; display: block; margin-bottom: 6px;">What (Short summary)</label>
                            <input type="text" id="editWhat" name="announcementWhat" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                        <div class="form-group">
                            <label for="editWhen" style="font-weight: 600; display: block; margin-bottom: 6px;">When (Date & Time)</label>
                            <input type="text" id="editWhen" name="announcementWhen" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label for="editWhere" style="font-weight: 600; display: block; margin-bottom: 6px;">Where (Location / Venue)</label>
                            <input type="text" id="editWhere" name="announcementWhere" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                        <div class="form-group">
                            <label for="editWho" style="font-weight: 600; display: block; margin-bottom: 6px;">Who (Target Audience)</label>
                            <input type="text" id="editWho" name="announcementWho" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editWhy" style="font-weight: 600; display: block; margin-bottom: 6px;">Why (Purpose / Objective)</label>
                        <textarea id="editWhy" name="announcementWhy" class="form-control" rows="2" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editContent" style="font-weight: 600; display: block; margin-bottom: 6px;">Full Description / Message</label>
                        <textarea id="editContent" name="announcementContent" class="form-control" rows="4" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"></textarea>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editStatus" style="font-weight: 600; display: block; margin-bottom: 6px;">Status</label>
                        <select id="editStatus" name="announcementStatus" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            <option value="published">Published (Visible on Website)</option>
                            <option value="draft">Draft (Hidden)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn-cancel" onclick="closeModal('editAnnouncementModal')" style="padding: 10px 18px; border: 1px solid #cbd5e1; background: white; border-radius: 6px; cursor: pointer;">Cancel</button>
                <button type="submit" form="editAnnouncementForm" class="btn-action" style="padding: 10px 20px; border-radius: 6px; cursor: pointer;">Save Changes</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openEditModal(ann) {
            document.getElementById('editId').value = ann.id;
            document.getElementById('editTitle').value = ann.title || '';
            document.getElementById('editWhat').value = ann.what || '';
            document.getElementById('editWhen').value = ann.when_date || '';
            document.getElementById('editWhere').value = ann.where_place || '';
            document.getElementById('editWho').value = ann.who || '';
            document.getElementById('editWhy').value = ann.why || '';
            document.getElementById('editContent').value = ann.content || '';
            document.getElementById('editStatus').value = ann.status || 'published';

            const preview = document.getElementById('editImagePreview');
            if (ann.cover_image) {
                preview.innerHTML = `<img src="${ann.cover_image}" alt="Cover" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; display: block;">`;
            } else {
                preview.innerHTML = `<span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">No cover image uploaded</span>`;
            }

            openModal('editAnnouncementModal');
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        };
    </script>
</body>
</html>
