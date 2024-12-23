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


<?php
include('../connection/db_conn.php'); // Include your DB connection

// Get the current month and year
$currentMonth = date('m');
$currentYear = date('Y');
$currentDay = date('d'); // Get the current day of the month

// Get the full name of the current month (e.g., "December")
$currentMonthName = date('F');

// Get the total number of days in the current month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear); // Get number of days in current month

// SQL query to get the attendance count for the current month and each day
$sql = "SELECT DAY(date_attendance) AS day, COUNT(*) AS attendance_count
        FROM employee_attendance
        WHERE MONTH(date_attendance) = ? AND YEAR(date_attendance) = ?
        GROUP BY DAY(date_attendance)
        ORDER BY DAY(date_attendance)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $currentMonth, $currentYear); // Bind current month and year
$stmt->execute();
$result = $stmt->get_result();

// Initialize arrays to store days (1 to last day of the month) and attendance count
$days = range(1, $daysInMonth); // Array of all days in the month (1 to 31)
$attendanceCounts = array_fill(0, $daysInMonth, 0); // Initialize all attendance counts to 0

// Populate the attendance counts for the days with records
while ($row = $result->fetch_assoc()) {
    $day = $row['day'];
    $attendanceCounts[$day - 1] = $row['attendance_count']; // Adjust index for 0-based array
}

$stmt->close(); // Close the statement
$conn->close(); // Close the database connection
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
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
    <div class="alert-modern" style="background-color: #f44336;">
      <i class="fa fa-eye"></i>
      <div class="value"><?php echo $row4['log_id']; ?></div>
      <div>Logged History</div>
    </div>
  </div>
</div> 


    <div class="row">
      <div class="col-lg-6">
        <canvas id="dashboardBarChart"></canvas>
      </div>
      <div class="col-lg-6 donut-chart-container">
        <canvas id="dashboardDonutChart"></canvas> <!-- Donut Chart -->
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <!-- <div class="card-title">
              Monthly Attendance Report
            </div> -->
          </div>
          <div class="card-body">
              <div id="chart"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
        var options = {
            series: [{
                name: "Attendance Count",
                data: <?php echo json_encode($attendanceCounts); ?> // PHP to JavaScript variable
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: '<?php echo $currentMonthName; ?> Attendance Report', // Dynamically set title with the current month name
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // Alternating row colors
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: <?php echo json_encode($days); ?>, // PHP to JavaScript variable for day labels (1 to 31)
                title: {
                    text: 'Day of the Month'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Bar Chart Configuration
    var barCtx = document.getElementById('dashboardBarChart').getContext('2d');
    var donutCtx = document.getElementById('dashboardDonutChart').getContext('2d');

    // Chart labels and data
    var chartLabels = ['Employee', 'Attendance Records', 'Schedule', 'Logged History'];
    var chartData = [
      <?php echo $row1['emp_id']; ?>, 
      <?php echo $row2['id']; ?>, 
      <?php echo $row3['ids']; ?>, 
      <?php echo $row4['log_id']; ?>
    ];

    // Calculate total for percentage calculations
    var totalValue = chartData.reduce(function(acc, value) { return acc + value; }, 0);

    // Colors for the charts
    var colors = [
      'rgba(72, 132, 239, 0.7)',  // Cool Blue
      'rgba(120, 233, 177, 0.7)', // Mint Green
      'rgba(255, 153, 122, 0.7)', // Soft Coral
      'rgb(255,0,255)', // Light Purple (changed)
    ];

    var hoverColors = [
      'rgba(72, 132, 239, 1)',  // Hover effect Cool Blue
      'rgba(120, 233, 177, 1)', // Hover effect Mint Green
      'rgba(255, 153, 122, 1)', // Hover effect Soft Coral
      'rgb(153,50,204)',
 // Light Purple hover (changed)
    ];

    // Initialize Bar Chart
    var barChart = new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: chartLabels,
        datasets: [{
          label: 'Count',
          data: chartData,
          backgroundColor: colors,
          borderColor: 'transparent',
          borderWidth: 0,
          hoverBackgroundColor: hoverColors,
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
            align: 'end',
            formatter: function(value) {
              return ((value / totalValue) * 100).toFixed(2) + '%';
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                var value = context.raw;
                var percentage = ((value / totalValue) * 100).toFixed(2);
                return `${context.label}: ${value} (${percentage}%)`;
              }
            }
          }
        }
      }
    });

    // Initialize Doughnut Chart
    var donutChart = new Chart(donutCtx, {
      type: 'doughnut',
      data: {
        labels: chartLabels,
        datasets: [{
          data: chartData,
          backgroundColor: colors,
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          datalabels: {
            formatter: function(value) {
              return ((value / totalValue) * 100).toFixed(2) + '%';
            },
            color: '#fff',
            font: { weight: 'bold' },
            anchor: 'end',
            align: 'center'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                var value = context.raw;
                var percentage = ((value / totalValue) * 100).toFixed(2);
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
