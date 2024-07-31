<?php 
	include '../connection/db_conn.php';
	if(isset($_POST['id'])){
		$id = $conn->real_escape_string(strip_tags($_POST['id']));
		$sql = "SELECT *, employee_overtime.id AS id FROM employee_overtime LEFT JOIN employee_records on employee_records.emp_id=employee_overtime.employee_id WHERE employee_overtime.id='$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}
?>