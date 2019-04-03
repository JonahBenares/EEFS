<?php
require_once 'C:\Users\Jonah\Dropbox\xampp\htdocs\EEFS\js\phpexcel\Classes\PHPExcel\IOFactory.php';
$objPHPExcel = new PHPExcel();

$inputFileName = 'C:\Users\Jonah\Dropbox\xampp\htdocs\EEFS\upload\emails\test.xlsx';
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
for ($row=2; $row<=$highestRow; $row++){ 
	$subject = $objPHPExcel->getActiveSheet()->getCell('A'.$row)->getValue();
	/*$sender = $objPHPExcel->getActiveSheet()->getCell('B'.$row)->getValue();
	$body = $objPHPExcel->getActiveSheet()->getCell('C'.$row)->getValue();*/


    $values .= "'".$subject."', ";
}

'name', 'id', 'desc';
substr($values, 0, -2);
insert into table (name, id, desc) values ( $values)

/*include 'includes/connection.php';
$string = "Hello world - cenpri";


$sql_comp = $con->query("SELECT company_id, company_name FROM company");
while($fetch_comp=$sql_comp->fetch_array()){
    $pos_comp = stripos($string, $fetch_comp['company_name']);

    if($pos_comp!==false){
        $compid= $fetch_comp['company_id'];
    }
}
echo $compid;
*/