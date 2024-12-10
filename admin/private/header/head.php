<?php
session_start();
if (!isset($_SESSION["email_address"])) {
    header("location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POTS - ESL</title>
    <!-- Core CSS -->
    <link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/main-style.css" rel="stylesheet">
    <!-- Additional CSS -->
    <link href="assets/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <style>
        .navbar-fixed-top {
            background-color: #007bff;
            color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            color: #fff;
            font-size: 1.5rem;
        }
        .navbar-brand:hover {
            color: #e9ecef;
        }
        .navbar-toggle {
            border-color: #fff;
        }
        .navbar-toggle .icon-bar {
            background-color: #fff;
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
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Navbar Top -->
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar">
            <div class="container-fluid">
                <!-- Navbar Header -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">
                        POTS - ESL
                    </a>
                </div>
                <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                        include '../connection/db_conn.php';
                        $userName = "Guest";
                        if (isset($_SESSION['email_address'])) {
                            $email = $conn->real_escape_string($_SESSION['email_address']);
                            $result = $conn->query("SELECT * FROM login_admin WHERE email_address = '$email'");
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $userName = htmlentities($row['name']);
                            }
                        }
                        ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-user"></i> Welcome, <?php echo ucwords($userName); ?> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="Logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <!-- Include JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
</body>
</html>
