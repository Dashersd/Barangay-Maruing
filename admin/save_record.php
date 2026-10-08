<?php
// admin/save_record.php

// Enable basic error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get the target file/table from the hidden input
    $target_file = isset($_POST['target_file']) ? $_POST['target_file'] : '';
    
    // Validate target_file to prevent invalid saves
    $allowed_targets = ['legend', 'purok1', 'purok2', 'purok3', 'purok4', 'purok5', 'purok6', 'purok7', 'purok8', 'purok9'];
    if (!in_array($target_file, $allowed_targets)) {
        die("Invalid target file specified.");
    }
    
    // Determine database table name
    if ($target_file === 'legend') {
        $table_name = 'sm_legend_spots';
    } else {
        $purok_num = str_replace('purok', '', $target_file);
        $table_name = 'sm_purok_' . $purok_num . '_spots';
    }
    
    // 2. Handle File Uploads
    // A. Marker Image
    $marker_image_path = "";
    if (isset($_FILES['marker_image']) && $_FILES['marker_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/markers/';
        // Ensure directory exists
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
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
        // Ensure directory exists
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $file_name = time() . '_house_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["house_image"]["name"]));
        $target_file_path = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES["house_image"]["tmp_name"], $target_file_path)) {
            $house_image_path = $target_file_path;
        }
    }
    
    // 3. Gather form data
    $house_number = isset($_POST['house_number']) ? trim($_POST['house_number']) : '';
    $husband_name = isset($_POST['husband_name']) ? trim($_POST['husband_name']) : '';
    $spouse_name = isset($_POST['spouse_name']) ? trim($_POST['spouse_name']) : '';
    $marker_width = isset($_POST['marker_width']) ? (int)$_POST['marker_width'] : 40;
    $marker_height = isset($_POST['marker_height']) ? (int)$_POST['marker_height'] : 40;
    $top_position = isset($_POST['top_position']) ? (float)$_POST['top_position'] : 50.00;
    $left_position = isset($_POST['left_position']) ? (float)$_POST['left_position'] : 50.00;
    
    // 4. Save to MySQL database using Prepared Statement to prevent SQL injection
    $sql = "INSERT INTO " . $table_name . " 
            (house_number, husband_name, spouse_name, marker_width, marker_height, top_position, left_position, marker_image, house_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // "sss" for 3 strings, "ii" for 2 ints, "dd" for 2 decimals, "ss" for 2 strings
        $stmt->bind_param("sssiiddss", $house_number, $husband_name, $spouse_name, $marker_width, $marker_height, $top_position, $left_position, $marker_image_path, $house_image_path);
        
        $stmt->execute();
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }
    
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
