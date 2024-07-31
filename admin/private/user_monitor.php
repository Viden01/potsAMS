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
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">   
                                <thead>
                                  <th>Email Address</th>
                                  <th>Action Login</th>
                                  <th>Action Logout</th>
                                  <th>IP</th>
                                  <th>Host</th>
                                  <th>Login In</th>
                                  <th>Log Out</th>
                                </thead>
                                <tbody>
                                  <?php
                                  include '../connection/db_conn.php';
                                    $sql = "SELECT * FROM `history_log` ORDER BY id DESC";
                                    $query = $conn->query($sql);
                                    while($row = $query->fetch_assoc()){
                                      echo "
                                        <tr>
                                          <td>".htmlentities($row['email_address'])."</td>
                                          <td>".htmlentities($row['action'])."</td>
                                          <td>".htmlentities($row['actions'])."</td>
                                          <td>".htmlentities($row['ip'])."</td>
                                          <td>".htmlentities($row['host'])."</td>
                                          <td style='color:green'>".htmlentities($row['login_time'])."</td>
                                          <td style='color:red'>".htmlentities($row['logout_time'])."</td>

                                      ";
                                    }
                                  ?>
                                </tbody>
                              </table>
                            </div> 
                        </div>
                    </div>
                    <?php include 'modal/cashadvance_edit_modal.php'; ?>
                    <?php include 'modal/cashadvance_del_modal.php'; ?>
                <script>
                $(function(){
                  /////////////edit/////////////
                  $('.edit').click(function(e){
                    e.preventDefault();
                    $('#edit').modal('show');
                    var id = $(this).data('id');
                    editID(id);
                  });
                   /////////////delete/////////////
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
                    url: 'cashadvance_row.php',
                    data: {id:id},
                    dataType: 'json',
                    success: function(response){
                       $('#id').val(response.id);
                      $('#edit_employeename').html(response.first_name+' '+response.last_name);
                      $('#edit_amount').val(response.amount);
                    }
                  });
                }
                ///////////////////////////////////      
                function delID(id){
                  $.ajax({
                    type: 'POST',
                    url: 'cashadvance_row2.php',
                    data: {id:id},
                    dataType: 'json',
                    success: function(response2){
                       $('#del_id').val(response2.id);
                      $('#view_employeename').html(response2.first_name+' '+response2.last_name);
                      $('#del_amount').val(response2.amount);
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
