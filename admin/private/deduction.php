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
                          <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add Deduction</a>
                        </div>
                     <!-- Modal -->
                     <?php include 'modal/deduction_modal.php'; ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">   
                                <thead>
                                  <th>Deduction Name</th>
                                  <th>Amount</th>
                                  <th>Date Created</th>
                                  <th>Action</th>
                                </thead>
                                <tbody>
                                  <?php
                                    $sql = "SELECT * FROM  employee_deductions";
                                    $query = $conn->query($sql);
                                    while($row = $query->fetch_assoc()){
                                      echo "
                                        <tr>
                                          <td>".htmlentities($row['deduction_name'])."</td>
                                          <td>".number_format(htmlentities($row['amount']), 2)."</td>
                                          <td>". date('M d, Y', strtotime(htmlentities($row['date_create']))) ."</td>
                                          <td>
                                            <button class='btn btn-success btn-sm edit btn-flat' data-id='".htmlentities($row['id'])."'><i class='fa fa-edit'></i> Edit</button>
                                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='".htmlentities($row['id'])."'><i class='fa fa-trash'></i> Delete</button>
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
                    <?php include 'modal/deduction_edit_modal.php'; ?>
                    <?php include 'modal/deduction_del_modal.php'; ?>
                    <script>
                    $(function(){
                     /////////edit//////////////
                      $('.edit').click(function(e){
                        e.preventDefault();
                        $('#edit').modal('show');
                        var id = $(this).data('id');
                        editID(id);
                      });
                     /////////delete//////////////
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
                        url: 'deduction_row.php',
                        data: {id:id},
                        dataType: 'json',
                        success: function(response){
                          $('#id').val(response.id);
                          $('#edit_deductionname').val(response.deduction_name);
                          $('#edit_amount').val(response.amount);
                        }
                      });
                    }
                   ////////////////////////////////////////////
                    function delID(id){
                      $.ajax({
                        type: 'POST',
                        url: 'deduction_row2.php',
                        data: {id:id},
                        dataType: 'json',
                        success: function(response2){
                          $('#del_id').val(response2.id);
                          $('#del_deductionname').html(response2.deduction_name);
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
