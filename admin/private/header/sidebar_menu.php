<?php
$name = "Julius Maru";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Menu</title>
    <style>
        .sidebar-collapse ul.nav li a {
            background-color: rgb(48, 83, 83);
            color: white;
        }

        .sidebar-collapse ul.nav li a:hover,
        .sidebar-collapse ul.nav li a:focus,
        .sidebar-collapse ul.nav li a:active {
            background-color: deepskyblue;
        }
    </style>
</head>
<body>
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <div class="user-section">
                        <div class="user-section-inner">
                            <img src="assets/img/user.jpg" alt="User Image">
                        </div>
                        <div class="user-info">
                            <div><strong><?php echo ucwords(htmlentities($name)); ?></strong></div>
                            <div class="user-text-online">
                                <span class="user-circle-online btn btn-success btn-circle"></span>&nbsp;Online
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <a href="dashboard.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="attendance.php"><i class="fa fa-history fa-fw"></i> Attendance</a>
                </li>
                <li>
                    <a href="employee_records.php"><i class="fa fa-users fa-fw"></i> Employee Records</a>
                </li>
                <li>
                    <a href="schedule.php"><i class="fa fa-calendar fa-fw"></i> Schedule</a>
                </li>
                <li>
                    <a href="deduction.php"><i class="fa fa-money fa-fw"></i> Deduction</a>
                </li>
                <li>
                    <a href="payroll.php"><i class="fa fa-file fa-fw"></i> Payroll</a>
                </li>
                <li>
                    <a href="payroll.php?show=generate"><i class="fa fa-file fa-fw"></i> Reports</a>
                </li>
            </ul>
        </div>
    </nav>
</body>
</html>
