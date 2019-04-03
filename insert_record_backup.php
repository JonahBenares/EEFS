<?php
include 'includes/connection.php';
date_default_timezone_set("Asia/Taipei");
session_start();
$userid= $_SESSION['userid'];

    foreach($_POST as $var=>$value)
    $$var = mysqli_real_escape_string($con,$value);
	
	
	if($doc_id==0){
   	    $sql5 = mysqli_query($con,"SELECT MAX(document_id) as docid From document_info");
        $fetch = $sql5->fetch_array();
        $docid = $fetch['docid']+1;

   		$get_type = $con->query("SELECT type_id FROM document_type WHERE type_name = '$doc_type'");

   		$rows_type = $get_type->num_rows;
   		if($rows_type==0){
   			$get_exist =  $con->query("SELECT MAX(type_id) as typeid FROM document_type");
   			$fetch_exist = $get_exist->fetch_array();
   			$typeid = $fetch_exist['typeid'] + 1;

   			$insert_type = $con->query("INSERT INTO document_type (type_id, type_name) VALUES ('$typeid', '$doc_type')");
   		} else {
   			$fetch_type = $get_type->fetch_array();
   			$typeid = $fetch_type['type_id'];

   		}



      $get_location = $con->query("SELECT location_id FROM document_location WHERE location_name = '$location'");
     // echo "SELECT location_id FROM document_location WHERE location_name = '$location'";
      
      $rows_location = $get_location->num_rows;
      if($rows_location==0){
        $get_exist1 =  $con->query("SELECT MAX(location_id) as locationid FROM document_location");
        $fetch_exist1 = $get_exist1->fetch_array();
        $locationid = $fetch_exist1['locationid'] + 1;

        $insert_location = $con->query("INSERT INTO document_location (location_id, location_name) VALUES ('$locationid', '$location')");
      } else {
        $fetch_location = $get_location->fetch_array();
        $locationid = $fetch_location['location_id'];

      }
   		
   		$now = date('Y-m-d H:i:s');
        $insert= $con->query("INSERT INTO document_info(document_id ,logged_date,document_date,company_id,location_id,user_id,type_id,department_id,subject,sender_company, sender_person,addressee_company, addressee_person, copy_type, confidential, signatory,remarks) VALUES ('$docid','$now','$doc_date','$company','$locationid','$userid','$typeid','$department','$subject','$sender_comp','$sender_person','$add_comp','$add_person','$copy_type', '$confidential','$signatory','$remarks')");
    
      
      for($x=1;$x<=3;$x++){
          $share='share'.$x;
          $suser = $$share;

          if(!empty($suser)){
            $insertshare= $con->query("INSERT INTO shared_document(document_id, user_id) VALUES ('$docid', '$suser')");
          }
      }

        
        if(!isset($counterX) || $counterX == ''){
            $ctrx = $counter;
        } 
        else{
            $ctrx = $counterX;
        }
      
        for($x=1; $x<=$ctrx;$x++){
            $a="attach_file".$x;
            if(!empty($_FILES[$a]["name"])){
                $activity = $_FILES[$a]['tmp_name'];
                $act = $_FILES[$a]["name"];
                $name = 'attach_name'.$x;
                $aname=$$name;
                $a = explode(".", $act); //attach file
                $ext = $a[1];
                if($ext=='php'){
                  echo "ext";
                } else { 
                  $afile = $subject."_".$userid.$x.".".$ext;
                  move_uploaded_file($_FILES['attach_file'.$x]['tmp_name'], "upload/" . $afile);
                  $update=mysqli_query($con,"INSERT INTO document_attach (document_id,attach_file,attach_remarks) VALUES ('$docid','$afile','$aname')");
                    if($update){
                      echo "ok";
                    } else {
                      echo "error";
                    }
                }
                
            }
        }  
    } else {

    	$get_type = $con->query("SELECT type_id FROM document_type WHERE type_name = '$doc_type'");

   		$rows_type = $get_type->num_rows;
   		if($rows_type==0){
   			$get_exist =  $con->query("SELECT MAX(type_id) as typeid FROM document_type");
   			$fetch_exist = $get_exist->fetch_array();
   			$typeid = $fetch_exist['typeid'] + 1;

   			$insert_type = $con->query("INSERT INTO document_type (type_id, type_name) VALUES ('$typeid', '$doc_type')");
   		} else {
   			$fetch_type = $get_type->fetch_array();
   			$typeid = $fetch_type['type_id'];

   		}

      $get_location = $con->query("SELECT location_id FROM document_location WHERE location_name = '$location'");

      $rows_location = $get_location->num_rows;
      if($rows_location==0){
        $get_exist1 =  $con->query("SELECT MAX(location_id) as locationid FROM document_location");
        $fetch_exist1 = $get_exist1->fetch_array();
        $locationid = $fetch_exist1['locationid'] + 1;

        $insert_location = $con->query("INSERT INTO document_location (location_id, location_name) VALUES ('$locationid', '$location')");
      } else {
        $fetch_location = $get_location->fetch_array();
        $locationid = $fetch_location['location_id'];

      }

    	$now = date('Y-m-d H:i:s');
        $update = mysqli_query($con,"UPDATE document_info SET logged_date='$now',document_date='$doc_date',company_id='$company',location_id='$locationid', user_id='$userid',type_id='$typeid',department_id='$department',subject='$subject',sender_company='$sender_comp', sender_person='$sender_person',addressee_company='$add_comp',addressee_person='$add_person',copy_type='$copy_type', confidential = '$confidential', signatory='$signatory',remarks='$remarks' WHERE document_id = '$doc_id'");
       
          if(!isset($counterX) || $counterX == ''){
            $ctrx = $counter;
        } 
        else{
            $ctrx = $counterX;
        }
        
        if($con->query("DELETE FROM shared_document WHERE document_id = '$doc_id'")){
           for($x=1;$x<=3;$x++){
          $share='share'.$x;
          $suser = $$share;
          if(!empty($suser)){
              $insertshare= $con->query("INSERT INTO shared_document(document_id, user_id) VALUES ('$doc_id', '$suser')");
          }
         }

        }
        

        $getattach = $con->query("SELECT attach_id FROM document_attach WHERE document_id = '$doc_id'");
        $rows_att=$getattach->num_rows;
       
       
        if($rows_att==$ctrx){
            for($x=1; $x<=$ctrx;$x++){
                $a="attach_file".$x;
                $name = 'attach_name'.$x;
                $aname=$$name;
                $attid = 'attach_id'.$x;
                $attachid=$$attid;
                if(!empty($_FILES[$a]["name"])){
                    $activity = $_FILES[$a]['tmp_name'];
                    $act = $_FILES[$a]["name"];
                    
                    $a = explode(".", $act); //attach file
                    $ext = $a[1];
                    if($ext=='php'){
                      echo "ext";
                    } else {
                      $afile = $subject."_".$userid.$x.".".$ext;
                      move_uploaded_file($_FILES['attach_file'.$x]['tmp_name'], "upload/" . $afile);
                      $update=mysqli_query($con,"UPDATE document_attach SET attach_file = '$afile', attach_remarks='$aname' WHERE attach_id='$attachid'");
                      if($update){
                         echo "ok";
                      } else {
                        echo "error";
                      }

                    }
                }
                if(!empty($aname)){
                      $update=mysqli_query($con,"UPDATE document_attach SET attach_remarks='$aname' WHERE attach_id='$attachid'");
                      if($update){
                         echo "ok";
                      } else {
                        echo "error";
                      }
                     
                }
            }
        } else {
            
            for($x=1; $x<=$ctrx;$x++){
                $a="attach_file".$x;
                 $name = 'attach_name'.$x;
                 $attid = 'attach_id'.$x;
                 $aname=$$name;
                 $attachid=$$attid;
                if(!empty($_FILES[$a]["name"])){
                    $activity = $_FILES[$a]['tmp_name'];
                    $act = $_FILES[$a]["name"];
                   
                    $a = explode(".", $act); //attach file
                    $ext = $a[1];
                    $afile = $subject."_".$userid.$x.".".$ext;

                    $getex=$con->query("SELECT attach_id FROM document_attach WHERE attach_id = '$attachid'");
                    $rowex=$getex->num_rows;
                    if($rowex>0){
                          if($ext=='php'){
                            echo "ext";
                          } else {
                          move_uploaded_file($_FILES['attach_file'.$x]['tmp_name'], "upload/" . $afile);
                          $update=mysqli_query($con,"UPDATE document_attach SET attach_file = '$afile', attach_remarks='$aname' WHERE attach_id='$attachid'");
                           if($update){
                             echo "ok";
                            } else {
                              echo "error";
                            }
                        }
                        
                    } else {
                      if($ext=='php'){
                            echo "ext";
                      } else {
                           move_uploaded_file($_FILES['attach_file'.$x]['tmp_name'], "upload/" . $afile);
                           $update=mysqli_query($con,"INSERT INTO document_attach (document_id,attach_file,attach_remarks) VALUES ('$doc_id','$afile','$aname')");
                          if($update){
                             echo "ok";
                            } else {
                              echo "error";
                            }
                      }
                   
                   
                    }
                }
                if(!empty($aname)){
                      $update=mysqli_query($con,"UPDATE document_attach SET attach_remarks='$aname' WHERE attach_id='$attachid'");
                      if($update){
                       echo "ok";
                      } else {
                        echo "error";
                      }
                    //  echo json_encode("UPDATE tmp_attachment_logs SET attach_name='$aname' WHERE attach_id='$attachid'");
                }
                
            }
        } 
        
    }
?>
      
