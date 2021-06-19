<?php 
include('header.php'); 
include('functions/functions.php');

if(isset($_GET['docid'])) { 
	$docid=$_GET['docid'];
	$get_details = $con->query("SELECT * FROM document_info WHERE document_id = '$docid'");
	$fetch_details = $get_details->fetch_array();
	$typeid=$fetch_details['type_id'];
    $locationid=$fetch_details['location_id'];

	$dept=$fetch_details['department_id'];
	$doctype=getInfo($con, 'type_name', 'document_type', 'type_id', $typeid);
    $docloc=getInfo($con, 'location_name', 'document_location', 'location_id', $locationid);
}
else $docid=NULL; 
?>
<script type="text/javascript" src="js/jquery.js"></script>  
<style>
.acti, .eval{
	    color:#f79393;
	    font-style: italic;
	    font-size:11px;
  	}
#name-type{float:left;list-style:none;margin-top:-3px;padding:0;width:350px;position: absolute; z-index:100;}
#name-type li:hover {
    background: #28422c;
    cursor: pointer;
    font-weight: bold;
    color: white;
}
#name-type li {
    padding: 5px;
    background-color: #b5e8bb;
    border-bottom: #bbb9b9 1px solid;
    border-radius: 10px;
}
#search-type{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
#search-location{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
.search{
  color:green;
  font-weight: bold;
}
.remove_filter{
  color:red;
  font-style: italic;
  font-size:12px;
}
</style>
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
                if(fileact.size > 2000000){
                document.getElementById("certBox1").innerHTML="Error: Picture size is too big. Max file size is 2mb.";
                counter_error++;
                }
            }
        } 
        else if(ctr>=2){
    	    for(x=1;x<=ctr;x++){
    	        act = document.getElementById('p_acti'+x);
    	        fileact = act.files[0];
    	        if(typeof fileact !== 'undefined'){
    	         	if(fileact.size > 2000000){
    		          document.getElementById('certBox'+x).innerHTML="Error: Picture size is too big. Max file size is 2mb.";
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
            var addressee =document.getElementById('addressee').value;
            frm.append('addressee', addressee);
            var signatory =document.getElementById('signatory').value;
            frm.append('signatory', signatory);
            var remarks =document.getElementById('remarks').value;
            frm.append('remarks', remarks);
            
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
                    //alert(output);
                    alert('New record successfully added!');
                    window.location = 'viewrecord.php';
                   }
                   // window.open('tmp_data.php', '_blank', 'width=600,height=500', 'fullscreen=yes,resizable=no');
                  //window.location = 'viewrecord.php';
               }
            });    
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
                ii--;
                $("div").remove(".acti" + ii);
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
<body>
	<?php include('navbars.php');?>
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
							<form style="margin:0px 50px 0px 50px">

                                
								<div class="col-lg-6">
									<div class="form-group label-floating">
	                                    <label class="control-label">Document Date:</label>
	                                    <input type="date" name = "doc_date" id="doc_date" class="form-control" style="width:100%" value="<?php echo (isset($_GET['docid']) ? $fetch_details['document_date'] : ''); ?>">
	                                </div>	    
                                    <div class="form-group label-floating">
                                        <label class="control-label">Document Location:</label>
                                        <input type="text" name = "location" id="location" class="form-control" style="width:100%" value="<?php echo (isset($_GET['docid']) ? $docloc : ''); ?>"><span id="suggestion-location"></span>
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
									<div class="form-group label-floating">
	                                    <label class="control-label">Subject:</label>
	                                    <input type="text" name = "subject" id="subject" class="form-control" style="width:100%"  value="<?php echo (isset($_GET['docid']) ? $fetch_details['subject'] : ''); ?>" >
	                                </div>                            
                                    <div class="form-group label-floating">
                                        <label class="control-label">Type of Document:</label>
                                        <input type="text" name = "doc_type" id="doc_type" class="form-control" style="width:100%" value="<?php echo (isset($_GET['docid']) ? $doctype : ''); ?>">
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
                                        <input type="text" name = "sen_com" id="sen_com" class="form-control" style="width:100%" value="" placeholder="Company">
                                         <input type="text" name = "sen_per" id="sen_per" class="form-control" style="width:100%" value="" placeholder="Person">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Addressee:</label>
                                        <input type="text" name = "add_com" id="add_com" class="form-control" style="width:100%" value="" placeholder="Company">
                                         <input type="text" name = "add_per" id="add_per" class="form-control" style="width:100%" value="" placeholder="Person">
                                    </div>
                                </div>
                                
								<div class="col-lg-12">
									<div class="form-group label-floating">
	                                    <label class="control-label">Remarks:</label>
	                                    <textarea type="text" rows="5" name = "remarks" id="remarks" class="form-control" style="width:100%" ><?php echo (isset($_GET['docid']) ? $fetch_details['remarks'] : ''); ?></textarea>
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
                                        <!--<div id='certBox1' class='acti'></div>-->
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
									<input type="button" value="<?php echo (isset($_GET['docid']) ? 'Save Changes' : 'Save'); ?>" name = "save_data" class=" btn btn-md btn-success" onclick='showFileSize();'style="background:#099428;width:100%"> 
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
	</div>	<!--/.main-->
	
</body>
<?php //include('scripts.php');?>
</html>