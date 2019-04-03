<?php 
	include('header.php');
	include 'functions/functions.php';
	if(isset($_GET['id'])){
        $id = $_GET['id'];
    } else {
        $id = '';
    }
    $usertype=$_SESSION['usertype'];
    $userid=$_SESSION['userid'];
?>
<link href="css/view_details.css" rel="stylesheet">
<body>
	<?php include('navbars.php');?>
	<div id="loader">
	  	<figure class="one"></figure>
	  	<figure class="two">loading</figure>
	</div>
	<div id="contents" style="display: none">
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
						$shared=getShared($con,$userid,$id);

						if(($usertype == 'Staff' && $row['confidential'] == 'Yes')){
							echo "<script>alert('You are not allowed to view this document.'); window.location='viewrecord.php';</script>";
						} else if($usertype=='Manager'){
							if($row['confidential'] == 'Yes' && ($shared==0 && $row['user_id'] != $userid)){
								echo "<script>alert('You are not allowed to view this document.'); window.location='viewrecord.php';</script>";
							}  
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
										<td class="tr-class" width="35%"><strong>Logged Date:</strong></td>
										<td class="tr-class" width="65%"><span><?php echo (!empty($row['logged_date']) ? date("F d, Y H:i:s",strtotime($row['logged_date'])) : '');?></span></td>
									</tr>
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
										<td class="tr-class" width="35%"><strong>Email Sender:</strong></td>
										<td class="tr-class" width="65%"><span><?php echo $row['email_sender'];?></span></td>
									</tr>
									<tr>
										<td class="tr-class" width="35%"><strong>Shared With:</strong></td>
										<td class="tr-class" width="65%"><span>
											<?php $select = $con->query("SELECT user_id FROM shared_document WHERE document_id = '$id'"); 
												$shared='';
												while($fetch = $select->fetch_array()){
													$shared.= getInfo($con, 'fullname', 'users', 'user_id', $fetch['user_id']) .", ";
												} 
												echo substr($shared, 0, -2);
												?>
												
											</span></td>
									</tr>
									<tr>
										<td colspan="2" style="padding:5px"><strong>Remarks:</strong></td>
									</tr>
									<tr>
										<td colspan="2">
											<p class="p_remarks"> 
												<span><?php $notes = str_replace("-", "<br>-", $row['remarks']); 
												echo $notes;?></span> 
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
										$cc=1;
		                                $sql1 = mysqli_query($con,"SELECT * FROM document_attach WHERE document_id = '$row[document_id]'");
		                                while ($row2 = mysqli_fetch_array($sql1)){    
		                                $cert1=explode(".",$row2['attach_file']);
		                                $attach2 = $cert1[1];
		                                if($attach2=='png' || $attach2=='jpg' || $attach2 == 'jpeg' || $attach2 == 'PNG' || $attach2 == 'JPG' || $attach2 == 'JPEG'){
		                            ?>
									<div class="col-lg-3 col-md-4 col-xs-6 thumb animate-box ">
		                                <div class="column thumbnail hover-shadow ">
		                                    <img class="img-responsive" src="
		                                    <?php 
		                                        if (empty($row2['attach_file'])){
		                                            echo 'upload/necs/gallery-icon-67820.png'; 
		                                        }
		                                        else{
		                                            echo 'upload/'. $row2['attach_file'];
		                                        }
                                    		?>" alt="<?php echo $row2['attach_file'];?>"  onclick="openModal();currentSlide(<?php echo $cc; ?>)">
		                                </div>
		                              <!--   <h6><?php //echo $row2['attach_file'];?></h6> -->
		                            </div>
		                            <?php } else { ?>
		                            	<div class="col-lg-3 col-md-4 col-xs-6 thumb animate-box ">
		                            		<a class=" shadow" href='upload/<?php echo $row2['attach_file']; ?>' target='_blank'><img class="img-responsive" src='upload/necs/Treetog-I-Documents.ico'><h5 class="sas" style="color:#0087ff"><?php echo $row2['attach_file']; ?></h5></a>
		                            	</div>
		                            <?php } $cc++; } ?>
		                            <div class="modal " id="mode">
	                                    <a class="prev" id="show-previous-image" style="text-decoration: none;" onclick="plusSlides(-1)">Previous</a>
	                                    <a id="show-next-image" class="next" style="text-decoration: none;" onclick="plusSlides(1)">Next</a>
	                                    <div class="modal-dialog" onclick="closeModal()">
	                                        <div class="modal-content">	                                        	
	                                            <div class="modal-header">
	                                                <button type="button" class="close" onclick="closeModal()">
	                                                <span aria-hidden="true" style="color: #ad4a00;">×</span>
	                                                <span class="sr-only">Close</span>
	                                                </button>
	                                                <br>
	                                            </div>
	                                                <?php
								                      $aa = 1;
								                      $sql1 = mysqli_query($con,"SELECT * FROM document_attach WHERE document_id = '$row[document_id]'");
								                      $bb = mysqli_num_rows($sql1);
								                      while ($row2 = mysqli_fetch_array($sql1)){ 
								                      $att=explode(".",$row2['attach_file']);
								                      $upload_att = $att[1];
								                  	?>
	                                                
	                                            
	                                            <div class="mySlides">
	                                            	<h4 class="numbertext" style="font-weight: 600; font-size: 15px"><?php echo $aa.'/'.$bb ?>&nbsp-&nbsp<?php echo $row2['attach_file'];?></h4>
	                                                <img src="<?php 
								                      if (empty($row2['attach_file'])){
								                          echo 'upload/necs/gallery-icon-67820.png'; 
								                      } else{
								                         if($upload_att == 'jpg' || $upload_att == 'png' || $upload_att == 'jpeg'  || $upload_att == 'PNG' || $upload_att == 'PNG' || $upload_att == 'JPG' || $upload_att == 'JPEG'){
								                            echo 'upload/'. $row2['attach_file']; 
								                           } else {
								                            echo "upload/files.png";
								                           }
								                      }
								                  ?>" style="width:100%">
	                                            </div>
	                                            <?php 
								                  $aa++; 
								                }?>   
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
<script type="text/javascript">
    function openModal() {
	  document.getElementById('mode').style.display = "block";
	}

	function closeModal() {
	  document.getElementById('mode').style.display = "none";

	}

	var slideIndex = 1;
	showSlides(slideIndex);

	function plusSlides(n) {
	  showSlides(slideIndex += n);
	}

	function currentSlide(n) {
	  showSlides(slideIndex = n);
	}

	function showSlides(n) {
	  var i;
	  var slides = document.getElementsByClassName("mySlides");
	  var dots = document.getElementsByClassName("demo");
	  var captionText = document.getElementById("kik");
	  if (n > slides.length) {slideIndex = 1}
	  if (n < 1) {slideIndex = slides.length}
	  for (i = 0; i < slides.length; i++) {
	      slides[i].style.display = "none";
	  }
	  for (i = 0; i < dots.length; i++) {
	      dots[i].className = dots[i].className.replace(" active", "");
	  }
	  slides[slideIndex-1].style.display = "block";
	  dots[slideIndex-1].className += " active";
	  captionText.innerHTML = dots[slideIndex-1].alt;
	}
</script>
</html>