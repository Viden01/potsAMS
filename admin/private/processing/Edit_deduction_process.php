<?php
     error_reporting(0);
	 include '../../connection/db_conn.php';

		$deduction_name = $conn->real_escape_string(strip_tags($_POST['deduction_name']));
		$amount = $conn->real_escape_string(strip_tags($_POST['amount']));
		$id = $conn->real_escape_string(strip_tags($_POST['id']));

		$sql = "UPDATE `employee_deductions` SET `deduction_name` = '$deduction_name', `amount` = '$amount' WHERE id = '$id'";
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

?>