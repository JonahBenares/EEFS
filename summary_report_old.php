<?php 
	include('header.php');
	include 'functions/functions.php';
	$usertype=$_SESSION['usertype'];
	$userid=$_SESSION['userid'];

    $year_from = isset($_POST['year_from']) ? $_POST['year_from'] : '';
    $year_to = isset($_POST['year_to']) ? $_POST['year_to'] : '';
    $document_type = isset($_POST['document_type']) ? $_POST['document_type'] : '';
    $department = isset($_POST['department']) ? $_POST['department'] : '';
    $years = getYears($con);

    function getYears($con) {
        $years = array();
        $res = mysqli_query($con, "SELECT DISTINCT YEAR(document_date) as year FROM document_info ORDER BY year DESC");
        while ($r = mysqli_fetch_assoc($res)) {
            $years[] = $r['year'];
        }
        return $years;
    }

    function getOptions($con, $table, $id_col, $name_col, $selected_val) {
        $options = "";
        $query = mysqli_query($con, "SELECT * FROM $table ORDER BY $name_col ASC");
        while ($row = mysqli_fetch_assoc($query)) {
            $selected = ($selected_val == $row[$id_col]) ? 'selected' : '';
            $options .= "<option value='" . $row[$id_col] . "' " . $selected . ">" . $row[$name_col] . "</option>";
        }
        return $options;
    }

    function getDepartmentName($con, $id) {
        $q = mysqli_query($con, "SELECT department_name FROM department WHERE department_id = '$id' LIMIT 1");
        if ($r = mysqli_fetch_assoc($q)) return $r['department_name'];
        return 'N/A';
    }

    function getDocumentTypeName($con, $id) {
        $q = mysqli_query($con, "SELECT type_name FROM document_type WHERE type_id = '$id' LIMIT 1");
        if ($r = mysqli_fetch_assoc($q)) return $r['type_name'];
        return 'N/A';
    }

    function showAppliedFilters($con, $year_from, $year_to, $document_type, $department) {
        $filters = array();

        $filters[] = "Year From: " . ($year_from != '' ? $year_from : "All");
        $filters[] = "Year To: " . ($year_to != '' ? $year_to : "All");
        $filters[] = "Department: " . (!empty($department) ? getDepartmentName($con, $department) : "All");
        $filters[] = "Document Type: " . (!empty($document_type) ? getDocumentTypeName($con, $document_type) : "All");

        $filterBadges = implode('', array_map(function($filter) {
            return "<span class='badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center' style='margin-right:5px'>"
                . htmlspecialchars($filter) . "</span>";
        }, $filters));

        $exportForm = '';
        if (!empty($_SESSION['export_data'])) {
            $exportForm = "
                <form action='export_summary_excel.php' method='post' style='display:inline-block; margin-left:10px;'>
                    <input type='hidden' name='export_excel' value='1'>
                    <button type='' class='btn btn-success btn-sm'>Export to Excel</button>
                </form>";
        }

        return "<br>
            <div id='filteredCard' class='card mt-3 shadow-sm' style='margin:10px 15px 0px 15px;'>
                <div class='card-body'>
                    <div class='row'>
                        <div class='d-flex flex-wrap align-items-center mt-3 gap-2 w-100'>
                            <strong class='me-2'>Filtered By:</strong>
                            $filterBadges
                            <a href='summary_annual.php' class='ms-auto text-decoration-underline text-white'>Clear Filter</a>
                            $exportForm
                        </div>
                    </div>
                </div>
            </div>";
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
                                    <label for="filter_type" class="form-label" style="height:34px!important">Filter Type</label>
                                    <select id="filter_type" class="form-control">
                                        <option value="">Select</option>
                                        <option value="annual">Annual</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>

                                <!-- Rest of the form, initially hidden -->
                                <div id="filter_fields" style="display:none;" class="row w-100 mt-2">
                                    <div class="col-sm-3 annual-group">
                                        <label for="year_from" class="form-label" style="height:34px!important">Year From</label>
                                        <select name="year_from" class="form-control">
                                            <option value="">All</option>
                                            <?php
                                            foreach ($years as $y) {
                                                $sel = ($year_from == $y) ? 'selected' : '';
                                                echo "<option value='" . $y . "' " . $sel . ">" . $y . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 annual-group">
                                        <label for="year_to" class="form-label" style="height:34px!important">Year To</label>
                                        <select name="year_to" class="form-control">
                                            <option value="">All</option>
                                            <?php
                                            foreach ($years as $y) {
                                                $sel = ($year_to == $y) ? 'selected' : '';
                                                echo "<option value='" . $y . "' " . $sel . ">" . $y . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Custom Range Inputs -->
                                    <div class="col-sm-3 custom-group">
                                        <label for="custom_date_from" class="form-label" style="height:34px!important" >Date From</label>
                                        <input type="date" id="custom_date_from" class="form-control" style="height:34px!important" />
                                    </div>
                                    <div class="col-sm-3 custom-group">
                                        <label for="custom_date_to" class="form-label" style="height:34px!important" >Date To</label>
                                        <input type="date" id="custom_date_to" class="form-control" style="height:34px!important" />
                                    </div>

                                    <!-- Document Type -->
                                    <div class="col-sm-2">
                                        <label for="document_type" class="form-label" style="height:34px!important">Document Type</label>
                                        <select name="document_type" class="form-control">
                                            <option value="">All</option>
                                            <?php echo getOptions($con, 'document_type', 'type_id', 'type_name', $document_type); ?>
                                        </select>
                                    </div>

                                    <!-- Department -->
                                    <div class="col-sm-2">
                                        <label for="department" class="form-label" style="height:34px!important">Department</label>
                                        <select name="department" class="form-control">
                                            <option value="">All</option>
                                            <?php echo getOptions($con, 'department', 'department_id', 'department_name', $department); ?>
                                        </select>
                                    </div>

                                    <!-- Filter Button -->
                                    <div class="col-sm-1 d-flex align-items-end">
                                        <label for="filter" class="form-label" style="color:white" style="height: 34px;width:100%">Filter</label>
                                        <button name="search_doc" type="submit" id="filterBtn" class="btn btn-primary w-100" style="height: 34px;width:100%">Filter</button>
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