<?php 
	include('header.php');
	include 'functions/functions.php';
	$userid=$_SESSION['userid'];
?>
	<body>
		<?php include('navbars.php');?>
		<div id="loader">
		  	<figure class="one"></figure>
		  	<figure class="two">loading</figure>
		</div>
		<div id="contents" style="display: none">	
			<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
				<div class="row">
					<ol class="breadcrumb">
						<li><a href="#">
							<em class="fa fa-home"></em>
						</a></li>
						<li class="active">Dashboard</li>
					</ol>
				</div><!--/.row-->
				
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Dashboard</h1>
					</div>
				</div><!--/.row-->
				
				<div class="row">
					<div class="col-md-4">				
						<div class="panel panel-default box-shadow">
							<div class="panel-heading-green" style="height:25px"></div>
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-12 col-md-12 col-lg-12">
										<?php 
											$sql = mysqli_query($con,"SELECT * FROM document_info ORDER BY document_id DESC");
											$row = mysqli_num_rows($sql);
										?>
										<div class="panel panel-teal panel-widget" style="padding:10px 0px">
											<a href = "viewrecord.php" class="large" style="font-size:10vmin"><?php echo $row;?></a>
											<hr class="" >
											<div class="overall-text"> Overall Records</div>		
										</div>
									</div>							
								</div>	
							</div>					
						</div>
					</div>
					<div class="col-md-4">				
						<div class="panel panel-default box-shadow">
							<div class="panel-heading-blue" style="height:25px"></div>
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-12 col-md-12 col-lg-12">
										<?php 
											$sql = mysqli_query($con,"SELECT * FROM document_info WHERE email_attach = '0'");
											$row = mysqli_num_rows($sql);
										?>
										<div class="panel panel-teal panel-widget" style="padding:10px 0px">
											<a href = "viewrecord.php?type=0" class="large" style="font-size:10vmin"><?php echo $row;?></a>
											<hr class="" >
											<div class="overall-text">Encoded</div>		
										</div>
									</div>							
								</div>	
							</div>					
						</div>
					</div>
					<div class="col-md-4">				
						<div class="panel panel-default box-shadow">
							<div class="panel-heading-purple" style="height:25px"></div>
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-12 col-md-12 col-lg-12">
										<?php 
											$sql = mysqli_query($con,"SELECT * FROM document_info WHERE email_attach = '1'");
											$row = mysqli_num_rows($sql);
										?>
										<div class="panel panel-teal panel-widget" style="padding:10px 0px">
											<a href = "viewrecord.php?type=1" class="large" style="font-size:10vmin"><?php echo $row;?></a>
											<hr class="" >
											<div class="overall-text"> Emails</div>		
										</div>
									</div>							
								</div>	
							</div>					
						</div>
					</div>			
				</div>			
			
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default box-shadow">
							<div class="panel-heading" style="border-bottom: 3px solid #099428;">			
								<ul class="pull-right panel-settings panel-button-tab-right">
									<li class="dropdown"><a class="pull-right dropdown-toggle" data-toggle="dropdown" href="#">
										<em class="fa fa-cogs"></em>
									</a>
										<ul class="dropdown-menu dropdown-menu-right">
											<li>
												<ul class="dropdown-settings">
													<li><a href="#">
														<em class="fa fa-cog"></em> Settings 1
													</a></li>
													<li class="divider"></li>
													<li><a href="#">
														<em class="fa fa-cog"></em> Settings 2
													</a></li>
													<li class="divider"></li>
													<li><a href="#">
														<em class="fa fa-cog"></em> Settings 3
													</a></li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
								<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
							</div>					
							<div class="panel-body" style="padding-bottom:50px;">
								<div class="col-sm-12 col-md-6 col-lg-3 border-right">
									<center>
										<h3 style="font-weight:600"><small>RECORD PER</small><br> COMPANY</h3>
									</center>
									<hr class="list-hr-yellow">
									<ul class="todo-list">
										<?php 
											$query1 = mysqli_query($con, "SELECT * FROM company ORDER BY company_name ASC");
											while($fetch = mysqli_fetch_array($query1)){
										?>
										<a href='viewrecord.php?companyid=<?php echo $fetch['company_id']; ?>'>
										<li class="todo-list-item">
											<div class="checkbox ">										
												<label class="dbrd-list" ><?php echo $fetch['company_name'];?></label>
											</div>
											<div class="pull-right action-buttons">
												<span class="badge badge-yellow "><?php echo companyCount($con,$fetch['company_id']); ?></span>
											</div>
										</li></a>
										<?php } ?>
										</li>
									</ul>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-3 border-right">
									<center>
										<h3 style="font-weight:600"><small>RECORD PER</small><br>LOCATION</h3>
									</center>
									<hr class="list-hr-red">
									<ul class="todo-list">
										<?php 
											$query2 = mysqli_query($con, "SELECT * FROM document_location ORDER BY location_name ASC");
											while($fetch1 = mysqli_fetch_array($query2)){
										?>
										<a href='viewrecord.php?locationid=<?php echo $fetch1['location_id']; ?>'>
											<li class="todo-list-item">									
												<div class="checkbox ">										
													<label class="dbrd-list" ><?php echo $fetch1['location_name'];?></label>
												</div>
												<div class="pull-right action-buttons">
													<span class="badge badge-red "><?php echo locationCounter($con,$fetch1['location_id']); ?></span>
												</div>									
											</li>
										</a>
										<?php } ?>
										</li>
									</ul>
								</div>
								<div class="col-sm-12 col-md-3 col-lg-3 border-right">
									<center>
										<h3 style="font-weight:600"><small>RECORD PER</small><br>DEPARTMENT</h3>
									</center>
									<hr class="list-hr-purple">
									<ul class="todo-list">
										<?php 
											$query = mysqli_query($con,"SELECT * FROM department ORDER BY department_name ASC");
											while($row2 = mysqli_fetch_array($query)){
										?>
										<a href='viewrecord.php?deptid=<?php echo $row2['department_id']; ?>'>
											<li class="todo-list-item">
												<div class="checkbox ">										
													<label class="dbrd-list" ><?php echo $row2['department_name'];?></label>
												</div>
												<div class="pull-right action-buttons">
													<span class="badge badge-purple "><?php echo documentCount($con,$row2['department_id']); ?></span>
												</div>
											</li>
										</a>
										<?php } ?>
									</ul>
								</div>
								<div class="col-sm-12 col-md-3 col-lg-3">
									<center>
										<h3 style="font-weight:600"><small>RECORD PER</small><br> DOCUMENT TYPE</h3>
									</center>
									<hr class="list-hr-blue">
									<ul class="todo-list">
										<?php 
											$query1 = mysqli_query($con, "SELECT * FROM document_type ORDER BY type_name ASC");
											while($fetch = mysqli_fetch_array($query1)){
										?>
										<a href='viewrecord.php?typeid=<?php echo $fetch['type_id']; ?>'>
											<li class="todo-list-item">
												<div class="checkbox ">										
													<label class="dbrd-list" ><?php echo $fetch['type_name'];?></label>
												</div>
												<div class="pull-right action-buttons">
													<span class="badge badge-blue "><?php echo typeCount($con,$fetch['type_id']); ?></span>
												</div>
											</li>
										</a>
										<?php } ?>
										</li>
									</ul>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>	<!--/.main-->
		</div>	
	</body>
	<?php include('scripts.php');?>
</html>