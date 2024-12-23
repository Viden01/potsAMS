<?php
include($_SERVER['DOCUMENT_ROOT'] . '/connection/db_conn.php'); // Adjust the path if necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the database connection exists
    if (!$conn) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
        exit();
    }

    // Escape and sanitize input data
    $employee_id = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $photo_data = $_POST['photo'];

    // Validate employee ID
    if (empty($employee_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Employee ID is required.']);
        exit();
    }

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

        // Store the photo path, current date, time, and default timeout in the database
        $sql = "
            INSERT INTO employee_attendance (employee_id, date_attendance, time_in, time_out, photo_path) 
            VALUES ('$employee_id', CURDATE(), CURTIME(), '00:00:00', '$file_name')
        ";

        if ($conn->query($sql)) {
            echo json_encode(['status' => 'success', 'message' => 'Attendance recorded successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to submit attendance. Error: ' . $conn->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No photo captured.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
