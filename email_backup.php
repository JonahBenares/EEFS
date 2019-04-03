<?php 
include 'includes/connection.php';
date_default_timezone_set("Asia/Taipei");
require_once 'C:\Users\Jonah\Dropbox\xampp\htdocs\EEFS\js\phpexcel\Classes\PHPExcel\IOFactory.php';
$objPHPExcel = new PHPExcel();

$inputFileName = 'C:\Users\Jonah\Dropbox\xampp\htdocs\EEFS\upload\exported\export.xlsx';
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 

for($a=2;$a<=$highestRow;$a++){
    $subject = $objPHPExcel->getActiveSheet()->getCell('A'.$a)->getValue();
    $sender = $objPHPExcel->getActiveSheet()->getCell('B'.$a)->getValue();
    $body = $objPHPExcel->getActiveSheet()->getCell('C'.$a)->getValue();

    //echo $subject . " - " . $sender . " - " . $body . "<br>";


    $today = date('Y-m-d');
    $get_max = $con->query("SELECT MAX(document_id) AS maxid FROM document_info");
    $fetch_max = $get_max->fetch_array();
    $maxid=$fetch_max['maxid']+1;

    $sql = $con->query("SELECT user_id, username FROM users");
    while($fetch=$sql->fetch_array()){
        $pos = stripos($body, $fetch['username']);
        if($pos!==false){
            $logid= $fetch['user_id'];
        }
    }

    $sql_comp = $con->query("SELECT company_id, company_name FROM company");
    while($fetch_comp=$sql_comp->fetch_array()){
        $pos_comp = stripos($body, $fetch_comp['company_name']);
        if($pos_comp!==false){
            $compid= $fetch_comp['company_id'];
        }
    }

    $sql_dept = $con->query("SELECT department_id, department_name FROM department");
    while($fetch_dept=$sql_dept->fetch_array()){
        $pos_dept = stripos($body, $fetch_dept['department_name']);
        if($pos_dept!==false){
            $deptid= $fetch_dept['department_id'];
        }
    }

    $loggeddate=date('Y-m-d H:i:s');
    if(strpos($subject, 'confidential') !== false){

         if(empty($compid)){
            $compid=0;
        } else {
            $compid=$compid;
        }

        if(empty($deptid)){
            $deptid=0;
        } else {
            $deptid=$deptid;
        }

        if(empty($logid)){
            $logid=0;
        } else {
            $logid=$logid;
        }
    $con->query("INSERT INTO document_info (document_date, document_id, user_id, company_id, department_id, logged_date, email_attach, email_sender, confidential, subject, remarks) VALUES ('$today','$maxid', '$logid', '$compid','$deptid','$loggeddate', '1', ' $sender', 'Yes','$subject', '$body')");
    } else {

         if(empty($compid)){
            $compid=0;
        } else {
            $compid=$compid;
        }

        if(empty($deptid)){
            $deptid=0;
        } else {
            $deptid=$deptid;
        }

        if(empty($logid)){
            $logid=0;
        } else {
            $logid=$logid;
        }

    $con->query("INSERT INTO document_info (document_date, document_id, user_id, company_id, department_id, logged_date, email_attach, email_sender, confidential, subject, remarks) VALUES ('$today','$maxid','$logid','$compid', '$deptid','$loggeddate', '1', ' $sender', 'No','$subject', '$body')");
    }

}


$dir = 'C:\Users\Jonah\Dropbox\xampp\htdocs\EEFS\upload\emails';
$files1 = scandir($dir);

$count= count($files1);
$now=date('Ymd');
for($x=2;$x<$count;$x++){
    $f = explode(".",$files1[$x]);
    $filename = $f[0].'_'.$now;

    $subject = explode("__",$filename);
    $subj = $subject[0];

    $getid=$con->query("SELECT document_id FROM document_info WHERE subject = '$subj'");
    $fetchid=$getid->fetch_array();

    $doc=$fetchid['document_id'];
    
    $ext=$f[1];
    $new_fn=$filename.'.'.$ext;
  
    $fname = 'C:\Users\Jonah\Dropbox\xampp\htdocs\EEFS\upload\emails\/'.$files1[$x];

    rcopy($fname , "C:\Users\Jonah\Dropbox\/xampp\htdocs\EEFS\upload\/".$new_fn);
   
    unlink("C:\Users\Jonah\Dropbox\/xampp\htdocs\EEFS\upload\/emails\/".$files1[$x]);
    

     $con->query("INSERT INTO document_attach (document_id, attach_file, attach_remarks) VALUES ('$doc', '$new_fn', 'Via Email')");
}



for($b=2;$b<=$highestRow;$b++){
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$b, null);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$b, null);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$b, null);


}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($inputFileName);



   function rcopy($src, $dst) {
        if (is_dir ( $src )) {
            mkdir ( $dst );
            $files = scandir ( $src );
            foreach ( $files as $file )
                if ($file != "." && $file != "..")
                    rcopy ( "$src/$file", "$dst/$file" );
        } else if (file_exists ( $src ))
            copy ( $src, $dst );
    }
?>