<?php 
    include('header.php');
    include 'functions/functions.php';
    $userid=$_SESSION['userid'];
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    } else {
        $id = '';
    }
    if(isset($_POST['updatedetails'])){
        foreach($_POST as $var=>$value)
            $$var = mysqli_real_escape_string($con,$value);

        $update = $con->query("UPDATE document_info SET document_date = '$document_date', department_id = '$department', type_id = '$type', subject = '$subject', addressee = '$addressee' WHERE document_id = '$id'");
        if($update){
            echo "<script>alert('Successfully Updated!'); window.opener.location.reload(); window.close();</script>";
        }
    }
?>
<style type="text/css"> 
    .main-panel>.content{
        margin-top: 0px!important;
    }
</style>
<body style="padding-top: 0px;background:#099428">
    <div class="wrapper">
        <div class="main-panel">
            <div class="content" >
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header" data-background-color="orange">  
                                    <h2 class="title" style="color:#fff">
                                        <strong>Update Details</strong>
                                    </h2>                                    
                                </div>
                                <div class="card-content table-responsive">
                                    <?php 
                                        $sql = mysqli_query($con,"SELECT * FROM document_info WHERE document_id = '$id'");
                                        $row = mysqli_fetch_array($sql);
                                    ?>
                                    <form method="POST" style="background:#fff;padding-top:10px">
                                        <div class="col-md-12">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Document Date</label>
                                                <input type="text" name = "document_date" class="form-control" style="width:100%" value = "<?php echo $row['document_date'];?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Department</label>
                                                <select class="form-control" id = "department" name = "department" required>
                                                    <?php                                        
                                                        $sql1 = mysqli_query($con,"SELECT * FROM department ORDER BY department_name DESC");
                                                        while($row1 = mysqli_fetch_array($sql1)) {
                                                    ?>
                                                    <option value = "<?php echo $row1['department_id']?>" <?php echo (($row1['department_id'] == $row['department_id']) ? ' selected' : ''); ?>><?php echo $row1["department_name"]?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Document Type</label>
                                                <input type="text" name = "type" class="form-control" style="width:100%" value = "<?php echo getInfo($con, 'type_name', 'document_type', 'type_id',  $row['type_id']);?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Subject</label>
                                                <input type="text" name = "subject" class="form-control" style="width:100%" value = "<?php echo $row['subject'];?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Addressee</label>
                                                <input type="text" name = "addressee" class="form-control" style="width:100%" value = "<?php echo $row['addressee'];?>">
                                            </div>
                                        </div>
                                        <br>
                                        <center>
                                            <input type="submit" class="btn btn-info" value = "Save Changes" name = "updatedetails">
                                        </center>
                                        <br>
                                        <input type='hidden' name='userid' value="<?php echo $userid; ?>">
                                        <input type='hidden' name='id' value="<?php echo $id; ?>">
                                    </form>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include('scripts.php');?>
</html>