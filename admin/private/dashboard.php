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

// Fetch Net Pay total from database
$queryNetPay = $conn->query("
    SELECT SUM((e.rate_per_hour * 
        (SUM(TIME_TO_SEC(TIMEDIFF(a.time_out, a.time_in))) / 3600))
        - (IFNULL(d.amount, 0) + IFNULL(ca.amount, 0))
    ) AS total_netpay
    FROM employee_records e
    LEFT JOIN employee_attendance a ON e.emp_id = a.employee_id
    LEFT JOIN (
        SELECT employee_id, SUM(amount) AS amount
        FROM employee_deductions
        GROUP BY employee_id
    ) d ON e.emp_id = d.employee_id
    LEFT JOIN (
        SELECT employee_id, SUM(amount) AS amount
        FROM employee_cashadvance
        GROUP BY employee_id
    ) ca ON e.emp_id = ca.employee_id
    WHERE a.time_in IS NOT NULL AND a.time_out IS NOT NULL
") or die(mysqli_error($conn));

$netPayRow = $queryNetPay->fetch_array();
$totalNetPay = $netPayRow['total_netpay'];

$total = $row1['emp_id'] + $row2['id'] + $row3['ids'] + $row4['log_id'];

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

      <!-- Net Pay Total -->
      <div class="col-lg-3">
        <div class="alert-modern" style="background-color: #f44336;">
          <i class="fa fa-money"></i>
          <div class="value"><?php echo number_format($totalNetPay, 2); ?></div>
          <div>Total Net Pay</div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <canvas id="dashboardBarChart"></canvas>
      </div>
      <div class="col-lg-6 donut-chart-container">
        <canvas id="dashboardDonutChart"></canvas>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var barCtx = document.getElementById('dashboardBarChart').getContext('2d');
      var donutCtx = document.getElementById('dashboardDonutChart').getContext('2d');

      var barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: ['Members', 'Attendance Records', 'Schedule', 'Net Pay'],
          datasets: [{
            label: 'Count',
            data: [
              <?php echo $row1['emp_id']; ?>, 
              <?php echo $row2['id']; ?>, 
              <?php echo $row3['ids']; ?>, 
              <?php echo round($totalNetPay, 2); ?>
            ],
            backgroundColor: [
              'rgba(72, 132, 239, 0.7)', 
              'rgba(120, 233, 177, 0.7)', 
              'rgba(255, 153, 122, 0.7)',
              'rgba(244, 67, 54, 0.7)'
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
        }
      });

      var donutChart = new Chart(donutCtx, {
        type: 'doughnut',
        data: {
          labels: ['Members', 'Attendance Records', 'Schedule', 'Net Pay'],
          datasets: [{
            data: [
              <?php echo $row1['emp_id']; ?>, 
              <?php echo $row2['id']; ?>, 
              <?php echo $row3['ids']; ?>, 
              <?php echo round($totalNetPay, 2); ?>
            ],
            backgroundColor: [
              'rgba(72, 132, 239, 0.7)', 
              'rgba(120, 233, 177, 0.7)', 
              'rgba(255, 153, 122, 0.7)',
              'rgba(244, 67, 54, 0.7)'
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
        }
      });
    });
  </script>
</body>
</html>
