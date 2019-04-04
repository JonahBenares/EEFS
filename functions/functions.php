<?php 
	function getInfo($con, $column, $table, $id, $value_id){
		$get = $con->query("SELECT $column FROM $table WHERE $id = '$value_id'");
		$fetch = $get->fetch_array();
		return $fetch[$column];
	}

	function count_attachment($con, $doc_id){
		$get = $con->query("SELECT document_id FROM document_attach WHERE document_id = '$doc_id'");
		$rows = $get->num_rows;
		return $rows;
	}


	function filteredSQL($con, $post){
	foreach($post as $var=>$value)
		$$var = mysqli_real_escape_string($con,$value);

	$docid=array();
	$sql = "SELECT document_id FROM document_info WHERE ";
	

	 if(!empty($doc_from)){
	 	if(!empty($doc_to)){
	 		$sql.=" document_date BETWEEN '$doc_from' AND '$doc_to' AND";
			
	 	} else {
	 		$sql.=" document_date BETWEEN '$doc_from' AND '$doc_from' AND";
	 	}

	 }

	 if(!empty($comp)){
	 	$sql.=" company_id = '$comp' AND";
	 }

	 if(!empty($docloc)){
	 	$sql.=" location_id = '$docloc' AND";
	 }

	 if(!empty($doctype)){
	 	$sql.=" type_id = '$doctype' AND";
	 }

	 if(!empty($dept)){
	 	$sql.=" department_id = '$dept' AND";
	 }

	 if(!empty($subj)){
	 	$sql.=" subject LIKE '%$subj%' AND";
		
	 }

	 if(!empty($sign)){
	 		$sql.=" signatory LIKE '%$sign%' AND";
	 }

	 if(!empty($copytype)){
	 		$sql.=" copy_type = '$copytype' AND";
	 }

	 if(!empty($confidential)){
	 		$sql.=" confidential = '$confidential' AND";
	 }

	 if(!empty($send)){
	 	$sql.=" (sender_company LIKE '%$send%' OR sender_person LIKE '%$send%' OR email_sender LIKE '%$send%') AND";
	 }

	 if(!empty($add)){
	 	$sql.=" (addressee_company LIKE '%$add%' OR addressee_person LIKE '%$add%') AND";
		
	 }

	 if(!empty($notes)){
	 	$sql.=" remarks LIKE '%$notes%' AND";
		
	 }

	  if(!empty($via)){
	  	if($via==1) $v=1;
	  	else $v=0;
	 	$sql.=" email_attach = '$v' AND";
	  }

	$query=substr($sql,0,-3);

	//echo $query;

	$searchHead=$con->query($query);
	$rows_head = $searchHead->num_rows;
	if($rows_head != 0){
		while($fetchHead = $searchHead->fetch_array()){
			$docid[] = $fetchHead['document_id'];
		}
 	}
 //	print_r($logid);
 	return array_unique($docid);
}


function filtersApplied($con, $post){
	
	foreach($post as $var=>$value)
		$$var = mysqli_real_escape_string($con,$value);

	$filter='';

	 if(!empty($doc_from)){
	 	if(!empty($doc_to)){
			$filter.='Date = ' . $doc_from . ' to '. $doc_to . ', ';
		 } else {
	 		$filter.='Date = ' . $doc_from. ', ';
	 	}
	 }
	 
	 if(!empty($comp)){
	 	$filter.='Company = ' . getInfo($con, 'company_name', 'company', 'company_id' ,$comp). ', ';
	 }

	if(!empty($docloc)){
	 	$filter.='Location = ' . getInfo($con, 'location_name', 'document_location', 'location_id' ,$docloc). ', ';
	 }
	 
	 if(!empty($doctype)){
	 	$filter.='Document Type = ' . getInfo($con, 'type_name', 'document_type', 'type_id' ,$doctype). ', ';
	 }

	 if(!empty($dept)){
	 	$filter.='Department = ' . getInfo($con, 'department_name', 'department', 'department_id' ,$dept). ', ';
	 }

	 if(!empty($subj)){

	 	$filter.='Subject = ' . $subj. ', ';
	 }

	  if(!empty($sign)){

	 	$filter.='Signatory = ' . $sign. ', ';
	 }

	 if(!empty($copytype)){

	 	$filter.='Type of Copy = ' . $copytype. ', ';
	 }

	 if(!empty($confidential)){

	 	$filter.='Confidential = ' . $confidential. ', ';
	 }

	 if(!empty($send)){

	 	$filter.='Sender Info = ' . $send. ', ';
	 }

	 if(!empty($add)){

	 	$filter.='Addressee Info = ' . $add. ', ';
	 }


	 if(!empty($notes)){
	 	$filter.='Remarks = ' . $notes. ', ';
	 }

	 if(!empty($via)){
	 	if($via==1) $a=' email';
	 	else $a = ' encoded';
	 	$filter.='Filed via = ' . $a. ', ';
	 }


	 $fil = substr($filter, 0, -2);
	 return $fil;
}
function companyCount($con, $company){
	$select = $con->query("SELECT company_id FROM document_info WHERE company_id = '$company'");
	$rows=$select->num_rows;
	return $rows;
}
function companyCounter($con){
	$select = $con->query("SELECT company_id FROM company");
	$rows=$select->num_rows;
	return $rows;
}
function documentCount($con, $department){
	$select = $con->query("SELECT department_id FROM document_info WHERE department_id = '$department'");
	$rows=$select->num_rows;
	return $rows;
}
function departmentCount($con){
	$select = $con->query("SELECT department_id FROM department");
	$rows=$select->num_rows;
	return $rows;
}
function typeCount($con, $type){
	$select = $con->query("SELECT type_id FROM document_info WHERE type_id = '$type'");
	$rows=$select->num_rows;
	return $rows;
}
function typeCounter($con){
	$select = $con->query("SELECT type_id FROM document_type");
	$rows=$select->num_rows;
	return $rows;
}
function locationCount($con){
	$select = $con->query("SELECT location_id FROM document_location");
	$rows=$select->num_rows;
	return $rows;
}
function locationCounter($con, $location){
	$select = $con->query("SELECT location_id FROM document_info WHERE location_id = '$location'");
	$rows=$select->num_rows;
	return $rows;
}
/*function encodedCounter($con, $encode){
	$select = $con->query("SELECT email_attach FROM document_info WHERE email_attach = '$encode'");
	$rows=$select->num_rows;
	return $rows;
}*/
/*function activeCount($con){
	$select = $con->query("SELECT status FROM users WHERE status = 'Active'");
	$rows=$select->num_rows;
	return $rows;
}*/

// Function to remove folders and files 
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file)
                if ($file != "." && $file != "..") rrmdir("$dir/$file");
            rmdir($dir);
        }
        else if (file_exists($dir)) unlink($dir);
    }

    // Function to Copy folders and files       
    function rcopy($src, $dst) {
        if (file_exists ( $dst ))
            rrmdir ( $dst );
        if (is_dir ( $src )) {
            mkdir ( $dst );
            $files = scandir ( $src );
            foreach ( $files as $file )
                if ($file != "." && $file != "..")
                    rcopy ( "$src/$file", "$dst/$file" );
        } else if (file_exists ( $src ))
            copy ( $src, $dst );
    }

    function getShared($con,$userid, $docid){
    	$select = $con->query("SELECT user_id FROM shared_document WHERE user_id = '$userid' AND document_id = '$docid'");
    	$rows=$select->num_rows;
    	return  $rows;
    	//echo "SELECT user_id FROM shared_document WHERE user_id = '$userid'";
    }
?>
