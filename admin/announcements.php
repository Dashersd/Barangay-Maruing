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
    <link rel="stylesheet" href="Admin CSS/announcements.css">
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
            <li><a href="#"><i class="bi bi-geo-alt-fill"></i> Spot Map</a></li>
            <li><a href="mediagallery.php"><i class="bi bi-image-fill"></i> Media Gallery</a></li>
            <li><a href="services.php"><i class="bi bi-card-list"></i> Services</a></li>
            <li><a href="announcements.php" class="active"><i class="bi bi-megaphone-fill"></i> Announcements</a></li>
            <li><a href="#"><i class="bi bi-telephone-fill"></i> Contact</a></li>
            <li><a href="settings.php"><i class="bi bi-gear-fill"></i> Settings</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="login.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </div>
            <li><a href="announcements.php" class="active"><i class="bi bi-megaphone-fill"></i> Announcements</a></li>
            <li><a href="#"><i class="bi bi-telephone-fill"></i> Contact</a></li>
            <li><a href="settings.php"><i class="bi bi-gear-fill"></i> Settings</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="login.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </div>
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
                <div class="user-avatar">
                    A
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="dashboard-content">
            <div class="admin-header-row">
                <h2>Announcements & Updates</h2>
                <button class="btn-action btn-add" onclick="openModal('addAnnouncementModal')"><i class="bi bi-plus"></i> Add New Announcement</button>
            </div>
            
            <div class="admin-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sep 20, 2026</td>
                            <td>Upcoming Medical Mission</td>
                            <td>Free medical check-ups and distribution...</td>
                            <td><span class="badge active">Published</span></td>
                            <td>
                                <button class="btn-sm btn-edit">Edit</button>
                                <button class="btn-sm btn-delete">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Add Announcement Modal -->
    <div id="addAnnouncementModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2>Add New Announcement</h2>
                <span class="close-modal" onclick="closeModal('addAnnouncementModal')">&times;</span>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <form action="#" method="POST" id="addAnnouncementForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="announcementTitle">Announcement Title</label>
                        <input type="text" id="announcementTitle" name="announcementTitle" class="form-control" required placeholder="e.g., Upcoming Medical Mission">
                    </div>
                    
                    <div class="form-group">
                        <label for="announcementImage">Cover Image</label>
                        <input type="file" id="announcementImage" name="announcementImage" class="form-control" accept="image/*" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="announcementWho">Who</label>
                            <input type="text" id="announcementWho" name="announcementWho" class="form-control" required placeholder="e.g., All residents">
                        </div>
                        <div class="form-group">
                            <label for="announcementWhen">When</label>
                            <input type="text" id="announcementWhen" name="announcementWhen" class="form-control" required placeholder="e.g., Sept 20, 2026, 8:00 AM">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="announcementWhat">What</label>
                        <input type="text" id="announcementWhat" name="announcementWhat" class="form-control" required placeholder="e.g., Free Medical Check-ups">
                    </div>
                    
                    <div class="form-group">
                        <label for="announcementWhy">Why</label>
                        <textarea id="announcementWhy" name="announcementWhy" class="form-control" rows="3" required placeholder="e.g., To ensure the health of our community."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="announcementStatus">Status</label>
                        <select id="announcementStatus" name="announcementStatus" class="form-control" required>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('addAnnouncementModal')">Cancel</button>
                <button type="submit" form="addAnnouncementForm" class="btn-submit">Publish Announcement</button>
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

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
