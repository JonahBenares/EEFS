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

    /*function clean($string) {
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}*/
?>
<link href="css/view_details.css" rel="stylesheet">
<style>
.viewer-container{
    text-align:center;
    margin-bottom:15px;
    padding:15px;
    border:1px solid #ddd;
    border-radius:10px;
    background:#fafafa;
}

/* IMAGE BOX */
.viewer-image{
    width:100%;
    height:450px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#fff;
    border:1px solid #e5e7ec;
    border-radius:8px;
    overflow:hidden;
}

.viewer-image img{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
}

/* REMARK */
.viewer-remark{
    margin-top:12px;
    font-size:16px;
    font-weight:bold;
    color:#333;
    min-height:24px;
}

/* CONTROLS */
.viewer-controls{
    margin-top:10px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

/* LEFT/RIGHT BUTTONS */
.nav-btn{
    background:#fff;
    border:1px solid #ccc;
    padding:6px 12px;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
    transition:0.2s;
}

.nav-btn:hover{
    background:#28a745;
    color:#fff;
    border-color:#28a745;
}

/* CENTER BUTTONS */
.center-controls{
    display:flex;
    gap:10px;
    justify-content:center;
    align-items:center;
}

/* THUMBNAILS */
.thumb-container{
    display:flex;
    gap:10px;
    overflow-x:auto;
    padding-bottom:5px;
}

.thumb-box{
    min-width:130px;
    cursor:pointer;
    text-align:center;
}

.thumb-img{
    width:100%;
    height:90px;
    object-fit:cover;
    border-radius:6px;
    border:2px solid transparent;
    transition:0.2s;
}

.thumb-box:hover .thumb-img{
    border:2px solid #28a745;
}

.thumb-remark{
    font-size:12px;
    font-weight:600;
    color:#555;
    margin-top:3px;
}
</style>
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
											<!-- <p class="p_remarks"> 
												<span><?php $notes = str_replace("*", "<br>*", $row['remarks']); 
												echo $notes;?></span> 
											</p> -->
											<p class="p_remarks">
									            <span>
									                <?php echo nl2br($row['remarks']); ?>
									            </span>
									        </p>
										</td>
									</tr>
								</table>
							</div>
							<div class="col-lg-6">
						    <div class="row" style="padding-left:15px">
						        <strong>Attachment(s):</strong>
						        <div style="border-bottom:1px solid #e5e7ec;width:100%;margin-bottom:10px"></div>
						    </div>

						    <div class="row">
						        <div class="col-lg-12">
									<?php
										$attachments = [];

										$sql1 = mysqli_query($con,"SELECT * FROM document_attach WHERE document_id = '$row[document_id]'");
										while ($row2 = mysqli_fetch_array($sql1)){    

										    $file   = $row2['attach_file'];
										    $remark = trim($row2['attach_remarks']);

										    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
										    $isImage = in_array($ext, ['png','jpg','jpeg']);

										    $filePath = "upload/".$file;

										    if(empty($file) || !file_exists($filePath)){
										        $filePath = "upload/files.png";
										        $isImage = false;
										    }

										    $attachments[] = [
										        'file' => $filePath,
										        'remark' => $remark,
										        'isImage' => $isImage
										    ];
										}

										$attachCount = count($attachments);
										?>

									<!-- ================= VIEWER ================= -->
									<div class="viewer-container">
									    <div class="viewer-image" style="<?php echo ($attachCount == 1 ? 'height:70vh;' : ''); ?>">
									        <img id="mainImage">
									    </div>

									    <div class="viewer-remark" id="mainRemark"></div>

									    <div class="viewer-controls">

									        <!-- LEFT BUTTON -->
									        <?php if($attachCount > 1){ ?>
									            <button class="nav-btn" onclick="prevImage()">&#10094; Prev</button>
									        <?php } else { ?>
									            <div></div>
									        <?php } ?>

									        <!-- CENTER ACTIONS -->
									        <div class="center-controls">
									            <button class="btn btn-success btn-sm" onclick="printImage()">Print</button>
									            <a id="saveBtn" download class="btn btn-primary btn-sm">Save Photo</a>
									        </div>

									        <!-- RIGHT BUTTON -->
									        <?php if($attachCount > 1){ ?>
									            <button class="nav-btn" onclick="nextImage()">Next &#10095;</button>
									        <?php } else { ?>
									            <div></div>
									        <?php } ?>

									    </div>
									</div>
										<!-- ================= THUMBNAILS ================= -->
										<?php if($attachCount > 1){ ?>
										<div class="thumb-container">
										    <?php foreach($attachments as $index => $att){ ?>
										        <div class="thumb-box" onclick="showImage(<?php echo $index; ?>)">
										            <img src="<?php echo $att['file']; ?>" class="thumb-img">

										            <?php if(!empty($att['remark'])){ ?>
										                <div class="thumb-remark"><?php echo $att['remark']; ?></div>
										            <?php } ?>
										        </div>
										    <?php } ?>
										</div>
										<?php } ?>
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

	function printImg() {
		  pwin = window.open(document.getElementById("mainImg").src,"_blank");
		  // pwin.onload = function () {window.print();}
		}
</script>
<script>
let attachments = <?php echo json_encode($attachments); ?>;
let currentFile = "";
let currentIndex = 0;

function showImage(index){
    if(index < 0 || index >= attachments.length) return;

    currentIndex = index;
    let data = attachments[index];

    currentFile = data.file;

    document.getElementById('mainImage').src = data.file;
    document.getElementById('saveBtn').href = data.file;

    document.getElementById('mainRemark').innerText =
        data.remark !== "" ? data.remark : "";
}

function prevImage(){
    if(attachments.length <= 1) return;
    if(currentIndex > 0){
        showImage(currentIndex - 1);
    }
}

function nextImage(){
    if(attachments.length <= 1) return;
    if(currentIndex < attachments.length - 1){
        showImage(currentIndex + 1);
    }
}

/* INIT */
if(attachments.length > 0){
    showImage(0);
}

function printImage(){
    if(!currentFile) return;

    let printWindow = window.open('', '_blank', 'width=900,height=600');

    printWindow.document.open();
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Image</title>
            <style>
                @page { margin: 0; }
                body {
                    margin: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
                img {
                    max-width: 100%;
                    max-height: 100%;
                }
            </style>
        </head>
        <body>
            <img id="printImg" src="${currentFile}">
        </body>
        </html>
    `);
    printWindow.document.close();

    let img = printWindow.document.getElementById('printImg');

    img.onload = function () {
        printWindow.focus();
        printWindow.print();

        // safer close after print dialog finishes
        printWindow.onafterprint = function () {
            printWindow.close();
        };

        // fallback in case onafterprint fails
        setTimeout(() => {
            if (!printWindow.closed) {
                printWindow.close();
            }
        }, 2000);
    };
}
</script>
</html>