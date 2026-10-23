<?php
// admin/services.php
require_once 'db_connect.php';

$success_msg = '';
$error_msg = '';

// Handle POST actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $action = $_POST['action'] ?? '';

    // 1. CREATE SERVICE
    if ($action === 'create') {
        $name = trim($_POST['serviceName'] ?? '');
        $icon = trim($_POST['serviceIcon'] ?? 'bi-file-earmark-text');
        $status = in_array($_POST['serviceStatus'] ?? '', ['active', 'inactive']) ? $_POST['serviceStatus'] : 'active';
        $fee = trim($_POST['serviceFee'] ?? '');
        $processing_time = trim($_POST['serviceProcessingTime'] ?? '');
        $description = trim($_POST['serviceDesc'] ?? '');
        $requirements = trim($_POST['serviceRequirements'] ?? '');
        $button_text = trim($_POST['serviceButtonText'] ?? 'Request Document');
        $button_url = trim($_POST['serviceButtonUrl'] ?? 'contact.php');
        $display_order = (int)($_POST['serviceDisplayOrder'] ?? 0);

        if (!empty($name) && !empty($description)) {
            $stmt = $conn->prepare("INSERT INTO services (name, icon, status, fee, processing_time, description, requirements, button_text, button_url, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssi", $name, $icon, $status, $fee, $processing_time, $description, $requirements, $button_text, $button_url, $display_order);
            if ($stmt->execute()) {
                header("Location: services.php?msg=created");
                exit();
            } else {
                $error_msg = "Error creating service: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error_msg = "Service name and description are required.";
        }
    }

    // 2. UPDATE SERVICE
    if ($action === 'update') {
        $id = (int)($_POST['serviceId'] ?? 0);
        $name = trim($_POST['serviceName'] ?? '');
        $icon = trim($_POST['serviceIcon'] ?? 'bi-file-earmark-text');
        $status = in_array($_POST['serviceStatus'] ?? '', ['active', 'inactive']) ? $_POST['serviceStatus'] : 'active';
        $fee = trim($_POST['serviceFee'] ?? '');
        $processing_time = trim($_POST['serviceProcessingTime'] ?? '');
        $description = trim($_POST['serviceDesc'] ?? '');
        $requirements = trim($_POST['serviceRequirements'] ?? '');
        $button_text = trim($_POST['serviceButtonText'] ?? 'Request Document');
        $button_url = trim($_POST['serviceButtonUrl'] ?? 'contact.php');
        $display_order = (int)($_POST['serviceDisplayOrder'] ?? 0);

        if ($id > 0 && !empty($name) && !empty($description)) {
            $stmt = $conn->prepare("UPDATE services SET name = ?, icon = ?, status = ?, fee = ?, processing_time = ?, description = ?, requirements = ?, button_text = ?, button_url = ?, display_order = ? WHERE id = ?");
            $stmt->bind_param("sssssssssii", $name, $icon, $status, $fee, $processing_time, $description, $requirements, $button_text, $button_url, $display_order, $id);
            if ($stmt->execute()) {
                header("Location: services.php?msg=updated");
                exit();
            } else {
                $error_msg = "Error updating service: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error_msg = "Invalid service ID or missing required fields.";
        }
    }

    // 3. DELETE SERVICE
    if ($action === 'delete') {
        $id = (int)($_POST['serviceId'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                header("Location: services.php?msg=deleted");
                exit();
            } else {
                $error_msg = "Error deleting service: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error_msg = "Invalid service ID.";
        }
    }

    // 4. TOGGLE STATUS
    if ($action === 'toggle_status') {
        $id = (int)($_POST['serviceId'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE services SET status = IF(status = 'active', 'inactive', 'active') WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                header("Location: services.php?msg=status_updated");
                exit();
            } else {
                $error_msg = "Error updating status: " . $conn->error;
            }
            $stmt->close();
        }
    }
}

// Check GET message alerts
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') $success_msg = "New service added successfully!";
    if ($_GET['msg'] === 'updated') $success_msg = "Service updated successfully!";
    if ($_GET['msg'] === 'deleted') $success_msg = "Service deleted successfully!";
    if ($_GET['msg'] === 'status_updated') $success_msg = "Service status toggled successfully!";
}

// Fetch all services ordered by display_order, then id
$services = [];
$services_res = $conn->query("SELECT * FROM services ORDER BY display_order ASC, id ASC");
if ($services_res) {
    while ($row = $services_res->fetch_assoc()) {
        $services[] = $row;
    }
}
$next_order = count($services) + 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services | Barangay Maruing</title>
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
    <link rel="stylesheet" href="Admin CSS/services.css?v=3">
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
            <li><a href="services.php" class="active"><i class="bi bi-card-list"></i> Services</a></li>
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
                <h2>Manage Services</h2>
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
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success_msg) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error_msg) ?>
                </div>
            <?php endif; ?>

            <div class="admin-header-row">
                <h2>Barangay Services (<?= count($services) ?>)</h2>
                <button class="btn-action btn-add" onclick="openModal('addServiceModal')"><i class="bi bi-plus-lg"></i> Add New Service</button>
            </div>
            
            <div class="admin-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 60px;">Icon</th>
                            <th>Service Name</th>
                            <th>Fee & Time</th>
                            <th>Description</th>
                            <th style="width: 100px;">Status</th>
                            <th class="action-th">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($services)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                                    <i class="bi bi-folder2-open" style="font-size: 2.5rem; display: block; margin-bottom: 10px; color: #94a3b8;"></i>
                                    No services found. Click <strong>"Add New Service"</strong> to create one.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td style="color: #94a3b8; font-weight: 600;">
                                        <?= (int)($service['display_order'] ?? 0) ?>
                                    </td>
                                    <td>
                                        <div class="table-icon-cell">
                                            <i class="bi <?= htmlspecialchars($service['icon'] ?: 'bi-file-earmark-text') ?>"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <strong style="color: #093d25;"><?= htmlspecialchars($service['name']) ?></strong>
                                    </td>
                                    <td style="font-size: 0.85rem; color: #475569;">
                                        <div><i class="bi bi-cash-stack" style="color: #137547;"></i> <?= !empty($service['fee']) ? htmlspecialchars($service['fee']) : 'Free' ?></div>
                                        <div><i class="bi bi-clock" style="color: #64748b;"></i> <?= !empty($service['processing_time']) ? htmlspecialchars($service['processing_time']) : 'Same Day' ?></div>
                                    </td>
                                    <td style="max-width: 280px; color: #475569; font-size: 0.88rem; line-height: 1.4;">
                                        <?= htmlspecialchars(mb_strimwidth($service['description'], 0, 100, '...')) ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $service['status'] === 'active' ? 'active' : 'inactive' ?>">
                                            <?= ucfirst(htmlspecialchars($service['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons-wrapper">
                                            <!-- Edit button -->
                                            <button class="btn-sm btn-edit" onclick='openEditModal(<?= json_encode($service, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>

                                            <!-- Toggle Status button -->
                                            <form method="POST" action="services.php" style="margin: 0; display: inline-flex;">
                                                <input type="hidden" name="action" value="toggle_status">
                                                <input type="hidden" name="serviceId" value="<?= (int)$service['id'] ?>">
                                                <button type="submit" class="btn-sm btn-toggle" title="Toggle Status">
                                                    <i class="bi <?= $service['status'] === 'active' ? 'bi-eye-slash' : 'bi-eye' ?>"></i>
                                                </button>
                                            </form>

                                            <!-- Delete button -->
                                            <button class="btn-sm btn-delete" onclick="openDeleteModal(<?= (int)$service['id'] ?>, '<?= addslashes(htmlspecialchars($service['name'])) ?>')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Add Service Modal (UX/UI Matching Attached Design) -->
    <div id="addServiceModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="bi bi-list"></i> Add New Service</h2>
                <button type="button" class="close-modal-btn" onclick="closeModal('addServiceModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form action="services.php" method="POST" id="addServiceForm">
                    <input type="hidden" name="action" value="create">

                    <!-- Service Name -->
                    <div class="form-group">
                        <label for="addServiceName">Service Name <span class="required">*</span></label>
                        <input type="text" id="addServiceName" name="serviceName" class="form-control" required placeholder="e.g., Barangay Clearance, Certificate of Indigency">
                    </div>
                    
                    <!-- 2-Column: Service Icon & Status -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="addServiceIcon">Service Icon <span class="required">*</span></label>
                            <select id="addServiceIcon" name="serviceIcon" class="form-control" required>
                                <option value="bi-file-earmark-text">📄 Document / Clearance</option>
                                <option value="bi-house-heart">🏠 Indigency / Welfare</option>
                                <option value="bi-briefcase">💼 Business / Permit</option>
                                <option value="bi-person-badge">🪪 Residency / ID Card</option>
                                <option value="bi-card-heading">📜 Certificate / Credential</option>
                                <option value="bi-shield-check">🛡️ Protection / Blotter</option>
                                <option value="bi-activity">🩺 Health Clinic / Medical</option>
                                <option value="bi-heart-pulse">❤️ Emergency / Senior Care</option>
                                <option value="bi-people">👥 Community / Youth</option>
                                <option value="bi-cash-coin">🪙 Cedula / Financial</option>
                                <option value="bi-building">🏢 Building / Construction</option>
                                <option value="bi-mortarboard">🎓 Education / Scholarship</option>
                                <option value="bi-tools">🛠️ Maintenance / Infrastructure</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="addServiceStatus">Status <span class="required">*</span></label>
                            <select id="addServiceStatus" name="serviceStatus" class="form-control" required>
                                <option value="active">Active (Visible to public)</option>
                                <option value="inactive">Inactive (Hidden from public)</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2-Column: Fee / Cost & Processing Time -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="addServiceFee">Fee / Cost</label>
                            <input type="text" id="addServiceFee" name="serviceFee" class="form-control" placeholder="e.g., Free, ₱50.00, ₱100.00">
                        </div>

                        <div class="form-group">
                            <label for="addServiceProcessingTime">Processing Time</label>
                            <input type="text" id="addServiceProcessingTime" name="serviceProcessingTime" class="form-control" placeholder="e.g., 15-30 Minutes, Same Day">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="addServiceDesc">Description <span class="required">*</span></label>
                        <textarea id="addServiceDesc" name="serviceDesc" class="form-control" rows="3" required placeholder="Brief description of the service and its purpose..."></textarea>
                    </div>

                    <!-- Requirements (Optional) -->
                    <div class="form-group">
                        <label for="addServiceRequirements">Requirements (Optional)</label>
                        <textarea id="addServiceRequirements" name="serviceRequirements" class="form-control" rows="3" placeholder="e.g., Valid Government ID, Cedula, Purok Certification"></textarea>
                        <small class="form-hint">List the required documents or qualifications needed by the resident.</small>
                    </div>

                    <!-- 2-Column: Action Button Text & Button Destination URL -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="addServiceButtonText">Action Button Text</label>
                            <input type="text" id="addServiceButtonText" name="serviceButtonText" class="form-control" value="Request Document" placeholder="Request Document">
                        </div>

                        <div class="form-group">
                            <label for="addServiceButtonUrl">Button Destination URL</label>
                            <input type="text" id="addServiceButtonUrl" name="serviceButtonUrl" class="form-control" value="contact.php" placeholder="contact.php">
                        </div>
                    </div>

                    <!-- Display Order -->
                    <div class="form-group">
                        <label for="addServiceDisplayOrder">Display Order</label>
                        <input type="number" id="addServiceDisplayOrder" name="serviceDisplayOrder" class="form-control" value="<?= $next_order ?>" min="1">
                        <small class="form-hint">Lower numbers appear first on the public services list.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('addServiceModal')">Cancel</button>
                <button type="submit" form="addServiceForm" class="btn-submit">Save Service</button>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal (UX/UI Matching Attached Design) -->
    <div id="editServiceModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="bi bi-pencil-square"></i> Edit Service</h2>
                <button type="button" class="close-modal-btn" onclick="closeModal('editServiceModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form action="services.php" method="POST" id="editServiceForm">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="serviceId" id="editServiceId">

                    <!-- Service Name -->
                    <div class="form-group">
                        <label for="editServiceName">Service Name <span class="required">*</span></label>
                        <input type="text" id="editServiceName" name="serviceName" class="form-control" required placeholder="e.g., Barangay Clearance, Certificate of Indigency">
                    </div>
                    
                    <!-- 2-Column: Service Icon & Status -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editServiceIcon">Service Icon <span class="required">*</span></label>
                            <select id="editServiceIcon" name="serviceIcon" class="form-control" required>
                                <option value="bi-file-earmark-text">📄 Document / Clearance</option>
                                <option value="bi-house-heart">🏠 Indigency / Welfare</option>
                                <option value="bi-briefcase">💼 Business / Permit</option>
                                <option value="bi-person-badge">🪪 Residency / ID Card</option>
                                <option value="bi-card-heading">📜 Certificate / Credential</option>
                                <option value="bi-shield-check">🛡️ Protection / Blotter</option>
                                <option value="bi-activity">🩺 Health Clinic / Medical</option>
                                <option value="bi-heart-pulse">❤️ Emergency / Senior Care</option>
                                <option value="bi-people">👥 Community / Youth</option>
                                <option value="bi-cash-coin">🪙 Cedula / Financial</option>
                                <option value="bi-building">🏢 Building / Construction</option>
                                <option value="bi-mortarboard">🎓 Education / Scholarship</option>
                                <option value="bi-tools">🛠️ Maintenance / Infrastructure</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editServiceStatus">Status <span class="required">*</span></label>
                            <select id="editServiceStatus" name="serviceStatus" class="form-control" required>
                                <option value="active">Active (Visible to public)</option>
                                <option value="inactive">Inactive (Hidden from public)</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2-Column: Fee / Cost & Processing Time -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editServiceFee">Fee / Cost</label>
                            <input type="text" id="editServiceFee" name="serviceFee" class="form-control" placeholder="e.g., Free, ₱50.00, ₱100.00">
                        </div>

                        <div class="form-group">
                            <label for="editServiceProcessingTime">Processing Time</label>
                            <input type="text" id="editServiceProcessingTime" name="serviceProcessingTime" class="form-control" placeholder="e.g., 15-30 Minutes, Same Day">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="editServiceDesc">Description <span class="required">*</span></label>
                        <textarea id="editServiceDesc" name="serviceDesc" class="form-control" rows="3" required placeholder="Brief description of the service and its purpose..."></textarea>
                    </div>

                    <!-- Requirements (Optional) -->
                    <div class="form-group">
                        <label for="editServiceRequirements">Requirements (Optional)</label>
                        <textarea id="editServiceRequirements" name="serviceRequirements" class="form-control" rows="3" placeholder="e.g., Valid Government ID, Cedula, Purok Certification"></textarea>
                        <small class="form-hint">List the required documents or qualifications needed by the resident.</small>
                    </div>

                    <!-- 2-Column: Action Button Text & Button Destination URL -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editServiceButtonText">Action Button Text</label>
                            <input type="text" id="editServiceButtonText" name="serviceButtonText" class="form-control" placeholder="Request Document">
                        </div>

                        <div class="form-group">
                            <label for="editServiceButtonUrl">Button Destination URL</label>
                            <input type="text" id="editServiceButtonUrl" name="serviceButtonUrl" class="form-control" placeholder="contact.php">
                        </div>
                    </div>

                    <!-- Display Order -->
                    <div class="form-group">
                        <label for="editServiceDisplayOrder">Display Order</label>
                        <input type="number" id="editServiceDisplayOrder" name="serviceDisplayOrder" class="form-control" min="1">
                        <small class="form-hint">Lower numbers appear first on the public services list.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('editServiceModal')">Cancel</button>
                <button type="submit" form="editServiceForm" class="btn-submit">Update Service</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteServiceModal" class="modal">
        <div class="modal-content" style="max-width: 440px;">
            <div class="modal-header">
                <h2><i class="bi bi-trash" style="color: #ef4444;"></i> Delete Service</h2>
                <button type="button" class="close-modal-btn" onclick="closeModal('deleteServiceModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form action="services.php" method="POST" id="deleteServiceForm">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="serviceId" id="deleteServiceId">
                    <p style="color: #475569; font-size: 0.95rem; line-height: 1.5; margin: 0;">
                        Are you sure you want to delete <strong id="deleteServiceName" style="color: #093d25;"></strong>?
                    </p>
                    <p style="color: #ef4444; font-size: 0.85rem; margin-top: 10px;">
                        <i class="bi bi-exclamation-circle"></i> This action cannot be undone and will remove it from the public services page.
                    </p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('deleteServiceModal')">Cancel</button>
                <button type="submit" form="deleteServiceForm" class="btn-submit" style="background-color: #ef4444;">Delete</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.style.display = 'flex';
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.style.display = 'none';
        }

        function openEditModal(service) {
            document.getElementById('editServiceId').value = service.id;
            document.getElementById('editServiceName').value = service.name || '';
            document.getElementById('editServiceDesc').value = service.description || '';
            document.getElementById('editServiceRequirements').value = service.requirements || '';
            document.getElementById('editServiceFee').value = service.fee || '';
            document.getElementById('editServiceProcessingTime').value = service.processing_time || '';
            document.getElementById('editServiceButtonText').value = service.button_text || 'Request Document';
            document.getElementById('editServiceButtonUrl').value = service.button_url || 'contact.php';
            document.getElementById('editServiceDisplayOrder').value = service.display_order || service.id;
            
            const iconSelect = document.getElementById('editServiceIcon');
            if (iconSelect) {
                let found = false;
                for (let i = 0; i < iconSelect.options.length; i++) {
                    if (iconSelect.options[i].value === service.icon) {
                        iconSelect.selectedIndex = i;
                        found = true;
                        break;
                    }
                }
                if (!found && service.icon) {
                    const opt = document.createElement('option');
                    opt.value = service.icon;
                    opt.textContent = service.icon;
                    iconSelect.appendChild(opt);
                    iconSelect.value = service.icon;
                }
            }

            document.getElementById('editServiceStatus').value = service.status || 'active';
            openModal('editServiceModal');
        }

        function openDeleteModal(id, name) {
            document.getElementById('deleteServiceId').value = id;
            document.getElementById('deleteServiceName').textContent = '"' + name + '"';
            openModal('deleteServiceModal');
        }

        // Close modal when clicking outside of it or pressing Escape key
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        };

        window.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal').forEach(function(m) {
                    m.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>
