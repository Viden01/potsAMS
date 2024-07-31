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
                            <button type="button"  data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-sm btn-flat" value="Add Employee"><i class="fa fa-plus"></i>Add Employee</button>
                        </div>
    <!-- Modal -->
                    <?php include('modal/employee_modal.php');?>
                    <div class="panel-body">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
          <th>Employee ID</th>
          <th>Full Name</th>
          <th>Age</th> <!-- Added Age Column -->
          <th>Position</th>
          <th>Time schedule</th>
          <th>Member start</th>
          <th>Profile</th>
          <th>Action</th>
        </thead>
        <tbody>
          <?php
          include '../connection/db_conn.php';
            $sql = "SELECT *, employee_records.emp_id AS emp_id, TIMESTAMPDIFF(YEAR, employee_records.birth_date, CURDATE()) AS age FROM employee_records LEFT JOIN employee_position ON employee_position.id=employee_records.position_id LEFT JOIN employee_schedule ON employee_schedule.id=employee_records.schedule_id";
            $query = $conn->query($sql);
            while($row = $query->fetch_assoc()){
              ?>
                <tr>
                  <td><?php echo htmlentities($row['employee_id']); ?></td>
                  <td><?php echo htmlentities($row['first_name'].' '.$row['last_name']); ?></td>
                  <td><?php echo htmlentities($row['age']); ?></td> <!-- Display Age -->
                  <td><?php echo htmlentities($row['emp_position']); ?></td>
                  <td><?php echo date('h:i A', strtotime(htmlentities($row['time_in']))).' - '.date('h:i A', strtotime(htmlentities($row['time_out']))); ?></td>
                  <td><?php echo date('M d, Y', strtotime(htmlentities($row['date_created']))) ?></td>
                  <td>
                    <div class="zoomin">
                      <img id="img" src="<?php echo (!empty(htmlentities($row['profile_pic'])))? substr(htmlentities($row['profile_pic']),3):'profile.jpg'; ?>" width="35px" height="35px">
                    </div>
                  </td>
                  <td>
                     <button class="btn btn-success btn-sm edit btn-flat" data-id="<?php echo htmlentities($row['emp_id']); ?>"><i class="fa fa-edit"></i> Edit</button>
                    <button class="btn btn-danger btn-sm delete btn-flat" data-id="<?php echo htmlentities($row['emp_id']); ?>"><i class="fa fa-trash"></i> Delete</button>
                  </td>
                </tr>
              <?php
            }
          ?>
        </tbody>
        </table>
    </div>
</div>

                    <!--End Advanced Tables -->
               <?php include('modal/employee_edit_modal.php');?>
               <?php include('modal/employee_del_modal.php');?>
                <script>
                $(function(){
                 /////////////edit///////////////
                  $('.edit').click(function(e){
                    e.preventDefault();
                    $('#edit').modal('show');
                    var id = $(this).data('id');
                    editID(id);
                  });
                  /////////////delete///////////////
                $('.delete').click(function(e){
                    e.preventDefault();
                    $('#delete').modal('show');
                    var id = $(this).data('id');
                    deleteID(id);
                  });

                });

                function editID(emp_id){
                  $.ajax({
                    type: 'POST',
                    url: 'employee_row.php',
                    data: {emp_id:emp_id},
                    dataType: 'json',
                    success: function(response){
                      $('#emp_id').val(response.emp_id);
                      $('#edit_firstname').val(response.first_name);
                      $('#edit_middlename').val(response.middle_name);
                      $('#edit_lastname').val(response.last_name);
                      $('#edit_address').val(response.complete_address);
                      $('#edit_birthdate').val(response.birth_date);
                      $('#edit_mobilenumber').val(response.Mobile_number);
                      $('#edit_gender').val(response.gender);
                      $('#edit_positionid').val(response.position_id);
                      $('#edit_maritalstatus').val(response.marital_status);
                      $('#edit_scheduleid').val(response.schedule_id);
                      $('#edit_profilepic').attr("src", response.profile_pic.slice(3));


                    }
                  });
                }
                ///////////////////////////////////////
              function deleteID(emp_id){
                  $.ajax({
                    type: 'POST',
                    url: 'employee_row2.php',
                    data: {emp_id:emp_id},
                    dataType: 'json',
                    success: function(response2){
                      $('#del_empid').val(response2.emp_id);
                      $('#del_employeename').html(response2.first_name+' '+response2.last_name);
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

</body>

</html>
