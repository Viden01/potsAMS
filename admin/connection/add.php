<?php
// Include the database connection file
include('db_conn.php');

// SQL query to drop the 'location' column and add 'latitude' and 'longitude' columns
$sql = "
    ALTER TABLE `employee_attendance`
    DROP COLUMN `location`,
    ADD COLUMN `latitude` VARCHAR(255) NULL,
    ADD COLUMN `longitude` VARCHAR(255) NULL
";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Columns 'location' dropped and 'latitude' & 'longitude' added successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
