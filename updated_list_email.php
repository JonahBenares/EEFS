<?php 
$con=mysqli_connect("localhost","root","1t@dm1N","db_filing");
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL:".mysqli_connect_error();
}

function select_column_where($table, $column, $where, $value){
    $con=mysqli_connect("localhost","root","1t@dm1N","db_filing");
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL:".mysqli_connect_error();
    }
    $getCol = mysqli_query($con, "SELECT $column FROM $table WHERE $where = '$value'");
    $fetchCol = mysqli_fetch_array($getCol);
    return $fetchCol[$column];
}

        $previousMonth = date('F', strtotime("-1 month"));
        $previousMonthNumber = date('m', strtotime("-1 month"));
        $previousYear = date('Y', strtotime("-1 month"));

        $to='mba_energreen2013@yahoo.com, syndey.cenpri@gmail.com, zyndyrosales.cenpri@gmail.com, cristycesar.cenpri@gmail.com';
        $subject="EEFS Report: For the month of ". $previousMonth." ".$previousYear;
      
        $message='';
        $message.="EEFS Report: For the month of ". $previousMonth." ".$previousYear."<br>";

        $message.="<table style='border-collapse:collapse; width:100%; font-size:10px'>";
        $message.="<tr>";
        $message.="<td style='border:1px solid;width:2%'><strong>Document Date</strong></td>";
        $message.="<td style='border:1px solid;width:5%'><strong>Subject</strong></td>";
        $message.="<td style='border:1px solid;width:2%'><strong>Company</strong></td>";
        $message.="<td style='border:1px solid;width:5%'><strong>Document Type</strong></td>";
        $message.="<td style='border:1px solid;width:10%'><strong>Document Location</strong></td>";
        $message.="<td style='border:1px solid;width:5%'><strong>Department</strong></td>";           
        $message.="<td style='border:1px solid;width:5%'><strong>Sender Company</strong></td>";    
        $message.="<td style='border:1px solid;width:5%'><strong>Sender Person</strong></td>";     
        $message.="<td style='border:1px solid;width:5%'><strong>Addressee Company</strong></td>";    
        $message.="<td style='border:1px solid;width:5%'><strong>Addressee Person</strong></td>";

        $getData = mysqli_query($con,"SELECT * FROM document_info WHERE EXTRACT(MONTH FROM logged_date) = 1 AND EXTRACT(YEAR FROM logged_date) = 2018 ORDER BY logged_date ASC");
        $num_rows = mysqli_num_rows($getData);

         while($d = mysqli_fetch_array($getData)){
            $company = select_column_where("company", "company_name", "company_id", $d['company_id']);
            $department = select_column_where("department", "department_name", "department_id", $d['department_id']);
            $document_type = select_column_where("document_type", "type_name", "type_id", $d['type_id']);
            $document_location = select_column_where("document_location", "location_name", "location_id", $d['location_id']);
                $message.="<tr>";
                $message.="<td style='border:1px solid'>".$d['document_date']."</td>";
                $message.="<td style='border:1px solid'>".$d['subject']."</td>";
                $message.="<td style='border:1px solid'>".$company."</td>";
                $message.="<td style='border:1px solid;'>".$document_type."</td>";                     
                $message.="<td style='border:1px solid'>".$document_location."</td>";
                $message.="<td style='border:1px solid'>".$department."</td>";
                $message.="<td style='border:1px solid'>".$d['sender_company']."</td>";
                $message.="<td style='border:1px solid'>".$d['sender_person']."</td>";
                $message.="<td style='border:1px solid'>".$d['addressee_company']."</td>";
                $message.="<td style='border:1px solid'>".$d['addressee_person']."</td>";
                $message.="</tr>";
         }

        $message.="</tr></table>";


$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
if($num_rows != 0){
$headers .= 'From: <filing.bacolod@gmail.com>' . "\r\n";
$headers .= 'CC: jonahbenares.cenpri@gmail.com' . "\r\n";
var_dump(mail($to,$subject,$message,$headers));
}

?>