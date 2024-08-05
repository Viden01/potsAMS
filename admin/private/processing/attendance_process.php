<?php
include '../../connection/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $status = $conn->real_escape_string(strip_tags($_POST['status']));
    $date = date('Y-m-d');
    $time_now = date('H:i:s');

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

        // Check if attendance exists for today
        $sql = "SELECT * FROM employee_attendance WHERE employee_id = '$emp' AND date_attendance = '$date'";
        $query = $conn->query($sql);

        if ($status == 'in') {
            if ($query->num_rows > 0) {
                echo '<div class="alert alert-danger">
                    <strong><i class="fas fa-times"></i>&nbsp;You have already clocked in today!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            } else {
                // Insert time in
                $sql = "INSERT INTO employee_attendance (employee_id, date_attendance, time_in) VALUES ('$emp', '$date', '$time_now')";
                if ($conn->query($sql)) {
                    echo '<div class="alert alert-success">
                        <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Time in recorded successfully</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    echo '<div class="alert alert-danger">
                        <strong><i class="fas fa-times"></i>&nbsp;Insert Failed: ' . $conn->error . '</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        } elseif ($status == 'out') {
            if ($query->num_rows == 0) {
                echo '<div class="alert alert-danger">
                    <strong><i class="fas fa-times"></i>&nbsp;You haven\'t clocked in today!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            } else {
                // Update time out
                $sql = "UPDATE employee_attendance SET time_out='$time_now' WHERE employee_id='$emp' AND date_attendance='$date'";
                if ($conn->query($sql)) {
                    echo '<div class="alert alert-success">
                        <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Time out recorded successfully</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';

                    // Calculate hours worked
                    $sql = "SELECT time_in FROM employee_attendance WHERE employee_id='$emp' AND date_attendance='$date'";
                    $query = $conn->query($sql);
                    $row = $query->fetch_assoc();

                    $time_in = new DateTime($row['time_in']);
                    $time_out = new DateTime($time_now);
                    $interval = $time_in->diff($time_out);
                    $hrs = $interval->format('%h');
                    $mins = $interval->format('%i');
                    $mins = $mins / 60;
                    $int = $hrs + $mins;
                    if ($int > 4) {
                        $int = $int - 1; // Deduct lunch break if worked hours exceed 4
                    }

                    $sql = "UPDATE employee_attendance SET number_of_hour = '$int' WHERE employee_id='$emp' AND date_attendance='$date'";
                    $conn->query($sql);
                } else {
                    echo '<div class="alert alert-danger">
                        <strong><i class="fas fa-times"></i>&nbsp;Update Failed: ' . $conn->error . '</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        }
    }
}
?>
