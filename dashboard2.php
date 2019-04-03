<?php 
	include('header.php');
	include 'functions/functions.php';
	$userid=$_SESSION['userid'];
?>
<style type="text/css">
	.fa-xxl {
  		font-size: 7em; 
  	}
  	.badge-success {
	    color: #fff;
	    background-color: #28a745;
	}
	.badge-purple {
	    color: #fff;
	    background-color: #cc30ff;
	}
	.badge-blue {
	    color: #fff;
	    background-color: #30a5ff;
	}
	.badge-yellow {
	    color: #fff;
	    background-color: #ffb53e;
	}
	.badge-red {
	    color: #fff;
	    background-color: #f9243f;
	}
	.badge-xl{
		font-size: 16px;
		padding:5px 8px 5px 8px;
	}
	.todo-list-item:hover{
		background: #99c1a2bd;
		color:white;
	}	
	.color-green {
	    color: #099428;
	}
	.list-hr-purple{
		border-bottom:3px solid #cc30ff;
		width: 70%;

	}
	.list-hr-red{
		border-bottom:3px solid #f9243f;
		width: 70%;

	}
	.list-hr-green{
		border-bottom:3px solid green;
		width: 70%;
	}
	.list-hr-yellow{
		border-bottom:3px solid #ffb53e;
		width: 70%;
	}
	.list-hr-blue{
		border-bottom:3px solid #30a5ff;
		width: 70%;

	}
	.dbrd-list{
		color: black;
	}
	.overall-text {
	    margin-top: -6px;
	    text-transform: uppercase;
	    font-weight: bold;
	    font-size: 1.5em;
	    color: #c5c7cc;
	}
	.panel-default .panel-heading-green {
	    background: #099428;
	    border-bottom: 1px solid #e9ecf2;
	    color: #444444;
	}
	.panel-default .panel-heading-blue {
	    background: #30a5ff;
	    border-bottom: 1px solid #e9ecf2;
	    color: #444444;
	}
	.panel-default .panel-heading-red {
	    background: #f9243f;
	    border-bottom: 1px solid #e9ecf2;
	    color: #444444;
	}
	.panel-default .panel-heading-yellow {
	    background: #ffb53e;
	    border-bottom: 1px solid #e9ecf2;
	    color: #444444;
	}
	.panel-default .panel-heading-purple {
	    background: #cc30ff;
	    border-bottom: 1px solid #e9ecf2;
	    color: #444444;
	}

</style>
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
				<li class="active">Dashboard</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Dashboard</h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-md-6">				
				<div class="panel panel-default box-shadow">
					<div class="panel-heading-green" style="height:25px"></div>
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12 col-md-12 col-lg-12">
								<?php 
									$sql = mysqli_query($con,"SELECT * FROM document_info ORDER BY document_id DESC");
									$row = mysqli_num_rows($sql);
								?>
								<div class="panel panel-teal panel-widget" style="padding:40px 0px">
									<div class="large" style="font-size:13vmin"><?php echo $row;?></div>
									<hr class="" >
									<div class="overall-text"> Overall Records</div>		
								</div>
							</div>
						</div>	
					</div>					
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default box-shadow">
					<div class="panel-heading-yellow" style="height:27px"></div>
					<div class="panel-body easypiechart-panel" style="padding:20px">
						<h1><strong><?php echo companyCounter($con); ?></strong></h1>
						<div class="text-muted">Company</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default box-shadow">
					<div class="panel-heading-red" style="height:27px"></div>
					<div class="panel-body easypiechart-panel" style="padding:20px">
						<h1><strong><?php echo locationCount($con); ?></strong></h1>
						<div class="text-muted">Document Location</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default box-shadow">
					<div class="panel-heading-purple" style="height:27px"></div>
					<div class="panel-body easypiechart-panel" style="padding:20px">
						<h1><strong><?php echo departmentCount($con); ?></strong></h1>
						<div class="text-muted">Number of Department</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default box-shadow">
					<div class="panel-heading-blue" style="height:27px"></div>
					<div class="panel-body easypiechart-panel" style="padding:20px">
						<h1><strong><?php echo typeCounter($con); ?></strong></h1>
						<div class="text-muted">Number of Document Types</div>
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
		
		<!-- <div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Site Traffic Overview
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
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="main-chart" id="line-chart" height="200" width="600"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div> --><!--/.row-->
		
		<!-- <div class="row">
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>New Orders</h4>
						<div class="easypiechart" id="easypiechart-blue" data-percent="92" ><span class="percent">92%</span></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Comments</h4>
						<div class="easypiechart" id="easypiechart-orange" data-percent="65" ><span class="percent">65%</span></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>New Users</h4>
						<div class="easypiechart" id="easypiechart-teal" data-percent="56" ><span class="percent">56%</span></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Visitors</h4>
						<div class="easypiechart" id="easypiechart-red" data-percent="27" ><span class="percent">27%</span></div>
					</div>
				</div>
			</div>
		</div> --><!--/.row-->
		
		<!-- <div class="row">
			<div class="col-md-6">
				<div class="panel panel-default chat">
					<div class="panel-heading">
						Chat
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
					<div class="panel-body">
						<ul>
							<li class="left clearfix"><span class="chat-img pull-left">
								<img src="http://placehold.it/60/30a5ff/fff" alt="User Avatar" class="img-circle" />
								</span>
								<div class="chat-body clearfix">
									<div class="header"><strong class="primary-font">John Doe</strong> <small class="text-muted">32 mins ago</small></div>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ante turpis, rutrum ut ullamcorper sed, dapibus ac nunc.</p>
								</div>
							</li>
							<li class="right clearfix"><span class="chat-img pull-right">
								<img src="http://placehold.it/60/dde0e6/5f6468" alt="User Avatar" class="img-circle" />
								</span>
								<div class="chat-body clearfix">
									<div class="header"><strong class="pull-left primary-font">Jane Doe</strong> <small class="text-muted">6 mins ago</small></div>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ante turpis, rutrum ut ullamcorper sed, dapibus ac nunc.</p>
								</div>
							</li>
							<li class="left clearfix"><span class="chat-img pull-left">
								<img src="http://placehold.it/60/30a5ff/fff" alt="User Avatar" class="img-circle" />
								</span>
								<div class="chat-body clearfix">
									<div class="header"><strong class="primary-font">John Doe</strong> <small class="text-muted">32 mins ago</small></div>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ante turpis, rutrum ut ullamcorper sed, dapibus ac nunc.</p>
								</div>
							</li>
						</ul>
					</div>
					<div class="panel-footer">
						<div class="input-group">
							<input id="btn-input" type="text" class="form-control input-md" placeholder="Type your message here..." /><span class="input-group-btn">
								<button class="btn btn-primary btn-md" id="btn-chat">Send</button>
						</span></div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						To-do List
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
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<ul class="todo-list">
							<li class="todo-list-item">
								<div class="checkbox">
									<input type="checkbox" id="checkbox-1" />
									<label for="checkbox-1">Make coffee</label>
								</div>
								<div class="pull-right action-buttons"><a href="#" class="trash">
									<em class="fa fa-trash"></em>
								</a></div>
							</li>
							<li class="todo-list-item">
								<div class="checkbox">
									<input type="checkbox" id="checkbox-2" />
									<label for="checkbox-2">Check emails</label>
								</div>
								<div class="pull-right action-buttons"><a href="#" class="trash">
									<em class="fa fa-trash"></em>
								</a></div>
							</li>
							<li class="todo-list-item">
								<div class="checkbox">
									<input type="checkbox" id="checkbox-3" />
									<label for="checkbox-3">Reply to Jane</label>
								</div>
								<div class="pull-right action-buttons"><a href="#" class="trash">
									<em class="fa fa-trash"></em>
								</a></div>
							</li>
							<li class="todo-list-item">
								<div class="checkbox">
									<input type="checkbox" id="checkbox-4" />
									<label for="checkbox-4">Make more coffee</label>
								</div>
								<div class="pull-right action-buttons"><a href="#" class="trash">
									<em class="fa fa-trash"></em>
								</a></div>
							</li>
							<li class="todo-list-item">
								<div class="checkbox">
									<input type="checkbox" id="checkbox-5" />
									<label for="checkbox-5">Work on the new design</label>
								</div>
								<div class="pull-right action-buttons"><a href="#" class="trash">
									<em class="fa fa-trash"></em>
								</a></div>
							</li>
							<li class="todo-list-item">
								<div class="checkbox">
									<input type="checkbox" id="checkbox-6" />
									<label for="checkbox-6">Get feedback on design</label>
								</div>
								<div class="pull-right action-buttons"><a href="#" class="trash">
									<em class="fa fa-trash"></em>
								</a></div>
							</li>
						</ul>
					</div>
					<div class="panel-footer">
						<div class="input-group">
							<input id="btn-input" type="text" class="form-control input-md" placeholder="Add new task" /><span class="input-group-btn">
								<button class="btn btn-primary btn-md" id="btn-todo">Add</button>
						</span></div>
					</div>
				</div>
			</div>
			
			
			<div class="col-md-6">
				<div class="panel panel-default ">
					<div class="panel-heading">
						Timeline
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
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body timeline-container">
						<ul class="timeline">
							<li>
								<div class="timeline-badge"><em class="glyphicon glyphicon-pushpin"></em></div>
								<div class="timeline-panel">
									<div class="timeline-heading">
										<h4 class="timeline-title">Lorem ipsum dolor sit amet</h4>
									</div>
									<div class="timeline-body">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer at sodales nisl. Donec malesuada orci ornare risus finibus feugiat.</p>
									</div>
								</div>
							</li>
							<li>
								<div class="timeline-badge primary"><em class="glyphicon glyphicon-link"></em></div>
								<div class="timeline-panel">
									<div class="timeline-heading">
										<h4 class="timeline-title">Lorem ipsum dolor sit amet</h4>
									</div>
									<div class="timeline-body">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
									</div>
								</div>
							</li>
							<li>
								<div class="timeline-badge"><em class="glyphicon glyphicon-camera"></em></div>
								<div class="timeline-panel">
									<div class="timeline-heading">
										<h4 class="timeline-title">Lorem ipsum dolor sit amet</h4>
									</div>
									<div class="timeline-body">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer at sodales nisl. Donec malesuada orci ornare risus finibus feugiat.</p>
									</div>
								</div>
							</li>
							<li>
								<div class="timeline-badge"><em class="glyphicon glyphicon-paperclip"></em></div>
								<div class="timeline-panel">
									<div class="timeline-heading">
										<h4 class="timeline-title">Lorem ipsum dolor sit amet</h4>
									</div>
									<div class="timeline-body">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<p class="back-link">Lumino Theme by <a href="https://www.medialoot.com">Medialoot</a></p>
			</div>
		</div> -->
	</div>	<!--/.main-->
	
</body>
<?php include('scripts.php');?>
</html>