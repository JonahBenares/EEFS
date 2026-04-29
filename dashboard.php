<?php 
	include('header.php');
	include 'functions/functions.php';
	$userid=$_SESSION['userid'];

	/* =========================
	   PRELOAD COUNTS (OPTIMIZED)
	========================= */

	// Overall
	$res_total = mysqli_query($con,"SELECT COUNT(*) as total FROM document_info");
	$total_records = mysqli_fetch_assoc($res_total)['total'];

	// Encoded
	$res_encoded = mysqli_query($con,"SELECT COUNT(*) as total FROM document_info WHERE email_attach = '0'");
	$total_encoded = mysqli_fetch_assoc($res_encoded)['total'];

	// Emails
	$res_email = mysqli_query($con,"SELECT COUNT(*) as total FROM document_info WHERE email_attach = '1'");
	$total_email = mysqli_fetch_assoc($res_email)['total'];

	// Company Counts
	$companyCounts = [];
	$res = mysqli_query($con, "SELECT company_id, COUNT(*) as total FROM document_info GROUP BY company_id");
	while($r = mysqli_fetch_assoc($res)){
		$companyCounts[$r['company_id']] = $r['total'];
	}

	// Location Counts
	$locationCounts = [];
	$res = mysqli_query($con, "SELECT location_id, COUNT(*) as total FROM document_info GROUP BY location_id");
	while($r = mysqli_fetch_assoc($res)){
		$locationCounts[$r['location_id']] = $r['total'];
	}

	// Department Counts
	$deptCounts = [];
	$res = mysqli_query($con, "SELECT department_id, COUNT(*) as total FROM document_info GROUP BY department_id");
	while($r = mysqli_fetch_assoc($res)){
		$deptCounts[$r['department_id']] = $r['total'];
	}

	// Type Counts
	$typeCounts = [];
	$res = mysqli_query($con, "SELECT type_id, COUNT(*) as total FROM document_info GROUP BY type_id");
	while($r = mysqli_fetch_assoc($res)){
		$typeCounts[$r['type_id']] = $r['total'];
	}
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
				<li><a href="#"><em class="fa fa-home"></em></a></li>
				<li class="active">Dashboard</li>
			</ol>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Dashboard</h1>
			</div>
		</div>

		<div class="row">

			<!-- OVERALL -->
			<div class="col-md-4">				
				<div class="panel panel-default box-shadow">
					<div class="panel-heading-green" style="height:25px"></div>
					<div class="panel-body">
						<div class="panel panel-teal panel-widget" style="padding:10px 0px">
							<a href="viewrecord.php" class="large" style="font-size:10vmin"><?php echo $total_records;?></a>
							<hr>
							<div class="overall-text"> Overall Records</div>		
						</div>
					</div>					
				</div>
			</div>

			<!-- ENCODED -->
			<div class="col-md-4">				
				<div class="panel panel-default box-shadow">
					<div class="panel-heading-blue" style="height:25px"></div>
					<div class="panel-body">
						<div class="panel panel-teal panel-widget" style="padding:10px 0px">
							<a href="viewrecord.php?type=0" class="large" style="font-size:10vmin"><?php echo $total_encoded;?></a>
							<hr>
							<div class="overall-text">Encoded</div>		
						</div>
					</div>					
				</div>
			</div>

			<!-- EMAIL -->
			<div class="col-md-4">				
				<div class="panel panel-default box-shadow">
					<div class="panel-heading-purple" style="height:25px"></div>
					<div class="panel-body">
						<div class="panel panel-teal panel-widget" style="padding:10px 0px">
							<a href="viewrecord.php?type=1" class="large" style="font-size:10vmin"><?php echo $total_email;?></a>
							<hr>
							<div class="overall-text"> Emails</div>		
						</div>
					</div>					
				</div>
			</div>

		</div>			

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default box-shadow">
					<div class="panel-heading" style="border-bottom: 3px solid #099428;"></div>					
					
					<div class="panel-body" style="padding-bottom:50px;">

						<!-- COMPANY -->
						<div class="col-sm-12 col-md-6 col-lg-3 border-right">
							<center><h3><small>RECORD PER</small><br> COMPANY</h3></center>
							<hr class="list-hr-yellow">
							<ul class="todo-list">
								<?php 
								$query1 = mysqli_query($con, "SELECT * FROM company ORDER BY company_name ASC");
								while($fetch = mysqli_fetch_assoc($query1)){
								?>
								<a href='viewrecord.php?companyid=<?php echo $fetch['company_id']; ?>'>
									<li class="todo-list-item">
										<label class="dbrd-list"><?php echo $fetch['company_name'];?></label>
										<div class="pull-right">
											<span class="badge badge-yellow">
												<?php echo isset($companyCounts[$fetch['company_id']]) ? $companyCounts[$fetch['company_id']] : 0; ?>
											</span>
										</div>
									</li>
								</a>
								<?php } ?>
							</ul>
						</div>

						<!-- LOCATION -->
						<div class="col-sm-12 col-md-6 col-lg-3 border-right">
							<center><h3><small>RECORD PER</small><br> LOCATION</h3></center>
							<hr class="list-hr-red">
							<ul class="todo-list">
								<?php 
								$query2 = mysqli_query($con, "SELECT * FROM document_location ORDER BY location_name ASC");
								while($fetch1 = mysqli_fetch_assoc($query2)){
								?>
								<a href='viewrecord.php?locationid=<?php echo $fetch1['location_id']; ?>'>
									<li class="todo-list-item">
										<label class="dbrd-list"><?php echo $fetch1['location_name'];?></label>
										<div class="pull-right">
											<span class="badge badge-red">
												<?php echo isset($locationCounts[$fetch1['location_id']]) ? $locationCounts[$fetch1['location_id']] : 0; ?>
											</span>
										</div>
									</li>
								</a>
								<?php } ?>
							</ul>
						</div>

						<!-- DEPARTMENT -->
						<div class="col-sm-12 col-md-3 col-lg-3 border-right">
							<center><h3><small>RECORD PER</small><br> DEPARTMENT</h3></center>
							<hr class="list-hr-purple">
							<ul class="todo-list">
								<?php 
								$query = mysqli_query($con,"SELECT * FROM department ORDER BY department_name ASC");
								while($row2 = mysqli_fetch_assoc($query)){
								?>
								<a href='viewrecord.php?deptid=<?php echo $row2['department_id']; ?>'>
									<li class="todo-list-item">
										<label class="dbrd-list"><?php echo $row2['department_name'];?></label>
										<div class="pull-right">
											<span class="badge badge-purple">
												<?php echo isset($deptCounts[$row2['department_id']]) ? $deptCounts[$row2['department_id']] : 0; ?>
											</span>
										</div>
									</li>
								</a>
								<?php } ?>
							</ul>
						</div>

						<!-- TYPE -->
						<div class="col-sm-12 col-md-3 col-lg-3">
							<center><h3><small>RECORD PER</small><br> DOCUMENT TYPE</h3></center>
							<hr class="list-hr-blue">
							<ul class="todo-list">
								<?php 
								$query1 = mysqli_query($con, "SELECT * FROM document_type ORDER BY type_name ASC");
								while($fetch = mysqli_fetch_assoc($query1)){
								?>
								<a href='viewrecord.php?typeid=<?php echo $fetch['type_id']; ?>'>
									<li class="todo-list-item">
										<label class="dbrd-list"><?php echo $fetch['type_name'];?></label>
										<div class="pull-right">
											<span class="badge badge-blue">
												<?php echo isset($typeCounts[$fetch['type_id']]) ? $typeCounts[$fetch['type_id']] : 0; ?>
											</span>
										</div>
									</li>
								</a>
								<?php } ?>
							</ul>
						</div>

					</div>
				</div>
			</div>
		</div>

	</div>
</div>

</body>
<?php include('scripts.php');?>
</html>