<?php
// Include the database connection
require_once 'db_connection.php';

try {
    // First, check if the column already exists to prevent errors
    $check_column_query = "SHOW COLUMNS FROM admin LIKE 'email'";
    $result = $conn->query($check_column_query);
    
    // If the column doesn't exist, add it
    if ($result->num_rows == 0) {
        $add_column_query = "ALTER TABLE admin ADD COLUMN email VARCHAR(255)";
        if ($conn->query($add_column_query) === TRUE) {
            echo "Email column added successfully. ";
        } else {
            throw new Exception("Error adding email column: " . $conn->error);
        }
    }

    // Update the email for row with ID 1
    $update_email_query = "UPDATE admin SET email = 'bsit.2s.maru.julius@gmail.com' WHERE id = 1";
    
    if ($conn->query($update_email_query) === TRUE) {
        echo "Email updated successfully for admin with ID 1.";
    } else {
        throw new Exception("Error updating email: " . $conn->error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn->close();
?>