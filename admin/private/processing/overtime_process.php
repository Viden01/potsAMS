<?php

	include '../../connection/db_conn.php';
if(isset($_POST['employee_id']) && isset($_POST['overtime_date']) && isset($_POST['overtime_hours']) && isset($_POST['overtime_mins']) && isset($_POST['overtime_rate'])){
		$employee = $conn->real_escape_string(strip_tags($_POST['employee_id']));
		$date = $conn->real_escape_string(strip_tags($_POST['overtime_date']));
		$hours = $conn->real_escape_string(strip_tags($_POST['overtime_hours'])) + ($conn->real_escape_string(strip_tags($_POST['overtime_mins']))/60);
		$rate = $conn->real_escape_string(strip_tags($_POST['overtime_rate']));
        $sql = "SELECT * FROM `employee_records` WHERE employee_id = '$employee'";
		$query = $conn->query($sql);
		if($query->num_rows < 1){
		   echo '<div class="alert alert-danger">
		      <strong><i class="fas fa-times"></i>;&nbsp;Employee not found!</strong>
		      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  </button>
		</div>';
		}
		else{
			$row = $query->fetch_assoc();
			$employee_id = $row['emp_id'];
 
		$sql = "INSERT INTO `employee_overtime` (employee_id, overtime_hours, overtime_mins, overtime_date) VALUES ('$employee_id', '$hours', '$rate', '$date')";
		if($conn->query($sql)){

			echo '<div class="alert alert-success">
			     <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Overtime added successfully</strong>
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			</div>';
		}
		else{
	   echo '<div class="alert alert-warning">
	      <strong><i class="fas fa-times"></i>;&nbsp;Insert Failed!</strong>
	      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>';
  }
 }
}
?>