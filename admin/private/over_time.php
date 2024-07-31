<?php include('header/head.php');?>
    <!-- navbar side -->
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
                          <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add Overtime</a>
                        </div>
                     <!-- Modal -->
                     <?php include 'modal/overtime_modal.php'; ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">   
                                  <thead>
                                    <th class="hidden"></th>
                                    <th>Overtime Date</th>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Number of Hours</th>
                                    <th>Rate</th>
                                    <th>Action</th>
                                  </thead>
                                  <tbody>
                                    <?php
                                      include '../connection/db_conn.php';
                                      $sql = "SELECT *, employee_overtime.id AS overid, employee_records.employee_id AS empid FROM employee_overtime LEFT JOIN employee_records ON employee_records.emp_id=employee_overtime.employee_id ORDER BY overtime_date DESC";
                                      $query = $conn->query($sql);
                                      while($row = $query->fetch_assoc()){
                                        echo "
                                          <tr>
                                            <td class='hidden'></td>
                                            <td>".date('M d, Y', strtotime(htmlentities($row['overtime_date'])))."</td>
                                            <td>".htmlentities($row['empid'])."</td>
                                            <td>".htmlentities($row['first_name'].' '.$row['last_name'])."</td>
                                            <td>".htmlentities($row['overtime_hours'])."</td>
                                            <td>".htmlentities($row['overtime_mins'])."</td>
                                            <td>
                                              <button class='btn btn-success btn-sm btn-flat edit' data-id='".htmlentities($row['overid'])."'><i class='fa fa-edit'></i> Edit</button>
                                              <button class='btn btn-danger btn-sm btn-flat delete' data-id='".htmlentities($row['overid'])."'><i class='fa fa-trash'></i> Delete</button>
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
                 <?php include 'modal/overtime_edit_modal.php'; ?>
                 <?php include 'modal/overtime_del_modal.php'; ?>
         <!--         <?php// include 'modal/scripts.php'; ?> -->
                  <script>
                  $(function(){
                    /////////////edit//////////////
                    $('.edit').click(function(e){
                      e.preventDefault();
                      $('#edit').modal('show');
                      var id = $(this).data('id');
                      editID(id);
                    });
                     /////////////delete//////////////
                    $('.delete').click(function(e){
                      e.preventDefault();
                      $('#delete').modal('show');
                      var id = $(this).data('id');
                      delID(id);
                    });

                  });

                  function editID(id){
                    $.ajax({
                      type: 'POST',
                      url: 'overtime_row.php',
                      data: {id:id},
                      dataType: 'json',
                      success: function(response){
                        var time = response.overtime_hours;
                        var split = time.split('.');
                        var hour = split[0];
                        var min = '.'+split[1];
                        min = min * 60;
                        console.log(min);
                          $('#id').val(response.id);
                          $('#edit_employeeid').val(response.employee_id);
                          $('#edit_hours').val(hour);
                          $('#edit_mins').val(min);
                          $('#edit_rate').val(response.overtime_mins);
                          $('#edit_date').val(response.overtime_date);
                      }
                    });
                  }
                 ///////////////////////////////////////////////////// 
                  function delID(id){
                    $.ajax({
                      type: 'POST',
                      url: 'overtime_row2.php',
                      data: {id:id},
                      dataType: 'json',
                      success: function(response2){
                          $('#del_id').val(response2.id);
                          $('#del_employeename').html(response2.first_name+' '+response2.last_name);

                      }
                    });
                  }
                  </script>
                    <!--End Advanced Tables -->
  
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

</body>

</html>
