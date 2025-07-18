<?php 
    include('header.php');
    include 'functions/functions.php';
    $usertype=$_SESSION['usertype'];
    $userid=$_SESSION['userid'];

    // Initialize filter variables
    $month = isset($_POST['month']) ? $_POST['month'] : '';
    $year_from = isset($_POST['year_from']) ? $_POST['year_from'] : '';
    $year_to = isset($_POST['year_to']) ? $_POST['year_to'] : '';
    $document_type = isset($_POST['document_type']) ? $_POST['document_type'] : '';
    $department = isset($_POST['department']) ? $_POST['department'] : '';

    function showAppliedFilters($con, $month, $year_from, $year_to, $document_type, $department) {
        $filters = [];

        $filters[] = "Year From: " . ($year_from != '' ? $year_from : "All");
        $filters[] = "Year To: " . ($year_to != '' ? $year_to : "All");

        $months_arr = [
            1 => "January", 2 => "February", 3 => "March", 4 => "April",
            5 => "May", 6 => "June", 7 => "July", 8 => "August",
            9 => "September", 10 => "October", 11 => "November", 12 => "December"
        ];

        $filters[] = "Month: " . ($month != '' ? $months_arr[(int)$month] : "All");

        if (!empty($department)) {
            $deptName = getDepartmentName($con, $department);
            $filters[] = "Department: $deptName";
        } else {
            $filters[] = "Department: All";
        }

        if (!empty($document_type)) {
            $docName = getDocumentTypeName($con, $document_type);
            $filters[] = "Document Type: $docName";
        } else {
            $filters[] = "Document Type: All";
        }

        return "<div class='alert alert-info mt-3'><strong>Filters Applied:</strong> " . implode(", ", $filters) . "</div>";
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
                            <form method="post" action="" class="form-row row gx-3 gy-2 align-items-end">

                                <!-- Month -->
                                <div class="col-sm-2 annual-group">
                                    <label for="month" class="form-label">Month</label>
                                    <select id="month" name="month" class="form-control" style="height:34px!important">
                                        <option value="">All</option>
                                        <?php 
                                        $months = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
                                        foreach($months as $num => $name){
                                            $sel = ($month == $num) ? 'selected' : '';
                                            echo "<option value='$num' $sel>$name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Year From -->
                                <div class="col-sm-2 annual-group">
                                    <label for="year_from" class="form-label">Year From</label>
                                    <select id="year_from" name="year_from" class="form-control" style="height:34px!important">
                                        <option value="">All</option>
                                        <?php 
                                        $res = mysqli_query($con, "SELECT DISTINCT YEAR(document_date) as year FROM document_info ORDER BY year DESC");
                                        while($r = mysqli_fetch_assoc($res)){
                                            $sel = ($year_from == $r['year']) ? 'selected' : '';
                                            echo "<option value='{$r['year']}' $sel>{$r['year']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Year To -->
                                <div class="col-sm-2 annual-group">
                                    <label for="year_to" class="form-label">Year To</label>
                                    <select id="year_to" name="year_to" class="form-control" style="height:34px!important">
                                        <option value="" selected>All</option>
                                        <?php 
                                        $res = mysqli_query($con, "SELECT DISTINCT YEAR(document_date) as year FROM document_info ORDER BY year DESC");
                                        while($r = mysqli_fetch_assoc($res)){
                                            $sel = ($year_to == $r['year']) ? 'selected' : '';
                                            echo "<option value='{$r['year']}' $sel>{$r['year']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Document Type -->
                                <div class="col-sm-2 annual-group">
                                    <label for="document_type" class="form-label">Document Type</label>
                                    <select id="document_type" name="document_type" class="form-control" style="height:34px!important">
                                        <option value="" >All</option>
                                        <?php 
                                        $types = mysqli_query($con, "SELECT * FROM document_type ORDER BY type_name ASC");
                                        while($t = mysqli_fetch_assoc($types)){
                                            $sel = ($document_type == $t['type_id']) ? 'selected' : '';
                                            echo "<option value='{$t['type_id']}' $sel>{$t['type_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Department -->
                                <div class="col-sm-2 annual-group">
                                    <label for="department" class="form-label">Department</label>
                                    <select id="department" name="department" class="form-control" style="height:34px!important">
                                        <option value="" >All</option>
                                            <?php 
                                            $depts = mysqli_query($con, "SELECT * FROM department ORDER BY department_name ASC");
                                            while($d = mysqli_fetch_assoc($depts)){
                                                $sel = ($department == $d['department_id']) ? 'selected' : '';
                                                echo "<option value='{$d['department_id']}' $sel>{$d['department_name']}</option>";
                                            }
                                            ?>
                                    </select>
                                </div>

                                <!-- Filter Button -->
                                <div class="col-sm-1 d-flex align-items-end">
                                        <label for="filter" class="form-label" style="color:white"></label>
                                        <button type="submit" name= "search_doc" class="btn btn-primary w-100" style="height: 34px;width:100%">Filter</button>
                                </div>


                            </form>

                            <?php 
                                if(isset($_POST['search_doc'])){ 
                                    echo showAppliedFilters($con, $month, $year_from, $year_to, $document_type, $department);

                                    $where = array();

                                    if($month != ''){
                                        $where[] = "MONTH(document_date) = '$month'";
                                    }
                                    if($year_from != ''){
                                        $where[] = "YEAR(document_date) >= '$year_from'";
                                    }
                                    if($year_to != ''){
                                        $where[] = "YEAR(document_date) <= '$year_to'";
                                    }
                                    if($document_type != ''){
                                        $where[] = "type_id = '$document_type'";
                                    }
                                    if($department != ''){
                                        $where[] = "department_id = '$department'";
                                    }

                                    $condition = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

                                    $query = "SELECT YEAR(document_date) as year, MONTH(document_date) as month, department_id, COUNT(*) as total 
                                            FROM document_info $condition 
                                            GROUP BY YEAR(document_date), MONTH(document_date), department_id 
                                            ORDER BY YEAR(document_date) DESC, MONTH(document_date) DESC";

                                    $result = mysqli_query($con, $query);

                                    if ($result && mysqli_num_rows($result) > 0) {

                                        if (!$result) {
                                            echo "<div class='alert alert-danger mt-4'>Query failed: " . mysqli_error($con) . "</div>";
                                        } elseif (mysqli_num_rows($result) > 0) {
                                            echo "<div class='mt-4'><table class='table table-bordered'>
                                                    <thead>
                                                        <tr>
                                                            <th>Year</th>
                                                            <th>Month</th>
                                                            <th>Department</th>
                                                            <th>No. of Docs Uploaded</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>";

                                            while($row = mysqli_fetch_assoc($result)){
                                                $months = array(
                                                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                                );

                                                $month_num = (int)$row['month'];
                                                $month_name = isset($months[$month_num]) ? $months[$month_num] : 'Unknown';
                                                
                                                $department_name = getDepartmentName($con, $row['department_id']); // consistent use

                                                echo "<tr>
                                                        <td>{$row['year']}</td>
                                                        <td>{$month_name}</td>
                                                        <td>{$department_name}</td>
                                                        <td>{$row['total']}</td>
                                                      </tr>";
                                            }
                                        }
                                            echo "</tbody></table></div>";
                                        } else {
                                            echo "<div class='alert alert-warning mt-4'>No records found for the selected filters.</div>";
                                        }
                                    }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
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

<?php 
// Helper function
    function getDepartmentName($con, $id) {
        $q = mysqli_query($con, "SELECT department_name FROM department WHERE department_id = '$id' LIMIT 1");
        if($r = mysqli_fetch_assoc($q)) return $r['department_name'];
        return 'N/A';
    }
?>  