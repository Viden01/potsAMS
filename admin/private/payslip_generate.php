<?php
include '../connection/db_conn.php';

// Initialize output buffering
ob_start();

$range = $_POST['date_range'];
$ex = explode(' - ', $range);
$from = date('Y-m-d', strtotime($ex[0]));
$to = date('Y-m-d', strtotime($ex[1]));

$sql = "SELECT *, SUM(amount) as total_amount FROM employee_deductions";
$query = $conn->query($sql);
$drow = $query->fetch_assoc();
$deduction = isset($drow['total_amount']) ? $drow['total_amount'] : 0;

$from_title = date('M d, Y', strtotime($ex[0]));
$to_title = date('M d, Y', strtotime($ex[1]));

require_once('../tcpdf/config/tcpdf_config.php'); 
require_once('../tcpdf/tcpdf.php');  
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
$pdf->SetCreator(PDF_CREATOR);  
$pdf->SetTitle('Payslip: '.$from_title.' - '.$to_title);  
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

$logoPath = 'picture2.jpg';
$y_position = 10;

$sql = "SELECT employee_records.*, 
               SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in))) / 3600 AS total_hours, 
               employee_attendance.employee_id AS emp_id, 
               employee_position.rate_per_hour  -- Ensure this field is included
        FROM employee_attendance 
        LEFT JOIN employee_records ON employee_records.emp_id = employee_attendance.employee_id 
        LEFT JOIN employee_position ON employee_position.id = employee_records.position_id 
        WHERE date_attendance BETWEEN '$from' AND '$to' 
        GROUP BY employee_attendance.employee_id 
        ORDER BY employee_records.last_name ASC, employee_records.first_name ASC";

$query = $conn->query($sql);
$total = 0;

while ($row = $query->fetch_assoc()) {
    // Debugging line
    var_dump($row);  

    if ($y_position > 250) {  // Check if we need a new page
        $pdf->AddPage();
        $y_position = 10;  // Reset y position
    }

    $pdf->SetY($y_position);

    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 15, $y_position, 20, 20, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }

    $empid = $row['emp_id'];
    
    $casql = "SELECT *, SUM(amount) AS cashamount FROM employee_cashadvance WHERE employee_id='$empid' AND date_created BETWEEN '$from' AND '$to'";
    $caquery = $conn->query($casql);
    $carow = $caquery->fetch_assoc();
    $cashadvance = isset($carow['cashamount']) ? $carow['cashamount'] : 0;

    // Ensure 'rate_per_hour' exists in $row
    $rate_per_hour = isset($row['rate_per_hour']) ? $row['rate_per_hour'] : 0;
    $total_hours = isset($row['total_hours']) ? $row['total_hours'] : 0;

    $gross = $rate_per_hour * $total_hours;
    $total_deduction = $deduction + $cashadvance;
    $net = $gross - $total_deduction;

    // Round amounts to two decimal places
    $gross = round($gross, 2);
    $total_deduction = round($total_deduction, 2);
    $net = round($net, 2);

    // Format total hours as H:i:s
    $total_seconds = $total_hours * 3600;  // Convert hours to seconds
    $formatted_hours = gmdate("H:i:s", $total_seconds);  // Convert seconds to H:i:s format

    $contents = '
        <h2 align="center">Phonics Online Tutorial Services - ESL</h2>
        <h4 align="center">'.$from_title.' - '.$to_title.'</h4>
        <table cellspacing="0" cellpadding="3">  
            <tr>  
                <td width="25%" align="right">Employee Name: </td>
                <td width="25%"><b>'.$row['first_name'].' '.$row['last_name'].'</b></td>
                <td width="25%" align="right">Rate per Hour: </td>
                <td width="25%" align="right">'.number_format($rate_per_hour, 2).'</td>
            </tr>
            <tr>
                <td width="25%" align="right">Employee ID: </td>
                <td width="25%">'.$row['employee_id'].'</td>   
                <td width="25%" align="right">Total Hours: </td>
                <td width="25%" align="right">'.$formatted_hours.'</td>
            </tr>
            <tr> 
                <td></td> 
                <td></td>
                <td width="25%" align="right"><b>Gross Pay: </b></td>
                <td width="25%" align="right"><b>'.number_format($gross, 2).'</b></td> 
            </tr>
            <tr> 
                <td></td> 
                <td></td>
                <td width="25%" align="right">Deduction: </td>
                <td width="25%" align="right">'.number_format($deduction, 2).'</td> 
            </tr>
            <tr> 
                <td></td> 
                <td></td>
                <td width="25%" align="right">Cash Advance: </td>
                <td width="25%" align="right">'.number_format($cashadvance, 2).'</td> 
            </tr>
            <tr> 
                <td></td> 
                <td></td>
                <td width="25%" align="right"><b>Total Deduction:</b></td>
                <td width="25%" align="right"><b>'.number_format($total_deduction, 2).'</b></td> 
            </tr>
            <tr> 
                <td></td> 
                <td></td>
                <td width="25%" align="right"><b>Net Pay:</b></td>
                <td width="25%" align="right"><b>'.number_format($net, 2).'</b></td> 
            </tr>
        </table>
        <br><hr>';

    $pdf->SetY($y_position + 25); // Move content down after logo
    $pdf->writeHTML($contents);

    $y_position += 110; // Move y position for the next payslip, adjust as necessary
}

// End output buffering and get contents
ob_end_clean();
$pdf->Output('payslip.pdf', 'I');
?>
