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
        <canvas id="dashboardPieChart"></canvas>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var pieCtx = document.getElementById('dashboardPieChart').getContext('2d');

      var gradientColors = [
        pieCtx.createLinearGradient(0, 0, 0, 400),
        pieCtx.createLinearGradient(0, 0, 0, 400),
        pieCtx.createLinearGradient(0, 0, 0, 400),
        pieCtx.createLinearGradient(0, 0, 0, 400)
      ];

      gradientColors[0].addColorStop(0, '#81C784');
      gradientColors[0].addColorStop(1, '#4CAF50');

      gradientColors[1].addColorStop(0, '#64B5F6');
      gradientColors[1].addColorStop(1, '#2196F3');

      gradientColors[2].addColorStop(0, '#FFD54F');
      gradientColors[2].addColorStop(1, '#FFB74D');

      gradientColors[3].addColorStop(0, '#E57373');
      gradientColors[3].addColorStop(1, '#F44336');

      var pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
          labels: ['Members', 'Attendance Records', 'Schedule', 'Logged History'],
          datasets: [{
            data: [<?php echo $row1['emp_id']; ?>, <?php echo $row2['id']; ?>, <?php echo $row3['ids']; ?>, <?php echo $row4['log_id']; ?>],
            backgroundColor: gradientColors,  // Use gradient colors
            hoverOffset: 10  // Enlarges segment on hover
          }]
        },
        options: {
          responsive: true,
          plugins: {
            datalabels: {
              color: '#333',
              font: {
                weight: 'bold',
                size: 14
              },
              formatter: (value, context) => {
                let percentage = ((value / context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0)) * 100).toFixed(1);
                return `${context.chart.data.labels[context.dataIndex]}: ${percentage}%`;
              },
              align: 'end',
              anchor: 'end'
            },
            tooltip: {
              callbacks: {
                label: function(tooltipItem) {
                  var dataset = tooltipItem.dataset;
                  var currentValue = dataset.data[tooltipItem.dataIndex];
                  var total = dataset.data.reduce((a, b) => a + b, 0);
                  var percentage = ((currentValue / total) * 100).toFixed(2);
                  return `${tooltipItem.label}: ${currentValue} (${percentage}%)`;
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
