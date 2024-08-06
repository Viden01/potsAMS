<?php include('header/head.php');?>
<?php include('header/sidebar_menu.php');?>

<!-- end navbar side -->
<!--  page-wrapper -->
<div id="page-wrapper">

    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
        </div>
        <!--End Page Header -->
    </div>

    <div class="row">
        <!-- Welcome -->
        <div class="col-lg-12">
            <div class="alert alert-info">
                <i class="fa fa-folder-open"></i><b>&nbsp;Hello ! </b>Welcome Back <b><?php echo ucwords(htmlentities($name)); ?></b>
            </div>
        </div>
        <!--end  Welcome -->
    </div>

    <!-- Advanced Tables -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add Attendance</a>
        </div>
        <!-- Modal -->
        <?php include 'modal/attendance_modal.php'; ?>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <th class="hidden"></th>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch attendance records
                        $sql = "SELECT *, employee_records.employee_id AS emp_id, employee_attendance.id AS attend 
                                FROM employee_attendance 
                                LEFT JOIN employee_records ON employee_records.emp_id=employee_attendance.employee_id 
                                ORDER BY employee_attendance.date_attendance DESC, employee_attendance.time_in DESC";
                        $query = $conn->query($sql);

                        if ($query === FALSE) {
                            echo "Error fetching records: " . $conn->error;
                        }

                        while($row = $query->fetch_assoc()){
                            // Determine if the employee has not yet clocked out
                            $time_out_display = !empty($row['time_out']) ? date('h:i A', strtotime(htmlentities($row['time_out']))) : 'Not Clocked Out';
                            $status = ($row['status']) ? '<button type="" class="btn btn-success btn-xs"><i class="fa fa-user-clock"></i> On Time</button>' : '<button type="" class="btn btn-danger btn-xs">Late</button>';
                            echo "
                                <tr>
                                    <td class='hidden'></td>
                                    <td>".$row['emp_id']."</td>
                                    <td>".htmlentities($row['first_name'].' '.$row['last_name'])."</td>
                                    <td>".date('h:i A', strtotime(htmlentities($row['time_in'])))."</td>
                                    <td>".$time_out_display."</td>
                                    <td>".$status."</td>
                                    <td>".date('M d, Y', strtotime(htmlentities($row['date_attendance'])))."</td>
                                    <td>
                                        <button class='btn btn-danger btn-sm btn-flat delete' data-id='".htmlentities($row['attend'])."'><i class='fa fa-trash'></i> Delete</button>
                                    </td>
                                </tr>
                            ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include 'modal/attendance_edit_modal.php'; ?>
    <?php include 'modal/attendance_del_modal.php'; ?>

    <script>
        $(function(){
            /////////delete//////////////
            $('.delete').click(function(e){
                e.preventDefault();
                $('#delete').modal('show');
                var id = $(this).data('id');
                delID(id);
            });
        });

        function delID(id){
            $.ajax({
                type: 'POST',
                url: 'attendance_row2.php',
                data: {id:id},
                dataType: 'json',
                success: function(response2){
                    $('#del_id').val(response2.id);
                    $('#del_employee').html(response2.first_name+' '+response2.last_name);
                    $('#del_timein').html(response2.time_in);
                    $('#del_timeout').html(response2.time_out);
                }
            });
        }

        // Add this section to handle 30 minutes advance time-in logic
        function recordTimeIn(employee_id) {
            $.ajax({
                url: 'attendance_timein_process.php', // This is the PHP file you will create to handle the process
                type: 'POST',
                data: { employee_id: employee_id },
                success: function(response) {
                    alert(response);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.log("Failed: " + status + " " + error);
                }
            });
        }
    </script>

</div>
<!-- end page-wrapper -->
</div>

<!-- end wrapper -->
<!-- Core Scripts - Include with every page -->
<script src="assets/plugins/jquery-1.10.2.js"></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/plugins/pace/pace.js"></script>
<script src="assets/scripts/siminta.js"></script>
<!-- Page-Level Plugin Scripts-->
<script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
<script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>

<script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script>

<script src="js/timepicki.js"></script>
<script>
    $('#timepicker1').timepicki();
</script>
<script>
    $('#timepicker2').timepicki();
</script>
<script>
    $('.timepicker1').timepicki();
</script>
<script>
    $('.timepicker2').timepicki();
</script>

</body>

</html>

<?php
// This is the code that you will place in 'attendance_timein_process.php'
include('connection/db_conn.php'); // Assuming this is your database connection file

// Assuming you have the employee_id from the form submission
$employee_id = $_POST['employee_id'];
$current_time = date('H:i:s');

// Fetch the employee's scheduled time
$sql = "SELECT employee_schedule.time_in AS scheduled_time_in FROM employee_records 
        LEFT JOIN employee_schedule ON employee_records.schedule_id = employee_schedule.id
        WHERE employee_records.emp_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$scheduled_time_in = $row['scheduled_time_in'];

// Calculate the 30-minute advance
$scheduled_time_minus_30 = date('H:i:s', strtotime($scheduled_time_in . ' -30 minutes'));

// Determine the time_in
if ($current_time >= $scheduled_time_minus_30 && $current_time <= $scheduled_time_in) {
    // Allow time-in 30 minutes before the scheduled time
    $time_in = $current_time;
} else {
    // Use the actual current time
    $time_in = $current_time;
}

// Insert the time-in record
$sql = "INSERT INTO employee_attendance (employee_id, time_in, date_attendance, status) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$date_attendance = date('Y-m-d');
$status = ($time_in <= $scheduled_time_in) ? 1 : 0; // 1 for on-time, 0 for late
$stmt->bind_param("isss", $employee_id, $time_in, $date_attendance, $status);

if ($stmt->execute()) {
    echo 'Attendance recorded successfully!';
} else {
    echo 'Error recording attendance!';
}

$stmt->close();
$conn->close();
?>
