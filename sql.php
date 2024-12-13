<?php
// Database connection
$conn = new mysqli('localhost', 'u510162695_potsesl', '1Potsesl', 'u510162695_potsesl');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to add a new column 'photo_path' to 'employee_attendance' table
$sql = "ALTER TABLE employee_attendance ADD COLUMN photo_path VARCHAR(255)";

// Execute the query and check if it was successful
if ($conn->query($sql) === TRUE) {
    echo "Column 'photo_path' added successfully.";
} else {
    echo "Error adding column: " . $conn->error;
}

// Close the connection
$conn->close();
?>
