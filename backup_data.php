<?php 
	include('header.php');
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
					<li class="active">Database</li>
				</ol>
			</div>
			
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Database</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default box-shadow">
						<div class="panel-heading">
							Database List 
							<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
						</div>
						<div class="panel-body">
							<div class="canvas-wrapper">
								<table class="table  table-hover table-bordered" id="tbl_department" style="width:100%">
									<thead class="th-header">
										<th>Database Name</th>
										<th>Action</th>
									</thead>
									<?php 
										$dir = opendir ("./Back-up/db_backup"); 
										while (false !== ($file = readdir($dir))) { 
											if (strpos($file,'.sql',1)) { 
												$filenameboth = str_replace('.sql', '', $file);
									?>
										<tr>
											<?php if($_SESSION['usertype'] == "Admin") { ?>
												<td><?php echo $filenameboth.".sql" ?></td>
												<td>
													<a href='Back-up/db_backup/<?php echo $filenameboth.".sql"?>' class='btn btn-info btn-xs'><span class = 'fa fa-download'></span> Download SQL</a>
												</td>
											<?php } else { ?>
												<td><span style = "color:#b7b1b1fa;"><?php echo $filenameboth.".sql" ?></span></td>
												<td>
													<a class='btn btn-info btn-xs' disabled ><span class = 'fa fa-download'> </span>Download SQL</a> 
												</td>
											<?php } ?>
										</tr>
									<?php } } ?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div><!--/.row-->
		</div>
	</div>
</body>
<?php include('scripts.php');?>
</html>