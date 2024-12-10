<?php
session_start();
if (!isset($_SESSION["email_address"])) {
    header("location:../index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POTS - ESL </title>
    <!-- Core CSS - Include with every page -->
    <link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/main-style.css" rel="stylesheet" />
    <link href="css/timepicki.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- Page-Level CSS -->
    <link href="assets/plugins/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <style type="text/css">
        .zoomin img {
            height: 35px;
            width: 35px;
            -webkit-transition: all 2s ease;
            -moz-transition: all 2s ease;
            -ms-transition: all 2s ease;
            transition: all 2s ease;
        }
        .zoomin img:hover {
            width: 100px;
            height: 100px;
        }
        .navbar-fixed-top {
            background-color: skyblue;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
        }
        .sidebar .nav > li > a:hover,
        .sidebar .nav > li > a.active {
            background-color: #007bff;
            color: #fff;
        }
        .nav-second-level {
            background-color: #454d55;
            padding-left: 20px;
        }
        .nav-second-level a:hover {
            background-color: #0069d9;
        }
        .fa-arrow {
            float: right;
        }
    </style>
</head>
<body>
    <!-- wrapper -->
    <div id="wrapper">
        <!-- navbar top -->
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar">
            <!-- navbar-header -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">
                    <h2 style="color: #fff">POTS - ESL</h2>
                </a>
            </div>
            <!-- end navbar-header -->
            <!-- navbar-top-links -->
            <ul class="nav navbar-top-links navbar-right">
                <!-- main dropdown -->
                <?php 
                include '../connection/db_conn.php';

                if (isset($_SESSION['email_address'])) {
                    $email = $conn->real_escape_string($_SESSION['email_address']);
                    $r = $conn->query("SELECT * FROM login_admin WHERE email_address = '$email'") or die ($conn->error);

                    if ($r->num_rows > 0) {
                        $row = $r->fetch_array();
                        $id = htmlentities($row['email_address']);
                        $ids = htmlentities($row['id']);
                        $name = htmlentities($row['name']);
                    } else {
                        $id = $ids = $name = "Unknown";
                    }
                } else {
                    $id = $ids = $name = "Unknown";
                }
                ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Welcome!, <?php echo ucwords(htmlentities($id)); ?> <i class="fa fa-user fa-3x"></i>
                    </a>
                    <!-- dropdown user-->
                    <ul class="dropdown-menu dropdown-user">
                        <li class="divider"></li>
                        <li><a href="Logout.php"><i class="fa fa-sign-out fa-fw"></i>Logout</a></li>
                    </ul>
                    <!-- end dropdown-user -->
                </li>
                <!-- end main dropdown -->
            </ul>
            <!-- end navbar-top-links -->
        </nav>
        <!-- end navbar top -->

        <!-- sidebar -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="sidebar-search">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </li>
                    <li>
                        <a href="dashboard.php" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-users fa-fw"></i> Employees<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="employee_list.php"><i class="fa fa-list"></i> Employee List</a>
                            </li>
                            <li>
                                <a href="add_employee.php"><i class="fa fa-plus"></i> Add Employee</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-calendar-check-o fa-fw"></i> Attendance<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="attendance_view.php"><i class="fa fa-eye"></i> View Attendance</a>
                            </li>
                            <li>
                                <a href="attendance_report.php"><i class="fa fa-file"></i> Attendance Report</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-money fa-fw"></i> Payroll<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="payroll_list.php"><i class="fa fa-list"></i> Payroll List</a>
                            </li>
                            <li>
                                <a href="generate_payroll.php"><i class="fa fa-calculator"></i> Generate Payroll</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-file-text fa-fw"></i> Reports<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="report_summary.php"><i class="fa fa-bar-chart"></i> Summary Report</a>
                            </li>
                            <li>
                                <a href="report_detailed.php"><i class="fa fa-table"></i> Detailed Report</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="settings.php"><i class="fa fa-cogs fa-fw"></i> Settings</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- end sidebar -->
    </div>
    <!-- end wrapper -->
</body>
</html>
