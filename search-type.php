<?php
include 'includes/connection.php';

  $query =$con->query("SELECT DISTINCT(type_name) FROM document_type WHERE type_name LIKE '%".$_POST["type"]."%'");
  $result = $query->num_rows;
  if(!empty($result)) {
  ?>
  <ul id="name-type">
  <?php
  while($fetch = mysqli_fetch_array($query)){
    $type = $fetch["type_name"];
  ?>
  <li onClick="selectType('<?php echo $type; ?>');"><?php echo $type; ?></li>
  <?php } ?>
  </ul>
<?php }  ?>