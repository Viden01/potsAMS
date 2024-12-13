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
    <!-- Page-Level CSS -->
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
    </style>
</head>
<body>
    <!--  wrapper -->
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
                        // Handle case where no rows are returned
                        $id = $ids = $name = "Unknown";
                    }
                } else {
                    // Handle case where session variable is not set
                    $id = $ids = $name = "Unknown";
                }
                ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                       Welcome!,</font> <?php echo ucwords(htmlentities($id)); ?> <i class="fa fa-user fa-3x"></i>
                    </a>
                    <!-- dropdown user-->
                    <ul class="dropdown-menu dropdown-user">
                        
                        <li class="divider"></li>
                        <li><a href="Logout.php" onclick="logoutAllSessions()">logout><i class="fa fa-sign-out fa-fw"></i>Logout</a>
                        </li>
                    </ul>
                    <!-- end dropdown-user -->
                </li>
                <!-- end main dropdown -->
            </ul>
            <!-- end navbar-top-links -->
        </nav>
        <!-- end navbar top -->
    </div>
    <!-- end wrapper -->
</body>
</html>
