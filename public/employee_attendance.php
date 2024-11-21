<?php
include '../connection/db_conn.php';

$timezone = 'Asia/Manila';
date_default_timezone_set($timezone);

if (isset($_POST['employee_id'])) {
    $output = array('error' => false);

    $employee_ID = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $status = $conn->real_escape_string(strip_tags($_POST['status']));
    $current_time = date('H:i:s');
    $date_now = date('Y-m-d');

    $sql = "SELECT * FROM employee_records WHERE employee_id = '$employee_ID'";
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $id = strip_tags($row['emp_id']);
        $sched = strip_tags($row['schedule_id']);
        
        // Get employee's schedule
        $sql = "SELECT * FROM employee_schedule WHERE id = '$sched'";
        $squery = $conn->query($sql);
        $srow = $squery->fetch_assoc();
        $schedule_start = $srow['time_in'];
        $grace_period_start = date('H:i:s', strtotime($schedule_start . ' -30 minutes'));

        if ($status == 'in') {
            // Check if employee has already timed in today
            $sql = "SELECT * FROM employee_attendance WHERE employee_id = '$id' AND date_attendance = '$date_now' AND time_in IS NOT NULL";
            $query = $conn->query($sql);
            if ($query->num_rows > 0) {
                $output['error'] = true;
                $output['message'] = 'You have already timed in for today';
            } else {
                // Allow clock-in regardless of scheduled time
                $logstatus = ($current_time > $schedule_start) ? 0 : 1;  // Adjust for late clock-in if needed
                $sql = "INSERT INTO employee_attendance (employee_id, date_attendance, time_in, status) VALUES ('$id', '$date_now', NOW(), '$logstatus')";
                if ($conn->query($sql)) {
                    $output['message'] = '<b>Time in: </b> ' . date('h:i A / M-d-Y', strtotime($current_time)) . ' <br><img src="' . (!empty(strip_tags($row["profile_pic"])) ? ''.substr(strip_tags($row['profile_pic']),7)  : './images/no_image.jpg') . '" width="300px" height="200px" style="box-shadow: 5px 5px #888888;"> <p style="font-size: 100%;font-weight: bold;"> Employee name: ' . ucwords(strip_tags($row['first_name'] . ' ' . $row['last_name'])) . '</p>';
                } else {
                    $output['error'] = true;
                    $output['message'] = $conn->error;
                }
            }
        } else {
            // Time-Out Logic
            $sql = "SELECT *, employee_attendance.id AS uid FROM employee_attendance LEFT JOIN employee_records ON employee_records.emp_id=employee_attendance.employee_id WHERE employee_attendance.employee_id = '$id' AND date_attendance = '$date_now'";
            $query = $conn->query($sql);
            if ($query->num_rows < 1) {
                $output['error'] = true;
                $output['message'] = 'Cannot Time Out. No time in record found.';
            } else {
                $row = $query->fetch_assoc();
                if ($row['time_out'] != '00:00:00') {
                    $output['error'] = true;
                    $output['message'] = 'You have already timed out for today';
                } else {
                    // Update time-out record
                    $lognow2 = date('H:i:s');
                    $sql = "UPDATE employee_attendance SET time_out = NOW() WHERE id = '".strip_tags($row['uid'])."'";
                    if ($conn->query($sql)) {
                        $output['message'] = '<b>Time out:</b> '.date('h:i A / M-d-Y', strtotime($lognow2)).' <br><img src="' .(!empty(strip_tags($row["profile_pic"])) ? ''.substr(strip_tags($row['profile_pic']),7)  : './images/no_image.jpg') . '" width="300px" height="200px" style="box-shadow: 5px 5px #888888;"> <p style="font-size: 100%;font-weight: bold;"> Employee name: '.ucwords(strip_tags($row['first_name'].' '.$row['last_name'])).'</p>';

                        // Calculate worked hours
                        $time_in = new DateTime(strip_tags($row['time_in']));
                        $time_out = new DateTime(strip_tags($row['time_out']));
                        $interval = $time_in->diff($time_out);
                        $hrs = $interval->format('%h');
                        $mins = $interval->format('%i');
                        $mins = $mins/60;
                        $int = $hrs + $mins;
                        if($int > 4){
                            $int = $int - 1;
                        }
                        $sql = "UPDATE employee_attendance SET number_of_hour = '$int' WHERE id = '".strip_tags($row['uid'])."'";
                        $conn->query($sql);
                    } else {
                        $output['error'] = true;
                        $output['message'] = $conn->error;
                    }
                }
            }
        }
    } else {
        $output['error'] = true;
        $output['message'] = 'Employee ID not found';
    }

    if ($output['error']) {
        $output['message'] = '<div id="alert-message-danger" class="alert alert-danger alert-dismissible mt20 text-center" style="display:block;">' . $output['message'] . '<script>setTimeout(function() { document.getElementById("alert-message-danger").style.display = "none"; }, 3000);</script></div>';
    } else {
        $output['message'] = '<div id="alert-message-success" class="alert alert-success alert-dismissible mt20 text-center" style="display:block;">' . $output['message'] . '<script>setTimeout(function() { document.getElementById("alert-message-success").style.display = "none"; }, 3000);</script></div>';
    }

    echo json_encode($output);
}
?>
