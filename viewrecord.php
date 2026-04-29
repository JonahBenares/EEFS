<?php 
	include('header.php');
	include 'functions/functions.php';
	$usertype=$_SESSION['usertype'];
	$userid=$_SESSION['userid'];

    // =======================
    // OPTIMIZATION CACHE
    // =======================
    $attachmentCache = [];
    $sharedCache = [];
?>
<style>
#tbl_record thead th {
    text-align: center !important;
    vertical-align: middle !important;
}

#tbl_record {
    table-layout: fixed;
    width: 100%;
}

/* Hidden ID column */
#tbl_record th:nth-child(1),
#tbl_record td:nth-child(1) {
    display: none;
}

/* Document Date */
#tbl_record th:nth-child(2),
#tbl_record td:nth-child(2) {
    width: 15%;
    text-align: center;
}

/* Subject */
#tbl_record th:nth-child(3),
#tbl_record td:nth-child(3) {
    width: 45%;
    text-align: center;
}

/* Attachments */
#tbl_record th:nth-child(4),
#tbl_record td:nth-child(4) {
    width: 25%;
    text-align: center;
}

/* Action */
#tbl_record th:nth-child(5),
#tbl_record td:nth-child(5) {
    width: 15%;
    text-align: center;
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

<!-- ================= HEADER ================= -->
<div class="row">
	<ol class="breadcrumb">
		<li><a href="#"><em class="fa fa-home"></em></a></li>
		<li class="active">View Records </li>
	</ol>
</div>

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">View Record</h1>
	</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="panel panel-default box-shadow">

<div class="panel-heading">
	Record List <small>(Document)</small>
	<span class="pull-right clickable panel-toggle panel-button-tab-left">
		<em class="fa fa-toggle-up"></em>
	</span>
	<a class="pull-right btn-success btn-fill panel-toggle" style="background:#099428;color:white" data-toggle="modal" data-target="#mdl_searchRecord">
		<em class="fa fa-search"></em>
	</a>
	<a class="pull-right btn-primary panel-toggle" style="background:#30a5ff;color:white" href="newrecord_first.php">
		<em class="fa fa-plus"></em>
	</a>					
</div>

<div class="panel-body">
<div class="canvas-wrapper">

<div id="tabl_rec" class="city">

<table class="table table-hover table-bordered" id="tbl_record" style="width:100%">

<?php
/* ======================================================
   SEARCH FILTER
====================================================== */
if(isset($_POST['search_doc'])){

	echo "Filters Applied: ".filtersApplied($con, $_POST) . 
	" <a href='$_SERVER[PHP_SELF]' style='color:red; font-size:11px'>Remove filter</a><br><br>";

	$docid=filteredSQL($con,$_POST);
?>

<thead class="th-header">
	<th style='display:none'></th>
	<th>Document Date</th>
	<th>Subject</th>
	<th>Addressee</th>
	<th>Attachments</th>								
	<th>Action</th>
</thead>

<?php
foreach($docid AS $id){

$sql = mysqli_query($con,"SELECT * FROM document_info WHERE document_id = '$id' ORDER BY document_id DESC");

while($row = mysqli_fetch_array($sql)){

$doc_id = $row['document_id'];

?>
<tr>
<td style='display:none'><?php echo $row['document_id'];?></td>
<td><?php echo $row['document_date'];?></td>

<td>
<?php if($row['email_attach'] == 1) { ?>
	<a class='btn btn-warning btn-xs'><span class='fa fa-envelope-square'></span></a>
<?php } 
if($row['confidential'] == 'Yes') 
	echo "<a class='btn btn-danger btn-xs'><span class='fa fa-lock'></span></a> | ";

echo $row['subject']; ?>
</td>
<td>
<?php
$company = trim($row['addressee_company']);
$person  = trim($row['addressee_person']);

echo $company;

if (!empty($company) && !empty($person)) {
    echo "/";
}

echo $person;
?>
</td>
<td style="width:25%;">

<?php
// ================= ATTACHMENT CACHE =================
if (!isset($attachmentCache[$doc_id])) {

	$att_sql = mysqli_query($con,"SELECT attach_remarks FROM document_attach WHERE document_id = '$doc_id'");

	$attach_remarks = [];

	while($att = mysqli_fetch_array($att_sql)){
		$remark = trim($att['attach_remarks']);
		if(!empty($remark)){
			$attach_remarks[] = $remark;
		}
	}

	$attachmentCache[$doc_id] = $attach_remarks;
}

$attach_remarks = $attachmentCache[$doc_id];
$att_count = count($attach_remarks);
?>

<?php if($att_count > 0){ ?>

	<!-- CLICKABLE TOGGLE -->
	<span style="color:#007bff; cursor:pointer; font-size:12px; display:inline-flex; align-items:center;"
		  data-toggle="collapse"
		  data-target="#remarks_<?php echo $doc_id; ?>"
		  id="toggle_<?php echo $doc_id; ?>">

		<span id="arrow_<?php echo $doc_id; ?>" style="margin-right:5px;">►</span>
		View List of Attachment(s)
	</span>

	<!-- COLLAPSE CONTENT -->
	<div id="remarks_<?php echo $doc_id; ?>" class="collapse" style="margin-top:5px; text-align:left;">
		<ul style="padding-left:18px; max-height:150px; overflow-y:auto; font-size:12px; margin:0;">
			<?php foreach($attach_remarks as $remark){ ?>
				<li><?php echo htmlspecialchars($remark); ?></li>
			<?php } ?>
		</ul>
	</div>

	<!-- TOGGLE SCRIPT -->
	<script>
	document.addEventListener("DOMContentLoaded", function () {
		var target = $("#remarks_<?php echo $doc_id; ?>");
		var arrow = $("#arrow_<?php echo $doc_id; ?>");

		target.on("show.bs.collapse", function () {
			arrow.text("▼");
		});

		target.on("hide.bs.collapse", function () {
			arrow.text("►");
		});
	});
	</script>

<?php } else { ?>
	<span style="color:gray; font-size:12px;"></span>
<?php } ?>

</td>

<td style="text-align:center; vertical-align:middle;">
<?php
// ================= SHARED CACHE =================
if (!isset($sharedCache[$doc_id])) {
	$sharedCache[$doc_id] = getShared($con,$userid,$doc_id);
}
$shared = $sharedCache[$doc_id];

if($usertype=='Staff') {
	if($row['confidential'] == 'No') { ?>
		<a href='newrecord.php?docid=<?php echo $doc_id; ?>' class="btn btn-info btn-sm"><span class="fa fa-pencil-square-o"></span></a>
		<a href='view_details.php?id=<?php echo $doc_id; ?>' target='_blank' class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
<?php } 
} else if($usertype=='Manager'){
	if($row['user_id'] == $userid || $row['confidential'] == 'No' || $shared > 0) { ?>
		<a href='newrecord.php?docid=<?php echo $doc_id; ?>' class="btn btn-info btn-sm"><span class="fa fa-pencil-square-o"></span></a>
		<a href='view_details.php?id=<?php echo $doc_id; ?>' target='_blank' class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
<?php } 
} else if($usertype=='Admin') { ?>
	<a href='newrecord.php?docid=<?php echo $doc_id; ?>' class="btn btn-info btn-sm"><span class="fa fa-pencil-square-o"></span></a>
	<a href='view_details.php?id=<?php echo $doc_id; ?>' target='_blank' class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
<?php } ?>
</td>

</tr>
<?php } } } 

/* ======================================================
   DEFAULT VIEW
====================================================== */
else { ?>

<thead class="th-header">
	<th style='display:none'></th>
	<th>Document Date</th>
	<th>Subject</th>
	<th>Addressee</th>
	<th>Attachments</th>								
	<th>Action</th>
</thead>

<?php
$current_year = date('Y');
$previous_year = $current_year - 1;

$sql = mysqli_query($con,"SELECT * FROM document_info 
WHERE YEAR(logged_date) IN ('$current_year', '$previous_year') 
ORDER BY document_id DESC");

while($row = mysqli_fetch_array($sql)){

$doc_id = $row['document_id'];
?>
<tr>
<td style='display:none'><?php echo $row['document_id'];?></td>
<td><?php echo $row['document_date'];?></td>

<td>
<?php if($row['email_attach'] == 1) { ?>
	<a class='btn btn-warning btn-xs'><span class='fa fa-envelope-square'></span></a>
<?php } 
if($row['confidential'] == 'Yes') 
	echo "<a class='btn btn-danger btn-xs'><span class='fa fa-lock'></span></a> | ";

echo $row['subject']; ?>
</td>
<td>
<?php
$company = trim($row['addressee_company']);
$person  = trim($row['addressee_person']);

echo $company;

if (!empty($company) && !empty($person)) {
    echo "/";
}

echo $person;
?>
</td>
<td style="width:25%;">

<?php
// ================= ATTACHMENT CACHE =================
if (!isset($attachmentCache[$doc_id])) {

	$att_sql = mysqli_query($con,"SELECT attach_remarks FROM document_attach WHERE document_id = '$doc_id'");

	$attach_remarks = [];

	while($att = mysqli_fetch_array($att_sql)){
		$remark = trim($att['attach_remarks']);
		if(!empty($remark)){
			$attach_remarks[] = $remark;
		}
	}

	$attachmentCache[$doc_id] = $attach_remarks;
}

$attach_remarks = $attachmentCache[$doc_id];
$att_count = count($attach_remarks);
?>

<?php if($att_count > 0){ ?>

	<!-- CLICKABLE TOGGLE -->
	<span style="color:#007bff; cursor:pointer; font-size:12px; display:inline-flex; align-items:center;"
		  data-toggle="collapse"
		  data-target="#remarks_<?php echo $doc_id; ?>"
		  id="toggle_<?php echo $doc_id; ?>">

		<span id="arrow_<?php echo $doc_id; ?>" style="margin-right:5px;">►</span>
		View List of Attachment(s)
	</span>

	<!-- COLLAPSE CONTENT -->
	<div id="remarks_<?php echo $doc_id; ?>" class="collapse" style="margin-top:5px; text-align:left;">
		<ul style="padding-left:18px; max-height:150px; overflow-y:auto; font-size:12px; margin:0;">
			<?php foreach($attach_remarks as $remark){ ?>
				<li><?php echo htmlspecialchars($remark); ?></li>
			<?php } ?>
		</ul>
	</div>

	<!-- TOGGLE SCRIPT -->
	<script>
	document.addEventListener("DOMContentLoaded", function () {
		var target = $("#remarks_<?php echo $doc_id; ?>");
		var arrow = $("#arrow_<?php echo $doc_id; ?>");

		target.on("show.bs.collapse", function () {
			arrow.text("▼");
		});

		target.on("hide.bs.collapse", function () {
			arrow.text("►");
		});
	});
	</script>

<?php } else { ?>
	<span style="color:gray; font-size:12px;"></span>
<?php } ?>

</td>

<td style="text-align:center; vertical-align:middle;">
<?php
if (!isset($sharedCache[$doc_id])) {
	$sharedCache[$doc_id] = getShared($con,$userid,$doc_id);
}
$shared = $sharedCache[$doc_id];

if($usertype=='Staff') {
	if($row['confidential'] == 'No') { ?>
		<a href='newrecord.php?docid=<?php echo $doc_id; ?>' class="btn btn-info btn-sm"><span class="fa fa-pencil-square-o"></span></a>
		<a href='view_details.php?id=<?php echo $doc_id; ?>' target='_blank' class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
<?php } 
} else if($usertype=='Manager'){
	if($row['user_id'] == $userid || $row['confidential'] == 'No' || $shared > 0) { ?>
		<a href='newrecord.php?docid=<?php echo $doc_id; ?>' class="btn btn-info btn-sm"><span class="fa fa-pencil-square-o"></span></a>
		<a href='view_details.php?id=<?php echo $doc_id; ?>' target='_blank' class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
<?php } 
} else if($usertype=='Admin') { ?>
	<a href='newrecord.php?docid=<?php echo $doc_id; ?>' class="btn btn-info btn-sm"><span class="fa fa-pencil-square-o"></span></a>
	<a href='view_details.php?id=<?php echo $doc_id; ?>' target='_blank' class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
<?php } ?>
</td>

</tr>
<?php } } ?>

</table>
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