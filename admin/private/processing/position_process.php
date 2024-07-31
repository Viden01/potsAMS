<?php
     error_reporting(0);
	 include '../../connection/db_conn.php';

		$emp_position = $conn->real_escape_string(strip_tags($_POST['emp_position']));
		$rate_per_hour = $conn->real_escape_string(strip_tags($_POST['rate_per_hour']));

		$sql = "INSERT INTO `employee_position` (emp_position, rate_per_hour, date_added)  VALUES ('$emp_position', '$rate_per_hour', NOW())";
		if($conn->query($sql)){
				echo '<div class="alert alert-success">
			     <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Insert Successfully!</strong>
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

?>