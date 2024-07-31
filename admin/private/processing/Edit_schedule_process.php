<?php
     error_reporting(0);
	 include '../../connection/db_conn.php';
       if(isset($_POST['id'])){
		$time_in = $conn->real_escape_string(strip_tags($_POST['time_in']));
		$time_out = $conn->real_escape_string(strip_tags($_POST['time_out']));
		$id = $conn->real_escape_string(strip_tags($_POST['id']));

		$sql = "UPDATE `employee_schedule` SET `time_in` = '$time_in', `time_out` = '$time_out' WHERE id = '$id'";
		if($conn->query($sql)){
				echo '<div class="alert alert-success">
			     <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Update Successfully!</strong>
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			</div>';
		}
		else{
	   echo '<div class="alert alert-warning">
	      <strong><i class="fas fa-times"></i>;&nbsp;Update Failed!</strong>
	      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>';
   }	
}
?>