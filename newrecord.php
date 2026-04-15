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
$doc_id = $_GET['docid'] ?? 0;

$attachments = [];

    $sql = mysqli_query($con, "
        SELECT attach_id, attach_file, attach_remarks
        FROM document_attach
        WHERE document_id = '$doc_id'
    ");

    while ($row = mysqli_fetch_assoc($sql)) {
        $attachments[] = [
            "id" => $row['attach_id'],
            "name" => $row['attach_file'],
            "remarks" => $row['attach_remarks']
        ];
    }
?>
<link href="css/newrecord.css" rel="stylesheet">
<script src="js/jquery-1.12.4.js"></script>
<script src="js/bootstrap.min.js"></script> 
<script type="text/javascript" src="js/jquery.js"></script> 
<script>
function showToast(message, type = "success") {
    let toast = document.getElementById("toast");

    // if (!toast) return console.error("Toast element not found!");

    // normalize type (optional safety)
    if (type === "added") type = "success";
    if (type === "updated") type = "updated";

    toast.className = "toast " + type;
    toast.innerText = message;

    // restart animation
    void toast.offsetWidth;

    toast.style.opacity = "1";
    toast.style.transform = "translateX(-50%) translateY(0)";

    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateX(-50%) translateY(-20px)";
    }, 2500);
}

function showFileSize() {

    if (!window.FileReader) {
        alert("File API not supported");
        return;
    }

    let frm = new FormData();

        let seen = new Set();

        document.querySelectorAll(".row").forEach(row => {

            let fileInput = row.querySelector(".fileReplace");
            let remarkInput = row.querySelector(".attach-name");
            let existingIdInput = row.querySelector("input[name='existing_attach_id[]']");
            let keepInput = row.querySelector("input[name^='existing_keep']");

            let remark = remarkInput ? remarkInput.value : "";

            // =========================
            // EXISTING FILE
            // =========================
            if (existingIdInput) {

                let id = existingIdInput.value;

                frm.append("existing_attach_id[]", id);
                frm.append(`existing_keep[${id}]`, keepInput.value);
                frm.append(`attach_name_existing[${id}]`, remark);

                // ✅ if user selected replacement file
                if (fileInput && fileInput.files.length > 0) {
                    frm.append(`attach_file_existing[${id}]`, fileInput.files[0]);
                }

            } else {

                // =========================
                // NEW FILE
                // =========================
                if (fileInput && fileInput.files.length > 0) {

                    let file = fileInput.files[0];

                    let key = file.name + file.size + file.lastModified;
                    if (seen.has(key)) return;
                    seen.add(key);

                    frm.append("attach_file[]", file);
                    frm.append("attach_name[]", remark);
                }
            }
        });

    // =========================
    // BASIC DATA
    // =========================
    frm.append("doc_id", document.getElementById("doc_id").value);
    frm.append("company", document.getElementById("company").value);
    frm.append("doc_date", document.getElementById("doc_date").value);
    frm.append("location", document.getElementById("location").value);
    frm.append("doc_type", document.getElementById("doc_type").value);
    frm.append("department", document.getElementById("department").value);
    frm.append("subject", document.getElementById("subject").value);
    frm.append("sender_comp", document.getElementById("sender_comp").value);
    frm.append("sender_person", document.getElementById("sender_person").value);
    frm.append("add_comp", document.getElementById("add_comp").value);
    frm.append("add_person", document.getElementById("add_person").value);
    frm.append("signatory", document.getElementById("signatory").value);
    frm.append("remarks", document.getElementById("remarks").value);

    let copy_type = $("input[name='copy_type']:checked").val();
    let confidential = $("input[name='confidential']:checked").val();

    frm.append("copy_type", copy_type || "");
    frm.append("confidential", confidential || "");

    frm.append("share1", document.getElementById("shareuser1").value);
    frm.append("share2", document.getElementById("shareuser2").value);
    frm.append("share3", document.getElementById("shareuser3").value);

    // =========================
    // VALIDATION
    // =========================
    if (!frm.get("doc_date")) return alert("Document date required");
    if (!frm.get("subject")) return alert("Subject required");
    if (!copy_type) return alert("Select copy type");
    if (!confidential) return alert("Select confidential option");

    // =========================
    // AJAX
    // =========================
    $("#content").hide();
    document.getElementById("loader").style.display = "block";

    $.ajax({
        type: "POST",
        url: "insert_record.php",
        data: frm,
        contentType: false,
        processData: false,
        cache: false,
        success: function (output) {

            output = output.trim();
            let parts = output.split("|");
            let status = parts[0];
            let id = parts[1];

            if (status === "added") {

                showToast("Record successfully added!", "added");

                setTimeout(() => {
                    window.location = "view_details.php?id=" + id;
                }, 3000);

            } 
            else if (status === "updated") {

                showToast("Record successfully updated!", "updated");

                setTimeout(() => {
                    window.location = "view_details.php?id=" + id;
                }, 3000);

            } 
            else {

                showToast("Unknown response: " + output, "error");
                console.log(output);
            }
        }
    });
}
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

    .to-delete input,
    .to-delete .attach-name {
        background: #f1f1f1;
        pointer-events: none;
    }

    .row.deleted .col-lg-6,
    .row.deleted .col-lg-5 {
        filter: grayscale(100%);
        opacity: 0.5;
    }

    .row.deleted .button-col {
        filter: none;
        opacity: 1;
    }

    .toast {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(-20px);
        background: #333;
        color: #fff;
        padding: 12px 18px;
        border-radius: 6px;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
        z-index: 9999;
        font-size: 14px;
        min-width: 200px;
        text-align: center;
    }

    .toast.success {
        background: #28a745; /* green */
    }

    .toast.updated {
        background: #ffc107; /* yellow */
        color: #000;
    }

    .toast.error {
        background: #dc3545;
    }
</style>
<body>
<div id="toast" class="toast"></div>
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
    	                                    
    	                                    <select type="text" name = "department" id="department" onchange="checksubject()" class="form-control" style="width:100%" value = "">
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
    	                                    <label for="item_name" class="control-label">Subject:</label>
    	                                    <input type="text" autocomplete="" onkeyup="checksubject()"  name = "subject" id="subject" class="form-control" style="width:100%"  value="<?php echo (isset($_GET['docid']) ? $fetch_details['subject'] : ''); ?>" >
                                             <div id='subj_msg' class='err_msg'></div>
                                             <span id="subject-check"></span>
                                             <span id="subject_msg" class='img-check'></span>
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
                                    </div>
                                    <div class="col-lg-12">
                                        <label style="font-weight:bold; display:block; margin-bottom:5px;">
                                            Attach Files:
                                        </label>
                                        <div id="dropArea"
                                            style="
                                                border: 3px dashed #999;
                                                padding: 60px 20px;
                                                text-align: center;
                                                cursor: pointer;
                                                margin-bottom: 15px;
                                                border-radius: 10px;
                                                background-color: #fafafa;
                                                font-size: 18px;
                                                font-weight: bold;
                                                color: #555;
                                                transition: 0.2s;
                                            "
                                            onmouseover="this.style.backgroundColor='#f0f0f0'"
                                            onmouseout="this.style.backgroundColor='#fafafa'">

                                            📁 Click or Drag & Drop files here
                                            <br>
                                            <small style="font-weight: normal; font-size: 14px; color: #888;">
                                                You can drop multiple files
                                            </small>
                                        </div>
                                        <div id="fileList"></div>
                                    </div>
                                
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

    var res_ext = document.getElementById('res_ext')?.value || '';

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
<script> 
    function checksubject() {
        var subject = document.getElementById("subject").value;
        var department = document.getElementById("department").value;
        $.ajax({
            type: "POST",
            url: "search_subject.php",
            data:"subject="+subject+"&department="+department,
            success: function(output){
                var output= output.trim();
                if(output=='existing') {
                    $("#subject-check").show();
                    $("#subject-check").html("Warning: Subject is already existing!");
                    $('input[type="button"]').attr('disabled','disabled');
                    $('input[type="submit"]').attr('disabled','disabled');
                    $("#subject-check").css("color","#f50000");
                }else{
                    $("#subject-check").hide();
                    $('input[type="button"]').removeAttr('disabled');
                    $('input[type="submit"]').removeAttr('disabled');
                }

                if(subject==''){
                    $("#subject-check").hide();
                    $('input[type="button"]').removeAttr('disabled');
                    $('input[type="submit"]').removeAttr('disabled');
                }
            }
        })
    }
</script>
<script type="text/javascript">
    function validateFiles() {
        hasFileError = false;
        fileErrorMsg.innerHTML = "";
        submitButton.disabled = false;

        document.querySelectorAll(".row").forEach(row => {

            let fileInput = row.querySelector(".fileReplace");

            if (fileInput && fileInput.files && fileInput.files.length > 0) {
                let file = fileInput.files[0];

                // 🔴 SIZE CHECK (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    hasFileError = true;
                    fileErrorMsg.innerHTML = "This file exceed 10MB limit.";
                }
            }
        });

        if (hasFileError) {
            submitButton.disabled = true;
        }
    }
</script>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function () {

    // =========================
    // GLOBAL VARIABLES
    // =========================
    let submitButton = document.getElementById("submitButton");
    let fileErrorMsg = document.getElementById("fileErrorMsg");
    let hasFileError = false;

    function isGenericFileName(name) {
        if (!name) return true;

        name = name.toLowerCase();
        name = name.replace(/\.[^/.]+$/, "");
        name = name.replace(/[\s_\-()]/g, "");

        return /^img\d*$/.test(name);
    }

    let dropArea = document.getElementById("dropArea");
    let fileList = document.getElementById("fileList");

    if (!dropArea || !fileList) return;

    // =========================
    // FILE PICKER
    // =========================
    let hiddenInput = document.createElement("input");
    hiddenInput.type = "file";
    hiddenInput.multiple = true;
    hiddenInput.style.display = "none";
    document.body.appendChild(hiddenInput);

    hiddenInput.addEventListener("change", function () {
        handleFiles(this.files);
        this.value = "";
    });

    dropArea.addEventListener("click", () => hiddenInput.click());

    dropArea.addEventListener("dragover", e => {
        e.preventDefault();
        dropArea.style.background = "#f1f1f1";
    });

    dropArea.addEventListener("dragleave", () => {
        dropArea.style.background = "#fff";
    });

    dropArea.addEventListener("drop", e => {
        e.preventDefault();
        dropArea.style.background = "#fff";
        handleFiles(e.dataTransfer.files);
    });

    let addedFiles = new Set();

    function handleFiles(files) {
        Array.from(files).forEach(file => {

            let key = file.name + file.size + file.lastModified;

            if (addedFiles.has(key)) return;

            addedFiles.add(key);

            addNewRow(file);
        });

        validateFiles();
    }

    // =========================
    // VALIDATION (UNCHANGED LOGIC)
    // =========================
        function validateFiles() {
            hasFileError = false;

            if (fileErrorMsg) fileErrorMsg.innerHTML = "";
            if (submitButton) submitButton.disabled = false;

            document.querySelectorAll(".row").forEach(row => {

                let fileInput = row.querySelector(".fileReplace");
                let errorBox = row.querySelector(".file-row-error");

                if (errorBox) errorBox.innerHTML = "";
                let box = row.querySelector(".file-box");

                if (box) {
                    box.style.border = "";
                    box.style.background = "";
                }

                // ❗ IMPORTANT FIX: check ALL files, not only [0]
                if (fileInput && fileInput.files && fileInput.files.length > 0) {

                    Array.from(fileInput.files).forEach(file => {

                        if (file.size > 10 * 1024 * 1024) {

                            hasFileError = true;

                            if (errorBox) {
                                errorBox.innerHTML +=
                                    `❌ "${file.name}" exceeds 10MB (${(file.size / 1024 / 1024).toFixed(2)} MB)<br>`;
                            }
                        }
                    });
                }
            });

            if (hasFileError && submitButton) {
                submitButton.disabled = true;
            }
        }

    // =========================
    // NEW FILE ROW
    // =========================
    function addNewRow(file) {

        let base = file.name.replace(/\.[^/.]+$/, "");
        let autoRemarks = isGenericFileName(base) ? "" : base;
        let row = document.createElement("div");
        row.className = "row";
        row.style.marginBottom = "5px";

        row.innerHTML = `
            <div class="col-lg-6">
                <button type="button" class="btn btn-primary btn-sm chooseFile">Choose File</button>
                <span class="fileLabel">${file.name}</span>

                <input type="file" name="attach_file[]" class="fileReplace" style="display:none;">
            <div class="file-row-error text-danger small"></div>
            </div>

            <div class="col-lg-5">
                <input type="text" name="attach_name[]" 
                       class="form-control form-control-sm attach-name" 
                       value="${autoRemarks}">
            </div>

            <div class="col-lg-1">
                <button type="button" class="btn btn-danger btn-sm removeBtn">X</button>
            </div>
        `;

        let fileInput = row.querySelector(".fileReplace");
        let label = row.querySelector(".fileLabel");
        let btn = row.querySelector(".chooseFile");
        let textInput = row.querySelector(".attach-name");

        // =========================
        // FIX: SAFE CHECK (NO CRASH)
        // =========================
        if (btn) {
            btn.onclick = () => fileInput.click();
        }

        let dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;

        row.dataset.fileKey = file.name + file.size + file.lastModified;

        fileInput.addEventListener("change", function () {
            let f = this.files[0];

            if (f) {
                label.textContent = f.name;

                let base = f.name.replace(/\.[^/.]+$/, "");
                if (!isGenericFileName(base)) {
                    textInput.value = base;
                } else {
                    textInput.value = ""; // ✅ FORCE CLEAR for img, img123, etc.
                }
            }

            validateFiles();
        });

        row.querySelector(".removeBtn").onclick = () => {
            row.remove();
            validateFiles();
        };

        fileList.appendChild(row);
    }

    // =========================
    // EXISTING FILES
    // =========================
    let existingFiles = <?= json_encode($attachments ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

    if (Array.isArray(existingFiles)) {
        existingFiles.forEach(f => addExistingRow(f));
    }

    function addExistingRow(file) {

        if (!file || !file.id) return;

        let filename = file.name || "";
        let remarks = file.remarks || "";

        let row = document.createElement("div");
        row.className = "row";
        row.style.marginBottom = "5px";

        row.innerHTML = `
            <div class="col-lg-6">
                <button type="button" class="btn btn-primary btn-sm chooseFile">Choose File</button>
                <span class="fileLabel">${filename}</span>

                <input type="file" name="attach_file[]" class="fileReplace" style="display:none;">
                <div class="file-row-error text-danger small"></div>

                <input type="hidden" name="existing_attach_id[]" value="${file.id}">
                <input type="hidden" name="existing_keep[${file.id}]" value="1">
            </div>

            <div class="col-lg-5">
                <input type="text"
                       name="attach_name[${file.id}]"
                       value="${remarks}"
                       class="form-control form-control-sm attach-name">
            </div>

            <div class="col-lg-1 button-col">
                <button type="button" class="btn btn-danger btn-sm btn-delete">X</button>
            </div>
        `;

        let fileInput = row.querySelector(".fileReplace");
        let chooseBtn = row.querySelector(".chooseFile");
        let label = row.querySelector(".fileLabel");
        let textInput = row.querySelector(".attach-name");

        let keepInput = row.querySelector(`input[name="existing_keep[${file.id}]"]`);
        let deleteBtn = row.querySelector(".btn-delete");

        // =========================
        // FIX: SAFE CHECK (NO CRASH)
        // =========================
        if (chooseBtn) {
            chooseBtn.onclick = () => fileInput.click();
        }

        fileInput.addEventListener("change", function () {
            let f = this.files[0];

            if (f) {
                label.textContent = f.name;

                let base = f.name.replace(/\.[^/.]+$/, "");

                if (isGenericFileName(base)) {
                    textInput.value = ""; // 🚨 FORCE CLEAR IF IMG FILE
                } else {
                    textInput.value = base;
                }
            }

            validateFiles();
        });

        deleteBtn.onclick = () => {

            if (keepInput.value === "1") {

                keepInput.value = "0";

                row.classList.add("deleted");

                deleteBtn.textContent = "↺";
                deleteBtn.classList.remove("btn-danger");
                deleteBtn.classList.add("btn-success");

            } else {

                keepInput.value = "1";

                row.classList.remove("deleted");

                deleteBtn.textContent = "X";
                deleteBtn.classList.remove("btn-success");
                deleteBtn.classList.add("btn-danger");
            }

            validateFiles();
        };

        fileList.appendChild(row);
    }

    validateFiles();

});
</script>
