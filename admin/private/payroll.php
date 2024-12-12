<?php
$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}

include('header/head.php');?>
<?php include('header/sidebar_menu.php');?>
<?php
include('header/timezone.php');

$range_to = date('m/d/Y');
$range_from = date('m/d/Y', strtotime('-30 day', strtotime($range_to)));

if (isset($_GET['range'])) {
    $range = $_GET['range'];
    $ex = explode(' - ', $range);
    $from = date('Y-m-d', strtotime($ex[0]));
    $to = date('Y-m-d', strtotime($ex[1]));
} else {
    $from = date('Y-m-d', strtotime($range_from));
    $to = date('Y-m-d', strtotime($range_to));
}
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
          
        </div>
    </div>

    <div class="row">
        <?php if (isset($_GET['show']) && $_GET['show'] === 'generate'): ?>
            <div class="col-lg-12" id="generate-payroll">
                <div class="alert alert-info">
                    <i class="fa fa-folder-open"></i><b>&nbsp;Generating Payroll Report...</b>
                </div>
                <form method="POST" action="payroll_generate.php" id="payrollGenerateForm">
                    <input type="hidden" name="date_range" value="<?php echo (isset($_GET['range'])) ? $_GET['range'] : $range_from.' - '.$range_to; ?>">
                </form>
                <script>
                    document.getElementById('payrollGenerateForm').submit();
                </script>
            </div>
        <?php else: ?>
            <div class="col-lg-12">
                <div class="alert alert-info">
                    
                </div>
            </div>
            <div class="form-row">
                <form method="POST" class="form-inline" id="payForm">
                    <div class="form-group col-md-5">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right col-sm-8" id="reservation" name="date_range" value="<?php echo (isset($_GET['range'])) ? $_GET['range'] : $range_from.' - '.$range_to; ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-5">
                        <button type="button" class="btn btn-success btn-sm btn-flat" id="payroll"><i class='fa fa-print'></i> Payroll</button>
                        <button type="button" class="btn btn-primary btn-sm btn-flat" id="payslip"><i class='fa fa-file'></i> Payslip</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
        <div class="box-header with-border"></div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <th>Employee Name</th>
                        <th>Position</th>
                        <th>Rate per Hour</th>
                        <th>Total Hours Worked</th>
                        <th>Deductions</th>
                        <th>Cash Advance</th>
                        <th>Net Pay</th>
                    </thead>
                    <tbody>
                        <?php
                        include '../connection/db_conn.php';

                        // Fetch total deductions
                        $sql = "SELECT SUM(amount) as total_amount FROM employee_deductions";
                        $query = $conn->query($sql);
                        $drow = $query->fetch_assoc();
                        $deduction = isset($drow['total_amount']) ? $drow['total_amount'] : 0;

                        // Fetch employee attendance and calculate totals
                        $sql = "SELECT employee_records.*, employee_position.*, 
                                SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in))) AS total_seconds 
                                FROM employee_attendance 
                                LEFT JOIN employee_records ON employee_records.emp_id = employee_attendance.employee_id 
                                LEFT JOIN employee_position ON employee_position.id = employee_records.position_id 
                                WHERE date_attendance BETWEEN '$from' AND '$to' 
                                GROUP BY employee_attendance.employee_id 
                                ORDER BY employee_records.last_name ASC, employee_records.first_name ASC";

                        $query = $conn->query($sql);
                        while ($row = $query->fetch_assoc()) {
                            $empid = $row['emp_id'];

                            // Fetch cash advance
                            $casql = "SELECT SUM(amount) AS cashamount FROM employee_cashadvance WHERE employee_id='$empid' AND date_created BETWEEN '$from' AND '$to'";
                            $caquery = $conn->query($casql);
                            $carow = $caquery->fetch_assoc();
                            $cashadvance = isset($carow['cashamount']) ? $carow['cashamount'] : 0;

                            // Convert total seconds to HH:MM:SS format and decimal hours
                            $total_seconds = $row['total_seconds'];
                            $total_hours = floor($total_seconds / 3600);
                            $total_minutes = floor(($total_seconds % 3600) / 60);
                            $total_seconds_display = $total_seconds % 60; // Ensure correct seconds display
                            $decimal_hours = $total_hours + ($total_minutes / 60) + ($total_seconds_display / 3600);

                            // Format total hours worked as HH:MM:SS
                            $formatted_total_hr = sprintf('%02d:%02d:%02d', $total_hours, $total_minutes, $total_seconds_display);

                            // Calculate gross, deductions, and net pay
                            $gross = $row['rate_per_hour'] * $decimal_hours; // Using decimal hours
                            $total_deduction = $deduction + $cashadvance;
                            $net = $gross - $total_deduction;

                            echo "
                              <tr>
                                <td>".$row['last_name'].", ".$row['first_name']."</td>
                                <td>".$row['emp_position']."</td>
                                <td>".number_format($row['rate_per_hour'], 2)."</td>
                                <td>".$formatted_total_hr."</td>
                                <td>".number_format($deduction, 2)."</td>
                                <td>".number_format($cashadvance, 2)."</td>
                                <td>".number_format($net, 2)."</td>
                              </tr>
                            ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include 'modal/position_edit_modal.php'; ?>
    <?php include 'modal/postion_del_modal.php'; ?>
    <?php include 'modal/cashadvance_edit_modal.php'; ?>
    <?php include 'modal/cashadvance_del_modal.php'; ?>

    <script>
    $(function(){
        $('.edit').click(function(e){
            e.preventDefault();
            $('#edit').modal('show');
            var id = $(this).data('id');
            editID(id);
        });

        $('.delete').click(function(e){
            e.preventDefault();
            $('#delete').modal('show');
            var id = $(this).data('id');
            delID(id);
        });

        $("#reservation").on('change', function(){
            var range = encodeURI($(this).val());
            window.location = 'payroll.php?range='+range;
        });

        $('#payroll').click(function(e){
            e.preventDefault();
            $('#payForm').attr('action', 'payroll_generate.php');
            $('#payForm').submit();
        });

        $('#payslip').click(function(e){
            e.preventDefault();
            $('#payForm').attr('action', 'payslip_generate.php');
            $('#payForm').submit();
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

    <script src="js/timepicki.js"></script>
    <script>
        $('#timepicker1').timepicki();
        $('#timepicker2').timepicki();
        $('.timepicker1').timepicki();
        $('.timepicker2').timepicki();
    </script>
</div>

</body>
</html>
