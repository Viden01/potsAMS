<?php 
include('header/head.php');
include('../connection/db_conn.php');

// Fetch data from the database
$query1 = $conn->query("SELECT COUNT(*) AS emp_id FROM employee_records") or die(mysqli_error($conn));
$row1 = $query1->fetch_array();

$query2 = $conn->query("SELECT COUNT(*) AS id FROM employee_attendance") or die(mysqli_error($conn));
$row2 = $query2->fetch_array();

$query3 = $conn->query("SELECT COUNT(*) AS ids FROM employee_schedule") or die(mysqli_error($conn));
$row3 = $query3->fetch_array();

$query4 = $conn->query("SELECT COUNT(*) AS log_id FROM history_log") or die(mysqli_error($conn));
$row4 = $query4->fetch_array();
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
  <style>
    .data-card {
      padding: 20px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-align: center;
      margin: 10px;
    }
    .data-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }
    .data-icon {
      font-size: 3rem;
      color: #fff;
      padding: 15px;
      border-radius: 50%;
      margin-bottom: 10px;
    }
    .card-title {
      font-size: 1.4rem;
      font-weight: bold;
    }
    .card-value {
      font-size: 2rem;
      color: #333;
    }
    .bg-green { background: #4caf50; }
    .bg-blue { background: #2196f3; }
    .bg-orange { background: #ff9800; }
    .bg-red { background: #f44336; }
  </style>
</head>
<body>
  <!-- Sidebar and Navbar (Unchanged) -->
  <?php include('header/sidebar_menu.php'); ?>
  
  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3">
        <div class="data-card">
          <div class="data-icon bg-green">
            <i class="fa fa-users"></i>
          </div>
          <div class="card-title">Employees</div>
          <div class="card-value"><?php echo $row1['emp_id']; ?></div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="data-card">
          <div class="data-icon bg-blue">
            <i class="fa fa-file"></i>
          </div>
          <div class="card-title">Attendance Records</div>
          <div class="card-value"><?php echo $row2['id']; ?></div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="data-card">
          <div class="data-icon bg-orange">
            <i class="fa fa-history"></i>
          </div>
          <div class="card-title">Schedules</div>
          <div class="card-value"><?php echo $row3['ids']; ?></div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="data-card">
          <div class="data-icon bg-red">
            <i class="fa fa-eye"></i>
          </div>
          <div class="card-title">Logged History</div>
          <div class="card-value"><?php echo $row4['log_id']; ?></div>
        </div>
      </div>
    </div>

    <!-- Charts (Keep both existing chart setup) -->
    <div class="row">
      <div class="col-lg-6">
        <canvas id="dashboardBarChart"></canvas>
      </div>
      <div class="col-lg-6">
        <div style="width: 70%; margin: auto;">
          <canvas id="dashboardDonutChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Bar Chart Setup
      var barCtx = document.getElementById('dashboardBarChart').getContext('2d');
      var barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: ['Members', 'Attendance Records', 'Schedule', 'Logged History'],
          datasets: [{
            label: 'Count',
            data: [<?php echo $row1['emp_id']; ?>, <?php echo $row2['id']; ?>, <?php echo $row3['ids']; ?>, <?php echo $row4['log_id']; ?>],
            backgroundColor: ['#4caf50', '#FFE87C', '#6667AB', '#B048B5'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            legend: {
              display: false
            }
          }
        }
      });

      // Donut Chart Setup
      const donutCtx = document.getElementById('dashboardDonutChart').getContext('2d');
      let donutChart = new Chart(donutCtx, {
        type: 'doughnut',
        data: {
          labels: ['Members', 'Attendance Records', 'Schedule', 'Logged History'],
          datasets: [{
            data: [<?php echo $row1['emp_id']; ?>, <?php echo $row2['id']; ?>, <?php echo $row3['ids']; ?>, <?php echo $row4['log_id']; ?>],
            backgroundColor: ['#4caf50', '#FFE87C', '#6667AB', '#B048B5'],
            hoverOffset: 10
          }]
        },
        options: {
          responsive: true,
          cutout: '60%', // Keeps the donut effect
          plugins: {
            legend: {
              position: 'bottom'
            }
          }
        }
      });
    });
  </script>

  <script src="assets/plugins/jquery-1.10.2.js"></script>
  <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
  <!-- Include the sidebar menu JavaScript for functionality -->
  <script src="private/assets/js/sidebar-menu.js"></script> <!-- Ensure this is correctly referenced -->
</body>
</html>
