<?php 
include('header/head.php');
include('../connection/db_conn.php');

// Fetch the admin record using the ID (assuming the ID is passed via GET)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query to fetch admin data by ID
    $query = $conn->query("SELECT * FROM admin WHERE id = '$id'") or die(mysqli_error($conn));
    $adminData = $query->fetch_assoc();
}

// Handle form submission and update the admin data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $photo = $_POST['photo'];

    // Update query to modify the admin details
    $updateQuery = "UPDATE admin SET username = ?, password = ?, firstname = ?, lastname = ?, photo = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssi", $username, $password, $firstname, $lastname, $photo, $id);

    // Execute the update query
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Admin information updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating admin information!</div>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Admin Information</title>
  <link href="private/assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
  <link href="private/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="private/assets/css/style.css" rel="stylesheet" />
  <link href="private/assets/css/main-style.css" rel="stylesheet" />
</head>
<body>
  <?php include('header/sidebar_menu.php'); ?>

  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Update Admin Information</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <form method="POST" action="">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($adminData['username']) ?>" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" value="<?= htmlspecialchars($adminData['password']) ?>" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" id="firstname" value="<?= htmlspecialchars($adminData['firstname']) ?>" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" id="lastname" value="<?= htmlspecialchars($adminData['lastname']) ?>" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="photo">Photo URL</label>
            <input type="text" name="photo" id="photo" value="<?= htmlspecialchars($adminData['photo']) ?>" class="form-control">
          </div>

          <button type="submit" class="btn btn-primary">Update Admin Information</button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
