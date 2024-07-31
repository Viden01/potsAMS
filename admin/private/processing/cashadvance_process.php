<?php
   error_reporting(0);
	 include '../../connection/db_conn.php';

		$employee = $conn->real_escape_string(strip_tags($_POST['employee_id']));
		$amount = $conn->real_escape_string(strip_tags($_POST['amount']));
		
		$sql = "SELECT * FROM employee_records WHERE employee_id = '$employee'";
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
			$sql = "INSERT INTO employee_cashadvance (employee_id, amount, date_created) VALUES ('$employee_id', '$amount', NOW())";
			if($conn->query($sql)){
			echo '<div class="alert alert-success">
			     <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Cash Advance added successfully</strong>
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
	

?>