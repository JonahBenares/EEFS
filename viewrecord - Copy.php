<?php 
	include('header.php');
	include 'functions/functions.php';
	$usertype=$_SESSION['usertype'];

?>
<div class="modal fade " tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loader">
    <div class="modal-dialog" role="document">
    	<div style="margin-top:200px">
	    	<center>
	        	<div class="loader"></div>
	        </center>
        </div>
    </div>
</div>
<body>

	<?php include('navbars.php');?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">View Records</li>
			</ol>
		</div>
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">View Record</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default box-shadow">
					<div class="panel-heading">
						Record List 
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
						<a class="pull-right  btn-success btn-fill panel-toggle" style="background:#099428;color:white" data-toggle="modal" data-target="#mdl_searchRecord"><em class="fa fa-search"></em></a>
						<a class="pull-right  btn-primary panel-toggle" style="background:#30a5ff;color:white" href="newrecord_first.php"><em class="fa fa-plus"></em></a>					
					</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							
							<table class="table  table-hover table-bordered" id="tbl_record" style="width:100%">
								<thead class="th-header">
									<th style='display:none'></th>
									<th>Document Date</th>
									<th>Location</th>
									<th>Department</th>
									<th>Document Type</th>
									<th>Subject</th>									
									<th>Action</th>
								</thead>
									<?php
									if(isset($_POST['search_doc'])){
									echo "Filters Applied: ".filtersApplied($con, $_POST) . " <a href='$_SERVER[PHP_SELF]' style='color:red; font-size:11px'>Remove filter</a><br><br>";
									$docid=filteredSQL($con,$_POST);
									foreach($docid AS $id){
										$sql = mysqli_query($con,"SELECT * FROM document_info WHERE document_id = '$id' ORDER BY document_id DESC");
										while($row = mysqli_fetch_array($sql)){
									?>
									<tr>
										<td style='display:none'><?php echo $row['document_id'];?></td>
										<td><?php echo $row['document_date'];?></td>
										<td><?php echo getInfo($con, 'location_name', 'document_location', 'location_id',  $row['location_id']);?></td>
										<td><?php echo getInfo($con, 'department_name', 'department', 'department_id',  $row['department_id']);?></td>
										<td><?php echo getInfo($con, 'type_name', 'document_type', 'type_id',  $row['type_id']);?></td>
										<td><?php if($row['confidential'] == 'Yes') echo "<a class='btn btn-danger btn-xs'><span class='fa fa-lock'></span></a> ";  echo $row['subject'];?></td>
										
										<td>
										<?php if($usertype=='Staff') {
											if($row['confidential'] == 'No') { ?>
											<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-xs" ><span class="fa fa-pencil"></span></a>
											<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-xs" ><span class="fa fa-eye"></span></a>
										<?php 
											} 
										} else if($usertype=='Admin') { ?>
										<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-xs" ><span class="fa fa-pencil"></span></a>
											<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-xs" ><span class="fa fa-eye"></span></a>
										<?php } ?>
										</td>
									</tr>	
									<?php
										}
									}
								} else if(!empty($_GET)) {
									$sql = "SELECT * FROM document_info WHERE ";
									if(isset($_GET['deptid'])){
										$deptid=$_GET['deptid'];
										$sql.=" department_id = '$deptid'";
										echo "Showing all documents under <span style='color:green; font-weight:bold'>".getInfo($con, 'department_name', 'department', 'department_id', $deptid)." Department</span>. <a href='$_SERVER[PHP_SELF]' style='color:red; font-size:11px'>Remove filter</a><br><br>";
									} 
									if(isset($_GET['typeid'])){
										$typeid=$_GET['typeid'];
										$sql.=" type_id = '$typeid'";
										echo "Showing all <span style='color:green; font-weight:bold'>". getInfo($con, 'type_name', 'document_type', 'type_id', $typeid)."</span>. <a href='$_SERVER[PHP_SELF]' style='color:red; font-size:11px'>Remove filter</a><br><br>";
									}
									if(isset($_GET['locationid'])){
										$locationid=$_GET['locationid'];
										$sql.=" location_id = '$locationid'";
										echo "Showing all documents in <span style='color:green; font-weight:bold'>". getInfo($con, 'location_name', 'document_location', 'location_id', $locationid)."</span>. <a href='$_SERVER[PHP_SELF]' style='color:red; font-size:11px'>Remove filter</a><br><br>";
									}
									if(isset($_GET['companyid'])){
										$companyid=$_GET['companyid'];
										$sql.=" location_id = '$companyid'";
										echo "Showing all documents in <span style='color:green; font-weight:bold'>". getInfo($con, 'company_name', 'company', 'company_id', $companyid)."</span>. <a href='$_SERVER[PHP_SELF]' style='color:red; font-size:11px'>Remove filter</a><br><br>";
									}
									
									$sql = mysqli_query($con,$sql);
									while($row = mysqli_fetch_array($sql)){
									?>
								<tr>
									<td><?php echo $row['document_date'];?></td>
									<td><?php echo getInfo($con, 'location_name', 'document_location', 'location_id',  $row['location_id']);?></td>
									<td><?php echo getInfo($con, 'department_name', 'department', 'department_id',  $row['department_id']);?></td>
									<td><?php echo getInfo($con, 'type_name', 'document_type', 'type_id',  $row['type_id']);?></td>
									<td><?php if($row['confidential'] == 'Yes') echo "<a class='btn btn-danger btn-xs'><span class='fa fa-lock'></span></a> ";  echo $row['subject'];?></td>
									
									<td>
									<?php if($usertype=='Staff') {
											if($row['confidential'] == 'No') { ?>
										<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-xs" ><span class="fa fa-pencil"></span></a>
										<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-xs" ><span class="fa fa-eye"></span></a>
									<?php 
											} 
										} else if($usertype=='Admin') { ?>
										<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-xs" ><span class="fa fa-pencil"></span></a>
										<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-xs" ><span class="fa fa-eye"></span></a>
										<?php } ?>
									</td>
								</tr>	
								<?php } 

								} else {
	
									$sql = mysqli_query($con,"SELECT * FROM document_info ORDER BY document_id DESC");
									while($row = mysqli_fetch_array($sql)){
									?>
									<tr>
										<td><?php echo $row['document_date'];?></td>
										<td><?php echo getInfo($con, 'location_name', 'document_location', 'location_id',  $row['location_id']);?></td>
										<td><?php echo getInfo($con, 'department_name', 'department', 'department_id',  $row['department_id']);?></td>
										<td><?php echo getInfo($con, 'type_name', 'document_type', 'type_id',  $row['type_id']);?></td>
										<td><?php if($row['confidential'] == 'Yes') echo "<a class='btn btn-danger btn-xs'><span class='fa fa-lock'></span></a> ";  echo $row['subject'];?></td>
										
										<td>
										<?php if($usertype=='Staff') {
											if($row['confidential'] == 'No') { ?>
											<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-sm" ><span class="fa fa-pencil"></span></a>
											<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-sm" ><span class="fa fa-eye"></span></a>
										<?php 
											} 
										} else if($usertype=='Admin') { ?>
										<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-sm" ><span class="fa fa-pencil"></span></a>
											<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-sm" ><span class="fa fa-eye"></span></a>
										<?php } ?>
										</td>
									</tr>
								<?php } }?>			
							</table>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		
		<!-- <div class="row">
			<div class="col-xs-6 col-md-3 col-lg-6">
				<div class="panel panel-default box-shadow">
					<div class="panel-body easypiechart-panel">
						<h4>Active</h4>
						<div class="easypiechart" id="easypiechart-blue" data-percent="50"><span class="percent">50%</span></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3 col-lg-6">
				<div class="panel panel-default box-shadow">
					<div class="panel-body easypiechart-panel">
						<h4>In-active</h4>
						<div class="easypiechart" id="easypiechart-orange" data-percent="65" ><span class="percent">65%</span></div>
					</div>
				</div>
			</div>
		</div> -->
	</div>
</body>
<?php include('scripts.php');?>
</html>