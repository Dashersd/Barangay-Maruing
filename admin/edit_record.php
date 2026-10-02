<?php
// edit_record.php
$target_file = $_GET['target_file'] ?? ($_POST['target_file'] ?? '');
$id = $_GET['id'] ?? ($_POST['id'] ?? '');

$allowed_targets = ['legend', 'purok1', 'purok2', 'purok3', 'purok4', 'purok5', 'purok6', 'purok7', 'purok8', 'purok9'];

if (!in_array($target_file, $allowed_targets) || empty($id)) {
    die("Invalid request.");
}

$json_file = 'data/' . $target_file . '.json';
$record = null;
$record_index = null;

if (file_exists($json_file)) {
    $data = json_decode(file_get_contents($json_file), true);
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            if (isset($v['id']) && $v['id'] === $id) {
                $record = $v;
                $record_index = $k;
                break;
            }
        }
    }
}

if (!$record) {
    die("Record not found.");
}

// Handle POST to update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data[$record_index]['house_number'] = $_POST['house_number'] ?? '';
    $data[$record_index]['husband_name'] = $_POST['husband_name'] ?? '';
    $data[$record_index]['spouse_name'] = $_POST['spouse_name'] ?? '';
    
    // Handle House Image Update
    if (isset($_FILES['house_image']) && $_FILES['house_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/houses/';
        $file_name = time() . '_house_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["house_image"]["name"]));
        $target_file_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES["house_image"]["tmp_name"], $target_file_path)) {
            // Delete old image if exists
            if (!empty($data[$record_index]['house_image']) && file_exists($data[$record_index]['house_image'])) {
                @unlink($data[$record_index]['house_image']);
            }
            $data[$record_index]['house_image'] = $target_file_path;
        }
    }
    
    file_put_contents($json_file, json_encode($data, JSON_PRETTY_PRINT));
    
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
                <label>House Number</label>
                <input type="text" name="house_number" value="<?php echo htmlspecialchars($record['house_number'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label>Husband Name</label>
                <input type="text" name="husband_name" value="<?php echo htmlspecialchars($record['husband_name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label>Spouse Name</label>
                <input type="text" name="spouse_name" value="<?php echo htmlspecialchars($record['spouse_name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label>Update House Image (Optional)</label>
                <?php if(!empty($record['house_image'])): ?>
                    <div class="current-image">
                        <img src="<?php echo htmlspecialchars($record['house_image']); ?>" alt="Current House Image">
                    </div>
                <?php endif; ?>
                <input type="file" name="house_image" accept="image/*">
            </div>
            
            <button type="submit" class="btn btn-save">Save Changes</button>
            <a href="<?php echo htmlspecialchars($back_url); ?>" class="btn btn-cancel">Cancel</a>
        </form>
    </div>
</body>
</html>
