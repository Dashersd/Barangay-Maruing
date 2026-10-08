<?php
// delete_record.php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_file = $_POST['target_file'] ?? '';
    $id = $_POST['id'] ?? '';
    
    $allowed_targets = ['legend', 'purok1', 'purok2', 'purok3', 'purok4', 'purok5', 'purok6', 'purok7', 'purok8', 'purok9'];
    
    if (in_array($target_file, $allowed_targets) && !empty($id)) {
        // Convert 'purok1' to 'sm_purok_1_spots' or 'legend' to 'sm_legend_spots'
        if ($target_file === 'legend') {
            $table_name = "sm_legend_spots";
        } else {
            $purok_num = str_replace('purok', '', $target_file);
            $table_name = "sm_purok_" . $purok_num . "_spots";
        }

        // Fetch to delete images
        $stmt = $conn->prepare("SELECT marker_image, house_image FROM $table_name WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                if (!empty($row['marker_image']) && file_exists($row['marker_image'])) {
                    @unlink($row['marker_image']);
                }
                if (!empty($row['house_image']) && file_exists($row['house_image'])) {
                    @unlink($row['house_image']);
                }
            }
            $stmt->close();
        }

        // Delete row
        $stmt = $conn->prepare("DELETE FROM $table_name WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    // Redirect back to the correct resident table
    $redirect = '';
    if ($target_file === 'legend') {
        $redirect = 'legends file.php';
    } else {
        $redirect = str_replace('purok', 'resident ', $target_file) . '.php';
    }
    header("Location: " . $redirect . "?deleted=1");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
