<?php include('header.php');?>
	<body>
		<script type="text/javascript">
		    function updateComp(company_id){
		      window.open('update_company.php?id='+company_id, '_blank', 'width=600,height=260');
		    }
		</script>

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
						<li class="active">Company</li>
					</ol>
				</div>
				
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Company</h1>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default box-shadow">
							<div class="panel-heading">
								Company List 
								<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
								<a class="pull-right  btn-primary panel-toggle" style="background:#30a5ff;color:white" data-toggle="modal" data-target="#mdl_company"><em class="fa fa-plus"></em></a>						
							</div>
							<div class="panel-body">
								<div class="canvas-wrapper">
									<table class="table  table-hover table-bordered" id="tbl_company" style="width:100%">
										<thead class="th-header">
											<th>Company Name</th>
											<th>Action</th>
										</thead>
										<?php 
											$sql = mysqli_query($con,"SELECT * FROM company ORDER BY company_id DESC");
											while($row = mysqli_fetch_array($sql)){
										?>
										<tr>
											<td><?php echo $row['company_name'];?></td>
											<td>
												<button name="button" class="btn btn-info btn-xs" value="update" onClick="updateComp(<?php echo $row['company_id']; ?>)"><span class="fa fa-pencil-square-o"></span></button>
												<a onclick="confirmationDelete(this);return false;" href='delete_comp.php?id=<?php echo $row['company_id']; ?>' class="btn btn-xs btn-danger  btn-fill edf" title='Delete Company' alt='Delete Company'><span class="fa fa-times"></span></a>
											</td>
										</tr>
										<?php } ?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div><!--/.row-->	
			</div>
		</div>
	</body>
	<script>
		function confirmationDelete(anchor){
	        var conf = confirm('Are you sure you want to delete this record?');
	        if(conf)
	        window.location=anchor.attr("href");
	    }
	</script>
	<?php include('scripts.php');?>
</html>