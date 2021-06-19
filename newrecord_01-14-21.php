<?php 
include('header.php'); 
include('functions/functions.php');
$usertype=$_SESSION['usertype'];
$userid=$_SESSION['userid'];
if(isset($_GET['docid'])) { 
	$docid=$_GET['docid'];
	$get_details = $con->query("SELECT * FROM document_info WHERE document_id = '$docid'");
	$fetch_details = $get_details->fetch_array();
	$typeid=$fetch_details['type_id'];
    $locationid=$fetch_details['location_id'];
    $compid=$fetch_details['company_id'];

	$dept=$fetch_details['department_id'];
	$doctype=getInfo($con, 'type_name', 'document_type', 'type_id', $typeid);
    $docloc=getInfo($con, 'location_name', 'document_location', 'location_id', $locationid);
    $copytype=$fetch_details['copy_type'];
    $confidential=$fetch_details['confidential'];
    $uid=$fetch_details['user_id'];

    $query = mysqli_query($con,"SELECT * FROM document_info WHERE document_id = '$docid'");
    $row = mysqli_fetch_array($query);
    $shared=getShared($con,$userid,$docid);
                        
    if(($usertype == 'Staff' && $confidential == 'Yes')){
        echo "<script>alert('You are not allowed to view this document.'); window.location='viewrecord.php';</script>";
    } else if($usertype=='Manager'){
        if($confidential == 'Yes' && ($shared==0 && $uid != $userid)){
            echo "<script>alert('You are not allowed to view this document.'); window.location='viewrecord.php';</script>";
        }  
    }

}
else $docid=NULL; 

if(isset($_GET['deleteattach'])){
    $attid=$_GET['attid'];
    $docid=$_GET['docid'];
   
    $select=mysqli_query($con, "SELECT attach_file FROM document_attach WHERE attach_id = '$attid'");
    $fetch = mysqli_fetch_array($select);
    $filename = $fetch['attach_file'];
    chmod('upload/'.$filename,0777);
    if(unlink('upload/'.$filename)){
         $deleteatt = mysqli_query($con, "DELETE FROM document_attach WHERE attach_id = '$attid'");
         if($deleteatt){
         echo "<script>alert('Attachment deleted.'); window.location='newrecord.php?docid=".$docid."'</script>";
         }
    }
    
}
?>
<link href="css/newrecord.css" rel="stylesheet">
<script src="js/jquery-1.12.4.js"></script>
<script src="js/bootstrap.min.js"></script> 
<script type="text/javascript" src="js/jquery.js"></script> 
<script>

	function showFileSize() {

        var input, file;
     

        if (!window.FileReader) {
            bodyAppend("p", "The file API isn't supported on this browser yet.");
            return;
        }
        counter = document.getElementById('counter').value;
        counterX = document.getElementById('counterX').value;
       
        var counter_error=0;
        if(counterX===''){
            ctr =  counter;
        } 
        else{
            counterX_val = document.getElementById('counterX').value;
            ctr =  counterX_val;
        }

        if(ctr==1){
            act = document.getElementById('p_acti1');
            fileact = act.files[0];
            if(typeof fileact !== 'undefined'){
                if(fileact.size > 500000000){
                document.getElementById("certBox1").innerHTML="Error: Picture size is too big. Max file size is 50mb.";
                counter_error++;
                }
            }
        } 
        else if(ctr>=2){
    	    for(x=1;x<=ctr;x++){
    	        act = document.getElementById('p_acti'+x);
    	        fileact = act.files[0];
    	        if(typeof fileact !== 'undefined'){
    	         	if(fileact.size > 500000000){
    		          document.getElementById('certBox'+x).innerHTML="Error: Picture size is too big. Max file size is 50mb.";
    		          counter_error++;
    	          	}
    	       	}
    	    }
        }
       
      
        if(counter_error==0){

            var frm = new FormData();
          
         
                if(counterX===''){
                    ctr =  counter;
                } else{
                    counterX_val = document.getElementById('counterX').value;
                    ctr =  counterX_val;             
                }

          
            frm.append('counter', counter);
            frm.append('counterX', counterX);

            if(ctr==1){
               act = document.getElementById('p_acti1');
               attachname1 = document.getElementById('attach_name1').value;
               attachid1 = document.getElementById('attach_id1').value;
               frm.append('attach_file1', act.files[0]);
               frm.append('attach_name1', attachname1);
               frm.append('attach_id1', attachid1);
                doc_id = document.getElementById('doc_id').value;
        	     frm.append('doc_id', doc_id);
            } 
            else if(ctr>=2){
        	    for(x=1;x<=ctr;x++){
        	       act = document.getElementById('p_acti'+x);
                   attachname1 = document.getElementById('attach_name'+x).value;
                   attachid1 = document.getElementById('attach_id'+x).value;
        	       frm.append('attach_file'+x, act.files[0]);
        	       frm.append('attach_name'+x, attachname1);
                   frm.append('attach_id'+x, attachid1);
        	    }
        	     doc_id = document.getElementById('doc_id').value;
        	     frm.append('doc_id', doc_id);
            } 

            var company =document.getElementById('company').value;
            frm.append('company', company);
            var doc_date =document.getElementById('doc_date').value;
            frm.append('doc_date', doc_date);
            var location =document.getElementById('location').value;
            frm.append('location', location);
            var doc_type =document.getElementById('doc_type').value;
            frm.append('doc_type', doc_type);
            var department =document.getElementById('department').value;
            frm.append('department', department);
            var subject =document.getElementById('subject').value;
            frm.append('subject', subject);
            var sender_comp =document.getElementById('sender_comp').value;
            frm.append('sender_comp', sender_comp);
            var sender_person =document.getElementById('sender_person').value;
            frm.append('sender_person', sender_person);
            var add_comp =document.getElementById('add_comp').value;
            frm.append('add_comp', add_comp);
            var add_person =document.getElementById('add_person').value;
            frm.append('add_person', add_person);
            var signatory =document.getElementById('signatory').value;
            frm.append('signatory', signatory);
            var copy_type = $("input[name='copy_type']:checked").val();
            frm.append('copy_type', copy_type);

            var confidential = $("input[name='confidential']:checked").val();
            frm.append('confidential', confidential);

            var share1 =document.getElementById('shareuser1').value;
            frm.append('share1', share1);

            var share2 =document.getElementById('shareuser2').value;
            frm.append('share2', share2);

            var share3 =document.getElementById('shareuser3').value;
            frm.append('share3', share3);

            var remarks =document.getElementById('remarks').value;
            frm.append('remarks', remarks);
       

            if(doc_date==''){
                $("#doc_date").focus();
                $("#date_msg").show();
                $("#date_msg").html("Document date field must not be empty.");
            } else if(subject==''){
                $("#subject").focus();
                $("#date_msg").hide();
                $("#subj_msg").show();
                $("#subj_msg").html("Subject field must not be empty.");
            } else if(typeof copy_type=='undefined'){
                $("#copy_type").focus();
                $("#date_msg").hide();
                $("#subj_msg").hide();
                $("#copy_msg").show();
                $("#copy_msg").html("Please choose type of copy.");
            } else if(typeof confidential=='undefined'){
                $("#confidential").focus();
                $("#date_msg").hide();
                $("#subj_msg").hide();
                $("#copy_msg").hide();
                $("#confi_msg").show();
                $("#confi_msg").html("Is this document confidential or not? Please choose.");
            }else {
                 $('#content').hide();
                document.getElementById("loader").style.display = "block";
                $.ajax({
                    type: 'POST',
                    url: "insert_record.php",
                    data: frm,
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function(output){
                        var output= output.trim();
                        if(output=='ext'){
                            alert('Error: File extension error.')
                        } else if(output=='error'){
                         alert('Error: There was an error in uploading your files.')
                        } else {
                            alert('Record successfully updated!');
                            window.location = 'viewrecord.php';
                        }
                   }
                }); 
            }
        }
    }

	$(function() {
         var ctrx = document.getElementById('counter').value
   

        if(ctrx == 0){
            var activityDiv = $('#p_activity');
        } else {
            var activityDiv = $('#p_activity1');
        }
        var ii = document.getElementById('counter').value;
        
		$('#addActivity').live('click', function() {
            ii++;
           
		    $('<div class = "acti'+ii+'"><div class="row"><div for="p_certs" class="col-lg-3"></div><div class="col-lg-3"><input type="file" name="attach_file'+ii+'" id="p_acti'+ii+'" class="btn btn-sm btn-normal " style="width:100%" ><div id="certBox'+ii+'" class="acti"></div></div><div for = "name_certs" class="col-lg-3"><input type="name" name="attach_name'+ii+'" id="attach_name'+ii+'" class="form-control" style="width:100%;height:35px;margin-bottom:5px;" placeholder="Remarks"></div><div class="col-lg-3"><a href="#" class="btn btn-primary btn-sm btn-fill" id="addActivity">+</a> || <a href="#" class="btn btn-danger btn-sm btn-fill" id="remActivity">x</a></div></div></div>').appendTo(activityDiv);
		    
               document.getElementById("counterX").value = ii;
               
                $('<input type="hidden" id="attach_id'+ii+'" name="attach_id'+ii+'" value="" />').appendTo(activityDiv);
                return false;
		});
		$('#remActivity').live('click', function() { 
            if( ii >= 2 ) {
              
                $("div").remove(".acti" + ii);
                  ii--;
            } 
            return false;
		});
    });
</script>
<script>
$(document).ready(function(){
 $("#doc_type").keyup(function(){
        $.ajax({
        type: "POST",
        url: "search-type.php",
        data:'type='+$(this).val(),
        beforeSend: function(){
          $("#doc_type").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function(data){
          $("#suggestion-type").show();
          $("#suggestion-type").html(data);
          $("#doc_type").css("background","#FFF");
        }
        });
      });

 $("#location").keyup(function(){
        $.ajax({
        type: "POST",
        url: "search-location.php",
        data:'location='+$(this).val(),
        beforeSend: function(){
          $("#location").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function(data){
          $("#suggestion-location").show();
          $("#suggestion-location").html(data);
          $("#location").css("background","#FFF");
        }
        });
      });
 });

   	function selectType(val) {
        $("#doc_type").val(val);
        $("#suggestion-type").hide();
    }

    function selectLocation(val) {
        $("#location").val(val);
        $("#suggestion-location").hide();
    }

</script>
<style type="text/css">
     /* The Modal (background) */
    .modal{
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 3000; /* Sit on top */
        padding-top: 50px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
    }


    /* Modal Content (image) */
    .modal-content {
        margin: auto;
        display: block;
        width: 45%;
        max-width: 700px;
    }

    /* lone of Modal Image */
    #lone {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 30px;
    }

    /* Add Animation */
    .modal-content, #lone {    
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
        from {-webkit-transform:scale(0)} 
        to {-webkit-transform:scale(1)}
    }

    @keyframes zoom {
        from {transform:scale(0)} 
        to {transform:scale(1)}
    }

    /* The Close Button */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }


  #resumeBox, #mapBox, #essayBox, #photoBox,.cert, .eval{
    color:red;
    font-style: italic;
    font-size:11px;
  }
 .display{
  color:blue;
  font-size:11px;
 }
 .card{
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(115, 115, 115, 0.1)!important;
        border:1px solid darkgrey;min-height:600px;max-height:5000px;margin:0px;
    }
</style>
<body>


	<?php include('navbars.php');?>
    <div id="loader" style="display:none">
        <figure class="one"></figure>
        <figure class="two">loading</figure>
    </div>
    <div id="content">    
    	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    		<div class="row">
    			<ol class="breadcrumb">
    				<li><a href="#">
    					<em class="fa fa-home"></em>
    				</a></li>
    				<li class="active"><?php echo (isset($_GET['docid']) ? 'Update Record' : 'New Record'); ?></li>
    			</ol>
    		</div><!--/.row-->
    		
    		<div class="row">
    			<div class="col-lg-12">
    				<h1 class="page-header"><?php echo (isset($_GET['docid']) ? 'Update Record' : 'Add New Record'); ?></h1>
    			</div>
    		</div><!--/.row-->
    		
    		<div class="row">
    			<div class="col-md-12">
    				<div class="panel panel-default box-shadow">
    					<div class="panel-heading">
    						<?php echo (isset($_GET['docid']) ? 'Update Record' : 'New Record'); ?>
    						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
    						<a class="pull-right  btn-primary panel-toggle" style="background:#099428;color:white" href="viewrecord.php"><em class="fa fa-eye"></em></a>
    					</div>
    					<div class="panel-body">
    						<div class="row">
    							<form style="margin:0px 50px 0px 50px" id='myForm'>                                    
    								<div class="col-lg-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Company:</label>
                                            <?php if(!empty($_GET['company'])) { ?>
                                            <span style='color:green; font-size:18px; font-weight:bold'><?php echo getInfo($con, 'company_name', 'company', 'company_id', $_GET['company']); ?></span>
                                             <input type = "hidden" name = "company" id='company' value='<?php echo $_GET['company']; ?>'>
                                            <?php } else if(isset($_GET['docid'])){ 
                                               $get_comp = mysqli_query($con, "SELECT * FROM company ORDER BY company_name ASC"); ?>
                                               <select type="text" name = "company" id="company" class="form-control" style="width:100%" value = "">
                                                <option value = "" selected>-Select Company-</option>
                                                <?php while($fetch_comp = $get_comp->fetch_array()){ ?> 

                                                    <option value="<?php echo $fetch_comp['company_id']; ?>" <?php echo (isset($_GET['docid']) ? (($fetch_comp['company_id']==$compid) ? ' selected' : '') : ''); ?>>
                                                        <?php echo $fetch_comp['company_name']; ?>
                                                    </option>
                                                <?php } ?>
                                                </select>
                                            <?php } ?>
                                        </div>      
    									<div class="form-group label-floating">
    	                                    <label class="control-label">Document Date:</label>
    	                                    <input type="date" name = "doc_date" id="doc_date" class="form-control" style="width:100%" value="<?php echo (isset($_GET['docid']) ? $fetch_details['document_date'] : ''); ?>">
                                            <div id='date_msg' class='err_msg'></div>
    	                                </div>	    
                                        <div class="form-group label-floating">
                                            <label class="control-label">Document Location:</label>
                                            <input type="text" autosuggest='off' name = "location" id="location" class="form-control" style="width:100%" value="<?php echo (isset($_GET['docid']) ? $docloc : ''); ?>"><span id="suggestion-location"></span>
                                        </div>
    	                                <?php $get_dept = mysqli_query($con, "SELECT * FROM department ORDER BY department_name ASC"); ?>
    	                                <div class="form-group label-floating ">
    	                                    <label class="control-label">Department:</label>
    	                                    
    	                                    <select type="text" name = "department" id="department" class="form-control" style="width:100%" value = "">
                                                <option value = "" selected>-Select Department-</option>
    	                                    <?php while($fetch_dept = $get_dept->fetch_array()){ ?> 

    	                                    	<option value="<?php echo $fetch_dept['department_id']; ?>" <?php echo (isset($_GET['docid']) ? (($fetch_dept['department_id']==$dept) ? ' selected' : '') : ''); ?>>
    	                                    		<?php echo $fetch_dept['department_name']; ?>
    	                                    	</option>
    	                                    <?php } ?>
    	                                    </select>
    	                                </div>
    								</div>
    								<div class="col-lg-6">
                                        <?php if(!empty($_GET['company'])) { ?>
                                        <div class="form-group label-floating" style='padding:14px'>
                                        </div> 
                                        <?php } else if (isset($_GET['docid'])) { ?>
                                         <div class="form-group label-floating" style='padding:30px'>
                                        </div>
                                        <?php } ?>
    									<div class="form-group label-floating">
    	                                    <label class="control-label">Subject:</label>
    	                                    <input type="text" autocomplete=""  name = "subject" id="subject" class="form-control" style="width:100%"  value="<?php echo (isset($_GET['docid']) ? $fetch_details['subject'] : ''); ?>" >
                                             <div id='subj_msg' class='err_msg'></div>
    	                                </div>                            
                                        <div class="form-group label-floating">
                                            <label class="control-label">Type of Document:</label>
                                            <input type="text"  autosuggest='off' name = "doc_type" id="doc_type" class="form-control" style="width:100%" value="<?php echo (isset($_GET['docid']) ? $doctype : ''); ?>">
                                            <span id="suggestion-type"></span>
                                        </div>
    	                                <div class="form-group label-floating">
    	                                    <label class="control-label">Signatory:</label>
    	                                    <input type="text" name = "signatory" id="signatory" class="form-control" style="width:100%" value="<?php echo (isset($_GET['docid']) ? $fetch_details['signatory'] : ''); ?>">
    	                                </div>	                                	
    								</div>
                                    <div class="col-lg-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Sender:</label>
                                            <input type="text" name = "sender_comp" id="sender_comp" class="form-control" style="width:100%" placeholder="Company" value="<?php echo (isset($_GET['docid']) ? $fetch_details['sender_company'] : ''); ?>">
                                             <input type="text" name = "sender_person" id="sender_person" class="form-control" style="width:100%" value="<?php echo (isset($_GET['docid']) ? $fetch_details['sender_person'] : ''); ?>" placeholder="Person">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Addressee:</label>
                                            <input type="text" name = "add_comp" id="add_comp" class="form-control" style="width:100%" value="<?php echo (isset($_GET['docid']) ? $fetch_details['addressee_company'] : ''); ?>" placeholder="Company">
                                             <input type="text" name = "add_person" id="add_person" class="form-control" style="width:100%" value="<?php echo (isset($_GET['docid']) ? $fetch_details['addressee_person'] : ''); ?>" placeholder="Person">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Type of Copy:</label>
                                            <div class="row"></div>
                                            <label class="btn btn-primary"><input type="radio" name = "copy_type" id="copy_type"  value="Original" <?php echo (isset($_GET['docid']) ? (($copytype=='Original') ? ' checked' : '') : ''); ?>> Original</label>
                                            <label class="btn btn-primary"><input type="radio" name = "copy_type" id="copy_type"  value="Copy" <?php echo (isset($_GET['docid']) ? (($copytype=='Copy') ? ' checked' : '') : ''); ?>> Copy</label>
                                            <div id='copy_msg' class='err_msg'></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Confidential?</label>
                                            <div class="row"></div>
                                            <label class="btn btn-danger"><input type="radio" name = "confidential" id="confidential"  value="Yes" <?php echo (isset($_GET['docid']) ? (($confidential=='Yes') ? ' checked' : '') : ''); ?> onclick=check_confi();> Yes</label>
                                            <label class="btn btn-danger"><input type="radio" name = "confidential" id="confidential"  value="No" <?php echo (isset($_GET['docid']) ? (($confidential=='No') ? ' checked' : '') : ''); ?> onclick=check_confi();> No</label>
                                            <div id='confi_msg' class='err_msg'></div>
                                        </div>
                                    </div>
                                    
                                     <?php if(isset($_GET['docid'])){
                                            $get = $con->query("SELECT user_id FROM shared_document WHERE document_id = '$_GET[docid]'");
                                            while($fetch_share = $get->fetch_array()){
                                                $shareid[]=$fetch_share['user_id'];
                                            }

                                            if(!empty($shareid[0])){
                                                $sh0=$shareid[0];
                                            } else {
                                                $sh0=0;
                                            }

                                            if(!empty($shareid[1])){
                                                $sh1=$shareid[1];
                                            } else {
                                                $sh1=0;
                                            }

                                            if(!empty($shareid[2])){
                                                $sh2=$shareid[2];
                                            } else {
                                                $sh2=0;
                                            }
                                            
                                     } ?>
                                    <div class="col-lg-12" id='shareUser' style='display:none'>
                                        <div class="form-group label-floating">
                                            <div style="border:3px solid #099428;padding:10px;border-radius:10px;box-shadow: -3px 4px 13px 0px #999;">
                                                <fieldset >
                                                    <legend style="color:#000">Choose three (3) users to share this Document:</legend>
                                                    <div class="col-lg-4">
                                                     <?php $user1=$con->query("SELECT user_id, fullname FROM users WHERE user_id != '$userid' ORDER BY fullname ASC") ?>
                                                       <select class="form-control" name='shareuser1' id='shareuser1'>
                                                            <option value='' selected>-Choose User-</option>
                                                            <?php while($fetch1 = $user1->fetch_array()){ ?>
                                                               <option value="<?php echo $fetch1['user_id']; ?>" <?php echo (isset($_GET['docid']) ? (($sh0==$fetch1['user_id']) ? ' selected' : '') : ''); ?>><?php echo $fetch1['fullname']; ?></option>
                                                            <?php } ?>
                                                       </select>
                                                    </div>
                                                    <div class="col-lg-4">
                                                       <?php $user1=$con->query("SELECT user_id, fullname FROM users WHERE user_id != '$userid' ORDER BY fullname ASC") ?>
                                                       <select class="form-control" name='shareuser2' id='shareuser2'>
                                                            <option value='' selected>-Choose User-</option>
                                                            <?php while($fetch1 = $user1->fetch_array()){ ?>
                                                               <option value="<?php echo $fetch1['user_id']; ?>" <?php echo (isset($_GET['docid']) ? (($sh1==$fetch1['user_id']) ? ' selected' : '') : ''); ?>><?php echo $fetch1['fullname']; ?></option>
                                                            <?php } ?>
                                                       </select>
                                                    </div>
                                                    <div class="col-lg-4">
                                                       <?php $user1=$con->query("SELECT user_id, fullname FROM users WHERE user_id != '$userid' ORDER BY fullname ASC") ?>
                                                       <select class="form-control" name='shareuser3' id='shareuser3'>
                                                            <option value='' selected>-Choose User-</option>
                                                            <?php while($fetch1 = $user1->fetch_array()){ ?>
                                                               <option value="<?php echo $fetch1['user_id']; ?>" <?php echo (isset($_GET['docid']) ? (($sh2==$fetch1['user_id']) ? ' selected' : '') : ''); ?>><?php echo $fetch1['fullname']; ?></option>
                                                            <?php } ?>
                                                       </select>
                                                   </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    
    								<div class="col-lg-12">
    									<div class="form-group label-floating">
    	                                    <label class="control-label">Remarks:</label>
    	                                    <textarea type="text" rows="20" name = "remarks" id="remarks" class="form-control" style="width:100%" ><?php echo (isset($_GET['docid']) ? $fetch_details['remarks'] : ''); ?></textarea>
    	                                </div>
    	                               <?php
    	                                    $sql2 = mysqli_query($con,"SELECT * FROM document_attach WHERE document_id = '$docid'");
    	                                    $row_num = $sql2->num_rows;
                                            $tmp_attach = $con->query("SELECT * FROM document_attach WHERE document_id = '$docid'");
                                            $rows_attach = $tmp_attach->num_rows;                               
                                            if($row_num==0) {
                                        ?>
                                <div id = "p_activity">
                                    <div class="row" >
                                        <div for="p_acti" class="col-lg-3">Attach Files:</div>
                                        <div class="col-lg-3">
                                            <input type="file" name="attach_file1" id="p_acti1" class="btn btn-sm btn-normal " style="width:100%">
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="name" name="attach_name1" id="attach_name1" class="form-control" style="width:100%;height:35px;margin-bottom:5px;" placeholder="Remarks" > 
                                            <input type = "hidden" value = "1" id = "counter" name = "counter">
                                        </div>                         
                                        <div class="col-lg-3">
                                            <a href="#" class="btn btn-primary btn-sm btn-fill" id="addActivity">+</a> || 
                                            <a href="#" class="btn btn-danger btn-sm btn-fill" id="remActivity">x</a>
                                        </div>
                                    </div>
                                      <input type = "hidden" value = "0" id = "attach_id1" name = "attach_id1">
                                    <div class = "row">
                                    	<div class = "col-lg-4"></div> 
                                        <div class = "col-lg-6">
                                        	<div id='certBox1' class='acti'></div>
                                        </div>
                                    </div>
                                </div>
                                <?php } if($row_num>0) { 
                                    $r=1;
                                    while($fetch_attach=$tmp_attach->fetch_array()) { ?>
                                    <div id = "p_activity"  >
                                    <div class="row" >
                                        <div for="p_acti" class="col-lg-3">Attach Files:</div>
                                        <div class="col-lg-3">
                                            <input type="file" name="attach_file<?php echo $r; ?>" id="p_acti<?php echo $r; ?>" class="btn btn-sm btn-normal " style="width:100%" >
                                                   <?php if(!empty($fetch_attach['attach_file'])){
                                                       $res = explode(".",$fetch_attach['attach_file']);
                                                       $ext_resume = $res[1]; 
                                                      if($ext_resume == 'png' || $ext_resume == 'jpg' || $ext_resume == 'jpeg' || $ext_resume == 'JPG' || $ext_resume == 'JPEG' || $ext_resume == 'PNG'){  ?>
                                                     <a name="upload/<?php echo $fetch_attach['attach_file']; ?>" style = "cursor:pointer;margin-left:10px"  id="bone<?php echo $r; ?>">
                                                      <?php echo (empty($docid) ? '' : $fetch_attach['attach_file']); ?>
                                                    </a> <a href="?deleteattach&attid=<?php echo $fetch_attach['attach_id']; ?>&docid=<?php echo $_GET['docid']; ?>" onclick="return confirm('Are you sure?');"  style="color:red; text-decoration: none">&nbsp<span class="fa fa-times"></span></a>

                                                    <div onclick="closeModal()">    
                                                        <div id="cone<?php echo $r; ?>" class="modal">
                                                            <span class="close" onclick="closeModal()">&times;</span>
                                                            <img class="modal-content" id="mone<?php echo $r; ?>">
                                                            <div id="lone<?php echo $r; ?>"></div>
                                                        </div>
                                                    </div>    
                                                    <?php } else { ?>
                                                        <a href="upload/<?php echo $fetch_attach['attach_file']; ?>"  class='display'>
                                                      <?php echo (empty($docid) ? '' : $fetch_attach['attach_file']); ?>
                                                       </a>
                                                    <?php } 
                                                    } ?>
                                                                                                   


                                                   <input type='hidden' name='res_ext' id = 'res_ext' value="<?php echo $ext_resume; ?>">
                                               
                                            
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="name" name="attach_name<?php echo $r; ?>" id="attach_name<?php echo $r; ?>" class="form-control" style="width:100%;height:35px;margin-bottom:5px;" value="<?php echo $fetch_attach['attach_remarks']; ?>" > 
                                        </div>                                
                                        <div class="col-lg-3">
                                            <a href="#" class="btn btn-primary btn-sm btn-fill" id="addActivity">+</a> || 
                                            <a href="#" class="btn btn-danger btn-sm btn-fill" id="remActivity">x</a>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-lg-4"></div>
                                        <div class = "col-lg-6">
                                            <div id='certBox1' class='acti'></div>
                                        </div>
                                    </div>
                                </div>
                                <input type = "hidden" value = "<?php echo $fetch_attach['attach_id']; ?>" id = "attach_id<?php echo $r; ?>" name = "attach_id<?php echo $r; ?>">
                                <input type = "hidden" value = "<?php echo $rows_attach; ?>" id = "counter" name = "counter">
                                    <?php
                                    $r++; } ?>
                                <?php } ?>
                                <div id = "p_activity1" >
                                </div>
                                <input type = "hidden" name = "counterX" id='counterX'>
    								</div>
    								<div class="col-lg-12">
    									<hr>
    									<input type="button"  id = "submitButton" value="<?php echo (isset($_GET['docid']) ? 'Save Changes' : 'Save'); ?>" name = "save_data" class=" btn btn-md btn-success" onclick='showFileSize();'style="background:#099428;width:100%"> 
    								</div>                                        
    								<?php if(!empty($docid)) { ?>
    									<input type='hidden' value='<?php echo $docid; ?>' name='doc_id' id='doc_id'>
    								<?php } else { ?>
                                    <input type='hidden' value='0' name='doc_id' id='doc_id'>
                                    <?php } ?>
    							</form>
    						</div>
    						<div  class="canvas-wrapper">						
    																			
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>	
    </div>
    <!--/.main-->
	
</body>

</html>
<script>   
    function check_confi(){
       var confi = $('input[name=confidential]:checked', '#myForm').val();
       if(confi=='Yes'){
        $('#shareUser').show();
       } else {
        $('#shareUser').hide();
       }
    }
</script>

<script type="text/javascript">
    function closeModal() {
      
         var count=document.getElementById('counter').value;
      for(var a=1;a<=count;a++){
      document.getElementById('cone'+a).style.display = "none";
    }

    }

    var res_ext = document.getElementById('res_ext').value;

if(res_ext =='jpg' || res_ext =='png' || res_ext =='jpeg' || res_ext =='JPG' || res_ext =='PNG' || res_ext =='JPEG' ){
      var count=document.getElementById('counter').value;
      for(var a=1;a<=count;a++){
          var modal = document.getElementById('cone'+a);


          var img = document.getElementById('bone'+a);
          var modalImg = document.getElementById("mone"+a);
          var captionText = document.getElementById("lone"+a);
          img.onclick = function(){

              modal.style.display = "block";
              modalImg.src = this.name;
              captionText.innerHTML = this.title;
          }

         
          var span = document.getElementsByClassName("close")[0];

         
          span.onclick = function() { 
              modal.style.display = "none";
          }
     }
}
</script>
