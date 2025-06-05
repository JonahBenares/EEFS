<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar box-shadow">
	<div class="profile-sidebar">
		<img src="upload/necs/flogo.png" class="img-responsive" alt="" style="width:60%;margin-left:18%;margin-bottom: 5%;box-shadow: -1px 2px 10px 1px #999; border-radius: 50%">
		<div style="width: 100%">
			<center>
				<strong>
				<div class="profile-usertitle-name"><?php echo $_SESSION['username'];?></div>
				</strong>
			</center>
		</div>
		<div class="clear"></div>
		<center>
			<div class="profile-usertitle-status" style="margin-top:15px;margin-bottom:0px"><?php echo $_SESSION['fullname'];?></div>
			<a href="#" data-toggle="modal" data-target="#settings"><span class="fa fa-cogs"></span> Change Password</a>
		</center>
	</div>
	<div class="divider"></div>	
	<ul class="nav menu">
		<li><a href="dashboard.php"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
		<li class="parent"><a data-toggle="collapse" href="#sub-item-1">
			<em class="fa fa-navicon">&nbsp;</em> Masterfile <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
			</a>
			<ul class="children collapse" id="sub-item-1">
			<?php if($_SESSION['usertype'] == "Admin") { ?>
				<li>
					<a class="" href="user.php">
						<span class="fa fa-user">&nbsp;</span> User
					</a>
				</li>
			<?php } ?>
				<li>
					<a class="" href="company.php">
						<span class="fa fa-building">&nbsp;</span> Company
					</a>
				</li>
				<li>
					<a class="" href="department.php">
						<span class="fa fa-users">&nbsp;</span> Department
					</a>
				</li>
			</ul>
		</li>
		<li><a href="newrecord_first.php"><em class="fa fa-plus">&nbsp;</em> New Record</a></li>
		<li><a href="viewrecord.php"><em class="fa fa-eye">&nbsp;</em> View Record</a></li>
		<?php if($_SESSION['usertype'] == "Admin") { ?>
			<li><a href="backup_data.php"><em class="fa fa-database">&nbsp;</em> Database</a></li>
		<?php } ?>
		<li class="parent"><a data-toggle="collapse" href="#reports">
			<em class="fa fa-line-chart">&nbsp;</em> Reports <span data-toggle="collapse" href="#reports" class="icon pull-right"><em class="fa fa-plus"></em></span>
			</a>
			<ul class="children collapse" id="reports">
				<li>
					<a class="" href="summary_report.php">
						<span class="fa fa-list">&nbsp;</span> Summary Report
					</a>
				</li>
				<li>
					<a class="" href="summary_annual.php">
						<span class="fa fa-list">&nbsp;</span> Annual Summary
					</a>
				</li>
			</ul>
		</li>
		<li><a href="logout.php"><em class="fa fa-power-off">&nbsp;</em> Logout</a></li>
	</ul>
</div>