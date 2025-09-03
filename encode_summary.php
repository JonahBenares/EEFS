<?php 
include('header.php');
include 'functions/functions.php';
   
$usertype = $_SESSION['usertype'];
$userid = $_SESSION['userid'];

$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
$date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';

function showAppliedFilters($date_from, $date_to) {
    $filters = [];

    if (!empty($date_from)) {
        $filters[] = "Date From: " . date("Y-m-j", strtotime($date_from));
    }

    if (!empty($date_to)) {
        $filters[] = "Date To: " . date("Y-m-j", strtotime($date_to));
    }

    if (empty($filters)) return ''; // nothing to show

    $filterBadges = implode('', array_map(function($filter) {
        return "<span class='badge bg-warning text-dark rounded-pill px-3 py-2' style='margin-right:5px'>" 
            . htmlspecialchars($filter) . "</span>";
    }, $filters));

    $exportForm = '';
    if (!empty($_SESSION['export_data'])) {
        $exportForm = "
            <form action='encode_summary_report_excel.php' method='post' style='display:inline-block; margin-left:10px;'>
                <input type='hidden' name='export_excel' value='1'>
                <input type='hidden' name='date_from' value='" . htmlspecialchars($date_from) . "'>
                <input type='hidden' name='date_to' value='" . htmlspecialchars($date_to) . "'>
                <button type='submit' class='btn btn-success btn-sm'>Export to Excel</button>
            </form>";
    }

    

    return "<br>
        <div id='filteredCard' class='card mt-3 shadow-sm' style='margin:10px 15px 0px 15px;'>
            <div class='card-body'>
                <div class='row'>
                    <div class='d-flex flex-wrap align-items-center mt-3 gap-2 w-100'>
                        <strong class='me-2'>Filtered Dates:</strong>
                        $filterBadges
                        <a href='encode_summary.php' class='ms-auto text-decoration-underline text-white'>Clear Filter</a>
                        $exportForm
                        <a href='encode_summary_report_excel.php' class='ms-auto text-decoration-underline text-white'></a>
                    </div>
                </div>
            </div>
        </div>";
    }

?>

<body>
    <?php include('navbars.php');?>
    <div id="loader"><figure class="one"></figure><figure class="two">loading</figure></div>
    <div id="contents" style="display:block">
        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="http://localhost/systems/eefs/dashboard.php">
                        <em class="fa fa-home"></em>
                    </a></li>
                    <li class="active">Encode Summary</li>
                </ol>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Encode Summary</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default box-shadow">
                        <div class="panel- bg-light p-3 rounded-top" style="padding: 20px 20px; border-bottom: 1px solid #ccc;">
                            <div class="main container-fluid">
                                <form method="post" class="row gx-3 gy-2 align-items-end">
                                    <div class="col-sm-2">
                                        <label for="year_from">Date From</label>
                                            <input type="date" name="date_from" value="<?php echo $date_from; ?>" class="form-control">
                                    </div>

                                    <div class="col-sm-2">
                                        <label for="year_to">Date To</label>
                                            <input type="date" name="date_to" value="<?php echo $date_to; ?>" class="form-control">
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-end">
                                        <label for="department" class="form-label" style="color:white">Filter</label>
                                        <button name="search_summary" type="submit" id="filterBtn" class="btn btn-primary w-100" style="height: 34px;width:100%">Filter</button>
                                    </div>
                                </form>
                                <?php
                                    if (isset($_POST['search_summary'])) {
                                        echo showAppliedFilters($date_from, $date_to);

                                        $conditions = [];
                                        if (!empty($date_from)) $conditions[] = "DATE(di.logged_date) >= '" . $date_from . "'";
                                        if (!empty($date_to))   $conditions[] = "DATE(di.logged_date) <= '" . $date_to . "'";

                                        $where = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

                                        $sql = "SELECT 
                                                    DATE(di.logged_date) AS log_date,
                                                    COUNT(*) AS total
                                                FROM document_info di
                                                
                                                $where
                                                GROUP BY log_date
                                                ORDER BY log_date ASC";

                                        $res = mysqli_query($con, $sql);
                                        
                                        if ($res && mysqli_num_rows($res)) {
                                        echo "
                                            <div class='panel-body'>
                                                <div class='canvas-wrapper'>
                                                    <div id='tabl_rec' class='city'>
                                                        <table class='table table-hover table-bordered' id='tbl_record' style='width:100%'>
                                                            <thead class='th-header'>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>";
                                                             $_SESSION['export_data'] = [];
                                                                while ($row = mysqli_fetch_assoc($res)) {

                                                                    $_SESSION['export_data'][] = [
                                                                        'Date' => $row['log_date'],
                                                                        'Total' => $row['total']
                                                                    ];
                                                                    echo '<tr>';
                                                                    echo '<td>' . date("Y-m-j", strtotime($row['log_date'])) . '</td>';
                                                                    echo '<td>' . $row['total'] . '</td>';
                                                                    echo '</tr>';

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


</script>