<?php 
	session_start();
    if(!empty($_SESSION)) echo "<script>window.location='dashboard.php';</script>";
?>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Energreen Electronic File System</title>
	<link href="upload/necs/flogo.png" rel="icon">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="css/fonts.css" rel="stylesheet">
</head>
<style type="text/css">

	.width-class{
		width: 100%;
		background:#009e2a;border: 1px solid #008323
	}
	.body-background{
		background: #222222;
	}
	.box-shadow{
		border-radius: 20px;
		box-shadow:9px 14px 20px 7px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	}
	.h2-class{
	    text-transform: uppercase;
	    font-weight: 500;
	    margin:0px;	   
	    color: #fff;
	    text-shadow: 2px 2px 4px #0c0c0c; 
	}
	.panel-heading-back{
		background:#009e2a!important;
	}
</style>
<body class="body-background">
	<div class="container-fluid">
		<br>
		<br>
		<center>
			<h1 class="h2-class"><span style="color:#009e2a">ENERGREEN</span> ELECTRONIC</h1>
			<h1 class="h2-class">FILING SYSTEM</h1>
		</center>
		<br>
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
				<div class="login-panel panel panel-default box-shadow">
					<div class="panel-heading panel-heading-back ">
						<center><h3 style="color:#fff;"><em class="fa fa-lg fa-lock"></em> Login</h3></center>
					</div>
					<div class="panel-body">
						<form role="form" method = "POST" action = "login.php">
							<fieldset>
								<div class="form-group">
									<input class="form-control" placeholder="Username" name="username" type="text" autofocus="">
								</div>
								<div class="form-group">
									<input class="form-control" placeholder="Password" name="password" type="password" value="">
								</div>	
								<input type="submit" name = "login" id = "login" class="btn btn-primary width-class" value="Login">					
							</fieldset>
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
	</div>
</body>
<?php include('scripts.php');?>
</html>