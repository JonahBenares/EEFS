<?php 
	include 'includes/connection.php';
	foreach($_POST as $var=>$value)
		$$var = mysqli_real_escape_string($con, $value);
		mysqli_query($con,"INSERT INTO department (department_name) VALUES ('$department_name')");
?>