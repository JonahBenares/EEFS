<?php
session_start();

if (isset($_POST['export_excel']) && !empty($_SESSION['export_data'])) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=summary_export.xls");

    echo "<table border='1'>";
    echo "<tr><th>Year</th><th>Month</th><th>Department</th><th>No. of Docs Uploaded</th></tr>";

    foreach ($_SESSION['export_data'] as $row) {
        echo "<tr>
                <td>{$row['Year']}</td>
                <td>{$row['Month']}</td>
                <td>{$row['Department']}</td>
                <td>{$row['No. of Docs Uploaded']}</td>
              </tr>";
    }

    echo "</table>";
    exit;
} else {
    echo "No data available to export.";
}
?>