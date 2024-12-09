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

      <!-- Logged History -->
      <!-- <div class="col-lg-3">
        <div class="alert-modern" style="background-color: #f44336;">
          <i class="fa fa-eye"></i>
          <div class="value"><?php echo $row4['log_id']; ?></div>
          <div>Logged History</div>
        </div>
      </div>
    </div> -->

    <div class="row">
      <div class="col-lg-6">
        <canvas id="dashboardBarChart"></canvas>
      </div>
      <div class="col-lg-6 donut-chart-container">
        <canvas id="dashboardDonutChart"></canvas> <!-- Donut Chart -->
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
          labels: ['Members', 'Attendance Records', 'Schedule',],
          datasets: [{
            label: 'Count',
            data: [<?php echo $row1['emp_id']; ?>, <?php echo $row2['id']; ?>, <?php echo $row3['ids']; ?>, <?php echo $row4['log_id']; ?>],
            backgroundColor: [
              'rgba(72, 132, 239, 0.7)',  // Cool Blue
              'rgba(120, 233, 177, 0.7)', // Mint Green
              'rgba(255, 153, 122, 0.7)', // Soft Coral
            ],
            borderColor: 'transparent',
            borderWidth: 0,
            hoverBackgroundColor: [
              'rgba(72, 132, 239, 1)',  // Hover effect Cool Blue
              'rgba(120, 233, 177, 1)', // Hover effect Mint Green
              'rgba(255, 153, 122, 1)', // Hover effect Soft Coral
            ],
            hoverBorderWidth: 0
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
                label: function(tooltipItem) {
                  var dataset = tooltipItem.dataset;
                  var currentValue = dataset.data[tooltipItem.dataIndex];
                  var total = dataset.data.reduce(function(a, b) {
                    return a + b;
                  }, 0);
                  var percentage = ((currentValue / total) * 100).toFixed(2) + '%';
                  return tooltipItem.label + ': ' + percentage;
                }
              }
            }
          }
        }
      });

      var donutChart = new Chart(donutCtx, {
        type: 'doughnut',
        data: {
          labels: ['Members', 'Attendance Records', 'Schedule',],
          datasets: [{
            data: [<?php echo $row1['emp_id']; ?>, <?php echo $row2['id']; ?>, <?php echo $row3['ids']; ?>, <?php echo $row4['log_id']; ?>],
            backgroundColor: [
              'rgba(72, 132, 239, 0.7)',  // Cool Blue
              'rgba(120, 233, 177, 0.7)', // Mint Green
              'rgba(255, 153, 122, 0.7)', // Soft Coral
 
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            datalabels: {
              formatter: function(value, context) {
                var total = context.chart.data.datasets[0].data.reduce(function(a, b) {
                  return a + b;
                }, 0);
                var percentage = ((value / total) * 100).toFixed(2) + '%';
                return percentage;
              },
              color: '#fff',
              font: {
                weight: 'bold'
              },
              anchor: 'end',
              align: 'end'
            },
            tooltip: {
              callbacks: {
                label: function(tooltipItem) {
                  var dataset = tooltipItem.dataset;
                  var currentValue = dataset.data[tooltipItem.dataIndex];
                  var total = dataset.data.reduce(function(a, b) {
                    return a + b;
                  }, 0);
                  var percentage = ((currentValue / total) * 100).toFixed(2) + '%';
                  return tooltipItem.label + ': ' + percentage;
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
