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
        <a href="employee_records.php" style="text-decoration: none; display: block;">
          <div class="alert-modern" style="background-color: #4caf50;">
            <i class="fa fa-users"></i>
            <div class="value"><?php echo $row1['emp_id']; ?></div>
            <div>Employees</div>
          </div>
        </a>
      </div>

      <!-- Attendance Records -->
      <div class="col-lg-3">
        <a href="attendance.php" style="text-decoration: none;">
          <div class="alert-modern" style="background-color: #2196f3;">
            <i class="fa fa-file"></i>
            <div class="value"><?php echo $row2['id']; ?></div>
            <div>Attendance Records</div>
          </div>
        </a>
      </div>

      <!-- Schedule -->
      <div class="col-lg-3">
        <a href="schedule.php" style="text-decoration: none;">
          <div class="alert-modern" style="background-color: #ff9800;">
            <i class="fa fa-history"></i>
            <div class="value"><?php echo $row3['ids']; ?></div>
            <div>Schedule</div>
          </div>
        </a>
      </div>

      <!-- Logged History -->
      <div class="col-lg-3">
        <a href="attendance.php" style="text-decoration: none;">
          <div class="alert-modern" style="background-color: #f44336;">
            <i class="fa fa-eye"></i>
            <div class="value"><?php echo $row4['log_id']; ?></div>
            <div>Log Activities</div>
          </div>
        </a>
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

      // Bar Chart
      var barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: ['Members', 'Attendance Records', 'Schedule', 'Log Activities'],
          datasets: [{
            label: 'Count',
            data: [<?php echo $row1['emp_id']; ?>, <?php echo $row2['id']; ?>, <?php echo $row3['ids']; ?>, <?php echo $row4['log_id']; ?>],
            backgroundColor: [
              'rgba(72, 132, 239, 0.7)',  // Cool Blue
              'rgba(120, 233, 177, 0.7)', // Mint Green
              'rgba(255, 153, 122, 0.7)', // Soft Coral
              'rgba(244, 67, 54, 0.7)'    // Soft Red
            ],
            hoverBackgroundColor: [
              'rgba(72, 132, 239, 1)',
              'rgba(120, 233, 177, 1)',
              'rgba(255, 153, 122, 1)',
              'rgba(244, 67, 54, 1)'
            ]
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            datalabels: {
              color: '#fff',
              font: {
                weight: 'bold'
              },
              anchor: 'end',
              align: 'end'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  var value = context.raw;
                  return `${context.label}: ${value}`;
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      // Donut Chart
      var donutChart = new Chart(donutCtx, {
        type: 'doughnut',
        data: {
          labels: ['Members', 'Attendance Records', 'Schedule', 'Log Activities'],
          datasets: [{
            data: [<?php echo $row1['emp_id']; ?>, <?php echo $row2['id']; ?>, <?php echo $row3['ids']; ?>, <?php echo $row4['log_id']; ?>],
            backgroundColor: [
              'rgba(72, 132, 239, 0.7)',  // Cool Blue
              'rgba(120, 233, 177, 0.7)', // Mint Green
              'rgba(255, 153, 122, 0.7)', // Soft Coral
              'rgba(244, 67, 54, 0.7)'    // Soft Red
            ]
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            datalabels: {
              formatter: function(value, context) {
                var total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                return ((value / total) * 100).toFixed(2) + '%';
              },
              color: '#fff',
              font: {
                weight: 'bold'
              }
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  var value = context.raw;
                  var total = context.dataset.data.reduce((a, b) => a + b, 0);
                  var percentage = ((value / total) * 100).toFixed(2);
                  return `${context.label}: ${value} (${percentage}%)`;
                }
              }
            }
          }
        }
      });
    });
  </script>

  <script src="assets/plugins/jquery-1.10.2.js"></script>
  <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
  <script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
  <script src="assets/plugins/pace/pace.js"></script>
  <script src="assets/scripts/siminta.js"></script>
</body>
</html>
