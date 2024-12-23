<?php
session_start(); // Start the session to store messages

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
    $attendance_type = $_POST['attendance_type']; // Get the attendance type from the form

    // Validate employee ID
    if (empty($employee_id)) {
        $_SESSION['status'] = 'Employee ID is required.';
        $_SESSION['status_icon'] = 'error';
        header("Location: index.php");
        exit();
    }

    // Decode and save the image if photo data is provided
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

        // Get the current date and time
        $current_date_time = new DateTime();
        $current_date_time->modify('+8 hours'); // Add 8 hours to current time

        // Format the date and time with 8-hour offset
        $adjusted_date = $current_date_time->format('Y-m-d');
        $adjusted_time = $current_date_time->format('H:i:s');

        // If "time_in" is selected, insert time_in and photo path
        if ($attendance_type === 'time_in') {
            // Check if employee has already clocked in today with adjusted date
            $checkSql = "SELECT * FROM employee_attendance WHERE employee_id = '$employee_id' AND date_attendance = '$adjusted_date'";
            $result = $conn->query($checkSql);

            if ($result->num_rows > 0) {
                // Employee has already clocked in today
                $_SESSION['status'] = 'You have already time in today.';
                $_SESSION['status_icon'] = 'error';
                header("Location: index.php");
                exit();
            }

            $sql = "
                INSERT INTO employee_attendance (employee_id, date_attendance, time_in, time_out, photo_path) 
                VALUES ('$employee_id', '$adjusted_date', '$adjusted_time', 'null', '$file_name')
            ";

            if ($conn->query($sql)) {
                $_SESSION['status'] = 'Time in recorded successfully.';
                $_SESSION['status_icon'] = 'success';
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['status'] = 'Failed to submit attendance. Error: ' . $conn->error;
                $_SESSION['status_icon'] = 'error';
                header("Location: index.php");
                exit();
            }
        }

        // If "time_out" is selected, update time_out and add 8 hours to date_attendance
        elseif ($attendance_type === 'time_out') {
            // Check if employee has already clocked in today with adjusted date
            $checkSql = "SELECT * FROM employee_attendance WHERE employee_id = '$employee_id' AND date_attendance = '$adjusted_date'";
            $result = $conn->query($checkSql);

            if ($result->num_rows === 0) {
                // Employee hasn't clocked in, so can't clock out
                $_SESSION['status'] = 'You need to clock in before you can clock out.';
                $_SESSION['status_icon'] = 'error';
                header("Location: index.php");
                exit();
            }

            $sql = "
                UPDATE employee_attendance 
                SET time_out = '$adjusted_time', 
                    photo_path = '$file_name', 
                    date_attendance = '$adjusted_date' 
                WHERE employee_id = '$employee_id' AND date_attendance = '$adjusted_date'
            ";

            if ($conn->query($sql)) {
                $_SESSION['status'] = 'Time out recorded successfully.';
                $_SESSION['status_icon'] = 'success';
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['status'] = 'Failed to submit attendance. Error: ' . $conn->error;
                $_SESSION['status_icon'] = 'error';
                header("Location: index.php");
                exit();
            }
        }

    } else {
        $_SESSION['status'] = 'No photo captured.';
        $_SESSION['status_icon'] = 'error';
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['status'] = 'Invalid request method.';
    $_SESSION['status_icon'] = 'error';
    header("Location: index.php");
    exit();
}
?>
