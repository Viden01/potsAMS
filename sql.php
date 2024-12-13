<?php
// Include the database connection
 // Adjust the path if needed

// Function to get all tables in the database
function getDatabaseTables($conn) {
    $tables = [];
    $result = $conn->query("SHOW TABLES");
    
    if ($result) {
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
    }
    
    return $tables;
}

// Function to get columns and their types for a table
function getTableColumnsWithTypes($conn, $tableName) {
    $columns = [];
    $result = $conn->query("SHOW COLUMNS FROM $tableName");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $columns[] = [
                'Field' => $row['Field'],
                'Type' => $row['Type']
            ];
        }
    }
    
    return $columns;
}

// Function to retrieve all data from a table
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
    // Get all tables in the database
    $tables = getDatabaseTables($conn);

    // Start HTML output
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Database Tables and Data</title>
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
            h1, h2 { color: #333; }
        </style>
    </head>
    <body>
        <h1>Database Tables and Data</h1>";

    // Iterate over each table
    foreach ($tables as $table) {
        echo "<h2>Table: " . htmlspecialchars($table) . "</h2>";

        // Get columns and types for the current table
        $columns = getTableColumnsWithTypes($conn, $table);

        // Display columns and their types
        echo "<table>
                <thead>
                    <tr>
                        <th>Column Name</th>
                        <th>Data Type</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($columns as $column) {
            echo "<tr>
                    <td>" . htmlspecialchars($column['Field']) . "</td>
                    <td>" . htmlspecialchars($column['Type']) . "</td>
                  </tr>";
        }
        echo "</tbody>
            </table>";

        // Get all data from the current table
        $tableData = getAllTableData($conn, $table);

        // Display table data if exists
        if (!empty($tableData)) {
            echo "<table>
                    <thead>
                        <tr>";
            foreach ($columns as $column) {
                echo "<th>" . htmlspecialchars($column['Field']) . "</th>";
            }
            echo "</tr>
                    </thead>
                    <tbody>";
            foreach ($tableData as $row) {
                echo "<tr>";
                foreach ($columns as $column) {
                    echo "<td>" . htmlspecialchars($row[$column['Field']] ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody>
                </table>";
        } else {
            echo "<p>No data found in this table.</p>";
        }
    }

    echo "</body>
    </html>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn->close();
?>
