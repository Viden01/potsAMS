<?php 
$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}

include('header/head.php');?>
     <!-- navbar side -->
     <?php include('header/sidebar_menu.php');?>
        <!-- end navbar side -->
        <!--  page-wrapper -->
        <div id="page-wrapper">

            <div class="row">
                <!-- Page Header -->
                <div class="col-lg-12">
                  
                </div>
                <!--End Page Header -->
            </div>

            <div class="row">
                <!-- Welcome -->
                <!-- <div class="col-lg-12">
                    <div class="alert alert-info">
                        
                    </div> -->
                </div>
                <!--end  Welcome -->
            </div>

            <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                          <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add CashAdvance</a>
                        </div>
                     <!-- Modal -->
                     <?php include 'modal/cashadvance_modal.php'; ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">   
                                <thead>
                                  <th>Date</th>
                                  <th>Employee ID</th>
                                  <th>Name</th>
                                  <th>Amount</th>
                                  <th>Action</th>
                                </thead>
                                <tbody>
                                  <?php
                                  include '../connection/db_conn.php';
                                    $sql = "SELECT *, employee_cashadvance.id AS cashid, employee_records.employee_id AS emp_id FROM employee_cashadvance LEFT JOIN employee_records ON employee_records.emp_id=employee_cashadvance.employee_id ORDER BY employee_cashadvance.date_created DESC";
                                    $query = $conn->query($sql);
                                    while($row = $query->fetch_assoc()){
                                      echo "
                                        <tr>
                                          <td>".date('M d, Y', strtotime(htmlentities($row['date_created'])))."</td>
                                          <td>".$row['emp_id']."</td>
                                          <td>".$row['first_name'].' '.$row['last_name']."</td>
                                          <td>".number_format(htmlentities($row['amount'], 2))."</td>
                                          <td>
                                            <button class='btn btn-success btn-sm edit btn-flat' data-id='".htmlentities($row['cashid'])."'><i class='fa fa-edit'></i> Edit</button>
                                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='".htmlentities($row['cashid'])."'><i class='fa fa-trash'></i> Delete</button>
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
