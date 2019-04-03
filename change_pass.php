<?php 
include 'includes/connection.php';
foreach($_POST as $var=>$value)
	$$var = mysqli_real_escape_string($con, $value);

	$sql = mysqli_query($con,"SELECT password, initial FROM users where user_id = '$userid'");
	$row = mysqli_fetch_array($sql);
	$ps=$row['password'];
	//echo json_encode($ps);
	
	if($row['initial'] == 1){
		if($old_password != $ps){
			echo 'Old';
		} else {
			if($new_password!=$confirm_password){
				echo 'Not';
			} else {
				$ss = md5($new_password);
				mysqli_query($con,"UPDATE users SET password = '$ss', initial='0' WHERE user_id = $userid");
				echo 'Success';
			}
		}
		
	} else{
		if(md5($old_password) != $ps){
			echo 'Old';
		} else {
			if($new_password != $confirm_password){
				echo 'Not';
			} else {
				$ss = md5($new_password);
				mysqli_query($con,"UPDATE users SET password = '$ss', initial='0' WHERE user_id = $userid");
				echo 'Success';
			}
		}
	}
	/*if(($old_password == $ps || md5($old_password) == $row['password']) && $new_password==$confirm_password){
		echo json_encode('Success');
	}*/


/*	if($new_password!=$confirm_password){
		echo json_encode("Not Match");
	} else {
		echo json_encode("Match");
		/*if ($old_password == $row['password']){
			$ss = md5($new_password);
			mysqli_query($con,"UPDATE users SET password = '$ss' WHERE user_id = $userid");
			echo json_encode('Success');
		}
		elseif (md5($old_password) == $row['password']) {

			$ss = md5($new_password);
			mysqli_query($con,"UPDATE users SET password = '$ss' WHERE user_id = $userid");
			echo json_encode('Success');
		}
		else{
			echo json_encode('old');
		}*/
	//}



?>