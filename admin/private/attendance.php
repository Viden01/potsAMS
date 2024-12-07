<?php include('header/head.php'); ?>
<?php include('header/sidebar_menu.php'); ?>

<div id="page-wrapper">

    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12"></div>
    </div>

    <div class="row">
        <!-- Welcome -->
        <div class="col-lg-12">
            <div class="alert alert-info"></div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat">
                <i class="fa fa-plus"></i> Add Attendance
            </a>
        </div>
        <?php include 'modal/attendance_modal.php'; ?>
        <div class="panel-body">
            <div class="table-responsive">
                <h4>Attendance Records</h4>
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
                        $sql = "SELECT *, employee_records.employee_id AS emp_id, employee_attendance.id AS attend 
                                FROM employee_attendance 
                                LEFT JOIN employee_records ON employee_records.emp_id = employee_attendance.employee_id 
                                ORDER BY employee_attendance.date_attendance DESC, employee_attendance.time_in DESC";
                        $query = $conn->query($sql);

                        while ($row = $query->fetch_assoc()) {
                            $time_out_display = !empty($row['time_out']) ? date('h:i A', strtotime(htmlentities($row['time_out']))) : '00:00';
                            $status = ($row['status']) ? '<button class="btn btn-success btn-xs"><i class="fa fa-check"></i> On Time</button>' : '<button class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Late</button>';
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
                                        <button class='btn btn-danger btn-sm btn-flat delete' data-id='".htmlentities($row['attend'])."'>
                                            <i class='fa fa-trash'></i> Delete
                                        </button>
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

    <!-- Photos Table -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Attendance Photos</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-photos">
                    <thead>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Photo</th>
                        <th>Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT *, employee_records.employee_id AS emp_id 
                                FROM attendance_photos 
                                LEFT JOIN employee_records ON employee_records.emp_id = attendance_photos.employee_id 
                                ORDER BY attendance_photos.date_attendance DESC";
                        $query = $conn->query($sql);

                        while ($row = $query->fetch_assoc()) {
                            echo "
                                <tr>
                                    <td>".$row['emp_id']."</td>
                                    <td>".htmlentities($row['first_name'].' '.$row['last_name'])."</td>
                                    <td><img src='uploads/photos/".htmlentities($row['photo'])."' alt='Photo' style='width: 50px; height: 50px;'></td>
                                    <td>".date('M d, Y', strtotime(htmlentities($row['date_attendance'])))."</td>
                                    <td>
                                        <button class='btn btn-danger btn-sm btn-flat delete' data-id='".htmlentities($row['id'])."'>
                                            <i class='fa fa-trash'></i> Delete
                                        </button>
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

    <!-- Location Table -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Attendance Locations</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-locations">
                    <thead>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT *, employee_records.employee_id AS emp_id 
                                FROM attendance_locations 
                                LEFT JOIN employee_records ON employee_records.emp_id = attendance_locations.employee_id 
                                ORDER BY attendance_locations.date_attendance DESC";
                        $query = $conn->query($sql);

                        while ($row = $query->fetch_assoc()) {
                            echo "
                                <tr>
                                    <td>".$row['emp_id']."</td>
                                    <td>".htmlentities($row['first_name'].' '.$row['last_name'])."</td>
                                    <td>".htmlentities($row['latitude'])."</td>
                                    <td>".htmlentities($row['longitude'])."</td>
                                    <td>".date('M d, Y', strtotime(htmlentities($row['date_attendance'])))."</td>
                                    <td>
                                        <button class='btn btn-danger btn-sm btn-flat delete' data-id='".htmlentities($row['id'])."'>
                                            <i class='fa fa-trash'></i> Delete
                                        </button>
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

    <script>
        $(function(){
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
                    // Update delete modal data
                }
            });
        }
    </script>

</div>

<script src="assets/plugins/jquery-1.10.2.js"></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
<script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
<script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
        $('#dataTables-photos').dataTable();
        $('#dataTables-locations').dataTable();
    });
</script>
</body>
</html>
