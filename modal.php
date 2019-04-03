<!-- Add New User -->
<script>
function addUser(){
    var  fullname = document.getElementById("fullname").value;
    var  username = document.getElementById("username").value;
    var  usertype = document.getElementById("usertype").value;
    var  status = document.getElementById("status").value;
    if(fullname == "" ){
        document.getElementById("fullname").focus();
        document.getElementById("errorBox").innerHTML="Error: Enter Fullname";
        return false;
    } 
    else if(username == "" ){
        document.getElementById("username").focus();
        document.getElementById("errorBox1").innerHTML="Error: Enter Username";
        return false;
    } 
    else if(usertype == "" ){
        document.getElementById("usertype").focus();
        document.getElementById("errorBox3").innerHTML="Error: Select Usertype";
        return false;
    }  
    else if(status == "" ){
        document.getElementById("status").focus();
        document.getElementById("errorBox4").innerHTML="Error: Select Status";
        return false;
    }
    else {
        var data = $("#add-user").serialize();
        $.ajax({
             data: data,
             type: "post",
             url: "add_user.php",
             success: function(output){
                var output = output.trim()
                if(output == "Invalid"){
                    $("#user_msg").show();
                }
                else if(output == "Success") {
                    alert('User successfully registered!');
                    window.location = 'user.php';
                }
            }
        });
    }
}
function Validate() {
   
   var data = $("#change-pass").serialize();

   $.ajax({
         data: data,
         type: "post",
         url: "change_pass.php",
         success: function(output){
          /*document.location='uploadfiles.php?id='+output;*/
         var output = output.trim()
         //alert(output);
         if(output == "Not"){
            $("#cpass_msg").show();
         } else if(output == "Old"){
            $("#oldpass_msg").show();
         } else if(output == "Success"){
            alert('Password successfully changed!');
            window.location = 'dashboard.php';
         }
        //  document.location='dashboard.php';
       }
    });
}
function depAdd() {
   
   var data = $("#add-dep").serialize();

   $.ajax({
        data: data,
        type: "post",
        url: "add_department.php",
        success: function(output){
            alert('Department successfully Added!');
            window.location = 'department.php';
       }
    });
}
function compAdd() {
   
   var data = $("#add-comp").serialize();

   $.ajax({
        data: data,
        type: "post",
        url: "add_company.php",
        success: function(output){
            alert('Company successfully Added!');
            window.location = 'company.php';
       }
    });
}
</script>
<style>
    #errorBox{
        color:red;
        font-size:12px;
        font-style:italic;
    }
    #errorBox1{
        color:red;
        font-size:12px;
        font-style:italic;
    }
    #errorBox3{
        color:red;
        font-size:12px;
        font-style:italic;
    }
    #errorBox4{
        color:red;
        font-size:12px;
        font-style:italic;
    }
</style>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add New User</h4>
			</div>
			<div class="modal-body">
			<form method = "POST" name = "adduser" id = "add-user">
                <div class="form-group label-floating hr_nomarg2">
                    <label class="control-label">Full Name</label>
                    <input type="text" id = "fullname" name = "fullname" class="form-control" required>
                    <div id="errorBox"></div>
                </div>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="control-label">Username</label>
                    <input type="text" name="username" id = "username" class="form-control" autocomplete="off" required>
                    <div id="user_msg" style = "display:none; width:100%;margin-top:0px;text-align:center;color:red;">
                        <h6 style="color:red">Username Already Taken!</h6>
                    </div>
                    <div id="errorBox1"></div>
                    <span id="suggestion-username"></span>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group label-floating hr_nomarg2">
                            <label class="">Usertype</label>
                            <select class="form-control" id = "usertype" name = "usertype" required>
                                <?php 
                                    $sql = mysqli_query($con,"SELECT * FROM usertype ORDER BY usertype_name DESC");
                                    while($row = mysqli_fetch_array($sql)){
                                ?>
                                <option value="<?php echo $row['usertype_id']?>"><?php echo $row["usertype_name"]?></option>
                                <?php } ?>
                            </select>
                            <div id="errorBox3"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group label-floating hr_nomarg2">
                            <label class="">Status</label>
                            <select class="form-control" id = "status" name = "status" required>
                                <option value = "Active">Active</option>
                                <option value = "Inactive">Inactive</option>
                            </select>
                            <div id="errorBox4"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onClick="addUser()">Save changes</button>
				</div>
			</form>
			</div>			
		</div>
	</div>
</div>

<!-- CHANGE PASSWORD -->
<div class="modal fade " tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="settings">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-color" >
                <h5 class="modal-title" id="addnewuser_modal">
                    <span class="fa fa-lock"></span>
                    <strong>Change Password</strong>
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close" style="color:#fff">
                    <span aria-hidden="true">&times;</span>
                </button>
                </h5>                
            </div>
            <div class="modal-body">
                <!-- <h4><strong>Change Password</strong></h4>
                <hr> -->
                <form method = "POST" id="change-pass">
                    <div class="form-group label-floating hr_nomarg2">
                        <label class="control-label">Current Password</label>
                        <input type="password" name = "old_password" id="old_password" class="form-control" required>
                         <div id="oldpass_msg" style = "display:none; width:100%;margin-top:0px;text-align:center;color:red;">
                            <h6 style="color:red">Old Password Incorrect!</h6>
                        </div>
                    </div>
                    <br>
                    <div class="form-group label-floating hr_nomarg2">
                        <label class="control-label">New Password</label>
                        <input type="password" id="new_password" name = "new_password" class="form-control password" required>
                    </div>
                    <div class="form-group label-floating hr_nomarg2">
                        <label class="control-label">Confirm Password</label>
                        <input type="password" name='confirm_password' id="confirm_password" class="form-control confirm_password"  required>
                     <!--onchange = "val_cpass()"   <div id="cpass_msg" style = "display:none; width:100%;margin-top:0px;text-align:center;color:red;">
                            <h6 style="color:red">Confirm Password not Match!</h6>
                        </div> -->
                        <div id="cpass_msg" style = "display:none; width:100%;margin-top:0px;text-align:center;color:red;">
                            <h6 style="color:red">Confirm Password not Match!</h6>
                        </div>
                    </div>
                    <div class="modal-footer" data-background-color="blue">
                        <button type="button" class="btn btn-info"  onclick="return Validate()">Save Changes</button>
                    </div>
                    <input type='hidden' name='userid' value='<?php echo $userid; ?>'>
                </form>
            </div>
        </div>
    </div>
</div>

<!--ADD DEPARTMENT-->

<div class="modal fade" id="mdl_department" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Department</h4>
            </div>
            <div class="modal-body">
            <form method = "POST" name = "add-dep" id = "add-dep">
                <div class="form-group label-floating hr_nomarg2">
                    <label class="control-label">Department Name</label>
                    <input type="text" id = "department_name" name = "department_name" class="form-control" required>
                    <div id="errorBox"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="return depAdd()">Save changes</button>
                </div>
            </form>
            </div>          
        </div>
    </div>
</div>

<!--ADD COMPANY-->

<div class="modal fade" id="mdl_company" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Company</h4>
            </div>
            <div class="modal-body">
            <form method = "POST" name = "add-comp" id = "add-comp">
                <div class="form-group label-floating hr_nomarg2">
                    <label class="control-label">Company Name</label>
                    <input type="text" id = "company_name" name = "company_name" class="form-control" required>
                    <div id="errorBox"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="return compAdd()">Save changes</button>
                </div>
            </form>
            </div>          
        </div>
    </div>
</div>

<!--SEARCH RECORD-->

<div class="modal fade" id="mdl_searchRecord" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Search Record</h4>
            </div>
            <div class="modal-body">
            <form method='POST'>
             
                <label class="">Document Date</label>
                <div class="row">                    
                    <div class="col-md-6">
                        <div class="form-group label-floating hr_nomarg2">
                            <input type='date' class="form-control" id = "doc_from" name = "doc_from" placeholder="From">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group label-floating hr_nomarg2">
                            <input type='date' class="form-control" id = "doc_to" name = "doc_to" placeholder="To">
                        </div>
                    </div>
                </div>   
                <?php $get_company = mysqli_query($con, "SELECT * FROM company ORDER BY company_name ASC"); ?>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Company:</label>
                    <select class="form-control" name = "comp" >
                        <option value = "" selected>-Select Company-</option>
                         <?php while($fetch_company = $get_company->fetch_array()){ ?> 
                        <option value = "<?php echo $fetch_company['company_id']; ?>"><?php echo $fetch_company['company_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <?php $get_location = mysqli_query($con, "SELECT * FROM document_location ORDER BY location_name ASC"); ?>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Document Location:</label>
                    <select class="form-control" name = "docloc" >
                        <option value = "" selected>-Select Location-</option>
                         <?php while($fetch_location = $get_location->fetch_array()){ ?> 
                        <option value = "<?php echo $fetch_location['location_id']; ?>"><?php echo $fetch_location['location_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php $get_type = mysqli_query($con, "SELECT * FROM document_type ORDER BY type_name ASC"); ?>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Document Type:</label>
                    <select class="form-control" name = "doctype" >
                        <option value = "" selected>-Select Document Type-</option>
                         <?php while($fetch_type = $get_type->fetch_array()){ ?> 
                        <option value = "<?php echo $fetch_type['type_id']; ?>"><?php echo $fetch_type['type_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php $getdept = mysqli_query($con, "SELECT * FROM department ORDER BY department_name ASC"); ?>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Department</label>
                    <select class="form-control" name = "dept" >
                         <option value = "" selected>-Select Department-</option>
                         <?php while($fetchdept = $getdept->fetch_array()){ ?> 
                        <option value = "<?php echo $fetchdept['department_id']; ?>"><?php echo $fetchdept['department_name']; ?></option>
                        <?php } ?>
                    </select>
                    <div id="errorBox3"></div>
                </div>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Subject</label>
                    <input class="form-control" name = "subj" >
                   
                </div>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Signatory</label>
                    <input class="form-control" name = "sign">
                </div>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Sender Information</label>
                    <input class="form-control" name = "send">
                </div>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Addressee Information</label>
                    <input class="form-control" name = "add">
                </div>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Type of Copy:</label>
                    <input type='radio' name = "copytype" value='Original'> Original
                    <input type='radio' name = "copytype" value='Copy'> Copy
                </div>
                 <div class="form-group label-floating hr_nomarg2">
                    <label class="">Confidential:</label>
                    <input type='radio' name = "confidential" value='Yes'> Yes
                    <input type='radio' name = "confidential" value='No'> No
                </div>
                 <div class="form-group label-floating hr_nomarg2">
                    <label class="">Filed Via:</label>
                    <input type='radio' name = "via" value='1'> Email
                    <input type='radio' name = "via" value='2'> Encoded
                </div>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Remarks</label>
                    <input class="form-control" name = "notes" >
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value='Submit' name='search_doc'>
                </div>
            </form>
            </div>          
        </div>
    </div>
</div>


<!--SEARCH RECORD EMAIL-->

<div class="modal fade" id="mdl_searchRecord_email" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Search Record - Email</h4>
            </div>
            <div class="modal-body">
            <form method='POST'>
             
                <label class="">Logged Date</label>
                <div class="row">                    
                    <div class="col-md-6">
                        <div class="form-group label-floating hr_nomarg2">
                            <input type='date' class="form-control" id = "logged_from" name = "logged_from" placeholder="From">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group label-floating hr_nomarg2">
                            <input type='date' class="form-control" id = "logged_to" name = "logged_to" placeholder="To">
                        </div>
                    </div>
                </div>   
               
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Subject</label>
                    <input class="form-control" name = "subj" >
                   
                </div>
               
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Sender Information</label>
                    <input class="form-control" name = "send">
                </div>
                 <div class="form-group label-floating hr_nomarg2">
                    <label class="">Confidential:</label>
                    <input type='radio' name = "confidential" value='Yes'> Yes
                    <input type='radio' name = "confidential" value='No'> No
                </div>
                <div class="form-group label-floating hr_nomarg2">
                    <label class="">Remarks</label>
                    <input class="form-control" name = "notes" >
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value='Submit' name='search_email'>
                </div>
            </form>
            </div>          
        </div>
    </div>
</div>


