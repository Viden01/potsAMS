<?php 

session_start();

include '../connection/db_conn.php';
if(isset($_POST['email_address']) && isset($_POST['user_password'])){

  date_default_timezone_set("Europe/London");
  $date = date("M-d-Y h:i A",strtotime("+0 HOURS"));

 $username = mysqli_real_escape_string($conn, $_POST["email_address"]);  
 $password = mysqli_real_escape_string($conn, $_POST["user_password"]);

 $query = $conn->query("SELECT * FROM  login_admin WHERE email_address = '$username'")or die(mysqli_error($conn));
		$row = $query->fetch_array();
           $id = htmlentities($row['id']);
           $user = htmlentities($row['email_address']);

           $_SESSION["user_no"] = $row["id"];
		   $_SESSION["email_address"] = $row["email_address"];
    
           $counter = mysqli_num_rows($query);
            
		  	if ($counter == 0) 
			  {	
				  echo '<div class="alert alert-danger">
		             <strong>Invalid Email Address and Password</strong>
		             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		            <span aria-hidden="true">&times;</span>
		          </button>
		             <script> setTimeout(function() {  window.location.href = "index.php" }, 1000); </script>
		        </div>';
			  } 

			  else
			  {
			  if(password_verify($password, $row["user_password"]))  
                 {
				  $_SESSION['email_address']=$id;	
			
                        if (!empty($_SERVER["HTTP_CLIENT_IP"]))
							{
							 $ip = $_SERVER["HTTP_CLIENT_IP"];
							}
							elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
							{
							 $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
							}
							else
							{
							 $ip = $_SERVER["REMOTE_ADDR"];
							}

							$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);


                           $remarks="Has LoggedIn the system at";  
                      
                          $conn->query("INSERT INTO history_log(id,email_address,action,ip,host,login_time) VALUES('$id','$user','$remarks','$ip','$host','$date')")or die(mysqli_error($conn));

                 echo '<div class="alert alert-success">
		             <strong>Login Successfully!</strong>
		             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		            <span aria-hidden="true">&times;</span>
		          </button>
		             <script> setTimeout(function() {  window.location.href = "private/dashboard.php" }, 1000); </script>
		        </div>';
		 }
	  }
   }
?>

