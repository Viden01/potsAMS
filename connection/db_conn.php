<?php
	$conn = new mysqli('localhost', 'u510162695_potsesl', '1Potsesl', 'u510162695_potsesl');
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
?>