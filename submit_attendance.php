<?php
include '../../connection/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $photo_data = $_POST['photo'];

    // Decode and save the image
    if (!empty($photo_data)) {
        $folderPath = "../../uploads/";
        $image_parts = explode(";base64,", $photo_data);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file_name = uniqid() . '.' . $image_type;
        $file_path = $folderPath . $file_name;

        // Save the file
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        file_put_contents($file_path, $image_base64);

        // Store the path in the database
        $sql = "INSERT INTO employee_attendance (employee_id, photo_path) VALUES ('$employee_id', '$file_name')";
        if ($conn->query($sql)) {
            echo "Attendance with photo submitted successfully.";
        } else {
            echo "Failed to submit attendance.";
        }
    } else {
        echo "No photo captured.";
    }
}
?>
