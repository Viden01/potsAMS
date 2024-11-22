<?php
session_start();
include('config/db_connection.php'); // Database connection file

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle Time In
if(isset($_POST['time_in'])) {
    $employee_id = sanitize($_POST['employee_id']);
    $date_attendance = date('Y-m-d');
    $time_in = date('H:i:s');
    
    // Check if employee has already timed in today
    $check_sql = "SELECT * FROM employee_attendance 
                  WHERE employee_id = ? AND date_attendance = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $employee_id, $date_attendance);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if($check_result->num_rows == 0) {
        // Determine punctuality status
        $scheduled_time = '09:00:00'; // Example scheduled start time
        $status = ($time_in <= $scheduled_time) ? 1 : 0;
        
        $insert_sql = "INSERT INTO employee_attendance 
                       (employee_id, date_attendance, time_in, status) 
                       VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("issi", $employee_id, $date_attendance, $time_in, $status);
        
        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Time In Recorded']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error Recording Time In']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Already Timed In Today']);
    }
}

// Handle Time Out
if(isset($_POST['time_out'])) {
    $employee_id = sanitize($_POST['employee_id']);
    $date_attendance = date('Y-m-d');
    $time_out = date('H:i:s');
    
    $update_sql = "UPDATE employee_attendance 
                   SET time_out = ?, 
                       total_hours = TIMEDIFF(?, time_in) 
                   WHERE employee_id = ? AND date_attendance = ? AND time_out IS NULL";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $time_out, $time_out, $employee_id, $date_attendance);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Time Out Recorded']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error Recording Time Out']);
    }
}

// Fetch Attendance Records
$sql = "SELECT 
            ea.*, 
            er.first_name, 
            er.last_name, 
            er.employee_id AS emp_id
        FROM 
            employee_attendance ea
        LEFT JOIN 
            employee_records er ON er.emp_id = ea.employee_id 
        ORDER BY 
            ea.date_attendance DESC, 
            ea.time_in DESC";

$query = $conn->query($sql);

// Display Attendance Records
if ($query->num_rows > 0) {
    while($row = $query->fetch_assoc()) {
        // Timezone and Time Conversion Fix
        $time_in = new DateTime($row['time_in'], new DateTimeZone('UTC'));
        $time_out = !empty($row['time_out']) ? 
            new DateTime($row['time_out'], new DateTimeZone('UTC')) : 
            null;
        
        // Convert to local timezone (adjust as needed)
        $time_in->setTimezone(new DateTimeZone('America/New_York'));
        if ($time_out) {
            $time_out->setTimezone(new DateTimeZone('America/New_York'));
        }
        
        echo "
        <tr>
            <td>".$row['emp_id']."</td>
            <td>".htmlentities($row['first_name'].' '.$row['last_name'])."</td>
            <td>".$time_in->format('h:i A')."</td>
            <td>".($time_out ? $time_out->format('h:i A') : 'Not Clocked Out')."</td>
            <td>".($row['status'] ? 'On Time' : 'Late')."</td>
            <td>".$time_in->format('M d, Y')."</td>
        </tr>";
    }
}

// Close connection
$conn->close();
?>

<!-- JavaScript for Time Handling -->
<script>
$(document).ready(function() {
    // Time In Button Handler
    $('#time_in_btn').click(function() {
        $.ajax({
            url: 'attendance.php',
            method: 'POST',
            data: {
                time_in: true,
                employee_id: $('#employee_id').val()
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert(response.message);
                    location.reload(); // Refresh page to show updated records
                } else {
                    alert(response.message);
                }
            }
        });
    });

    // Time Out Button Handler
    $('#time_out_btn').click(function() {
        $.ajax({
            url: 'attendance.php',
            method: 'POST',
            data: {
                time_out: true,
                employee_id: $('#employee_id').val()
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert(response.message);
                    location.reload(); // Refresh page to show updated records
                } else {
                    alert(response.message);
                }
            }
        });
    });
});
</script>