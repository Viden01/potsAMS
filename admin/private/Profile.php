<?php 
include('header/head.php');
include('../connection/db_conn.php');

// Fetch all admin data
$query = $conn->query("SELECT * FROM admin") or die(mysqli_error($conn));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission and update the admin data
    foreach ($_POST['id'] as $index => $id) {
        $username = $_POST['username'][$index];
        $password = $_POST['password'][$index];
        $firstname = $_POST['firstname'][$index];
        $lastname = $_POST['lastname'][$index];
        $photo = $_POST['photo'][$index];

        // Update query
        $updateQuery = "UPDATE admin SET username = ?, password = ?, firstname = ?, lastname = ?, photo = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssssi", $username, $password, $firstname, $lastname, $photo, $id);

        // Execute the update
        $stmt->execute();
    }

    // Display a success message
    echo "<div class='alert alert-success'>Admin data updated successfully!</div>";
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Data</title>
  <link href="private/assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
  <link href="private/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="private/assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
  <link href="private/assets/css/style.css" rel="stylesheet" />
  <link href="private/assets/css/main-style.css" rel="stylesheet" />
</head>
<body>
  <?php include('header/sidebar_menu.php'); ?>

  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Admin Data</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">

      <?php
// Include the database connection
include('../connection/db_conn.php');

// Fetch the current admin data
$query = $conn->query("SELECT * FROM admin WHERE id = 1"); // Assuming you are editing the admin with ID 1
$row = $query->fetch_assoc();
?>

<form method="POST" action="Editprofile.php" enctype="multipart/form-data">
    <div class="form-group">
        <label for="id">ID</label>
        <input type="text" name="id" id="id" value="<?= $row['id'] ?>" class="form-control" readonly>
    </div>
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?= $row['username'] ?>" class="form-control">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?= $row['password'] ?>" class="form-control">
    </div>
    <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" name="firstname" id="firstname" value="<?= $row['firstname'] ?>" class="form-control">
    </div>
    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" id="lastname" value="<?= $row['lastname'] ?>" class="form-control">
    </div>
    <div class="form-group">
        <label for="photo">Photo</label>
        <input type="file" name="photo" id="photo" class="form-control">
        <small>Current photo: <?= $row['photo'] ?></small>
    </div>
    <button type="submit" class="btn btn-primary">Update Admin Data</button>
</form>

      </div>
    </div>
  </div>

</body>
</html>
