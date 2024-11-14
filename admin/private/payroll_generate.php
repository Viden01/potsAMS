<?php
include '../connection/db_conn.php';

// Function to generate the row content for the payroll
function generateRow($from, $to, $conn, $deduction) {
    $contents = '';
    $sql = "SELECT employee_records.*, 
                   employee_position.rate_per_hour, 
                   SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in))) / 3600 AS total_hours, 
                   employee_attendance.employee_id AS emp_id 
            FROM employee_attendance 
            LEFT JOIN employee_records ON employee_records.emp_id = employee_attendance.employee_id 
            LEFT JOIN employee_position ON employee_position.id = employee_records.position_id 
            WHERE date_attendance BETWEEN '$from' AND '$to' 
            GROUP BY employee_attendance.employee_id 
            ORDER BY employee_records.last_name ASC, employee_records.first_name ASC";
    $query = $conn->query($sql);
    if (!$query) {
        die("Error in SQL query: " . $conn->error);
    }
    $total = 0;
    while ($row = $query->fetch_assoc()) {
        $empid = $row['emp_id'];
        // Fetch cash advance
        $casql = "SELECT SUM(amount) AS cashamount FROM employee_cashadvance WHERE employee_id='$empid' AND date_created BETWEEN '$from' AND '$to'";
        $caquery = $conn->query($casql);
        if (!$caquery) {
            die("Error in cash advance query: " . $conn->error);
        }
        $carow = $caquery->fetch_assoc();
        $cashadvance = isset($carow['cashamount']) ? $carow['cashamount'] : 0;

        // Calculate gross, deductions, and net pay
        $rate_per_hour = isset($row['rate_per_hour']) ? $row['rate_per_hour'] : 0;
        $total_hours = isset($row['total_hours']) ? $row['total_hours'] : 0;
        $gross = $rate_per_hour * $total_hours;
        $total_deduction = $deduction + $cashadvance;
        $net = $gross - $total_deduction;

        $total += $net;

        $contents .= '
        <tr>
            <td>'.$row['last_name'].', '.$row['first_name'].'</td>
            <td>'.$row['employee_id'].'</td>
            <td align="right">'.number_format($net, 2).'</td>
        </tr>';
    }

    $contents .= '
        <tr>
            <td colspan="2" align="right"><b>Total</b></td>
            <td align="right"><b>'.number_format($total, 2).'</b></td>
        </tr>';

    return $contents;
}

// Get date range from POST data
$range = $_POST['date_range'];
$ex = explode(' - ', $range);
$from = date('Y-m-d', strtotime($ex[0]));
$to = date('Y-m-d', strtotime($ex[1]));

// Get total deductions
$sql = "SELECT SUM(amount) as total_amount FROM employee_deductions";
$query = $conn->query($sql);
if (!$query) {
    die("Error in deduction query: " . $conn->error);
}
$drow = $query->fetch_assoc();
$deduction = isset($drow['total_amount']) ? $drow['total_amount'] : 0;

$from_title = date('M d, Y', strtotime($ex[0]));
$to_title = date('M d, Y', strtotime($ex[1]));

// Include TCPDF library and set up PDF document
require_once('../tcpdf/config/tcpdf_config.php'); 
require_once('../tcpdf/tcpdf.php');   
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
$pdf->SetCreator(PDF_CREATOR);  
$pdf->SetTitle('Payroll: '.$from_title.' - '.$to_title);  
$pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
$pdf->SetDefaultMonospacedFont('helvetica');  
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
$pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
$pdf->setPrintHeader(false);  
$pdf->setPrintFooter(false);  
$pdf->SetAutoPageBreak(TRUE, 10);  
$pdf->SetFont('helvetica', '', 11);  
$pdf->AddPage();  

$logoPath = 'picture2.jpg';  // Adjust the path to the logo image
if (file_exists($logoPath)) {
    $pdf->Image($logoPath, 15, 10, 20, 20, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
}

$content = '';  
$content .= '
    <div style="text-align:center;">
        <h2>Phonics Online Tutorial Services - ESL</h2>
        <h4>'.$from_title.' - '.$to_title.'</h4>
    </div>
    <table border="1" cellspacing="0" cellpadding="3">  
       <tr>  
            <th width="40%" align="center"><b>Employee Name</b></th>
            <th width="30%" align="center"><b>Employee ID</b></th>
            <th width="30%" align="center"><b>Net Pay</b></th> 
       </tr>';  

$content .= generateRow($from, $to, $conn, $deduction);  
$content .= '</table>';  

$pdf->writeHTML($content);  

// Save PDF to a temporary file
$tempfile = 'temp_payroll.pdf';
$pdf->Output($tempfile, 'F');

// Use JavaScript to open the PDF and print automatically
echo "
    <script>
        var pdfWindow = window.open('$tempfile', '_blank');
        pdfWindow.onload = function() {
            pdfWindow.print();  // Trigger print dialog when PDF is loaded
        };
    </script>
";
?>
