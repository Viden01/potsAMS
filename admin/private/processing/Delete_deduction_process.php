<?php 
	 include '../../connection/db_conn.php';
	if(isset($_POST['id'])){
		$id = $conn->real_escape_string(strip_tags($_POST['id']));
		$sql = "DELETE FROM `employee_deductions` WHERE id = '$id'"; 
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}
?>