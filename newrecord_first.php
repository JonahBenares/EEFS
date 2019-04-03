<?php 
include('header.php');
include('functions/functions.php');
?>
<link href="css/newrecord_first.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.js"></script>
  
<body>
	<?php include('navbars.php');?>
    	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    		<div class="row">
    			<ol class="breadcrumb">
    				<li><a href="#">
    					<em class="fa fa-home"></em>
    				</a></li>
    				<li class="active">New Record</li>
    			</ol>
    		</div><!--/.row-->
    		
    		<div class="row">
    			<div class="col-lg-12">
    				<h1 class="page-header">Add New Record</h1>
    			</div>
    		</div><!--/.row-->
    		
    		<div class="row">
    			<div class="col-md-12">
    				<div class="panel panel-default box-shadow">
    					<div class="panel-heading">
    						New Record
    						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
    						<a class="pull-right  btn-primary panel-toggle" style="background:#099428;color:white" href="viewrecord.php"><em class="fa fa-eye"></em></a>						
    					</div>
    					<div class="panel-body">
    						<div class="row">
    							<form method='GET' action='newrecord.php' style="margin:0px 50px 0px 50px">                             
                                    <div class="col-lg-12">
                                        <h2 class="control-label">Choose which COMPANY does this file belongs to:</h2>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php 
                                            $get_company = $con->query("SELECT * FROM company"); 
                                            while($fetch=$get_company->fetch_array()){ ?>
                                            <label class="btn btn-default form-check-label box-shadow">
                                                <input class="form-check-input" type="radio" autocomplete="off" 
                                                name='company' value='<?php echo $fetch['company_id']; ?>' required><label class="h2"> <?php echo $fetch['company_name']; ?></label>
                                            </label>
                                            <?php } ?>
                                        </div>
                                        <hr>
                                        <input type="submit" name="" class=" btn btn-md btn-primary " style="width:93%" value="PROCEED">
                                    </div>
    							</form>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>	<!--/.main-->
    	
</body>
<?php include('scripts.php');?>
</html>