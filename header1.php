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
	<link href="upload/necs/folder_icon.png" rel="icon">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href='css/dataTables.bootstrap.min.css' rel='stylesheet'>
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="css/fonts.css" rel="stylesheet">
	<link href="css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="css/loader.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.js"></script>   
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<style type="text/css">
	.width-class{
		width: 100%;
	}
	.body-background{
		background-color: #444444;
	}
	.box-shadow{
		box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	}
	.h2-class{
	    text-transform: uppercase;
	    font-weight: 500;
	    margin:0px;	   
	    color: #fff; 
	}
	.sidebar ul.nav a:hover, .sidebar ul.nav li.parent ul li a:hover {
    text-decoration: none;
    background-color: #0c9c2a;
    color: #fff;
	}
	.th-header{
		background: #099428;
		color: #fff;
	}
	.modal-header{
		background: #099428;
	}
	.modal-title{
		color: #fff;
	}
</style>