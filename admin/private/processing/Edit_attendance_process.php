<?php
include '../../connection/db_conn.php';

if (isset($_POST['id']) && isset($_POST['employee_name']) && isset($_POST['time_in']) && isset($_POST['time_out']) && isset($_POST['date_attendance'])) {
    $id = $conn->real_escape_string(strip_tags($_POST['id']));
    $employee_name = $conn->real_escape_string(strip_tags($_POST['employee_name']));
    $date = $conn->real_escape_string(strip_tags($_POST['date_attendance']));
    $time_in = $conn->real_escape_string(strip_tags($_POST['time_in']));
    $time_in = date('H:i:s', strtotime($time_in));
    $time_out = $conn->real_escape_string(strip_tags($_POST['time_out']));
    $time_out = date('H:i:s', strtotime($time_out));

    // Debugging: Check if the employee_name is set correctly
    if (empty($employee_name)) {
        echo '<div class="alert alert-warning">
              <strong><i class="fas fa-times"></i>;&nbsp;Employee name is empty!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
        exit();
    }

    // Update the employee name in the employee records table
    $sql_update_name = "UPDATE employee_records 
                        SET employee_name = '$employee_name' 
                        WHERE emp_id = (SELECT employee_id FROM employee_attendance WHERE id = '$id')";

    if (!$conn->query($sql_update_name)) {
        echo '<div class="alert alert-warning">
              <strong><i class="fas fa-times"></i>;&nbsp;Update Employee Name Failed: ' . $conn->error . '</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
        exit();
    }

    // Update the attendance record
    $sql = "UPDATE employee_attendance 
            SET date_attendance = '$date', time_in = '$time_in', time_out = '$time_out' 
            WHERE id = '$id'";

    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">
             <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Attendance updated successfully</strong>
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';

        $sql = "SELECT * FROM employee_attendance WHERE id = '$id'";
        $query = $conn->query($sql);
        $row = $query->fetch_assoc();
        $emp = htmlentities($row['employee_id']);

        $sql = "SELECT * FROM employee_records 
                LEFT JOIN employee_schedule ON employee_schedule.id = employee_records.schedule_id 
                WHERE employee_records.emp_id = '$emp'";
        $query = $conn->query($sql);
        $srow = $query->fetch_assoc();

        // Updates
        $logstatus = ($time_in > $srow['time_in']) ? 0 : 1;

        if ($srow['time_in'] > $time_in) {
            $time_in = htmlentities($srow['time_in']);
        }

        if ($srow['time_out'] < $time_out) {
            $time_out = htmlentities($srow['time_out']);
        }

        $time_in = new DateTime($time_in);
        $time_out = new DateTime($time_out);
        $interval = $time_in->diff($time_out);
        $hrs = $interval->format('%h');
        $mins = $interval->format('%i');
        $mins = $mins / 60;
        $int = $hrs + $mins;
        if ($int > 4) {
            $int = $int - 1;
        }

        $sql = "UPDATE employee_attendance 
                SET number_of_hour = '$int', status = '$logstatus' 
                WHERE id = '$id'";
        $conn->query($sql);
    } else {
        echo '<div class="alert alert-warning">
              <strong><i class="fas fa-times"></i>;&nbsp;Update Attendance Failed: ' . $conn->error . '</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
}
?>
