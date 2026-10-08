<?php
// get_markers.php
header('Content-Type: application/json');
include 'admin/db_connect.php';

$purok_num = isset($_GET['purok']) ? (int)$_GET['purok'] : 0;

if ($purok_num >= 1 && $purok_num <= 9) {
    $table_name = "sm_purok_" . $purok_num . "_spots";
    
    // Select the spots. We also prefix paths with 'admin/' since images were uploaded in 'admin/uploads/'
    // But the public page is at the root, so 'admin/uploads/markers/...' will resolve correctly from root.
    $sql = "SELECT house_number, husband_name, spouse_name, marker_width, marker_height, top_position, left_position, marker_image, house_image FROM " . $table_name;
    
    $result = $conn->query($sql);
    
    $markers = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Because the frontend is at the root folder, and the image paths in the database 
            // are saved as 'uploads/markers/...', we need to prepend 'admin/' to the paths.
            if (!empty($row['marker_image'])) {
                $row['marker_image'] = 'admin/' . $row['marker_image'];
            }
            if (!empty($row['house_image'])) {
                $row['house_image'] = 'admin/' . $row['house_image'];
            }
            $markers[] = $row;
        }
    }
    
    echo json_encode(["status" => "success", "data" => $markers]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Purok Number"]);
}
?>
