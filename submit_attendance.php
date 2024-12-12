<?php
include($_SERVER['DOCUMENT_ROOT'] . '/connection/db_conn.php'); // Adjust the path if necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the database connection exists
    if (!$conn) {
        die("Database connection failed.");
    }

    // Escape and sanitize input data
    $employee_id = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $photo_data = $_POST['photo'];
    $latitude = $conn->real_escape_string(strip_tags($_POST['latitude']));
    $longitude = $conn->real_escape_string(strip_tags($_POST['longitude']));

    // Decode and save the image
    if (!empty($photo_data)) {
        $folderPath = "../../uploads/";
        $image_parts = explode(";base64,", $photo_data);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file_name = uniqid() . '.' . $image_type;
        $file_path = $folderPath . $file_name;

        // Create the directory if it doesn't exist
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Save the image to the server
        file_put_contents($file_path, $image_base64);

        // Insert attendance data into the database
        $sql = "INSERT INTO employee_attendance (employee_id, photo_path, latitude, longitude, time_in, date_attendance)
                VALUES ('$employee_id', '$file_name', '$latitude', '$longitude', NOW(), CURDATE())";

        if ($conn->query($sql)) {
            echo "Attendance with photo and location submitted successfully.";
        } else {
            echo "Failed to submit attendance. Error: " . $conn->error;
        }
    } else {
        echo "No photo captured.";
    }
}
?>
