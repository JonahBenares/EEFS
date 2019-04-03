<?php 
include 'includes/connection.php';
foreach($_POST as $var=>$value)
	$$var = mysqli_real_escape_string($con, $value);
	$query=mysqli_query($con,"SELECT * from users where username = '$username'");
	$row = mysqli_fetch_array($query);
	$num_rows = mysqli_num_rows($query);
	if ($num_rows<>0){
		echo "Invalid";
	}
	else {
		if($status == 'Active'){
			mysqli_query($con,"INSERT INTO users (fullname, username, password, usertype_id, initial, status) VALUES ('$fullname','$username','1234','$usertype','1','Active')");
			echo "Success";
		}
		else if($status == 'Inactive') {
			mysqli_query($con,"INSERT INTO users (fullname, username, password, usertype_id, initial, status) VALUES ('$fullname','$username','1234','$usertype','0','Inactive')");
			echo "Success";
		}
	}