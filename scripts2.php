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
	$(document).ready( function () {
		$('#loader').modal('show')
		setTimeout(function() {$('#loader').modal('hide');}, 1000);
	} );
</script>
<script>
	window.onload = function () {
		var chart1 = document.getElementById("line-chart").getContext("2d");
		window.myLine = new Chart(chart1).Line(lineChartData, {
		responsive: true,
		scaleLineColor: "rgba(0,0,0,.2)",
		scaleGridLineColor: "rgba(0,0,0,.05)",
		scaleFontColor: "#c5c7cc"
		});
	};
</script>
<script>
		$(document).ready(function(){
		  // Add smooth scrolling to all links
		  $("a").on('click', function(event) {

		    // Make sure this.hash has a value before overriding default behavior
		    if (this.hash !== "") {
		      // Prevent default anchor click behavior
		      event.preventDefault();

		      // Store hash
		      var hash = this.hash;

		      // Using jQuery's animate() method to add smooth page scroll
		      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
		      $('html, body').animate({
		        scrollTop: $(hash).offset().top
		      }, 800, function(){
		   
		        // Add hash (#) to URL when done scrolling (default click behavior)
		        window.location.hash = hash;
		      });
		    } // End if
		  });

		  $("a").on('click', function(event) {

		    // Make sure this.hash has a value before overriding default behavior
		    if (this.hash !== "") {
		      // Prevent default anchor click behavior
		      event.preventDefault();

		      // Store hash
		      var hash = this.hash;

		      // Using jQuery's animate() method to add smooth page scroll
		      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
		      $('html, body').animate({
		        scrollTop: $(hash).offset().top
		      }, 800, function(){
		   
		        // Add hash (#) to URL when done scrolling (default click behavior)
		        window.location.hash = hash;
		      });
		    } // End if
		  });

		});
	</script>
	