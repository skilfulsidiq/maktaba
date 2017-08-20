<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';
$parentID = (int)$_POST['parentID'];
$childquery = $conn->query("SELECT * FROM catagories WHERE parent = '$parentID' ORDER BY category");

ob_start();
?>
  <option value=""></option>
  <?php while($child = mysqli_fetch_assoc($childquery)):?>
  <option value="<?=$child['id'];?>"><?=$child['category'];?></option>
  <?php endwhile; ?>
<?php echo ob_get_clean();?>
