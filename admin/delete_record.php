<?php
// delete_record.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_file = $_POST['target_file'] ?? '';
    $id = $_POST['id'] ?? '';
    
    $allowed_targets = ['legend', 'purok1', 'purok2', 'purok3', 'purok4', 'purok5', 'purok6', 'purok7', 'purok8', 'purok9'];
    
    if (in_array($target_file, $allowed_targets) && !empty($id)) {
        $json_file = 'data/' . $target_file . '.json';
        if (file_exists($json_file)) {
            $data = json_decode(file_get_contents($json_file), true);
            if (is_array($data)) {
                $new_data = [];
                foreach ($data as $record) {
                    if (isset($record['id']) && $record['id'] === $id) {
                        // Delete the image file if it exists
                        if (!empty($record['marker_image']) && file_exists($record['marker_image'])) {
                            @unlink($record['marker_image']);
                        }
                        // Delete the house image if it exists
                        if (!empty($record['house_image']) && file_exists($record['house_image'])) {
                            @unlink($record['house_image']);
                        }
                    } else {
                        $new_data[] = $record;
                    }
                }
                file_put_contents($json_file, json_encode($new_data, JSON_PRETTY_PRINT));
            }
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
