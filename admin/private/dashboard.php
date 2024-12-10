<?php 
include('header/head.php');
include('../connection/db_conn.php');

// Fetch data from database
$query1 = $conn->query("SELECT COUNT(*) AS emp_id FROM employee_records") or die(mysqli_error($conn));
$row1 = $query1->fetch_array();

$query2 = $conn->query("SELECT COUNT(*) AS id FROM employee_attendance") or die(mysqli_error($conn));
$row2 = $query2->fetch_array();

$query3 = $conn->query("SELECT COUNT(*) AS ids FROM employee_schedule") or die(mysqli_error($conn));
$row3 = $query3->fetch_array();

$query4 = $conn->query("SELECT COUNT(*) AS log_id FROM history_log") or die(mysqli_error($conn));
$row4 = $query4->fetch_array();

// Fetch the total net pay
$queryNetPay = $conn->query("SELECT SUM(net_pay) AS total_netpay FROM payroll") or die(mysqli_error($conn));
$rowNetPay = $queryNetPay->fetch_array();

$totalNetPay = $rowNetPay['total_netpay']; // Total net pay
$total = $row1['emp_id'] + $row2['id'] + $row3['ids'] + $row4['log_id'];
$totalPercentage = ($totalNetPay / $total) * 100; // Percentage of net pay (relative to total dashboard metrics)

// Format numbers for display
$formattedNetPay = number_format($totalNetPay, 2);
$formattedPercentage = number_format($totalPercentage, 2) . '%';

$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="private/assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
  <link href="private/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="private/assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
  <link href="private/assets/css/style.css" rel="stylesheet" />
  <link href="private/assets/css/main-style.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <style>
    /* Adjust donut chart size */
    #dashboardDonutChart {
      width: 100%;
      height: 350px;
      max-width: 500px;
      margin: 0 auto;
    }

    /* Modern Card Style */
    .dashboard-card {
      background-color: #ffffff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease-in-out;
      text-align: center;
      margin-bottom: 20px;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .dashboard-card i {
      font-size: 3rem;
      color: #4caf50;
    }

    .dashboard-card b {
      font-size: 1.5rem;
      color: #333;
    }

    .alert-modern {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: white;
      border-radius: 10px;
      padding: 15px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    .alert-modern i {
      font-size: 2rem;
    }

    .alert-modern .value {
      font-size: 2.5rem;
    }

    .alert-modern:hover {
      background: linear-gradient(135deg, #2575fc, #6a11cb);
    }

    .donut-chart-container {
      transition: all 0.5s ease-in-out;
    }

    .donut-chart-container:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    /* Modern Bar Chart */
    .bar-chart {
      border-radius: 10px;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .bar-chart .bar {
      border: none;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <?php include('header/sidebar_menu.php'); ?>

  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
      </div>
    </div>

    <div class="row">
      <!-- Employee -->
      <div class="col-lg-3">
        <div class="alert-modern" style="background-color: #4caf50;">
          <i class="fa fa-users"></i>
          <div class="value"><?php echo $row1['emp_id']; ?></div>
          <div>Employees</div>
        </div>
      </div>

      <!-- Attendance Records -->
      <div class="col-lg-3">
        <div class="alert-modern" style="background-color: #2196f3;">
          <i class="fa fa-file"></i>
          <div class="value"><?php echo $row2['id']; ?></div>
          <div>Attendance Records</div>
        </div>
      </div>

      <!-- Schedule -->
      <div class="col-lg-3">
        <div class="alert-modern" style="background-color: #ff9800;">
          <i class="fa fa-history"></i>
          <div class="value"><?php echo $row3['ids']; ?></div>
          <div>Schedule</div>
        </div>
      </div>

      <!-- Total Net Pay -->
      <div class="col-lg-3">
        <div class="alert-modern" style="background-color: #9c27b0;">
          <i class="fa fa-money"></i>
          <div class="value"><?php echo $formattedNetPay; ?></div>
          <div>Total Net Pay</div>
          <small>Percentage: <?php echo $formattedPercentage; ?></small>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
