<script src="js/jquery-1.12.4.js"></script> 
 
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>

<script src="js/bootstrap.min.js"></script>
<script src="js/chart.min.js"></script>
<script src="js/chart-data.js"></script>
<script src="js/easypiechart.js"></script>
<script src="js/easypiechart-data.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/custom.js"></script>

<script type="text/javascript">
		window.onload = function () {
			var	myVar;
			myVar	=setTimeout(showPage,2000);
		};
		function showPage() {
			document.getElementById("loader").style.display = "none";
			document.getElementById("contents").style.display = "block";			
		}
	$(document).ready( function () {
	    $('#tbl_user').DataTable({
	    	"aaSorting": [[ 0, "desc" ]]
	    });
	    $('#tbl_department').DataTable({
	    	"aaSorting": [[ 0, "asc" ]]
	    });
	    $('#tbl_company').DataTable({
	    	"aaSorting": [[ 0, "asc" ]]
	    });
	    $('#tbl_record').DataTable({
	    	"aaSorting": [[ 0, "desc" ]]
	    });
	    $('#tbl_email').DataTable({
	    	"aaSorting": [[ 0, "desc" ]]
	    });
	});	
</script>
