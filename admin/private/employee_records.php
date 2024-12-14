<?php
// Ensure the URL doesn't have .php extensions
$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}

include('header/head.php');
include('header/sidebar_menu.php');

// Function to generate a new Employee ID
function generateEmployeeID($conn, $prefix = "EMP") {
    // Find the last employee ID with the given prefix
    $sql = "SELECT emp_id FROM employee_records WHERE emp_id LIKE '$prefix%' ORDER BY emp_id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['emp_id'];

        // Extract the numeric part of the last ID
        $numeric_part = (int)filter_var($last_id, FILTER_SANITIZE_NUMBER_INT);

        // Increment the numeric part for the new ID
        $new_numeric_part = str_pad($numeric_part + 1, 3, '0', STR_PAD_LEFT);
    } else {
        // Start with 001 if no previous ID exists
        $new_numeric_part = "001";
    }

    // Combine the prefix and new numeric part
    return $prefix . $new_numeric_part;
}

include '../connection/db_conn.php';

// Generate the latest Employee ID
$latestEmployeeID = generateEmployeeID($conn);
?>
<!-- Page Content -->
<div id="page-wrapper">

    <div class="row">
        <div class="col-lg-12">
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info">
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-sm btn-flat">
                <i class="fa fa-plus"></i> Add Employee
            </button>
        </div>

        <?php include('modal/employee_modal.php'); ?>

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <th>Employee ID</th>
                        <th>Full Name</th>
                        <th>Age</th>
                        <th>Position</th>
                        <th>Time Schedule</th>
                        <th>Member Start</th>
                        <th>Profile</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT *, 
                                    employee_records.emp_id AS employee_id, 
                                    TIMESTAMPDIFF(YEAR, employee_records.birth_date, CURDATE()) AS age 
                                FROM employee_records 
                                LEFT JOIN employee_position ON employee_position.id = employee_records.position_id 
                                LEFT JOIN employee_schedule ON employee_schedule.id = employee_records.schedule_id";

                        $query = $conn->query($sql);

                        while ($row = $query->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo htmlentities($row['employee_id']); ?></td>
                                <td><?php echo htmlentities($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlentities($row['age']); ?></td>
                                <td><?php echo htmlentities($row['emp_position']); ?></td>
                                <td><?php echo date('h:i A', strtotime(htmlentities($row['time_in']))) . ' - ' . date('h:i A', strtotime(htmlentities($row['time_out']))); ?></td>
                                <td><?php echo date('M d, Y', strtotime(htmlentities($row['date_created']))); ?></td>
                                <td>
                                    <div class="zoomin">
                                        <img id="img" src="<?php echo (!empty(htmlentities($row['profile_pic']))) ? substr(htmlentities($row['profile_pic']), 3) : 'profile.jpg'; ?>" width="35px" height="35px">
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm edit btn-flat" data-id="<?php echo htmlentities($row['employee_id']); ?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete btn-flat" data-id="<?php echo htmlentities($row['employee_id']); ?>">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="add_employee.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="emp_id">Employee ID</label>
                            <input type="text" class="form-control" id="emp_id" name="emp_id" value="<?php echo $latestEmployeeID; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <select class="form-control" id="position" name="position_id" required>
                                <option value="" disabled selected>Select Position</option>
                                <?php
                                $positionQuery = "SELECT * FROM employee_position";
                                $positions = $conn->query($positionQuery);
                                while ($position = $positions->fetch_assoc()) {
                                    echo "<option value='" . $position['id'] . "'>" . $position['emp_position'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Additional fields here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('modal/employee_edit_modal.php'); ?>
    <?php include('modal/employee_del_modal.php'); ?>

    <script>
        $(function(){
            // Edit Employee
            $('.edit').click(function(e){
                e.preventDefault();
                $('#edit').modal('show');
                var id = $(this).data('id');
                editID(id);
            });

            // Delete Employee
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
                data: {emp_id: emp_id},
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

        function deleteID(emp_id){
            $.ajax({
                type: 'POST',
                url: 'employee_row2.php',
                data: {emp_id: emp_id},
                dataType: 'json',
                success: function(response2){
                    $('#del_empid').val(response2.emp_id);
                    $('#del_employeename').html(response2.first_name + ' ' + response2.last_name);
                }
            });
        }
    </script>
</div>

<script src="assets/plugins/jquery-1.10.2.js"></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/plugins/pace/pace.js"></script>
<script src="assets/scripts/siminta.js"></script>
<script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
<script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>

<script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script>

</body>
</html>
