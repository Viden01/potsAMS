<?php
include '../../connection/db_conn.php';

$employee = $conn->real_escape_string(strip_tags($_POST['employee_id']));
$time_in = $conn->real_escape_string(strip_tags($_POST['time_in']));
$date = $conn->real_escape_string(strip_tags($_POST['date_attendance']));
$time_out = $conn->real_escape_string(strip_tags($_POST['time_out']));
$photo = isset($_POST['photo']) ? $_POST['photo'] : null;

// Format time inputs
$time_in = date('H:i:s', strtotime($time_in));
$time_out = date('H:i:s', strtotime($time_out));

// Check if employee exists
$sql = "SELECT * FROM employee_records WHERE employee_id = '$employee'";
$query = $conn->query($sql);

if ($query->num_rows < 1) {
    echo '<div class="alert alert-danger">
        <strong><i class="fas fa-times"></i>&nbsp;Employee not found!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';
} else {
    $row = $query->fetch_assoc();
    $emp = htmlentities($row['emp_id']);

    // Check if attendance for the day already exists
    $sql = "SELECT * FROM employee_attendance WHERE employee_id = '$emp' AND date_attendance = '$date'";
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        echo '<div class="alert alert-danger">
            <strong><i class="fas fa-times"></i>&nbsp;Employee attendance for the day exists!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';
    } else {
        // Handle employee schedule and status
        $sched = $row['schedule_id'];
        $sql = "SELECT * FROM employee_schedule WHERE id = '$sched'";
        $squery = $conn->query($sql);
        $scherow = $squery->fetch_assoc();
        $logstatus = ($time_in > $scherow['time_in']) ? 0 : 1;

        // Save photo if provided
        $photoPath = null;
        if ($photo) {
            $photo = str_replace('data:image/png;base64,', '', $photo);
            $photo = str_replace(' ', '+', $photo);
            $photoData = base64_decode($photo);

            $uploadDir = '../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $photoPath = $uploadDir . uniqid() . '.png';
            file_put_contents($photoPath, $photoData);

            // Store relative path for database
            $photoPath = str_replace('../../', '', $photoPath);
        }

        // Insert attendance record
        $sql = "INSERT INTO employee_attendance (employee_id, date_attendance, time_in, time_out, status, photo_path) 
                VALUES ('$emp', '$date', '$time_in', '$time_out', '$logstatus', '$photoPath')";
        if ($conn->query($sql)) {
            echo '<div class="alert alert-success">
                <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Attendance added successfully</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
            $id = $conn->insert_id;

            // Calculate hours worked
            $time_in_obj = new DateTime($time_in);
            $time_out_obj = new DateTime($time_out);
            $interval = $time_in_obj->diff($time_out_obj);
            $hrs = $interval->format('%h');
            $mins = $interval->format('%i') / 60;
            $int = $hrs + $mins;
            if ($int > 4) {
                $int = $int - 1;
            }

            // Update hours worked in attendance record
            $sql = "UPDATE employee_attendance SET number_of_hour = '$int' WHERE id = '$id'";
            $conn->query($sql);
        } else {
            echo '<div class="alert alert-warning">
                <strong><i class="fas fa-times"></i>&nbsp;Insert Failed!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
        }
    }
}
?>
