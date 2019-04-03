<?php
include'includes/connection.php';
	$con->query("DELETE FROM `department` WHERE `department_id` = '$_GET[id]'") or die(mysqli_error());
	echo "<script type='text/javascript'>alert('Record Successfully Deleted!');</script>";
	echo "<script>document.location='department.php'</script>";
?>