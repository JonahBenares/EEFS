<?php
include 'includes/connection.php';

  $query =$con->query("SELECT DISTINCT(location_name) FROM document_location WHERE location_name LIKE '%".$_POST["location"]."%'");
  $result = $query->num_rows;
  if(!empty($result)) {
  ?>
  <ul id="name-type">
  <?php
  while($fetch = mysqli_fetch_array($query)){
    $location = $fetch["location_name"];
  ?>
  <li onClick="selectLocation('<?php echo $location; ?>');"><?php echo $location; ?></li>
  <?php } ?>
  </ul>
<?php }  ?>