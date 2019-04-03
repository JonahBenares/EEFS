<?php 
	include 'includes/connection.php';
    session_start();
    if(empty($_SESSION)) echo "<script>alert('You are not logged in. Please login to continue.'); window.location='index.php';</script>";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Energreen Electronic Filing System</title>
	<link href="upload/necs/flogo.png" rel="icon">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href='css/dataTables.bootstrap.min.css' rel='stylesheet'>
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="css/fonts.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<!-- <link href="css/jquery.dataTables.min.css" rel="stylesheet"> -->
</head>