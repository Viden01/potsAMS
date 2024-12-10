<?php 
    include '../connection/db_conn.php';

    if (isset($_POST['id'])) {
        $id = $conn->real_escape_string(strip_tags($_POST['id']));

        // First, try to delete the attendance record from the database
        $delete_sql = "DELETE FROM employee_attendance WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            // If the deletion is successful, send a success response
            echo json_encode(["status" => "success", "id" => $id]);
        } else {
            // If deletion fails, return an error response
            echo json_encode(["status" => "error", "message" => "Failed to delete record"]);
        }

        $stmt->close();
        $conn->close();
    }
?>
