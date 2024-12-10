<?php 
	include '../connection/db_conn.php';
	if(isset($_POST['id'])){
		$id = $conn->real_escape_string(strip_tags($_POST['id']));
		$sql = "SELECT *, employee_attendance.id as id FROM employee_attendance LEFT JOIN employee_records ON employee_records.emp_id=employee_attendance.employee_id WHERE employee_attendance.id = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}
?>