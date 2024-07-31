<?php 
	include '../connection/db_conn.php';
	if(isset($_POST['emp_id'])){
		$id = $conn->real_escape_string(strip_tags($_POST['emp_id']));
		$sql = "SELECT * FROM employee_records WHERE emp_id = '$id'"; 

		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}
?>