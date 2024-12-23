<?php
// Include the database connection file
include('connection.php');

// SQL query to add the location column
$sql = "ALTER TABLE `employee_attendance` ADD COLUMN `location` VARCHAR(255) NULL";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Column 'location' added successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
