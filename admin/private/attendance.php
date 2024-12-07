<div class="panel panel-default">
    <div class="panel-heading">
        <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat">
            <i class="fa fa-plus"></i> Add Attendance
        </a>
    </div>
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
                    <th>Photo</th>
                    <th>Location</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php
                    // Fetch attendance records
                    $sql = "SELECT *, 
                                employee_records.employee_id AS emp_id, 
                                employee_attendance.id AS attend 
                            FROM employee_attendance 
                            LEFT JOIN employee_records ON employee_records.emp_id = employee_attendance.employee_id 
                            ORDER BY employee_attendance.date_attendance DESC, employee_attendance.time_in DESC";
                    $query = $conn->query($sql);

                    while ($row = $query->fetch_assoc()) {
                        $time_out_display = !empty($row['time_out']) 
                            ? date('h:i A', strtotime(htmlentities($row['time_out']))) 
                            : '00:00';
                        $status = ($row['status']) 
                            ? '<button class="btn btn-success btn-xs"><i class="fa fa-check"></i> On Time</button>' 
                            : '<button class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Late</button>';
                        
                        $photo_display = !empty($row['photo']) 
                            ? "<img src='uploads/photos/" . htmlentities($row['photo']) . "' style='width: 50px; height: 50px;' alt='Photo'>" 
                            : "No Photo";
                        
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
                                <td>".$photo_display."</td>
                                <td>".$location_display."</td>
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
