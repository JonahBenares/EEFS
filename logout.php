<?php
session_start();
?>
<head>
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"><\/script>')</script>
	<link href="css/logout.css" rel="stylesheet">
</head>
<body class="body-background">
<div class="loader-body">
	<center>
		<div id="loader">
		  	<figure class="one"></figure>
		  	<figure class="two" >Logging out..</figure>
		</div>
	</center>
	
	<?php
		session_destroy();
	 	echo '<meta http-equiv="refresh" content="2;url=index.php">';
	?>
</div>


