<?php
include($_SERVER['DOCUMENT_ROOT'] . '/connection/db_conn.php'); // Adjust the path if necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the database connection exists
    if (!$conn) {
        http_response_code(500);
        die("Database connection failed.");
    }

    // Escape and sanitize input data
    $employee_id = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $photo_data = $_POST['photo'];
    $attendance_type = $conn->real_escape_string(strip_tags($_POST['attendance_type']));
    $time_in = $conn->real_escape_string(strip_tags($_POST['time_in']));
    $time_out = $conn->real_escape_string(strip_tags($_POST['time_out']));

    // Validate employee_id
    if (empty($employee_id)) {
        http_response_code(400);
        die("Employee ID is required.");
    }

    // Check if employee exists
    $check_employee = $conn->prepare("SELECT id FROM employees WHERE id = ?");
    $check_employee->bind_param("i", $employee_id);
    $check_employee->execute();
    $check_employee->store_result();

    if ($check_employee->num_rows === 0) {
        http_response_code(404);
        die("Employee not found.");
    }
    $check_employee->close();

    // Decode and save the image
    $file_name = null;
    if (!empty($photo_data)) {
        $folderPath = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
        $image_parts = explode(";base64,", $photo_data);

        // Validate base64 format
        if (count($image_parts) !== 2 || !str_contains($image_parts[0], 'image/')) {
            http_response_code(400);
            die("Invalid photo data format.");
        }

        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $allowed_types = ['jpeg', 'png', 'gif'];

        // Validate file type
        if (!in_array($image_type, $allowed_types)) {
            http_response_code(400);
            die("Invalid image type. Allowed types: jpeg, png, gif.");
        }

        $image_base64 = base64_decode($image_parts[1]);
        if ($image_base64 === false) {
            http_response_code(400);
            die("Invalid base64 data.");
        }

        $file_name = uniqid() . '.' . $image_type;
        $file_path = $folderPath . $file_name;

        // Create the directory if it doesn't exist
        if (!is_dir($folderPath)) {
            if (!mkdir($folderPath, 0777, true) && !is_dir($folderPath)) {
                http_response_code(500);
                die("Failed to create upload directory.");
            }
        }

        // Save the image to the server
        if (file_put_contents($file_path, $image_base64) === false) {
            http_response_code(500);
            die("Failed to save photo.");
        }
    }

    // Insert attendance record into the database
    $sql = $conn->prepare("INSERT INTO employee_attendance (employee_id, photo_path, attendance_type, time_in, time_out) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param("issss", $employee_id, $file_name, $attendance_type, $time_in, $time_out);

    if ($sql->execute()) {
        echo "Attendance submitted successfully.";
    } else {
        http_response_code(500);
        echo "Failed to submit attendance. Error: " . $conn->error;
    }

    $sql->close();
}
?>
