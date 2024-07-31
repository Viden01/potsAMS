<?php 
	include '../connection/db_conn.php';
	if(isset($_POST['id'])){
		$id = $conn->real_escape_string(strip_tags($_POST['id']));
		$sql = "SELECT *, employee_cashadvance.id AS caid FROM employee_cashadvance LEFT JOIN employee_records on employee_records.emp_id=employee_cashadvance.employee_id WHERE employee_cashadvance.id='$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}
?>