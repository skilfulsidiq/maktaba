<?php
    require_once '../core/connect.php';
    include('includes/head.php');
    include('includes/nav.php');
// get brand from database
$sql = "SELECT * FROM brand ORDER BY brand";
$result = $conn->query($sql);
$errors = array();


//delete brand
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
  $del = (int)$_GET['delete'];
  // $del = sanitize($_GET['delete']);
  echo $del;
  // $del = sanitize($_GET['delete']);
  $sql = "DELETE FROM brand WHERE id = '$del'";
  $conn->query($sql);
  header('location:brand.php');
}
  // EDIT A BRAND
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
  $edit_id = (int)$_GET['edit'];
  // $edit_id = sanitize($_GET['edit']);
  $sql2 = "SELECT * FROM brand WHERE id='$edit_id'";
  $result2 =$conn->query($sql2);
  $query_result = mysqli_fetch_assoc($result2);
}
//Add new Brand process
if(isset($_POST['submit'])){
  $brand =$_POST['brand'];
   //if it is blank
  if($_POST['brand']==''){
    $errors[].="you must enter a Brand";
  }//if brand exist
  $sql="SELECT * FROM brand WHERE brand = '$brand'";
    if (isset($_GET['edit'])) {
      //Populate the brand in the input box for edit
      $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";
    }
    $result = $conn->query($sql);
    $count = mysqli_num_rows($result);
    if($count > 0){
        $errors[].= "$brand already exists. Please choose another brand....";
    }//display error
  if(!empty($errors)){
    echo display_error($errors);
    }
  else{
//add brand
  $sql="INSERT INTO brand (brand) VALUES ('$brand')";
  //UPDATE or EDIT EXISTING brand
  if (isset($_GET['edit'])) {
    $sql= "UPDATE brand SET brand = '$brand' WHERE id ='$edit_id'";
  }
  $conn->query($sql);
  header('location:brand.php');
  }
}
?>
<h2 class="text-center">Brands</h2><hr>
<!--Brand Form-->
<div class="text-center">
                    <!-- //action:if edit button is click action should Focus on id of the edit button click -->
    <form class="form-inline" action="brand.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
      <div class="form-group">
        <!-- //if edit button is clicked Populate the input box with the value -->
        <?php
              $brand_value = '';
        if (isset($_GET['edit'])) {
          $brand_value = $query_result['brand'];
          # code...
        }else{
          if (isset($_POST['brand'])) {
            $brand_value = $_POST['brand'];
          }
        }

         ?>
            <label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add A');?> Brand:</label>
            <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value ?>">
            <?php
              if (isset($_GET['edit'])):?>
                <a href="brand.php" class="btn btn-primary">Cancel</a>
              <?php endif; ?>


            <input type="submit" name="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add'); ?> Brand" class="btn  btn-success" />
        </div>
    </form>
</div><hr>

<table class="table table-bordered table-striped table-auto table-condense">
    <thead>
        <th></th>
        <th>Brand</th>
        <th></th>
    </thead>
    <tbody>
        <?php while($brand = mysqli_fetch_assoc($result)):?>
        <tr class="bg-info">
            <td><a href="brand.php?edit=<?= $brand['id']; ?>" class="btn btn-xs btn-success "><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td><?=$brand['brand']; ?></td>
            <td><a href="brand.php?delete=<?= $brand['id']; ?>" class="btn btn-xs btn-danger "><span class="glyphicon glyphicon-remove-sign"></span></a></td>
        </tr>
        <?php endwhile ?>
    </tbody>
</table>

<?php
include 'includes/footer.php';
?>
