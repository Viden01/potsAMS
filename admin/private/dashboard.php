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
      <div class="col-lg-3">
        <div class="alert alert-success text-center">
          <i class="fa fa-users fa-3x"></i>&nbsp;<b><?php echo $row1['emp_id']; ?></b> Employee
        </div>
      </div>
      <div class="col-lg-3">
        <div class="alert alert-info text-center">
          <i class="fa fa-file fa-3x"></i>&nbsp;<b><?php echo $row2['id']; ?></b> Attendance records
        </div>
      </div>
      <div class="col-lg-3">
        <div class="alert alert-warning text-center">
          <i class="fa fa-history fa-3x"></i>&nbsp;<b><?php echo $row3['ids']; ?></b> Schedule
        </div>
      </div>
      <div class="col-lg-3">
        <div class="alert alert-danger text-center">
          <i class="fa fa-eye fa-3x"></i>&nbsp;<b><?php echo $row4['log_id']; ?></b> Logged history
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <canvas id="dashboardBarChart"></canvas>
      </div>
      <div class="col-lg-6">
        <canvas id="dashboardDonutChart"></canvas>
      </div>
    </div>
  </div>

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
            backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#f44336'],
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
            backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#f44336'],
            hoverOffset: 10  // Pop effect on hover
          }]
        },
        options: {
          responsive: true,
          cutout: '50%', // Donut hole size
          plugins: {
            legend: {
              position: 'bottom'
            },
            tooltip: {
              callbacks: {
                label: function(tooltipItem) {
                  const dataset = tooltipItem.dataset;
                  const currentValue = dataset.data[tooltipItem.dataIndex];
                  const total = dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = ((currentValue / total) * 100).toFixed(2) + '%';
                  return `${tooltipItem.label}: ${percentage}`;
                }
              }
            }
          },
          onClick: (event, elements) => {
            if (elements.length > 0) {
              const index = elements[0].index;
              const activeSegment = donutChart.getDatasetMeta(0).data[index];
              
              // Toggle "pop" effect
              activeSegment.options.hoverOffset = activeSegment.options.hoverOffset ? 0 : 20;
              donutChart.update();
            }
          }
        }
      });
    });
  </script>

  <script src="assets/plugins/jquery-1.10.2.js"></script>
  <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
</body>
</html>
