<?php
// save_record.php

// Enable basic error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get the target JSON file from the hidden input
    $target_file = isset($_POST['target_file']) ? $_POST['target_file'] : '';
    
    // Validate target_file to prevent directory traversal or invalid saves
    $allowed_targets = ['legend', 'purok1', 'purok2', 'purok3', 'purok4', 'purok5', 'purok6', 'purok7', 'purok8', 'purok9'];
    if (!in_array($target_file, $allowed_targets)) {
        die("Invalid target file specified.");
    }
    
    // 2. Handle File Uploads
    // A. Marker Image
    $marker_image_path = "";
    if (isset($_FILES['marker_image']) && $_FILES['marker_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/markers/';
        $file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["marker_image"]["name"]));
        $target_file_path = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES["marker_image"]["tmp_name"], $target_file_path)) {
            $marker_image_path = $target_file_path;
        }
    }
    
    // B. House Image
    $house_image_path = "";
    if (isset($_FILES['house_image']) && $_FILES['house_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/houses/';
        $file_name = time() . '_house_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["house_image"]["name"]));
        $target_file_path = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES["house_image"]["tmp_name"], $target_file_path)) {
            $house_image_path = $target_file_path;
        }
    }
    
    // 3. Gather form data
    $new_record = [
        "id" => uniqid(),
        "house_number" => isset($_POST['house_number']) ? trim($_POST['house_number']) : '',
        "husband_name" => isset($_POST['husband_name']) ? trim($_POST['husband_name']) : '',
        "spouse_name" => isset($_POST['spouse_name']) ? trim($_POST['spouse_name']) : '',
        "marker_width" => isset($_POST['marker_width']) ? (float)$_POST['marker_width'] : 40,
        "marker_height" => isset($_POST['marker_height']) ? (float)$_POST['marker_height'] : 40,
        "top_position" => isset($_POST['top_position']) ? (float)$_POST['top_position'] : 50.00,
        "left_position" => isset($_POST['left_position']) ? (float)$_POST['left_position'] : 50.00,
        "marker_image" => $marker_image_path,
        "house_image" => $house_image_path,
        "date_added" => date('Y-m-d H:i:s')
    ];
    
    // 4. Update the JSON file
    $json_file = 'data/' . $target_file . '.json';
    
    $existing_data = [];
    if (file_exists($json_file)) {
        $json_content = file_get_contents($json_file);
        if ($json_content) {
            $decoded = json_decode($json_content, true);
            if (is_array($decoded)) {
                $existing_data = $decoded;
            }
        }
    }
    
    $existing_data[] = $new_record;
    
    // Save back to JSON
    file_put_contents($json_file, json_encode($existing_data, JSON_PRETTY_PRINT));
    
    // 5. Redirect back to the map page
    $redirect_page = '';
    if ($target_file === 'legend') {
        $redirect_page = 'legend.php';
    } else {
        // Convert 'purok1' to 'purok 1.php'
        $redirect_page = str_replace('purok', 'purok ', $target_file) . '.php';
    }
    
    header("Location: " . $redirect_page . "?success=1");
    exit();
} else {
    // Not a POST request
    header("Location: dashboard.php");
    exit();
}
?>
