<?php
include '../../connection/db_conn.php';

// Check if required POST variables are set
if (isset($_POST['employee_id'], $_POST['status'])) {
    // Sanitize and validate input
    $employee = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $status = $conn->real_escape_string(strip_tags($_POST['status']));
    
    $date = date('Y-m-d'); // Current date
    $time = date('H:i:s'); // Current time

    // Check if employee exists
    $sql = "SELECT emp_id, schedule_id FROM employee_records WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $employee);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows < 1) {
        echo 'Error: Employee not found!';
    } else {
        $row = $result->fetch_assoc();
        $emp = htmlentities($row['emp_id']);
        $sched = $row['schedule_id'];

        // Check if attendance for the day exists
        $sql = "SELECT * FROM employee_attendance WHERE employee_id = ? AND date_attendance = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $emp, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($status == 'in') {
            if ($result->num_rows > 0) {
                echo 'Error: Attendance already recorded for today!';
            } else {
                // Insert time in
                $sql = "INSERT INTO employee_attendance (employee_id, date_attendance, time_in) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sss', $emp, $date, $time);
                
                if ($stmt->execute()) {
                    echo 'Success: Time In recorded successfully';
                } else {
                    echo 'Error: Insert Failed!';
                }
            }
        } else if ($status == 'out') {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['time_out'] != null) {
                    echo 'Error: Time Out already recorded for today!';
                } else {
                    // Update time out
                    $sql = "UPDATE employee_attendance SET time_out = ? WHERE employee_id = ? AND date_attendance = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('sss', $time, $emp, $date);
                    
                    if ($stmt->execute()) {
                        echo 'Success: Time Out recorded successfully';
                        
                        // Calculate hours
                        $time_in = new DateTime($row['time_in']);
                        $time_out = new DateTime($time);
                        $interval = $time_in->diff($time_out);
                        $hrs = $interval->format('%h');
                        $mins = $interval->format('%i');
                        $mins = $mins / 60;
                        $total_hours = $hrs + $mins;

                        if ($total_hours > 4) {
                            $total_hours -= 1;
                        }

                        // Update number of hours
                        $sql = "UPDATE employee_attendance SET number_of_hour = ? WHERE employee_id = ? AND date_attendance = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('dss', $total_hours, $emp, $date);
                        $stmt->execute();
                    } else {
                        echo 'Error: Update Failed!';
                    }
                }
            } else {
                echo 'Error: No Time In recorded for today!';
            }
        }
    }
} else {
    echo 'Error: Missing required data!';
}
?>
