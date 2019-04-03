<?php 
	include('header.php');
	include 'functions/functions.php';
	if(isset($_GET['id'])){
        $id = $_GET['id'];
    } else {
        $id = '';
    }
    $usertype=$_SESSION['usertype'];
?>
<style type="text/css">
	.man {
      position: relative;
      width: 100%;
    }

    .image {
      display: block;
      width: 100%;
      height: auto;
      border-radius: 20px;
    }

    .hovering {
      position: absolute;
      bottom: 100%;
      left: 0;
      right: 0;
      background-color: #14771a99;
      overflow: hidden;
      width: 100%;
      height:0;
      transition: .5s ease;
      border-radius: 20px;
    }

    .man:hover .hovering {
      bottom: 0;
      height: 100%;
    }

    .text {
      color: white;
      font-size: 30px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      text-align: center;
    }

    .close {
      color: white;
      position: absolute;
      top: 10px;
      right: 25px;
      font-size: 35px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: #999;
      text-decoration: none;
      cursor: pointer;
    }

    .mySlides {
      display: none;
      width: 
    }

    .cursor {
      cursor: pointer
    }

    /* Next & previous buttons */
    .prev,
    .next {
    	display: block;
	    z-index: 3;
	    color: white;
	    background-color: rgba(72, 72, 72, 0.47);
	    font-size: 6vmin;
		cursor: pointer;
		position: absolute;
		top: 50%;
		width: auto;
		padding: 16px;
		margin-top: -50px;
		color: white;
		font-weight: bold;
		font-size: 20px;
		transition: 0.6s ease;
		border-radius: 0 3px 3px 0;
		user-select: none;
		-webkit-user-select: none;
    }
	

    /* Position the "next button" to the right */
    .next {
      right: 0;
      border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover,
    .next:hover {
      background-color: rgba(0, 0, 0, 0.8);
    }

    /* Number text (1/3 etc) */
    .numbertext {
		color: #f2f2f2;
		font-size: 12px;
		padding: 8px 12px;
		position: absolute;
		top: 0;
    }
    .tr-class{
    	padding: 5px;
    	border-bottom:1px solid #e5e7ec;
    }
    .panel-confi{
    	background: linear-gradient(to right, #fbbfbf , #ffffff7a)!important;
    }

	.tooltip {
	    position: relative;
	    display: inline-block;
	    border-bottom: 1px dotted black;
	}

	.tooltiptext {
	    visibility: visible;
	    width: 120px;
	    background-color: #f9243f;
	    color: #fff;
	    text-align: center;
	    border-radius: 6px;
	    padding: 5px 0;
	    box-shadow: -2px 2px 5px #4848486e;
	    /* Position the tooltip */
	    position: absolute;
	    z-index: 1;
	    bottom: 98%;
	    left: 7%;
	    margin-left: -60px;
	}

</style>
<div class="modal fade " tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loader">
    <div class="modal-dialog" role="document">
    	<div style="margin-top:200px">
	    	<center>
	        	<div class="loader"></div>
	        </center>
        </div>
    </div>
</div>
<body>
	<?php include('navbars.php');?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li>
					<a href="#">
						<em class="fa fa-home"></em>
					</a>
				</li>
				<li class="active">
					<a href="viewrecord.php">
						View Records
					</a>
				</li>
				<li class="active">					
					Subject
				</li>
			</ol>
		</div>
		<div style="margin-top:40px"></div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default box-shadow">
					<?php 

						$query = mysqli_query($con,"SELECT * FROM document_info WHERE document_id = '$id'");
						$row = mysqli_fetch_array($query);

						if($usertype != 'Admin' && $row['confidential'] == 'Yes'){
					    	echo "<script>alert('You are not allowed to view this document.'); window.location='viewrecord.php';</script>";
					    }
					?>

						<?php if($row['confidential'] == 'Yes') { ?>
						<span class="tooltiptext"> <span class="fa fa-lock"></span>&nbsp Confidential</span>
						<?php } ?>
					<div class="panel-heading <?php echo (($row[confidential] == 'Yes') ? 'panel-confi' :''); ?>" style="border-bottom:3px solid #099428">
						<table width="100%">
							<tr>
								<td style="text-transform:uppercase;padding-left:20px">
									<strong>
										<a>								
										</a> <?php echo $row['subject'];?>
									</strong>
									<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
									<a class="pull-right  btn-primary panel-toggle" style="background:#30a5ff;color:white" href="newrecord.php?docid=<?php echo $id; ?>"><em class="fa fa-edit"></em></a>
								</td>
							</tr>
						</table>
					</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<div class="col-lg-6" style="border-right:1px solid #e5e7ec">
								<table width="100%">
									<tr>
										<td class="tr-class" width="35%"><strong>Document Date:</strong></td>
										<td class="tr-class" width="65%"><span><?php echo (!empty($row['document_date']) ? date("F d, Y",strtotime($row['document_date'])) : '');?></span></td>
									</tr>
									<tr>
										<td class="tr-class" width="35%"><strong>Company:</strong></td>
										<td class="tr-class" width="65%"><span><?php echo getInfo($con, 'company_name', 'company', 'company_id',  $row['company_id']);?></span></td>
									</tr>
									<tr>
										<td class="tr-class" width="35%"><strong>Document Location:</strong></td>
										<td class="tr-class" width="65%"><span><?php echo getInfo($con, 'location_name', 'document_location', 'location_id',  $row['location_id']);?></span></td>
									</tr>
									<tr>
										<td class="tr-class" width="35%"><strong>Document type:</strong></td>
										<td class="tr-class" width="65%"><span><?php echo getInfo($con, 'type_name', 'document_type', 'type_id',  $row['type_id']);?></span></td>
									</tr>
									<tr>
										<td class="tr-class" width="35%"><strong>Department:</strong></td>
										<td class="tr-class" width="65%"><span><?php echo getInfo($con, 'department_name', 'department', 'department_id',  $row['department_id']);?></span></td>
									</tr>
									<tr>
										<td class="tr-class" width="35%"><strong>Sender:</strong></td>
										<td class="tr-class"><span><?php echo $row['sender_company'].' / '.$row['sender_person'];?></span></td>
									</tr>
									<tr>
										<td class="tr-class" width="35%"><strong>Addressee:</strong></td>
										<td class="tr-class"><span><?php echo $row['addressee_company'].' / '.$row['addressee_person'];?></span></td>
									</tr>
									<tr>
										<td class="tr-class" width="35%"><strong>Type of Copy:</strong></td>
										<td class="tr-class" width="65%"><span><?php echo $row['copy_type'];?></span></td>
									</tr>
									<tr>
										<td class="tr-class" width="35%"><strong>Signatory:</strong></td>
										<td class="tr-class" width="65%"><span><?php echo $row['signatory'];?></span></td>
									</tr>
									<tr>
										<td colspan="2" style="padding:5px"><strong>Remarks:</strong></td>
									</tr>
									<tr>
										<td colspan="2">
											<p class="p_remarks"> 
												<span><?php echo $row['remarks'];?></span> 
											</p>
										</td>
									</tr>
								</table>
							</div>
							<div class="col-lg-6">
								<div class="row" style="padding-left:15px">
									<strong>Attatchment(s):</strong>
									<div style="border-bottom:1px solid #e5e7ec;width:50%;margin-bottom:5px"></div>
								</div>
								<div class="row">
									<?php 
		                                $sql1 = mysqli_query($con,"SELECT * FROM document_attach WHERE document_id = '$row[document_id]'");
		                                while ($row2 = mysqli_fetch_array($sql1)){    
		                                $cert1=explode(".",$row2['attach_file']);
		                                $attach2 = $cert1[1];
		                                if($attach2=='png' || $attach2=='jpg' || $attach2 == 'jpeg' || $attach2 == 'PNG' || $attach2 == 'JPG' || $attach2 == 'JPEG'){
		                            ?>
									<div class="col-lg-3 col-md-4 col-xs-6 thumb animate-box ">
		                                <a class="thumbnail shadow" href="#" data-image-id="" data-toggle="modal" data-title="<?php echo $row2['attach_remarks'];?>" data-caption="<?php echo $row2['attach_remarks'];?>" data-image="upload/<?php echo $row2['attach_file'];?>" data-target="#image-gallery">
		                                    <img class="img-responsive" src="
		                                    <?php 
		                                        if (empty($row2['attach_file'])){
		                                            echo 'upload/necs/gallery-icon-67820.png'; 
		                                        }
		                                        else{
		                                            echo 'upload/'. $row2['attach_file'];
		                                        }
                                    		?>" alt="<?php echo $row2['attach_remarks'];?>">
		                                </a>
		                                <h6><?php echo $row2['attach_remarks'];?></h6>
		                            </div>
		                            <?php } else { ?>
		                            	<div class="col-lg-3 col-md-4 col-xs-6 thumb animate-box ">
		                            		<a class=" shadow" href='upload/<?php echo $row2['attach_file']; ?>' target='_blank'><img class="img-responsive" src='upload/necs/Treetog-I-Documents.ico'><h5 class="sas" style="color:#0087ff"><?php echo $row2['attach_file']; ?></h5></a>
		                            	</div>
		                            <?php } } ?>
		                            <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	                                    <a class="prev" id="show-previous-image" style="text-decoration: none;">Previous</a>
	                                    <a id="show-next-image" class="next" style="text-decoration: none;">Next</a>
	                                    <div class="modal-dialog">
	                                        <div class="modal-content">
	                                            <div class="modal-header">
	                                                <button type="button" class="close" data-dismiss="modal">
	                                                <span aria-hidden="true" style="color: #ad4a00;">×</span>
	                                                <span class="sr-only">Close</span>
	                                                </button>
	                                                <h4 class="modal-title" id="image-gallery-title"></h4>
	                                            </div>
	                                            <div class="modal-body">
	                                                <div class="zoomin">
	                                                	<center>
	                                                    <img id="image-gallery-image" class="img-responsive"></center> 
	                                                </div>
	                                            </div>
	                                            <div class="modal-footer">                                      
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div> 
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
<script>
            $(document).ready(function(){

                loadGallery(true, 'a.thumbnail');

                //This function disables buttons when needed
                function disableButtons(counter_max, counter_current){
                    $('#show-previous-image, #show-next-image').show();
                    if(counter_max == counter_current){
                        $('#show-next-image').hide();
                    } else if (counter_current == 1){
                        $('#show-previous-image').hide();
                    }
                }

                /**
                 *
                 * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
                 * @param setClickAttr  Sets the attribute for the click handler.
                 */

                function loadGallery(setIDs, setClickAttr){
                    var current_image,
                        selector,
                        counter = 0;

                    $('#show-next-image, #show-previous-image').click(function(){
                        if($(this).attr('id') == 'show-previous-image'){
                            current_image--;
                        } else {
                            current_image++;
                        }

                        selector = $('[data-image-id="' + current_image + '"]');
                        updateGallery(selector);
                    });

                    function updateGallery(selector) {
                        var $sel = selector;
                        current_image = $sel.data('image-id');
                        $('#image-gallery-caption').text($sel.data('caption'));
                        $('#image-gallery-title').text($sel.data('title'));
                        $('#image-gallery-image').attr('src', $sel.data('image'));
                        disableButtons(counter, $sel.data('image-id'));
                    }

                    if(setIDs == true){
                        $('[data-image-id]').each(function(){
                            counter++;
                            $(this).attr('data-image-id',counter);
                        });
                    }
                    $(setClickAttr).on('click',function(){
                        updateGallery($(this));
                    });
                }
            });
    </script>
</html>