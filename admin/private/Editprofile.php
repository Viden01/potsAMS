<?php
// Include the database connection
include('../connection/db_conn.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the POST data
    $id = $_POST['id'];  // Admin ID
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    
    // Handle the photo upload if a new photo is provided
    $photo = $_FILES['photo']['name'];  // New photo uploaded
    $target_dir = "../uploads/";  // Directory where the photo will be uploaded
    $target_file = $target_dir . basename($photo);

    // Check if a new photo is uploaded
    if ($photo) {
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            echo "The file has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        // If no new photo is uploaded, keep the existing photo
        $photo = $_POST['current_photo'];  // Keep the old photo URL if no new file is uploaded
    }

    // Update the admin data in the database
    $updateQuery = "UPDATE admin SET username = ?, password = ?, firstname = ?, lastname = ?, photo = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssi", $username, $password, $firstname, $lastname, $photo, $id);

    // Execute the update query
    if ($stmt->execute()) {
        // Redirect back to profile.php after a successful update
        header("Location: profile.php");
        exit;  // Make sure to exit after the header redirect
    } else {
        echo "<div class='alert alert-danger'>Error updating admin information: " . $stmt->error . "</div>";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
