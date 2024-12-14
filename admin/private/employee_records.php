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
include '../connection/db_conn.php'; // Database connection

// Function to generate a new Employee ID
function generateEmployeeID($conn, $prefix = "EMP") {
    $sql = "SELECT emp_id FROM employee_records WHERE emp_id LIKE '$prefix%' ORDER BY emp_id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['emp_id'];

        // Extract numeric part of last ID
        $numeric_part = (int)filter_var($last_id, FILTER_SANITIZE_NUMBER_INT);

        // Increment and format the numeric part
        $new_numeric_part = str_pad($numeric_part + 1, 3, '0', STR_PAD_LEFT);
    } else {
        // If no previous ID exists, start with 001
        $new_numeric_part = "001";
    }

    return $prefix . $new_numeric_part;
}

// Generate the new Employee ID
$latestEmployeeID = generateEmployeeID($conn);
?>
<!-- Page Content -->
<div id="page-wrapper">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Employee Management</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info">
                Use the "Add Employee" button to add a new employee.
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <button type="button" data-toggle="modal" data-target="#addEmployeeModal" class="btn btn-primary btn-sm btn-flat">
                <i class="fa fa-plus"></i> Add Employee
            </button>
        </div>

        <!-- Add Employee Modal -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="add_employee.php">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Full Name</th>
                            <th>Age</th>
                            <th>Position</th>
                            <th>Time Schedule</th>
                            <th>Member Start</th>
                            <th>Profile</th>
                            <th>Action</th>
                        </tr>
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
                                    <img src="<?php echo !empty(htmlentities($row['profile_pic'])) ? htmlentities($row['profile_pic']) : 'profile.jpg'; ?>" width="35px" height="35px">
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
</div>

<script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script>
