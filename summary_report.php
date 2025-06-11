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
					<li class="active">Summary Report </li>
				</ol>
			</div>
			
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Summary Report</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default box-shadow">
						<div class="panel- bg-light p-3 rounded-top" style="padding: 20px 20px; border-bottom: 1px solid #ccc;">
                            <form class="form-row row gx-3 gy-2 align-items-end">
                                <!-- Only visible by default -->
                                <div class="col-sm-2">
                                    <label for="filter_type" class="form-label">Filter Type</label>
                                    <select id="filter_type" class="form-control" style="height:34px!important">
                                        <option value="">Select</option>
                                        <option value="annual">Annual</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>

                                <!-- Rest of the form, initially hidden -->
                                <div id="filter_fields" style="display:none;" class="row w-100 mt-2">
                                    <!-- Annual Inputs -->
                                    <!-- <div class="col-sm-2 annual-group">
                                        <label for="month" class="form-label">Month</label>
                                        <select id="month" class="form-control" style="height:34px!important">
                                            <option value="">Select Month</option>
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div> -->
                                    <div class="col-sm-3 annual-group">
                                        <label for="year_from" class="form-label">Year From</label>
                                        <input type="number" id="year_from" min="2014" max="2099" step="1" placeholder="YYYY" class="form-control" style="height:34px!important" />
                                    </div>
                                    <div class="col-sm-3 annual-group">
                                        <label for="year_to" class="form-label">Year To</label>
                                        <input type="number" id="year_from" min="2014" max="2099" step="1" placeholder="YYYY" class="form-control" style="height:34px!important" />
                                    </div>

                                    <!-- Custom Range Inputs -->
                                    <div class="col-sm-3 custom-group">
                                        <label for="custom_date_from" class="form-label">Date From</label>
                                        <input type="date" id="custom_date_from" class="form-control" style="height:34px!important" />
                                    </div>
                                    <div class="col-sm-3 custom-group">
                                        <label for="custom_date_to" class="form-label">Date To</label>
                                        <input type="date" id="custom_date_to" class="form-control" style="height:34px!important" />
                                    </div>

                                    <!-- Document Type -->
                                    <div class="col-sm-2">
                                        <label for="doc_type" class="form-label">Document Type</label>
                                        <select id="doc_type" class="form-control" style="height:34px!important">
                                            <option value="">Select type</option>
                                            <option value="report">Report</option>
                                            <option value="invoice">Invoice</option>
                                            <option value="memo">Memo</option>
                                        </select>
                                    </div>

                                    <!-- Department -->
                                    <div class="col-sm-4">
                                        <label for="department" class="form-label">Department</label>
                                        <select id="month" class="form-control" style="height:34px!important">
                                            <option value="">IT</option>
                                            <option value="">Admin</option>
                                            <option value="">HR</option>
                                            <option value="">Accoubnting</option>
                                        </select>
                                    </div>

                                    <!-- Filter Button -->
                                    <div class="col-sm-1 d-flex align-items-end">
                                        <label for="department" class="form-label" style="color:white">button</label>
                                        <button type="button" id="filterBtn" class="btn btn-primary w-100" style="height: 34px;width:100%">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <div id="filteredCard" class="card mt-3 shadow-sm" style="margin:10px 15px 0px 15px; display: none !important;">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="d-flex flex-wrap align-items-center mt-3 gap-2">
                                            <strong class="me-2">Filtered By:</strong>

                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center">
                                                Blue
                                            </span>
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center">
                                                Large size
                                            </span>
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center">
                                                Boots
                                            </span>
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center">
                                                Casual
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
                                                <th>Document Date</th>
                                                <th>Company</th>
                                                <th>Location</th>
                                                <th>Department</th>
                                                <th>Document Type</th>
                                                <th>Subject</th>
                                                <th>Action</th>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterType = document.getElementById('filter_type');
    const filterFields = document.getElementById('filter_fields');
    const annualFields = document.querySelectorAll('.annual-group');
    const customFields = document.querySelectorAll('.custom-group');

    function toggleFields() {
        if (filterType.value === 'annual') {
            filterFields.style.display = 'flex';
            annualFields.forEach(el => el.style.display = 'block');
            customFields.forEach(el => el.style.display = 'none');
        } else if (filterType.value === 'custom') {
            filterFields.style.display = 'flex';
            annualFields.forEach(el => el.style.display = 'none');
            customFields.forEach(el => el.style.display = 'block');
        } else {
            filterFields.style.display = 'none';
        }
    }

    toggleFields(); // initial state

    filterType.addEventListener('change', toggleFields);
});

document.addEventListener("DOMContentLoaded", function () {
    const filterBtn = document.getElementById('filterBtn');
    const filteredCard = document.getElementById('filteredCard');

    filterBtn.addEventListener('click', function () {
        // Show the filtered card
        filteredCard.style.display = 'block';
    });
});
</script>
</html>