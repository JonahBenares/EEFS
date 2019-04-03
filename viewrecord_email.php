<?php 
	include('header.php');
	include 'functions/functions.php';
	$usertype=$_SESSION['usertype'];
	$userid=$_SESSION['userid'];
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
				<li class="active">View Records / Email</li>
			</ol>
		</div>
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">View Record - Via Email</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default box-shadow">
					<div class="panel-heading">
						Record List <small>(Email)</small>
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
						<a class="pull-right  btn-success btn-fill panel-toggle" style="background:#099428;color:white" data-toggle="modal" data-target="#mdl_searchRecord_email"><em class="fa fa-search"></em></a>
						<a class="pull-right btn-warning panel-toggle" style="background:#ffb53e;color:white" id="btn_email"  href="viewrecord.php"><em class="fa fa-archive"></em></a>
						<a class="pull-right btn-primary panel-toggle" style="background:#30a5ff;color:white" href="newrecord_first.php"><em class="fa fa-plus"></em></a>					
					</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<div id="tabl_email" class="city">
								<table  class="table  table-hover table-bordered" id="tbl_email" style="width:100%">
									<thead class="th-header">
										<th style='display:none'>Doc ID</th>
										<th>Logged Date</th>
										<th>Company</th>
										<th>Department</th>
										<th>Subject</th>
										<th>Sender</th>
										<th>Number of Attachment</th>
										<th>Action</th>
									</thead>
								<tbody>
								<?php
									if(isset($_POST['search_email'])){
										echo "Filters Applied: ".filtersApplied($con, $_POST) . " <a href='$_SERVER[PHP_SELF]' style='color:red; font-size:11px'>Remove filter</a><br><br>";
										$docid=filteredSQL($con,$_POST);

										foreach($docid AS $id){
											$sql = mysqli_query($con,"SELECT * FROM document_info WHERE document_id = '$id' AND email_attach = '1' ORDER BY document_id DESC");
											while($row = mysqli_fetch_array($sql)){ ?>
												<tr>
												<td style='display:none'><?php echo $row['document_id']; ?></td>
												<td><?php echo $row['logged_date']; ?></td>
												<td><?php echo getInfo($con, 'company_name', 'company', 'company_id',  $row['company_id']);?></td>
												<td><?php echo getInfo($con, 'department_name', 'department', 'department_id',  $row['department_id']);?></td>
												<td><?php if($row['confidential'] == 'Yes') echo "<a class='btn btn-danger btn-xs'><span class='fa fa-lock'></span></a> ";  echo $row['subject']; ?></td>
												<td><?php echo $row['email_sender']; ?></td>
												<td><?php echo count_attachment($con,$row['document_id']); ?></td>
												<td>
											<?php if($usertype=='Staff') {
												if($row['confidential'] == 'No') { ?>
												<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-xs" ><span class="fa fa-pencil"></span></a>
												<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-xs" ><span class="fa fa-eye"></span></a>
											<?php 
												} 
											} else if($usertype=='Manager'){
												if($row['user_id'] == $userid || $row['confidential'] == 'No') { ?>
												<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-sm" ><span class="fa fa-pencil"></span></a>
												<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-sm" ><span class="fa fa-eye"></span></a>
												<?php }
											} else if($usertype=='Admin') { ?>
											<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-xs" ><span class="fa fa-pencil"></span></a>
												<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-xs" ><span class="fa fa-eye"></span></a>
											<?php } ?>
													</td>
											</tr>
											<?php }
										}
									} else {
									
								$sql = mysqli_query($con,"SELECT * FROM document_info WHERE email_attach = '1' ORDER BY document_id DESC");
									while($row = mysqli_fetch_array($sql)){
								?>	
									<tr>
										<td style='display:none'><?php echo $row['document_id']; ?></td>
										<td><?php echo $row['logged_date']; ?></td>
										<td><?php echo getInfo($con, 'company_name', 'company', 'company_id',  $row['company_id']);?></td>
										<td><?php echo getInfo($con, 'department_name', 'department', 'department_id',  $row['department_id']);?></td>
										<td><?php if($row['confidential'] == 'Yes') echo "<a class='btn btn-danger btn-xs'><span class='fa fa-lock'></span></a> ";  echo $row['subject']; ?></td>
										<td><?php echo $row['email_sender']; ?></td>
										<td><?php echo count_attachment($con,$row['document_id']); ?></td>
										<td>
											<?php if($usertype=='Staff') {
												if($row['confidential'] == 'No') { ?>
												<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-xs" ><span class="fa fa-pencil"></span></a>
												<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-xs" ><span class="fa fa-eye"></span></a>
											<?php 
												} 
											} else if($usertype=='Manager'){
												if($row['user_id'] == $userid || $row['confidential'] == 'No') { ?>
												<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-sm" ><span class="fa fa-pencil"></span></a>
												<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-sm" ><span class="fa fa-eye"></span></a>
												<?php }
											} else if($usertype=='Admin') { ?>
											<a href='newrecord.php?docid=<?php echo $row['document_id']; ?>' class="btn btn-info btn-xs" ><span class="fa fa-pencil"></span></a>
												<a href='view_details.php?id=<?php echo $row['document_id']; ?>' target='_blank' class="btn btn-warning btn-xs" ><span class="fa fa-eye"></span></a>
											<?php } ?>
											</td>
									</tr>
								<?php } 
									
								} ?>
								</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
	</div>
</body>
<?php include('scripts.php');?>

</html>