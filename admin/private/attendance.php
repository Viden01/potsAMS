<?php include('header/head.php'); ?>
<?php include('header/sidebar_menu.php'); ?>

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
$sql = "SELECT 
            employee_records.emp_id AS employee_id, 
            employee_records.first_name, 
            employee_records.last_name, 
            employee_attendance.time_in, 
            employee_attendance.time_out, 
            employee_attendance.status, 
            employee_attendance.date_attendance, 
            employee_attendance.photo_path,
            employee_attendance.latitude, 
            employee_attendance.longitude, 
            employee_attendance.id AS attend
        FROM employee_attendance 
        LEFT JOIN employee_records 
            ON employee_attendance.employee_id = employee_records.emp_id 
        ORDER BY employee_attendance.date_attendance DESC, employee_attendance.time_in DESC";

$query = $conn->query($sql);

if ($query === FALSE) {
    echo "<tr><td colspan='9'>Error fetching records: " . $conn->error . "</td></tr>";
} elseif ($query->num_rows == 0) {
    echo "<tr><td colspan='9'>No attendance records found.</td></tr>";
} else {
    while ($row = $query->fetch_assoc()) {
        if (empty($row['first_name']) || empty($row['last_name'])) {
            // Log unmatched employee ID for debugging
            error_log("Unmatched employee_id in attendance: " . htmlentities($row['employee_id']));
            continue; // Skip rows with no matching employee record
        }

         // Use 12-hour time format (h:i A)
         $time_in_display = !empty($row['time_in']) ? date('h:i A', strtotime(htmlentities($row['time_in']))) : '12:00 AM';
         $time_out_display = !empty($row['time_out']) ? date('h:i A', strtotime(htmlentities($row['time_out']))) : '12:00 AM';
 
      
        $status = ($row['status']) 
            ? '<button class="btn btn-success btn-xs"><i class="fa fa-check"></i> On Time</button>' 
            : '<button class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Late</button>';

        // Display photo if available
        $photo_display = !empty($row['photo_path']) 
            ? "<img src='uploads/" . htmlentities($row['photo_path']) . "' style='width: 50px; height: 50px;' alt='Photo'>" 
            : "No Photo";

        // Generate the Google Maps link for the location
        $location_display = '';
        if (!empty($row['latitude']) && !empty($row['longitude'])) {
            $latitude = htmlentities($row['latitude']);
            $longitude = htmlentities($row['longitude']);
            $location_display = "<iframe width='100%' height='150' frameborder='0' style='border:0' src='https://www.google.com/maps/embed/v1/place?key=YOUR_GOOGLE_MAPS_API_KEY&q=$latitude,$longitude' allowfullscreen></iframe>";
        } else {
            $location_display = "No Location Available";
        }

        echo "
            <tr>
                <td class='hidden'></td>
                <td>".htmlentities($row['employee_id'])."</td>
                <td>".htmlentities($row['first_name'].' '.$row['last_name'])."</td>
                <td>".htmlentities($time_in_display)."</td>
                <td>".htmlentities($time_out_display)."</td>
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
                data: {id: id},
                dataType: 'json',
                success: function(response2){
                    $('#del_id').val(response2.id);
                    $('#del_employee').html(response2.first_name + ' ' + response2.last_name);
                    $('#del_timein').html(response2.time_in);
                    $('#del_timeout').html(response2.time_out);
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
