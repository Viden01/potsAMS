<?php
session_start();
if (!isset($_SESSION["email_address"])) {
    header("location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POTS - ESL</title>
    <!-- Core CSS - Include with every page -->
    <link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/main-style.css" rel="stylesheet" />
    <!-- Page-Level CSS -->
    <link href="assets/plugins/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <style type="text/css">
        .navbar-fixed-top {
            background-color: #007bff;
            color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand h2 {
            color: #fff;
            font-size: 1.5rem;
            margin: 0;
        }
        .navbar-top-links .dropdown-toggle {
            color: #fff;
            font-size: 1rem;
        }
        .dropdown-menu {
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .dropdown-menu a {
            color: #333;
        }
        .dropdown-menu a:hover {
            background-color: #007bff;
            color: #fff;
        }
        .fa-user {
            margin-right: 8px;
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
                    <h2>POTS - ESL</h2>
                </a>
            </div>
            <!-- end navbar-header -->
            <!-- navbar-top-links -->
            <ul class="nav navbar-top-links navbar-right">
                <?php 
                include '../connection/db_conn.php';
                if (isset($_SESSION['email_address'])) {
                    $email = $conn->real_escape_string($_SESSION['email_address']);
                    $result = $conn->query("SELECT * FROM login_admin WHERE email_address = '$email'") or die($conn->error);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_array();
                        $userName = htmlentities($row['name']);
                    } else {
                        $userName = "Unknown";
                    }
                } else {
                    $userName = "Guest";
                }
                ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user"></i> Welcome, <?php echo ucwords($userName); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="Logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- end navbar-top-links -->
        </nav>
        <!-- end navbar top -->
    </div>
    <!-- end wrapper -->
</body>
</html>
