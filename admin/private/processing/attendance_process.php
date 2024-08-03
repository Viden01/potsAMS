<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../connection/db_conn.php';

if (isset($_POST['employee_id'], $_POST['status'])) {
    $employee = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $status = $conn->real_escape_string(strip_tags($_POST['status']));
    
    $date = date('Y-m-d'); // Current date
    $time = date('H:i:s'); // Current time

    $sql = "SELECT emp_id, schedule_id FROM employee_records WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('s', $employee);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows < 1) {
            echo 'Error: Employee not found!';
        } else {
            $row = $result->fetch_assoc();
            $emp = htmlentities($row['emp_id']);
            $sched = $row['schedule_id'];

            $sql = "SELECT * FROM employee_attendance WHERE employee_id = ? AND date_attendance = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('ss', $emp, $date);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($status == 'in') {
                    if ($result->num_rows > 0) {
                        echo 'Error: Attendance already recorded for today!';
                    } else {
                        $sql = "INSERT INTO employee_attendance (employee_id, date_attendance, time_in) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        if ($stmt) {
                            $stmt->bind_param('sss', $emp, $date, $time);
                            if ($stmt->execute()) {
                                echo 'Success: Time In recorded successfully';
                            } else {
                                echo 'Error: Insert Failed!';
                            }
                        } else {
                            echo 'Error: Prepare statement failed!';
                        }
                    }
                } else if ($status == 'out') {
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        if ($row['time_out'] != null) {
                            echo 'Error: Time Out already recorded for today!';
                        } else {
                            $sql = "UPDATE employee_attendance SET time_out = ? WHERE employee_id = ? AND date_attendance = ?";
                            $stmt = $conn->prepare($sql);
                            if ($stmt) {
                                $stmt->bind_param('sss', $time, $emp, $date);
                                if ($stmt->execute()) {
                                    echo 'Success: Time Out recorded successfully';

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

                                    $sql = "UPDATE employee_attendance SET number_of_hour = ? WHERE employee_id = ? AND date_attendance = ?";
                                    $stmt = $conn->prepare($sql);
                                    if ($stmt) {
                                        $stmt->bind_param('dss', $total_hours, $emp, $date);
                                        $stmt->execute();
                                    } else {
                                        echo 'Error: Prepare statement failed!';
                                    }
                                } else {
                                    echo 'Error: Update Failed!';
                                }
                            } else {
                                echo 'Error: Prepare statement failed!';
                            }
                        }
                    } else {
                        echo 'Error: No Time In recorded for today!';
                    }
                }
            } else {
                echo 'Error: Prepare statement failed!';
            }
        }
    } else {
        echo 'Error: Prepare statement failed!';
    }
} else {
    echo 'Error: Missing required data!';
}
?>
