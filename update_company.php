<?php 
    include('header.php');
    include 'functions/functions.php';
    $userid=$_SESSION['userid'];
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    } else {
        $id = '';
    }
    if(isset($_POST['updateuser'])){
        foreach($_POST as $var=>$value)
            $$var = mysqli_real_escape_string($con,$value);

        $update = $con->query("UPDATE company SET company_name = '$company_name' WHERE company_id = '$id'");
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
                                        <strong>Update Company</strong>
                                    </h2>                                    
                                </div>
                                <div class="card-content table-responsive">
                                    <?php 
                                        $sql = mysqli_query($con,"SELECT * FROM company WHERE company_id = '$id'");
                                        $row = mysqli_fetch_array($sql);
                                    ?>
                                    <form method="POST" style="background:#fff;padding-top:10px">
                                        <div class="col-md-12">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Company Name</label>
                                                <input type="text" name = "company_name" class="form-control" style="width:100%" value = "<?php echo $row['company_name'];?>">
                                            </div>
                                        </div>
                                        <br>
                                        <center>
                                            <input type="submit" class="btn btn-info" value = "Save Changes" name = "updateuser">
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