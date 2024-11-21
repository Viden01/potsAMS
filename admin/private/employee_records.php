<?php
include '../connection/db_conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Records</title>
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <script src="path/to/jquery.min.js"></script>
    <script src="path/to/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Employee Records</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">Add Employee</button>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Profile</th>
                    <th>Full Name</th>
                    <th>Address</th>
                    <th>Birthday</th>
                    <th>Mobile Number</th>
                    <th>Gender</th>
                    <th>Position</th>
                    <th>Schedule</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT 
                            e.employee_id, e.first_name, e.middle_name, e.last_name, e.complete_address, 
                            e.birth_date, e.Mobile_number, e.gender, p.emp_position, 
                            s.time_in, s.time_out, e.profile_pic
                        FROM employee_records e
                        LEFT JOIN employee_position p ON e.position_id = p.id
                        LEFT JOIN employee_schedule s ON e.schedule_id = s.id";
                $query = $conn->query($sql);

                while ($row = $query->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['employee_id']}</td>
                            <td><img src='{$row['profile_pic']}' alt='Profile' style='width: 50px; height: 50px; border-radius: 50%;'></td>
                            <td>{$row['first_name']} {$row['middle_name']} {$row['last_name']}</td>
                            <td>{$row['complete_address']}</td>
                            <td>{$row['birth_date']}</td>
                            <td>{$row['Mobile_number']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['emp_position']}</td>
                            <td>{$row['time_in']} - {$row['time_out']}</td>
                            <td>
                                <button class='btn btn-success btn-sm edit-btn' data-id='{$row['employee_id']}'>Edit</button>
                                <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['employee_id']}'>Delete</button>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Include Employee Modal -->
    <?php 
    // Update the include path as needed
    include '../modals/employee_modal.php'; 
    ?>

    <script>
        // Handle Delete
        $(document).on('click', '.delete-btn', function () {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this record?')) {
                $.post('processing/employee_delete.php', { employee_id: id }, function (response) {
                    alert(response);
                    location.reload();
                });
            }
        });

        // Handle Edit (Optional, Requires Edit Modal)
        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            // Load employee data and open an edit modal
            // Implementation depends on your requirements
        });
    </script>
</body>
</html>
