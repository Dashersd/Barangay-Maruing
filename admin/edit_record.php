<?php
// edit_record.php
include 'db_connect.php';

$target_file = $_GET['target_file'] ?? ($_POST['target_file'] ?? '');
$id = $_GET['id'] ?? ($_POST['id'] ?? '');

$allowed_targets = ['legend', 'purok1', 'purok2', 'purok3', 'purok4', 'purok5', 'purok6', 'purok7', 'purok8', 'purok9'];

if (!in_array($target_file, $allowed_targets) || empty($id)) {
    die("Invalid request.");
}

if ($target_file === 'legend') {
    $table_name = "sm_legend_spots";
} else {
    $purok_num = str_replace('purok', '', $target_file);
    $table_name = "sm_purok_" . $purok_num . "_spots";
}

$record = null;
$stmt = $conn->prepare("SELECT * FROM $table_name WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $record = $row;
    }
    $stmt->close();
}

if (!$record) {
    die("Record not found in the database. (If you recently updated from JSON, this record might not exist in the new database).");
}

// Handle POST to update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $house_number = $_POST['house_number'] ?? '';
    $husband_name = $_POST['husband_name'] ?? '';
    $spouse_name = $_POST['spouse_name'] ?? '';
    $house_image_path = $record['house_image'];
    
    // Handle House Image Update
    if (isset($_FILES['house_image']) && $_FILES['house_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/houses/';
        $file_name = time() . '_house_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["house_image"]["name"]));
        $target_file_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES["house_image"]["tmp_name"], $target_file_path)) {
            // Delete old image if exists
            if (!empty($house_image_path) && file_exists($house_image_path)) {
                @unlink($house_image_path);
            }
            $house_image_path = $target_file_path;
        }
    }
    
    $stmt = $conn->prepare("UPDATE $table_name SET house_number = ?, husband_name = ?, spouse_name = ?, house_image = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("ssssi", $house_number, $husband_name, $spouse_name, $house_image_path, $id);
        $stmt->execute();
        $stmt->close();
    }
    
    $redirect = '';
    if ($target_file === 'legend') {
        $redirect = 'legends file.php';
    } else {
        $redirect = str_replace('purok', 'resident ', $target_file) . '.php';
    }
    header("Location: " . $redirect . "?edited=1");
    exit();
}

$back_url = '';
if ($target_file === 'legend') {
    $back_url = 'legends file.php';
} else {
    $back_url = str_replace('purok', 'resident ', $target_file) . '.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Record</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Montserrat', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .edit-card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 500px; }
        h2 { margin-top: 0; color: #1e293b; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #475569; font-size: 0.9rem; }
        input[type="text"], input[type="file"] { width: 100%; padding: 12px 15px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; font-family: 'Montserrat', sans-serif; outline: none; transition: border-color 0.2s; }
        input[type="text"]:focus, input[type="file"]:focus { border-color: #0f766e; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-family: 'Montserrat', sans-serif; transition: background 0.2s; display: inline-block; text-decoration: none; text-align: center; }
        .btn-save { background: #0f766e; color: white; width: 100%; margin-bottom: 10px; }
        .btn-save:hover { background: #0d9488; }
        .btn-cancel { background: #e2e8f0; color: #475569; width: 100%; box-sizing: border-box; }
        .btn-cancel:hover { background: #cbd5e1; }
        .current-image { margin-bottom: 10px; }
        .current-image img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="edit-card">
        <h2>Edit Record Details</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="target_file" value="<?php echo htmlspecialchars($target_file); ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            
            <div class="form-group">
                <label>House Number (Optional)</label>
                <input type="text" name="house_number" value="<?php echo htmlspecialchars($record['house_number'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label>Husband Name / Household Head</label>
                <input type="text" name="husband_name" value="<?php echo htmlspecialchars($record['husband_name'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Spouse Name</label>
                <input type="text" name="spouse_name" value="<?php echo htmlspecialchars($record['spouse_name'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Household Image (Optional)</label>
                <?php if (!empty($record['house_image'])): ?>
                    <div class="current-image">
                        <img src="<?php echo htmlspecialchars($record['house_image']); ?>" alt="Current House Image">
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 5px;">Current image</div>
                    </div>
                <?php endif; ?>
                <input type="file" name="house_image" accept="image/*">
                <div style="font-size: 0.8rem; color: #64748b; margin-top: 5px;">Leave empty to keep the current image</div>
            </div>
            
            <button type="submit" class="btn btn-save">Save Changes</button>
            <a href="<?php echo htmlspecialchars($back_url); ?>" class="btn btn-cancel">Cancel</a>
        </form>
    </div>
</body>
</html>
