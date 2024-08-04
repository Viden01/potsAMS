<?php
    include '../../connection/db_conn.php';

    $employee = $conn->real_escape_string(strip_tags($_POST['employee_id']));
    $time_in = date('H:i:s'); // Automatically set current time for time_in
    $time_out = null; // Set time_out to null initially
    $date = date('Y-m-d'); // Automatically set current date

    $sql = "SELECT * FROM employee_records WHERE employee_id = '$employee'";
    $query = $conn->query($sql);

    if($query->num_rows < 1){
        echo '<div class="alert alert-danger">
              <strong><i class="fas fa-times"></i>;&nbsp;Employee not found!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              </div>';
    } else {
        $row = $query->fetch_assoc();
        $emp = htmlentities($row['emp_id']);

        $sql = "SELECT * FROM employee_attendance WHERE employee_id = '$emp' AND date_attendance = '$date'";
        $query = $conn->query($sql);

        if($query->num_rows > 0){
            echo '<div class="alert alert-danger">
                  <strong><i class="fas fa-times"></i>;&nbsp;Employee attendance for the day exists!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>';
        } else {
            // Updates
            $sched = $row['schedule_id'];
            $sql = "SELECT * FROM employee_schedule WHERE id = '$sched'";
            $squery = $conn->query($sql);
            $scherow = $squery->fetch_assoc();
            $logstatus = ($time_in > $scherow['time_in']) ? 0 : 1;

            $sql = "INSERT INTO employee_attendance (employee_id, date_attendance, time_in, time_out, status) VALUES ('$emp', '$date', '$time_in', '$time_out', '$logstatus')";
            if($conn->query($sql)){
                echo '<div class="alert alert-success">
                      <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Attendance added successfully</strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                      </div>';
                $id = $conn->insert_id;

                // Additional calculations (e.g., updating time_out later) would go here

                // Code for updating number_of_hour will be handled when time_out is recorded
            } else {
                echo '<div class="alert alert-warning">
                      <strong><i class="fas fa-times"></i>;&nbsp;Insert Failed!</strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                      </div>';
            }
        }
    }
?>
