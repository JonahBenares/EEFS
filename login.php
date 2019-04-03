<?php 
include 'includes/connection.php';
session_start();
if(isset($_POST['login'])){
    foreach($_POST as $var=>$value)
        $$var = mysqli_real_escape_string($con, $value);
    $ss = md5($password);
    $get=$con->query("SELECT * FROM users WHERE status = 'Active' AND username = '$username' AND (password='$password' OR password = '$ss')");
    $rows = $get->num_rows;
    $fetch=$get->fetch_array();
    if($rows>0){
        $_SESSION['userid'] = $fetch['user_id'];
        $_SESSION['username'] = $fetch['username'];
        $_SESSION['fullname'] = $fetch['fullname'];
        if($fetch['usertype_id'] == 1) $_SESSION['usertype'] = 'Admin';
        if($fetch['usertype_id'] == 2) $_SESSION['usertype'] = 'Manager';
        if($fetch['usertype_id'] == 3) $_SESSION['usertype'] = 'Staff';
        echo "<script>window.location = 'dashboard.php';</script>";
    } 

    else {
        echo "<script>alert('Username/Password incorrect Or Inactive'); window.location = 'index.php';</script>";
    }
}
?>