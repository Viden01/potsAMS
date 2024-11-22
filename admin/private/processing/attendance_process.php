<?php
include '../../connection/db_conn.php';

// Check if all required fields are present
if (isset($_POST['employee_id'], $_POST['latitude'], $_POST['longitude'], $_FILES['selfie'])) {

    // Sanitize inputs
    $employee = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $latitude = $conn->real_escape_string(strip_tags($_POST['latitude']));
    $longitude = $conn->real_escape_string(strip_tags($_POST['longitude']));
    
    // Handling selfie upload
    $targetDir = "../../uploads/selfies/";
    $fileName = basename($_FILES['selfie']['name']);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['selfie']['tmp_name'], $targetFilePath)) {
        
        // Get employee data
        $sql = "SELECT * FROM employee_records WHERE employee_id = '$employee'";
        $query = $conn->query($sql);

        if ($query->num_rows < 1) {
            echo '<div class="alert alert-danger">
                    <strong><i class="fas fa-times"></i>&nbsp;Employee not found!</strong>
                  </div>';
        } else {
            $row = $query->fetch_assoc();
            $emp = htmlentities($row['emp_id']);
            
            // Check if attendance already exists for today
            $date = date('Y-m-d'); // Assuming attendance is always for the current date
            $sql = "SELECT * FROM employee_attendance WHERE employee_id = '$emp' AND date_attendance = '$date'";
            $query = $conn->query($sql);

            if ($query->num_rows > 0) {
                echo '<div class="alert alert-danger">
                        <strong><i class="fas fa-times"></i>&nbsp;Attendance for today already exists!</strong>
                      </div>';
            } else {
                // Default time_in and time_out handling (Current time recorded)
                $time_in = date('H:i:s');

                // Schedule and late detection
                $sched = $row['schedule_id'];
                $sql = "SELECT * FROM employee_schedule WHERE id = '$sched'";
                $squery = $conn->query($sql);
                $scherow = $squery->fetch_assoc();
                $logstatus = ($time_in > $scherow['time_in']) ? 0 : 1;

                // Insert attendance record with selfie and geolocation
                $sql = "INSERT INTO employee_attendance (employee_id, date_attendance, time_in, selfie_path, latitude, longitude, status)
                        VALUES ('$emp', '$date', '$time_in', '$targetFilePath', '$latitude', '$longitude', '$logstatus')";

                if ($conn->query($sql)) {
                    echo '<div class="alert alert-success">
                            <strong><i class="fas fa-check"></i>&nbsp;Attendance recorded successfully!</strong>
                          </div>';
                } else {
                    echo '<div class="alert alert-danger">
                            <strong><i class="fas fa-times"></i>&nbsp;Error recording attendance!</strong>
                          </div>';
                }
            }
        }
    } else {
        echo '<div class="alert alert-danger">
                <strong><i class="fas fa-times"></i>&nbsp;Error uploading selfie.</strong>
              </div>';
    }
} else {
    echo '<div class="alert alert-warning">
            <strong><i class="fas fa-exclamation-triangle"></i>&nbsp;All fields are required!</strong>
          </div>';
}
?>
