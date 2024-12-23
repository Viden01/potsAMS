<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/connection/db_conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$conn) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
        exit();
    }

    $employee_id = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $photo_data = $_POST['photo'];
    $attendance_type = $_POST['attendance_type'];
    $latitude = $conn->real_escape_string(strip_tags($_POST['latitude']));
    $longitude = $conn->real_escape_string(strip_tags($_POST['longitude']));

    if (empty($employee_id)) {
        $_SESSION['status'] = 'Employee ID is required.';
        $_SESSION['status_icon'] = 'error';
        header("Location: index.php");
        exit();
    }

    $file_name = "";
    if (!empty($photo_data)) {
        $folderPath = "../../admin/private/images/";
        $image_parts = explode(";base64,", $photo_data);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file_name = uniqid() . '.' . $image_type;
        $file_path = $folderPath . $file_name;

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        if (!file_put_contents($file_path, $image_base64)) {
            $_SESSION['status'] = 'Failed to save photo.';
            $_SESSION['status_icon'] = 'error';
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['status'] = 'No photo captured.';
        $_SESSION['status_icon'] = 'error';
        header("Location: index.php");
        exit();
    }

    $current_date_time = new DateTime();
    $current_date_time->modify('+8 hours');
    $adjusted_date = $current_date_time->format('Y-m-d');
    $adjusted_time = $current_date_time->format('H:i:s');

    if ($attendance_type === 'time_in') {
        $checkSql = "SELECT * FROM employee_attendance WHERE employee_id = '$employee_id' AND date_attendance = '$adjusted_date'";
        $result = $conn->query($checkSql);

        if ($result->num_rows > 0) {
            $_SESSION['status'] = 'You have already time in today.';
            $_SESSION['status_icon'] = 'error';
            header("Location: index.php");
            exit();
        }

        $sql = "
            INSERT INTO employee_attendance (employee_id, date_attendance, time_in, time_out, photo_path, latitude, longitude) 
            VALUES ('$employee_id', '$adjusted_date', '$adjusted_time', NULL, '$file_name', '$latitude', '$longitude')
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
    } elseif ($attendance_type === 'time_out') {
        $checkSql = "SELECT * FROM employee_attendance WHERE employee_id = '$employee_id' AND date_attendance = '$adjusted_date'";
        $result = $conn->query($checkSql);

        if ($result->num_rows === 0) {
            $_SESSION['status'] = 'You need to clock in before you can clock out.';
            $_SESSION['status_icon'] = 'error';
            header("Location: index.php");
            exit();
        }

        $sql = "
            UPDATE employee_attendance 
            SET time_out = '$adjusted_time', 
                photo_path = '$file_name', 
                latitude = '$latitude', 
                longitude = '$longitude' 
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
    $_SESSION['status'] = 'Invalid request method.';
    $_SESSION['status_icon'] = 'error';
    header("Location: index.php");
    exit();
}
?>
