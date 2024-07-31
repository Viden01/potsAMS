<?php
     error_reporting(0);
	 include '../../connection/db_conn.php';

		$time_in = $conn->real_escape_string(strip_tags($_POST['time_in']));
		$time_out = $conn->real_escape_string(strip_tags($_POST['time_out']));

		$sql = "INSERT INTO `employee_schedule` (time_in, time_out, date_added)  VALUES ('$time_in', '$time_out', NOW())";
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