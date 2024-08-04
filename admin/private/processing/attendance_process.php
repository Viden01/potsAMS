<?php
include '../../connection/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve POST data
    $employee_id = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $time_in = $conn->real_escape_string(strip_tags($_POST['time_in']));
    $time_out = $conn->real_escape_string(strip_tags($_POST['time_out']));
    $date_attendance = $conn->real_escape_string(strip_tags($_POST['date_attendance']));

    // Convert time to the correct format (HH:MM:SS)
    $time_in = date('H:i:s', strtotime($time_in));
    $time_out = date('H:i:s', strtotime($time_out));

    // Check if the employee exists
    $sql = "SELECT * FROM employee_records WHERE employee_id = '$employee_id'";
    $query = $conn->query($sql);

    if ($query->num_rows < 1) {
        echo '<div class="alert alert-danger">Employee not found!</div>';
    } else {
        $row = $query->fetch_assoc();
        $emp = htmlentities($row['employee_id']);

        // Check if attendance for the specific date already exists
        $sql = "SELECT * FROM employee_attendance WHERE employee_id = '$emp' AND date_attendance = '$date_attendance'";
        $query = $conn->query($sql);

        if ($query->num_rows > 0) {
            echo '<div class="alert alert-danger">Attendance for this day already exists!</div>';
        } else {
            // Retrieve the employee's schedule
            $sched = $row['schedule_id'];
            $sql = "SELECT * FROM employee_schedule WHERE id = '$sched'";
            $squery = $conn->query($sql);
            $scherow = $squery->fetch_assoc();

            // Determine log status (On Time or Late)
            $logstatus = ($time_in > $scherow['time_in']) ? 0 : 1;

            // Insert the attendance record
            $sql = "INSERT INTO employee_attendance (employee_id, date_attendance, time_in, time_out, status) 
                    VALUES ('$emp', '$date_attendance', '$time_in', '$time_out', '$logstatus')";

            if ($conn->query($sql)) {
                echo '<div class="alert alert-success">Attendance added successfully.</div>';
            } else {
                echo '<div class="alert alert-danger">Insert failed! Please try again.</div>';
            }
        }
    }
}
?>
