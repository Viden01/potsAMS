<?php
$name = "Julius Maru";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Menu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .navbar-default {
            width: 250px;
            background-color: #305353;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
            color: white;
        }

        .sidebar-collapse {
            padding: 15px;
        }

        .user-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-section img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
        }

        .user-info {
            margin-top: 10px;
        }

        .user-text-online {
            font-size: 0.9rem;
            color: lightgreen;
        }

        ul.nav {
            list-style: none;
            padding: 0;
        }

        ul.nav li {
            margin-bottom: 10px;
        }

        ul.nav li a {
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 5px;
            background-color: rgb(48, 83, 83);
            color: white;
            transition: all 0.3s ease;
        }

        ul.nav li a:hover {
            background-color: deepskyblue;
            color: white;
        }

        ul.nav li a i {
            margin-right: 8px;
        }

        ul.nav ul.nav-second-level {
            padding-left: 15px;
        }

        ul.nav ul.nav-second-level li a {
            font-size: 0.9rem;
            background-color: rgba(48, 83, 83, 0.8);
        }

        ul.nav ul.nav-second-level li a:hover {
            background-color: deepskyblue;
        }

        .selected > a {
            background-color: deepskyblue;
        }
    </style>
</head>
<body>
    <nav class="navbar-default navbar-static-side" role="navigation">
        <!-- Sidebar Collapse -->
        <div class="sidebar-collapse">
            <!-- User Section -->
            <div class="user-section">
                <div class="user-section-inner">
                    <img src="assets/img/user.jpg" alt="User Image">
                </div>
                <div class="user-info">
                    <div><strong><?php echo ucwords(htmlentities($name)); ?></strong></div>
                    <div class="user-text-online">
                        <span class="user-circle-online"></span> Online
                    </div>
                </div>
            </div>
            <!-- Navigation Menu -->
            <ul class="nav" id="side-menu">
                <li class="selected">
                    <a href="dashboard.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-history fa-fw"></i> Attendance <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="attendance.php">&rarr; Attendance Records</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-users fa-fw"></i> Employee <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="employee_records.php">&rarr; Employee Records</a></li>
                        <li><a href="schedule.php">&rarr; Schedule</a></li>
                        <li><a href="cashadvance.php">&rarr; Cash Advance</a></li>
                        <li><a href="position.php">&rarr; Position</a></li>
                    </ul>
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
