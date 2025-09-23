<?php
session_start();

if (isset($_POST['export_excel']) && !empty($_SESSION['export_data'])) {
    $exportDate = date("Y-m-d");
    $exportedBy = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
    $filters = isset($_SESSION['export_filters']) ? $_SESSION['export_filters'] : [];

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=annual_report_export.xls");

    echo "<table border='1'>";

    // Header and company details
    echo "
        <tr>
            <td colspan='4' style='font-size:16px; font-weight:bold; text-align: center'>
                Central Negros Power Reliability, Inc. <br>
                (Main Office) 88 Corner Rizal-Mabini Sts. Bacolod City <br>
                (Plant Site) Purok San Jose, Barangay Calumangan, Bago City <br>
                Telfax: (034) 436-1932
            </td>
        </tr>
        <tr>
            <td colspan='4' style='font-size:20px; font-weight:bold; text-align: center'>EEFS Annual Report</td>
        </tr>
        <tr>
            <td colspan='2'>Date Exported: {$exportDate}</td>
            <td colspan='2'>Exported By: {$exportedBy}</td>
        </tr>
    ";

    // Filtered keywords
    if (!empty($filters)) {
        echo "<tr><td colspan='4'><strong>Filtered By:</strong><br>";
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                echo "{$key}: {$value}<br>";
            }
        }
        echo "</td></tr>";
    }

    // Table headers
    echo "
        <tr>
            <th colspan='1'>Year</th>
            <th colspan='1'>Month</th>
            <th colspan='1'>Department</th>
            <th colspan='1'>No. of Docs Uploaded</th>
        </tr>";

    // Data rows
    foreach ($_SESSION['export_data'] as $row) {
        echo "<tr>
                <td colspan='1'>{$row['Year']}</td>
                <td colspan='1'>{$row['Month']}</td>
                <td colspan='1'>{$row['Department']}</td>
                <td colspan='1'>{$row['No. of Docs Uploaded']}</td>
              </tr>";
    }

    echo "</table>";
    exit;
} else {
    echo "No data available to export.";
}
