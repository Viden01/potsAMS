<?php
// Include the database connection
include 'connection/db_conn.php';  // Assuming the connection script you shared is saved as db_connection.php

// Function to get all column names from the admin table
function getTableColumns($conn, $tableName) {
    $columns = [];
    $result = $conn->query("SHOW COLUMNS FROM $tableName");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
    }
    
    return $columns;
}

// Function to retrieve all data from the table
function getAllTableData($conn, $tableName) {
    $data = [];
    $result = $conn->query("SELECT * FROM $tableName");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    return $data;
}

try {
    // Get columns of the admin table
    $columns = getTableColumns($conn, 'login_admin');
    
    // Get all data from the admin table
    $adminData = getAllTableData($conn, 'login_admin');
    
    // Start HTML output
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Admin Table Data</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-bottom: 20px; 
            }
            th, td { 
                border: 1px solid #ddd; 
                padding: 8px; 
                text-align: left; 
            }
            th { 
                background-color: #f2f2f2; 
                font-weight: bold; 
            }
            h1 { color: #333; }
        </style>
    </head>
    <body>
        <h1>Admin Table Data</h1>
        <table>
            <thead>
                <tr>";
    
    // Print table headers
    foreach ($columns as $column) {
        echo "<th>" . htmlspecialchars($column) . "</th>";
    }
    echo "</tr>
            </thead>
            <tbody>";
    
    // Print table rows
    foreach ($adminData as $row) {
        echo "<tr>";
        foreach ($columns as $column) {
            echo "<td>" . htmlspecialchars($row[$column] ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    
    echo "</tbody>
        </table>
    </body>
    </html>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn->close();
?>