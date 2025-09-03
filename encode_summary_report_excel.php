<?php
session_start();

if (isset($_POST['export_excel']) && !empty($_SESSION['export_data'])) {
    $exportDate = date("Y-m-d");
    $exportedBy = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';

    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';

    // Filter export data by date range
    $filteredData = array_filter($_SESSION['export_data'], function ($row) use ($date_from, $date_to) {
        $row_date = $row['Date'];
        if (!empty($date_from) && $row_date < $date_from) return false;
        if (!empty($date_to) && $row_date > $date_to) return false;
        return true;
    });

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=encode_summary_export.xls");

    echo "<table border='1'>";

    // Company header
    echo "
        <tr>
            <td colspan='2' style='font-size:16px; font-weight:bold; text-align: center'>
                Central Negros Power Reliability, Inc. <br>
                (Main Office) 88 Corner Rizal-Mabini Sts. Bacolod City <br>
                (Plant Site) Purok San Jose, Barangay Calumangan, Bago City <br>
                Telfax: (034) 436-1932
            </td>
        </tr>
        <tr>
            <td colspan='2' style='font-size:20px; font-weight:bold; text-align: center'>EEFS Encode Summary Report</td>
        </tr>
        <tr>
            <td colspan='1'>Date Exported: {$exportDate}</td>
            <td colspan='1'>Exported By: {$exportedBy}</td>
        </tr>
    ";


    // Table headers
    echo "
        <tr>
            <th>Date</th>
            <th>Total Encode</th>
        </tr>";

    // Table rows
    foreach ($filteredData as $row) {
        echo "<tr>
                <td>" . date("Y-m-j", strtotime($row['Date'])) . "</td>
                <td>{$row['Total']}</td>
              </tr>";
    }

    echo "</table>";
    exit;
} else {
    echo "No data available to export.";
}
