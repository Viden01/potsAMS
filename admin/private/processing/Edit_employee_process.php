<?php
     error_reporting(0);
	 include '../../connection/db_conn.php';

		$first_name = $conn->real_escape_string(strip_tags($_POST['first_name']));
		$middle_name = $conn->real_escape_string(strip_tags($_POST['middle_name']));
		$last_name = $conn->real_escape_string(strip_tags($_POST['last_name']));
		$complete_address = $conn->real_escape_string(strip_tags($_POST['complete_address']));
		$birth_date = $conn->real_escape_string(strip_tags($_POST['birth_date']));
		$Mobile_number = $conn->real_escape_string(strip_tags($_POST['Mobile_number']));
		$gender = $conn->real_escape_string(strip_tags($_POST['gender']));
		$position_id = $conn->real_escape_string(strip_tags($_POST['position_id']));
		$marital_status = $conn->real_escape_string(strip_tags($_POST['marital_status']));
		$schedule_id = $conn->real_escape_string(strip_tags($_POST['schedule_id']));

        $image = addslashes(file_get_contents($_FILES['profile_pic']['tmp_name']));
        $image_name = addslashes($_FILES['profile_pic']['name']);
		$image_size = getimagesize($_FILES['profile_pic']['tmp_name']);
		move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "../../../images/" .date("Ymd").time().'_'. $_FILES["profile_pic"]["name"]);
		$fileName = "../../../images/" .date("Ymd").time().'_'. $_FILES["profile_pic"]["name"];

		$emp_id = $conn->real_escape_string(strip_tags($_POST['emp_id']));

		$sql = "UPDATE `employee_records` SET `first_name` = '$first_name', `middle_name` = '$middle_name', `last_name` = '$last_name', `complete_address` = '$complete_address', `birth_date` = '$birth_date', `Mobile_number` = '$Mobile_number', `gender` = '$gender', `position_id` = '$position_id', `marital_status` = '$marital_status', `schedule_id` = '$schedule_id', `profile_pic` = '$fileName' WHERE emp_id = '$emp_id'";
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