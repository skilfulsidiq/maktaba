<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';
  include 'includes/head.php';
  include 'includes/nav.php';

  if (isset($_GET['add'])|| isset($_GET['edit'])) {
    //query to fecth brand
    $brandsql = $conn->query("SELECT * FROM brand ORDER BY brand");
    $parentsql = $conn->query("SELECT * FROM categories WHERE parent = 0");
    //for edit
    $title = ((isset($_POST['title']) && $_POST['title'] != '' )?sanitize($_POST['title']):'');
    $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
    $category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
    if (isset($_GET['edit'])) {
        $edit_id = (int)$_GET['edit'];
        //populate the input field for edit
        $editsql = $conn->query("SELECT * FROM product WHERE id = '$edit_id'");
        $product = mysqli_fetch_assoc($editsql);
        $category = ((isset($_POST['child']) && $_POST['child'] !='')?sanitize($_POST['child']):$product['category']);
        $title = ((isset($_POST['title']) && $_POST['title'] != '' )?sanitize($_POST['title']):$product['title']);
        $brand = ((isset($_POST['brand']) && $_POST['brand'] != '' )?sanitize($_POST['brand']):$product['brand_id']);
        //parent query for category
        $parentquery = $conn->query("SELECT * FROM categories WHERE id='$category'");
        $parentresult = mysqli_fetch_assoc($parentquery);
        $parent = ((isset($_POST['parent']) && $_POST['parent'] != '' )?sanitize($_POST['parent']):$parentresult['parent']);
    }
    //When Save Changes Button is clikced in the quantity and size modal
    if($_POST){


      $category = sanitize($_POST['child']);
      $price = sanitize($_POST['price']);
      $list_price = sanitize($_POST['list_price']);
      $size = sanitize($_POST['sizes']);
      $decription = sanitize($_POST['description']);
      $dbpath = '';
      if(!empty($_POST['sizes'])){
        $sizeString = sanitize($_POST['sizes']);
        $sizeString = rtrim($sizeArray,',');
        $sizeArray = explode(',',$sizeString);
        $sArray =array();
        $qArray = array();
        foreach ($sizeArray as $ss) {
          $s = explode(':',$ss);
          $sArray = $s[0];
          $qArray = $s[1];
        }
      }else{$sizeArray = array();}
      //form validation
      $required = array('title','brand','price','parent');//,'child'
      foreach ($required as $field) {
        if($_POST[$field]==''){
          $errors[] = 'All fields with Astrisk are required';
          break;

        }
      }
      //file upload validation
    if(!empty($_FILES)){
        var_dump($_FILES);
        $photo= $_FILES['photo'];
        $name = $photo['name'];
        $nameArray = explode('.',$name);
        $filename = $nameArray[0];
        $fileExt = $nameArray[1];
        $mine = explode('/',$photo['type']);
        $mineType = $mine[0];
        $mineExt = $mine[0][1];
        $location = $photo['tmp_name'];
        $fileSize = $photo['size'];
        $allowedImage = array('jpg','PNG','gif','jpeg','png');
        $uploadname = md5(microtime()).'.'.$fileExt;
          $uploadPath = BASEURL.'images/products/'.$uploadname;
        $dbpath = '/ecommerce/images/products/'.$uploadPath;
        if($mineType != 'image'){
          $errors[] = "File must be an image";
        }
        if (!in_array($fileExt,$allowedImage)) {
          $errors[]="The photo must either be jpg, png, jpeg or gif";
        }
        if ($fileSize > 10000000) {
            $errors[]="The file must not be greater than 10MB";
        }
        if ($fileExt != $mineExt && ($mineExt == 'jpeg' && $fileExt !='jpg')) {
            $errors[] = "File extension does not match the file";
        }

      }
      if(!empty($errors)){
        echo display_error($errors);
      }else{
        //upload files and insert details
        move_uploaded_file($location, $uploadPath);
        $insertsql = "INSERT INTO product(title,price,list_price,brand,category,image,decription,size)
        VALUES('$title','$price','$list_price','$brand','$category','$dbpath','$decription','$size')";
        $conn->query($insertsql);
        header('LOCATION:product.php');
      }
    }


  ?>
  <!-- ADD PRODUCT -->
  <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit ':'Add A New'); ?> Product</h2><hr>
  <form action="product.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="post" enctype="multipart/form-data">
    <!-- title -->
    <div class="form-group col-md-3">
      <label for="title">Title:* </label>
      <input type="text" name="title" class="form-control" id="title" value="<?=$title; ?>">
    </div>
    <!--Brand  -->
    <div class="form-group col-md-3">
      <label for="brand">Brand*: </label>
      <select class="form-control" id="brand" name="brand">
        <option value=""<?=(($brand == '')?' selected':''); ?>></option>
        <?php while($b = mysqli_fetch_assoc($brandsql)): ?>
        <option value="<?=$b['id']; ?>"<?=(($brand == $b['id'])?' selected':''); ?>><?=$b['brand']; ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <!-- PArent Category -->
    <div class="form-group col-md-3">
      <label for="parent">Parent categories*: </label>
        <select class="form-control" id="parent" name="parent">
          <option value=""<?=(($parent == '')?' selected':''); ?>></option>
          <?php while($p = mysqli_fetch_assoc($parentsql)): ?>
          <option value="<?=$p['id']; ?>"<?=(($parent == $p['id'])?' selected':''); ?>><?php echo $p['category']; ?></option>
          <?php endwhile; ?>
        </select>
    </div>
    <!--Child category  -->
    <div class="form-group col-md-3">
      <label for="child">Child categories: </label>
      <select class="form-control" id="child" name="child">
      </select>
    </div>
    <!-- Price -->
    <div class="form-group col-md-3">
      <label for="price">Price*: </label>
      <input type="text" id="price" name="price" class="form-control" value="<?=((isset($_POST['price']))?$_POST['price']:''); ?>">
    </div>
    <!-- List price -->
    <div class="form-group col-md-3">
      <label for="list_price">List Price: </label>
      <input type="text" id="list_price" name="list_price" class="form-control" value="<?=((isset($_POST['list_price']))?$_POST['list_price']:''); ?>">
    </div>
    <!-- Quantity and Size Button-->
    <div class="form-group col-md-3">
      <label>Quantity & sizes:</label>
      <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Quantity & Sizes</button>
    </div>
    <!-- Quantity and Size Text field -->
    <div class="form-group col-md-3">
      <label for="sizes">Quantity and Sizes</label>
      <input type="text" id="sizes" class="form-control" name="sizes" value="<?=((isset($_POST['sizes']))?$_POST['sizes']:''); ?>" readonly>
    </div>
    <!-- Product Photo -->
    <div class="form-group col-md-6">
      <label for="photo">Product Photo: </label>
      <input type="file" id="photo" name="photo" class="form-control">
    </div>
    <!--DEcription  -->
    <div class="form-group col-md-6">
      <label for="description">Description: </label>
      <textarea id="description" name="description" class="form-control" rows="6"><?=((isset($_POST['description']))?sanitize($_POST['description']):''); ?></textarea>
    </div>
    <!-- ADD Button -->
    <div class="form-group pull-right">
      <a href="product.php" class="btn btn-default">Cancel</a>
      <input type="submit" name="addProduct" value="<?=((isset($_GET['edit']))?'Edit ':'Add ');?>Product" class="btn btn-<?=((isset($_GET['edit']))?'success':'primary');?>">
    </div>
    <div class="clearfix"></div>
  </form>
  <!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Size & Quantity</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <?php for($i =1; $i <=12; $i++): ?>
            <div class="form-group col-md-4">
              <label for="size<?=$i; ?>">Size:</label>
              <input type="text" name="size<?= $i; ?>" id="size<?= $i; ?>" class="form-control" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>">
            </div>
            <div class="form-group col-md-2">
              <label for="qty<?= $i; ?>">Quantity:</label>
              <input type="number" name="quantity<?= $i; ?>" id="qty<?= $i; ?>" min="0" class="form-control" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>">
            </div>
          <?php endfor; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSize();jQuery('#sizesModal').modal('toggle');return false ;">Save changes</button>
      </div>
    </div>
  </div>
</div>
  <!-- end of modal -->
  <?php }else{
  //database
  //Populated product table
  $sql = "SELECT * FROM product WHERE deleted = 0";
  $pre_result = $conn->query($sql);

  //Toggle featured dynamically
  if (isset($_GET['featured'])) {
    $id = (int)$_GET['id'];
    $featured = (int)$_GET['featured'];
    $featuredSql = "UPDATE product SET featured ='$featured' WHERE id ='$id'";
    $conn->query($featuredSql);
    header('Location:product.php');
  }

?>
<!-- VIEW PRODUCT -->
<h2 class="text-center">Products</h2>
<a href="product.php?add=1" class="btn btn-success pull-right" id="add-product-add">Add Product </a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condense table-striped">
  <thead><th></th><th>Product</th><th>Price</th><th>Category</th><th>Featured</th><th>Sold</th></thead>
  <tbody>
    <?php while($product = mysqli_fetch_assoc($pre_result)) :
      // var_dump($product);
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
        <td class="text-center"><a href="product.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
        <a href="product.php?delete =<?=$product['id'];?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span></a></td>
        <td><?=$product['title'];?></td>
        <td><?=money($product['price']);?></td>
        <td><?=$category; ?></td>
        <td><a href="product.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-primary">
        <span class="glyphicon glyphicon-<?=(($product['featured']== 1)?'minus':'plus'); ?>"></span></a>&nbsp<?=(($product['featured']== 1)?'Featured Product':'');?></td>
        <td></td>
      </tr>

    <?php endwhile; ?>
  </tbody>
</table>
<?php }include 'includes/footer.php';?>
