        <?php include('header/head.php');?>
        <?php include('header/sidebar_menu.php');?>

        <!-- end navbassr side -->
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
                <!--end  Welcome -->
            </div>

            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                <!-- <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add Attendance</a> -->
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
                                <th>Photo</th> <!-- New Photo Column -->
                                <th>Location</th> <!-- New Location Column -->
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch attendance records
                                $sql = "SELECT * FROM  employee_records, employee_id WHERE employee_records.employee_id";

                                        // SELECT * FROM  employee_records, employee_id WHERE employee_records.employee_id
                                $query = $conn->query($sql);

                                if ($query === FALSE) {
                                    echo "Error fetching records: " . $conn->error;
                                }

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
                                            <td>".$row['emp_id']."</td>
                                            <td>".htmlentities($row['first_name'].' '.$row['last_name'])."</td>
                                            <td>".date('h:i A', strtotime(htmlentities($row['time_in'])))."</td>
                                            <td>".$time_out_display."</td>
                                            <td>".$status."</td>
                                            <td>".date('M d, Y', strtotime(htmlentities($row['date_attendance'])))."</td>
                                            <td>".$photo_display."</td> <!-- Display Photo -->
                                            <td>".$location_display."</td> <!-- Display Location -->
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
