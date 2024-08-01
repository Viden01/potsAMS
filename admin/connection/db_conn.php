<?php
	$conn = new mysqli('127.0.0.1', 'u510162695_potsesl', '1Potsesl', 'u510162695_potsesl');
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
?>