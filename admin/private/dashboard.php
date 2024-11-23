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
    /* Modern Card Style */
    .dashboard-card {
      background-color: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease-in-out;
      text-align: center;
      margin-bottom: 20px;
      height: 120px;
    }
    .dashboard-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    .dashboard-card i {
      font-size: 3rem;
      transition: color 0.3s ease-in-out;
    }
    .dashboard-card:hover i {
      color: #fff;
    }
    .dashboard-card b {
      font-size: 2rem;
      color: #333;
      font-weight: bold;
    }
    .dashboard-card p {
      font-size: 1.2rem;
      color: #777;
    }

    /* Color Scheme */
    .card-emp { background-color: #4caf50; }
    .card-attendance { background-color: #2196f3; }
    .card-schedule { background-color: #ff9800; }
    .card-history { background-color: #f44336; }

    /* Data Value styling */
    .data-value {
      font-size: 2rem;
      color: white;
      font-weight: bold;
    }

    /* Animation for the cards */
    .animate {
      animation: fadeInUp 1s ease-out forwards;
    }

    /* Fade-in Animation */
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(50px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
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
        <div class="dashboard-card card-emp animate">
          <i class="fa fa-users"></i>
          <div class="data-value"><?php echo $row1['emp_id']; ?></div>
          <p>Employees</p>
        </div>
      </div>

      <!-- Attendance Records -->
      <div class="col-lg-3">
        <div class="dashboard-card card-attendance animate">
          <i class="fa fa-file"></i>
          <div class="data-value"><?php echo $row2['id']; ?></div>
          <p>Attendance Records</p>
        </div>
      </div>

      <!-- Schedule -->
      <div class="col-lg-3">
        <div class="dashboard-card card-schedule animate">
          <i class="fa fa-history"></i>
          <div class="data-value"><?php echo $row3['ids']; ?></div>
          <p>Schedule</p>
        </div>
      </div>

      <!-- Logged History -->
      <div class="col-lg-3">
        <div class="dashboard-card card-history animate">
          <i class="fa fa-eye"></i>
          <div class="data-value"><?php echo $row4['log_id']; ?></div>
          <p>Logged History</p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <canvas id="dashboardBarChart"></canvas>
      </div>
      <div class="col-lg-6">
        <canvas id="dashboardDonutChart"></canvas> <!-- Donut Chart -->
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var barCtx = document.getElementById('dashboardBarChart').getContext('2d');
      var donutCtx = document.getElementById('dashboardDonutChart').getContext('2d'); // New context for donut chart

      var barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: ['Members', 'Attendance Records', 'Schedule', 'Logged History'],
          datasets: [{
            label: 'Count',
            data: [<?php echo $row1['emp_id']; ?>, <?php echo $row2['id']; ?>, <?php echo $row3['ids']; ?>, <?php echo $row4['log_id']; ?>],
            backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#f44336'],
            borderColor: ['#388e3c', '#1976d2', '#f57c00', '#d32f2f'],
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      var donutChart = new Chart(donutCtx, {
        type: 'doughnut',  // Donut chart type
        data: {
          labels: ['Members', 'Attendance Records', 'Schedule', 'Logged History'],
          datasets: [{
            data: [<?php echo $row1['emp_id']; ?>, <?php echo $row2['id']; ?>, <?php echo $row3['ids']; ?>, <?php echo $row4['log_id']; ?>],
            backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#f44336'],
            borderColor: ['#fff', '#fff', '#fff', '#fff'],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,  // Allow custom height
          aspectRatio: 1,  // Aspect ratio for the chart (1 = square)
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
