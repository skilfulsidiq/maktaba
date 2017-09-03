<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';
if(!is_logged_in()){
        loggin_error_redirect();
    }
  include 'includes/head.php';
  include 'includes/nav.php';
  ?>
  <?php
  //  restore deleted product
  if(isset($_GET['archived'])){
        $id = sanitize($_GET['archived']);
        $conn->query("UPDATE product SET deleted = 0 WHERE id ='$id'");
        header('LOCATION:product.php');
    }
  ?>
  <h2 class="text-center"> Products Archived</h2>
<hr>
<?php 
    //query to fetch the deleted product
      $sql = "SELECT * FROM product WHERE deleted = 1";
    $result=$conn->query($sql);
?>
<table class="table table-bordered table-condense table-striped">
  <thead><th></th><th>Product</th><th>Price</th><th>Category</th><th>Sold</th></thead>
  <tbody>
    <?php while($archived = mysqli_fetch_assoc($result)) :
          ?>
      <tr>
        <td class="text-center">
        <a href="product.php?archived=<?php echo $archived['id'];?>" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-refresh"></span></a>
        <td><?=$archived['title'];?></td>
        <td><?=money($archived['price']);?></td>
        <td><?php 
        //getting the child id
          $childId = $archived['category'];
          $cquery = "SELECT * FROM categories WHERE id ='$childId'";
          $result = $conn->query($cquery);
          $cresult = mysqli_fetch_array($result);
          //getting the parent id
          $parentId = $cresult['parent'];
          $pquery = "SELECT * FROM categories WHERE id ='$parentId'";
          $result = $conn->query($pquery);
          $presult = mysqli_fetch_assoc($result);
          $category = $presult['category'].'-'.$cresult['category'];
        echo $category;?></td>
        <td><?=$archived['sold'];?></td>
      </tr>

    <?php endwhile; ?>
  </tbody>
</table>
 
  <?php include 'includes/footer.php';?>