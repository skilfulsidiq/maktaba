<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';
$parentID = (int)$_POST['parentID'];
$selected = sanitize($_POST['selected']);
$childquery = $conn->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category");
ob_start();
?>
  <option value=""></option>
  <?php while($child = mysqli_fetch_assoc($childquery)):?>
  <option value="<?php echo $child['id'];?>"<?=(($selected == $child['id'])?' selected':'') ?> ><?php echo $child['category'];?></option>
  <?php endwhile; ?>
<?php echo ob_get_clean();?>
