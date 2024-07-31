<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Menu</title>
    <style>
        .sidebar-collapse ul.nav li a {
            background-color: rgb(48 83 83);
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
        <!-- sidebar-collapse -->
        <div class="sidebar-collapse">
            <!-- side-menu -->
            <ul class="nav" id="side-menu">
                <li>
                    <!-- user image section-->
                    <div class="user-section">
                        <div class="user-section-inner">
                            <img src="assets/img/user.jpg" alt="">
                        </div>
                        <div class="user-info">
                            <div> <strong><?php echo ucwords(htmlentities($name)); ?></strong></div>
                            <div class="user-text-online">
                                <span class="user-circle-online btn btn-success btn-circle"></span>&nbsp;Online
                            </div>
                        </div>
                    </div>
                    <!--end user image section-->
                </li>

                <li class="selected">
                    <a href="dashboard.php"><i class="fa fa-dashboard fa-fw"></i>Dashboard</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-history fa-fw"></i> Attendance<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="attendance.php">&rarr; Attendance records</a>
                        </li>
                    </ul>
                    <!-- second-level-items -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-users fa-fw"></i> Employee<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="employee_records.php">&rarr; Employee records</a>
                        </li>
                       
                        <li>
                            <a href="schedule.php">&rarr; Schedule</a>
                        </li>
                        <li>
                            <a href="cashadvance.php">&rarr; Cash Advance</a>
                        </li>
                        <li>
                            <a href="position.php">&rarr; Position</a>
                        </li>
                    </ul>
                    <!-- second-level-items -->
                </li>

                <li>
                    <a href="deduction.php"><i class="fa fa-money fa-fw"></i> Deduction</a>
                </li>
                <li>
                    <a href="payroll.php"><i class="fa fa-file fa-fw"></i> Payroll</a>
                </li>
                <li>
                    <a href="user_monitor.php"><i class="fa fa-eye fa-fw"></i> Admin logged</a>
                </li>
                <li>
                    <a href="payroll.php?show=generate"><i class="fa fa-file fa-fw"></i> Reports</a>
                </li>
            </ul>
            <!-- end side-menu -->
        </div>
        <!-- end sidebar-collapse -->
    </nav>
</body>
</html>
