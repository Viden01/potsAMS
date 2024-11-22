<?php
    // Set the timezone to Asia/Manila
    date_default_timezone_set('Asia/Manila');

    include '../../connection/db_conn.php';
    
    $employee = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $date = $conn->real_escape_string(strip_tags($_POST['date_attendance']));
    
    // Get the current time in Asia/Manila timezone
    $current_time = date('H:i:s'); // Time format 'H:i:s' (24-hour format)
    
    // Use current time for time_in if the user doesn't provide it
    $time_in = isset($_POST['time_in']) ? $conn->real_escape_string(strip_tags($_POST['time_in'])) : $current_time;
    $time_in = date('H:i:s', strtotime($time_in));
    
    // Use current time for time_out if the user doesn't provide it
    $time_out = isset($_POST['time_out']) ? $conn->real_escape_string(strip_tags($_POST['time_out'])) : $current_time;
    $time_out = date('H:i:s', strtotime($time_out));

    // Fetch employee details
    $sql = "SELECT * FROM employee_records WHERE employee_id = '$employee'";
    $query = $conn->query($sql);

    if($query->num_rows < 1){
        echo '<div class="alert alert-danger">
              <strong><i class="fas fa-times"></i>&nbsp;Employee not found!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
    else{
        $row = $query->fetch_assoc();
        $emp = htmlentities($row['emp_id']);

        // Check if the employee already has an attendance record for the day
        $sql = "SELECT * FROM employee_attendance WHERE employee_id = '$emp' AND date_attendance = '$date'";
        $query = $conn->query($sql);

        if($query->num_rows > 0){
            echo '<div class="alert alert-danger">
                  <strong><i class="fas fa-times"></i>&nbsp;Employee attendance for the day exists!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
        }
        else{
            // Fetch employee schedule details
            $sched = $row['schedule_id'];
            $sql = "SELECT * FROM employee_schedule WHERE id = '$sched'";
            $squery = $conn->query($sql);
            $scherow = $squery->fetch_assoc();

            // Determine status based on time_in and schedule time
            $logstatus = (strtotime($time_in) > strtotime($scherow['time_in'])) ? 0 : 1; // 0 = late, 1 = on time

            // Insert the attendance record into the database
            $sql = "INSERT INTO employee_attendance (employee_id, date_attendance, time_in, time_out, status) VALUES ('$emp', '$date', '$time_in', '$time_out', '$logstatus')";
            if($conn->query($sql)){
                echo '<div class="alert alert-success">
                       <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Attendance added successfully</strong>
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
                $id = $conn->insert_id;

                // Fetch the schedule again to ensure we're working with the correct times
                $sql = "SELECT * FROM employee_records LEFT JOIN employee_schedule ON employee_schedule.id=employee_records.schedule_id WHERE employee_records.emp_id = '$emp'";
                $query = $conn->query($sql);
                $srow = $query->fetch_assoc();

                // Adjust time_in and time_out based on the schedule
                if($srow['time_in'] > $time_in){
                    $time_in = htmlentities($srow['time_in']);
                }

                if($srow['time_out'] < $time_out){
                    $time_out = htmlentities($srow['time_out']);
                }

                // Calculate the number of hours worked
                $time_in = new DateTime($time_in);
                $time_out = new DateTime($time_out);
                $interval = $time_in->diff($time_out);
                $hrs = $interval->format('%h');
                $mins = $interval->format('%i');
                $mins = $mins / 60;
                $int = $hrs + $mins;
                if($int > 4){
                    $int = $int - 1; // Subtract 1 hour for break time if worked more than 4 hours
                }

                // Update the attendance record with the number of hours worked
                $sql = "UPDATE employee_attendance SET number_of_hour = '$int' WHERE id = '$id'";
                $conn->query($sql);
            }
            else{
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
