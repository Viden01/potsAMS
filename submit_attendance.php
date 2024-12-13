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

    // Decode and save the image
    if (!empty($photo_data)) {
        $folderPath = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
        $image_parts = explode(";base64,", $photo_data);

        // Validate base64 format
        if (count($image_parts) !== 2 || !str_contains($image_parts[0], 'image/')) {
            die("Invalid photo data format.");
        }

        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $allowed_types = ['jpeg', 'png', 'gif'];

        // Validate file type
        if (!in_array($image_type, $allowed_types)) {
            die("Invalid image type. Allowed types: jpeg, png, gif.");
        }

        $image_base64 = base64_decode($image_parts[1]);
        if ($image_base64 === false) {
            die("Invalid base64 data.");
        }

        $file_name = uniqid() . '.' . $image_type;
        $file_path = $folderPath . $file_name;

        // Create the directory if it doesn't exist
        if (!is_dir($folderPath)) {
            if (!mkdir($folderPath, 0777, true) && !is_dir($folderPath)) {
                die("Failed to create upload directory.");
            }
        }

        // Save the image to the server
        if (file_put_contents($file_path, $image_base64) === false) {
            die("Failed to save photo.");
        }

        // Store the photo path in the database
        $sql = $conn->prepare("INSERT INTO employee_attendance (employee_id, photo_path) VALUES (?, ?)");
        $sql->bind_param("is", $employee_id, $file_name);

        if ($sql->execute()) {
            echo "Attendance with photo submitted successfully.";
        } else {
            echo "Failed to submit attendance. Error: " . $conn->error;
        }

        $sql->close();
    } else {
        echo "No photo captured.";
    }
}
?>
