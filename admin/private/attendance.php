<?php
include('header/head.php');
include('header/sidebar_menu.php');
// include('connection/db_conn.php');
?>

<!-- end navbar side -->
<!--  page-wrapper -->
<div id="page-wrapper">

    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12">
        </div>
        <!--End Page Headers -->
    </div>

    <div class="row">
        <!-- Welcome -->
        <div class="col-lg-12">
            <div class="alert alert-info">
            </div>
        </div>
        <!--end Welcome -->
    </div>

    <!-- Advanced Tables -->
    <div class="panel panel-default">
        <div class="panel-heading">
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
                        <th>Photo</th>
                        <th>Location</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch attendance records with proper column references
                        $sql = "SELECT employee_records.first_name, employee_records.last_name, 
                                       employee_records.emp_id, 
                                       employee_attendance.time_in, employee_attendance.time_out, 
                                       employee_attendance.status, employee_attendance.date_attendance, 
                                       employee_attendance.photo, employee_attendance.latitude, employee_attendance.longitude, 
                                       employee_attendance.id AS attend
                                FROM employee_attendance
                                LEFT JOIN employee_records ON employee_records.emp_id = employee_attendance.employee_id
                                ORDER BY employee_attendance.date_attendance DESC, employee_attendance.time_in DESC";

                        $query = $conn->query($sql);

                        if ($query === FALSE) {
                            echo "Error fetching records: " . $conn->error;
                        }

                        if ($query->num_rows > 0) {
                            while ($row = $query->fetch_assoc()) {
                                // Determine if the employee has not yet clocked out
                                $time_out_display = !empty($row['time_out']) ? date('h:i A', strtotime(htmlentities($row['time_out']))) : '00:00';
                                $status = ($row['status']) ? '<button class="btn btn-success btn-xs"><i class="fa fa-check"></i> On Time</button>' : '<button class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Late</button>';

                                // Display photo if available
                                $photo_display = !empty($row['photo']) 
                                    ? "<img src='uploads/photos/" . htmlentities($row['photo']) . "' style='width: 50px; height: 50px;' alt='Photo'>" 
                                    : "No Photo";

                                // Display location (latitude, longitude) if available
                                $location_display = (!empty($row['latitude']) && !empty($row['longitude'])) 
                                    ? htmlentities($row['latitude']) . ", " . htmlentities($row['longitude']) 
                                    : "No Location";

                                echo "
                                    <tr>
                                        <td class='hidden'></td>
                                        <td>".htmlentities($row['emp_id'])."</td>
                                        <td>".htmlentities($row['first_name'].' '.$row['last_name'])."</td>
                                        <td>".date('h:i A', strtotime(htmlentities($row['time_in'])))."</td>
                                        <td>".$time_out_display."</td>
                                        <td>".$status."</td>
                                        <td>".date('M d, Y', strtotime(htmlentities($row['date_attendance'])))."</td>
                                        <td>".$photo_display."</td>
                                        <td>".$location_display."</td>
                                        <td>
                                            <button class='btn btn-danger btn-sm btn-flat delete' data-id='".htmlentities($row['attend'])."'>
                                                <i class='fa fa-trash'></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                ";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No attendance records found.</td></tr>";
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
