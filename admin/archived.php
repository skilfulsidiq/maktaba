<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';
  include 'includes/head.php';
  include 'includes/nav.php';


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
    $result=$conn->query("SELECT * FROM product WHERE deleted = 1");
?>
<table class="table table-bordered table-condense table-striped">
  <thead><th></th><th>Product</th><th>Price</th><th>Category</th><th>Sold</th></thead>
  <tbody>
    <?php while($product = mysqli_fetch_assoc($result)) :
         //getting the child id
          $childId = $product['category'];
          $cquery = "SELECT * FROM categories WHERE id ='$childId'";
          $result = $conn->query($cquery);
          $cresult = mysqli_fetch_array($result);
          //getting the parent id
          $parentId = $cresult['parent'];
          $pquery = "SELECT * FROM categories WHERE id ='$parentId'";
          $result = $conn->query($pquery);
          $presult = mysqli_fetch_assoc($result);
          $category = $presult['category'].'-'.$cresult['category'];
          ?>
      <tr>
        <td class="text-center">
        <a href="product.php?archived=<?=$product['id'];?>" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-refresh"></span></a>
        <td><?=$product['title'];?></td>
        <td><?=money($product['price']);?></td>
        <td><?=$category;?></td>
        <td><?=$product['sold'];?></td>
      </tr>

    <?php endwhile; ?>
  </tbody>
</table>

  <?php include 'includes/footer.php';?>