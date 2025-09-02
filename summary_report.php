<?php 
include('header.php');
include 'functions/functions.php';

$usertype = $_SESSION['usertype'];
$userid = $_SESSION['userid'];

$filter_type   = isset($_POST['filter_type']) ? $_POST['filter_type'] : '';
$year_from     = isset($_POST['year_from']) ? $_POST['year_from'] : '';
$year_to       = isset($_POST['year_to']) ? $_POST['year_to'] : '';
$date_from     = isset($_POST['date_from']) ? $_POST['date_from'] : '';
$date_to       = isset($_POST['date_to']) ? $_POST['date_to'] : '';
$document_type = isset($_POST['document_type']) ? $_POST['document_type'] : '';
$department    = isset($_POST['department']) ? $_POST['department'] : '';

function getYears($con) {
   $years = array();
        $sql = "
            SELECT DISTINCT SUBSTRING(document_date, 1, 4) AS year 
            FROM document_info 
            WHERE 
                LENGTH(document_date) >= 10 
                AND document_date REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
            ORDER BY year DESC
        ";
        
        $res = mysqli_query($con, $sql);
        while ($r = mysqli_fetch_assoc($res)) {
            $year = $r['year'];
            if (preg_match('/^\d{4}$/', $year)) {
                $years[] = $year;
            }
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

function showAppliedFilters($con, $filter_type, $year_from, $year_to, $date_from, $date_to, $document_type, $department) {
    $filters = array();

    $filters[] = "Filter Type: " . ($filter_type != '' ? ucfirst($filter_type) : "All");

    if ($filter_type == 'annual') {
        $filters[] = "Year From: " . ($year_from != '' ? $year_from : "All");
        $filters[] = "Year To: " . ($year_to != '' ? $year_to : "All");
    } elseif ($filter_type == 'custom') {
        $filters[] = "Date From: " . ($date_from != '' ? $date_from : "All");
        $filters[] = "Date To: " . ($date_to != '' ? $date_to : "All");
    }

    $filters[] = "Department: " . (!empty($department) ? getDepartmentName($con, $department) : "All");
    $filters[] = "Document Type: " . (!empty($document_type) ? getDocumentTypeName($con, $document_type) : "All");

    $filterBadges = implode('', array_map(function($filter) {
        return "<span class='badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center' style='margin-right:5px'>"
            . htmlspecialchars($filter) . "</span>";
    }, $filters));

    $exportForm = '';
    if (!empty($_SESSION['export_data'])) {
        $exportForm = "
            <form action='export_summary_report_excel.php' method='post' style='display:inline-block; margin-left:10px;'>
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
                        <a href='summary_report.php' class='ms-auto text-decoration-underline text-white'>Clear Filter</a>
                        $exportForm
                        <a href='export_summary_report_excel.php' class='ms-auto text-decoration-underline text-white'></a>
                    </div>
                </div>
            </div>
        </div>";
}

function renderViewButton($usertype, $userid, $row, $shared) {
    $docid = $row['document_id'];
    $viewBtn = "<a href='view_details.php?id={$docid}' target='_blank' class='btn btn-warning btn-sm'><span class='fa fa-eye'></span></a>";

    if ($usertype === 'Admin') {
        return $viewBtn;
    }

    if ($usertype === 'Manager') {
        if ($row['user_id'] == $userid || $row['confidential'] === 'No' || $shared > 0) {
            return $viewBtn;
        }
    }

    if ($usertype === 'Staff') {
        if ($row['confidential'] === 'No') {
            return $viewBtn;
        }
    }

    return ""; 
}


?>

<body>
<?php include('navbars.php');?>
<div id="loader"><figure class="one"></figure><figure class="two">loading</figure></div>
<div id="contents" style="display:none">
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="http://localhost/systems/eefs/dashboard.php"><em class="fa fa-home"></em></a></li>
                <li class="active">Summary Report </li>
            </ol>
        </div>
        <div class="row">
            <div class="col-lg-12"><h1 class="page-header">Summary Report</h1></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default box-shadow">
                    <div class="panel- bg-light p-3 rounded-top" style="padding: 20px 20px; border-bottom: 1px solid #ccc;">
                        <div class="main container-fluid">
                            <form method="post" class="row gx-3 gy-2 align-items-end">
                                <?php $years = getYears($con); ?>

                                <!-- Filter Type -->
                                <div class="col-sm-2">
                                    <label for="filter_type">Filter Type</label>
                                    <select name="filter_type" id="filter_type" class="form-control">
                                        <option value="">Select</option>
                                        <option value="annual" <?php echo ($filter_type=='annual' ? 'selected' : ''); ?>>Annual</option>
                                        <option value="custom" <?php echo ($filter_type=='custom' ? 'selected' : ''); ?>>Custom Range</option>
                                    </select>
                                </div>

                                <!-- Annual Filters -->
                                <div class="col-sm-2 annual-group">
                                    <label for="year_from">Year From</label>
                                    <select name="year_from" class="form-control">
                                        <option value="">All</option>
                                        <?php foreach ($years as $y) {
                                            $sel = ($year_from == $y) ? 'selected' : '';
                                            echo "<option value='" . $y . "' " . $sel . ">" . $y . "</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2 annual-group">
                                    <label for="year_to">Year To</label>
                                    <select name="year_to" class="form-control">
                                        <option value="">All</option>
                                        <?php foreach ($years as $y) {
                                            $sel = ($year_to == $y) ? 'selected' : '';
                                            echo "<option value='" . $y . "' " . $sel . ">" . $y . "</option>";
                                        } ?>
                                    </select>
                                </div>

                                <!-- Custom Range -->
                                <div class="col-sm-2 custom-group" style="display:none;">
                                    <label for="date_from">Date From</label>
                                    <input type="date" name="date_from" value="<?php echo $date_from; ?>" class="form-control">
                                </div>
                                <div class="col-sm-2 custom-group" style="display:none;">
                                    <label for="date_to">Date To</label>
                                    <input type="date" name="date_to" value="<?php echo $date_to; ?>" class="form-control">
                                </div>

                                <!-- Document Type -->
                                <div class="col-sm-2">
                                    <label for="document_type">Document Type</label>
                                    <select name="document_type" class="form-control">
                                        <option value="">All</option>
                                        <?php echo getOptions($con, 'document_type', 'type_id', 'type_name', $document_type); ?>
                                    </select>
                                </div>

                                <!-- Department -->
                                <div class="col-sm-2">
                                    <label for="department">Department</label>
                                    <select name="department" class="form-control">
                                        <option value="">All</option>
                                        <?php echo getOptions($con, 'department', 'department_id', 'department_name', $department); ?>
                                    </select>
                                </div>

                                <div class="col-sm-1 d-flex align-items-end">
                                    <label for="department" class="form-label" style="color:white">Filter</label>
                                    <button name="search_doc" type="submit" id="filterBtn" class="btn btn-primary w-100" style="height: 34px;width:100%">Filter</button>
                                </div>
                            </form>

                            <?php
                            if (isset($_POST['search_doc'])) {
                                echo showAppliedFilters($con, $filter_type, $year_from, $year_to, $date_from, $date_to, $document_type, $department);

                                $conditions = array();

                                if ($filter_type == 'annual') {
                                    if ($year_from != '') $conditions[] = "YEAR(document_date) >= '" . $year_from . "'";
                                    if ($year_to != '') $conditions[] = "YEAR(document_date) <= '" . $year_to . "'";
                                } elseif ($filter_type == 'custom') {
                                    if ($date_from != '') $conditions[] = "document_date >= '" . $date_from . "'";
                                    if ($date_to != '') $conditions[] = "document_date <= '" . $date_to . "'";
                                }

                                if ($document_type != '') $conditions[] = "type_id = '" . $document_type . "'";
                                if ($department != '') $conditions[] = "department_id = '" . $department . "'";

                                $where = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

                                $sql = "SELECT 
                                            di.document_id,
                                            di.user_id,
                                            di.confidential,
                                            di.document_date,
                                            c.company_name,
                                            l.location_name,
                                            d.department_name,
                                            t.type_name,
                                            di.subject
                                        FROM document_info di
                                        LEFT JOIN company c ON di.company_id = c.company_id
                                        LEFT JOIN document_location l ON di.location_id = l.location_id
                                        LEFT JOIN department d ON di.department_id = d.department_id
                                        LEFT JOIN document_type t ON di.type_id = t.type_id
                                        $where
                                        ORDER BY di.document_date DESC";

                                $res = mysqli_query($con, $sql);

                                if ($res && mysqli_num_rows($res)) {
                                    echo "
                                    <div class='panel-body'>
                                        <div class='canvas-wrapper'>
                                            <div id='tabl_rec' class='city'>
                                                <table class='table table-hover table-bordered' id='tbl_record' style='width:100%'>
                                                    <thead class='th-header'>
                                                        <tr>
                                                            <th>Document Date</th>
                                                            <th>Company</th>
                                                            <th>Location</th>
                                                            <th>Department</th>
                                                            <th>Document Type</th>
                                                            <th>Subject</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>";
                                                    $_SESSION['export_data'] = [];
                                                        while ($row = mysqli_fetch_assoc($res)) {
                                                            $_SESSION['export_data'][] = [
                                                                'Document Date' => $row['document_date'],
                                                                'Company' => $row['company_name'],
                                                                'Location' => $row['location_name'],
                                                                'Department' => $row['department_name'],
                                                                'Document Type' => $row['type_name'],
                                                                'Subject' => $row['subject']
                                                            ]; 
                                                            $shared = getShared($con, $userid, $row['document_id']);
                                                            echo "<tr>
                                                                    <td>" . $row['document_date'] . "</td>
                                                                    <td>" . $row['company_name'] . "</td>
                                                                    <td style='width:25%'>" . $row['location_name'] . "</td>
                                                                    <td style='width:15%'>" . $row['department_name'] . "</td>
                                                                    <td style='width:10%'>" . $row['type_name'] . "</td>
                                                                    <td style='width:50%'>" . $row['subject'] . "</td>
                                                                    <td align='center'>" . renderViewButton($usertype, $userid, $row, $shared) . "</td>
                                                                  </tr>";   
                                                        }
                                                        echo "  
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>";


                                } else {
                                    echo "<br><div class='alert alert-warning mt-4'>No records found for the selected filters.</div>";
                                }
                            }
                            ?>
                        </div>
                    </div>     
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('scripts.php'); ?>
</body>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterType = document.getElementById('filter_type');
    const annualFields = document.querySelectorAll('.annual-group');
    const customFields = document.querySelectorAll('.custom-group');

    function toggleFields() {
        if (filterType.value === 'annual') {
            annualFields.forEach(el => el.style.display = 'block');
            customFields.forEach(el => el.style.display = 'none');
        } else if (filterType.value === 'custom') {
            annualFields.forEach(el => el.style.display = 'none');
            customFields.forEach(el => el.style.display = 'block');
        } else {
            annualFields.forEach(el => el.style.display = 'none');
            customFields.forEach(el => el.style.display = 'none');
        }
    }

    toggleFields(); 
    filterType.addEventListener('change', toggleFields);
});
</script>
