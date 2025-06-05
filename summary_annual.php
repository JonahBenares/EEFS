<?php 
	include('header.php');
	include 'functions/functions.php';
	$usertype=$_SESSION['usertype'];
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
					<li class="active">Annual Summary Report </li>
				</ol>
			</div>
			
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Annual Summary Report</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default box-shadow">
						<div class="panel- bg-light p-3 rounded-top" style="padding: 20px 20px; border-bottom: 1px solid #ccc;">
                            <form class="form-row row gx-3 gy-2 align-items-end">
                                <!-- Date From -->
                                <div class="col-sm-2">
                                    <label for="date_from" class="form-label">Date From</label>
                                    <input type="date" id="date_from" class="form-control" style="height:34px!important" />
                                </div>

                                <!-- Date To -->
                                <div class="col-sm-2">
                                    <label for="date_to" class="form-label">Date To</label>
                                    <input type="date" id="date_to" class="form-control" style="height:34px!important" />
                                </div>

                                <!-- Document Type -->
                                <div class="col-sm-5">
                                    <label for="doc_type" class="form-label">Document Type</label>
                                    <select id="doc_type" class="form-control" style="height:34px!important">
                                        <option value="">Select type</option>
                                        <option value="report">Report</option>
                                        <option value="invoice">Invoice</option>
                                        <option value="memo">Memo</option>
                                        <!-- Add more as needed -->
                                    </select>
                                </div>

                                <!-- Year -->
                                <div class="col-sm-2">
                                    <label for="doc_year" class="form-label">Year</label>
                                    <input type="number" id="doc_year" class="form-control" placeholder="e.g. 2025" style="height:34px!important" />
                                </div>

                                <!-- Filter Button -->
                                <div class="col-sm-1 d-flex align-items-end">
                                    <label for="doc_type" class="form-label" style="margin:10px"></label>
                                    <button type="submit" class="btn btn-primary w-100" style="height: 34px;width:100%">Filter</button>
                                </div>
                            </form>
                            <div class="card mt-3 shadow-sm" style="margin:10px 15px 0px 15px">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="d-flex flex-wrap align-items-center mt-3 gap-2">
                                            <strong class="me-2">Filtered By:</strong>

                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center">
                                                Blue
                                                <button type="button" class="btn-close btn-close-dark ms-2" aria-label="Remove"></button>
                                            </span>

                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center">
                                                Large size
                                                <button type="button" class="btn-close btn-close-dark ms-2" aria-label="Remove"></button>
                                            </span>

                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center">
                                                Boots
                                                <button type="button" class="btn-close btn-close-dark ms-2" aria-label="Remove"></button>
                                            </span>

                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center">
                                                Casual
                                                <button type="button" class="btn-close btn-close-dark ms-2" aria-label="Remove"></button>
                                            </span>
                                            <a href="#" class="ms-auto text-decoration-underline text-white">Clear all</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="panel-body">
							<div class="canvas-wrapper">
								<div id="tabl_rec" class="city">
									<table class="table table-hover table-bordered" id="tbl_record" style="width:100%">
                                        <thead class="th-header">
                                            <tr>
                                                <th style="display:none"></th>
                                                <th>Year</th>
                                                <th>Month</th>
                                                <th>Department</th>
                                                <th>No. of Docs Uploaded</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic rows will be inserted here via JavaScript -->
                                        </tbody>
                                    </table>
								</div>
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