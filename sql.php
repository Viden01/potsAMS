<?php
// Include the database connection
require_once 'connection/db_conn.php'; // Assuming your connection script is named db_connection.php

// SQL to add new columns to the admin table
$columns_to_add = [
    'token' => 'ALTER TABLE admin ADD COLUMN token VARCHAR(255) NULL',
    'reset_token_at' => 'ALTER TABLE admin ADD COLUMN reset_token_at DATETIME NULL',
    'code' => 'ALTER TABLE admin ADD COLUMN code VARCHAR(50) NULL'
];

// Track successful and failed column additions
$successful_columns = [];
$failed_columns = [];

// Add each column
foreach ($columns_to_add as $column_name => $sql) {
    // Check if column already exists to prevent duplicate column errors
    $check_column_query = "SHOW COLUMNS FROM admin LIKE '$column_name'";
    $result = $conn->query($check_column_query);
    
    if ($result->num_rows == 0) {
        // Column does not exist, so try to add it
        if ($conn->query($sql) === TRUE) {
            $successful_columns[] = $column_name;
        } else {
            $failed_columns[] = $column_name;
        }
    } else {
        // Column already exists
        $successful_columns[] = $column_name;
    }
}

// Output results
if (empty($failed_columns)) {
    if (count($successful_columns) > 0) {
        echo "Successfully added/verified columns: " . implode(', ', $successful_columns);
    } else {
        echo "All specified columns already exist in the admin table.";
    }
} else {
    echo "Failed to add columns: " . implode(', ', $failed_columns);
    echo "\nError details: " . $conn->error;
}

// Close the connection
$conn->close();
?>