<?php 
	include('header.php');
	include'functions/functions.php';
	if(isset($_POST['updateuser'])){
        updateUser($con,$_POST);
    }
?>
	<script type="text/javascript">
	    function updateUser(user_id){
	      window.open('update_user.php?id='+user_id, '_blank', 'top=100%,left=360%,width=600,height=400');
	    }
	</script>
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
						<li class="active">User</li>
					</ol>
				</div>
				
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Users</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default box-shadow">
							<div class="panel-heading">
								User List 
								<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
								<a class="pull-right  btn-primary panel-toggle" style="background:#30a5ff;color:white" data-toggle="modal" data-target="#myModal"><em class="fa fa-plus"></em></a>						
							</div>
							<div class="panel-body">
								<div class="canvas-wrapper">
									<table class="table  table-hover table-bordered" id="tbl_user" style="width:100%">
										<thead class="th-header">
											<th>Fullname</th>
											<th>Username</th> 
											<th>Usertype</th>
											<th>Status</th>
											<th>Action</th>
										</thead>
										<tbody>
											<?php 
		                                        $sql = mysqli_query($con,"SELECT * FROM users ORDER BY user_id DESC");
		                                        while($row = mysqli_fetch_array($sql)){
		                                    ?> 
											<tr>
												<td width="50%"><?php echo $row['fullname'];?></td>
												<td width="25%"><?php echo $row['username'];?></td> 
												<td width="20%"><?php echo getInfo($con, 'usertype_name', 'usertype', 'usertype_id',  $row['usertype_id']);?></td>
												<td width="4%">
													<?php if($row['status'] == 'Active') { ?>
		                                                <span class="label label-success">Active</span></td>
		                                            <?php } else { ?>
		                                                <span class="label label-danger">Inactive</span></td>
		                                            <?php } ?>
												</td>
												<td width="1%">
													<button name="button" class="btn btn-info btn-xs" value="update" onClick="updateUser(<?php echo $row['user_id']; ?>)"><span class="fa fa-pencil-square-o"></span></button>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div><!--/.row-->
				<?php 
					$sql1 = mysqli_query($con,"SELECT * FROM users ORDER BY status DESC");
					$count = mysqli_num_rows($sql1);
				?>
				<div class="row">
					<div class="col-xs-6 col-md-3 col-lg-6">
						<div class="panel panel-teal panel-widget panel-default box-shadow">
							<div>
								<?php 
									$query = $con->query("SELECT * FROM users WHERE status = 'Active'");
									$row = mysqli_fetch_array($query);
									$num = $query->num_rows;
								?>
								<div class="row no-padding">
									<em class="fa fa-xl fa-users color-blue"></em>
									<h1 style="font-size:10vmin"><?php echo $num?></h1>
									<div class="text-muted">Active User/s</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-6 col-md-3 col-lg-6">
						<div class="panel panel-teal panel-widget panel-default box-shadow">
							<div>
								<?php 
									$query1 = $con->query("SELECT * FROM users WHERE status = 'Inactive'");
									$rows = $query1->num_rows;
								?>
								<div class="row no-padding">
									<em class="fa fa-xl fa-users color-red"></em>
									<h1 style="font-size:10vmin"><?php echo $rows ?></h1>
									<div class="text-muted">Inactive User/s</div>
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