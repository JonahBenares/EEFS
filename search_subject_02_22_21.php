<?php
include 'includes/connection.php';
	foreach($_POST as $var=>$value)
   	$$var = mysqli_real_escape_string($con,$value);
	$sql = mysqli_query($con,"SELECT * FROM document_info WHERE subject = '$subject'");
	$num_rows = mysqli_num_rows($sql);
	if($num_rows==0){
		echo 'available';
	}else{
		echo 'existing';
	}
?>