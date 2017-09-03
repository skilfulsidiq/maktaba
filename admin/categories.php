<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';
  if(!is_logged_in()){
        loggin_error_redirect();
    }
  include 'includes/head.php';
  include 'includes/nav.php';

  $sql= "SELECT * FROM categories WHERE parent=0";
  $result = $conn->query($sql);
  $error  = array();

  //delete brand
  if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $del = (int)$_GET['delete'];
    // $del = sanitize($del);
    $delsql = "SELECT * FROM categories WHERE id = '$del'";
    $delresult = $conn->query($delsql);
    $delrow = mysqli_fetch_assoc($delresult);
    if ($delrow['parent'] == 0) {
      $delpar = "DELETE FROM categories WHERE parent = '$del'";
      $conn->query($delpar);
    }
    $sql = "DELETE FROM categories WHERE id = '$del'";
    $conn->query($sql);
    header('location:categories.php');
  }
    // EDIT A BRAND
  if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $sql2 = "SELECT * FROM categories WHERE id='$edit_id'";
    $result2 =$conn->query($sql2);
    $edit_category = mysqli_fetch_assoc($result2);
  }
    $category ='';
    $post_parent = '';
  //process form
  if (isset($_POST) && !empty($_POST)) {
    $post_parent = $_POST['parent'];
    $category = $_POST['category'];
    $sqlform = "SELECT * FROM categories WHERE category ='$category' AND parent ='$post_parent'";
    if (isset($_GET['edit'])) {
      $id = $edit_category['id'];
        $sqlform = "SELECT * FROM categories WHERE category ='$category' AND parent ='$post_parent' AND id !='$id'";# code...
    }
    $fresult = $conn->query($sqlform);
    $count = mysqli_num_rows($fresult);
    //if category is balnk
    if ($category =="") {
      $error[].='The category cannot be blank';
    }
    //category exist
    if ($count > 0) {
        $error[] .= $category.' already exist. please choose a new catagory';
    }

    //display and update
    if (!empty($error)) {
        $display = display_error($error); ?>
        <script>
        jQuery('document').ready(function(){
          jQuery('#errors').html('<?=$display; ?>');
        });
        </script>
    <?php }else {
      $savecat = "INSERT INTO categories (category, parent) VALUES('$category', $post_parent)";
      //Update Existing category
      if (isset($_GET['edit'])) {
        $savecat= "UPDATE categories SET category = '$category',parent ='$post_parent' WHERE id ='$edit_id'";
        header('Localtion:categories.php');
      }
      $conn->query($savecat);
      header('location:categories.php');
    }

  }
  $category_value = '';
  $parent_value = '';
  if (isset($_GET['edit'])) {
    $category_value = $edit_category['category'];
    $parent_value = $edit_category['parent'];
  }else {
    if (isset($_POST)) {
      $category_value = $category;
      $parent_value = $post_parent;
    }
  }
?>
<h2 class="text-center">Categories</h2><hr>
<div class="row">
  <!-- FORM -->
  <div class="col-md-6">
        <!-- action:if edit button is click action should Focus on id of the edit button click -->
    <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">


      <legend><?=((isset($_GET['edit']))?'Edit':'Add A');?>category</legend>
      <div id="errors"></div>
      <div class="form-group">
        <label for="parent">Parent</label>
        <select class="form-control" name="parent" id="parent">
          <option value="0"<?=(($parent_value == 0))?'selected="selected"':'' ?>>Parent</option>
          <?php while($parent = mysqli_fetch_assoc($result)): ?>
          <option value="<?=$parent['id'];?>"<?=(($parent_value == $parent['id']))?'selected="selected"':'' ?>><?=$parent['category']; ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="category">Category</label>
        <!-- if edit button is clicked Populate the input box with the value -->
        <input type="text" name="category" id="category" class="form-control" value="<?=$category_value ;?>">
      </div>
      <div class="form-group">
        <?php
          if (isset($_GET['edit'])):?>
            <a href="categories.php" class="btn btn-success">Cancel</a>
          <?php endif; ?>
        <input type="submit" name="submit" value="<?=((isset($_GET['edit']))?'Edit Category':'Add Category');?>" class="btn btn-primary ">
      </div>

    </form>

  </div>
  <!--categories  -->
  <div class="col-md-6">
    <table class="table table-bordered">
      <thead>
        <th>Categories</th><th>Parent</th><th></th>
      </thead>
      <tbody>
        <?php
        $sql= "SELECT * FROM categories WHERE parent=0";
        $result = $conn->query($sql);
        while($cat = mysqli_fetch_assoc($result)):
          $parent_id =(int)$cat['id'];
          $sql2 ="SELECT * FROM categories WHERE parent ='$parent_id'";
          $cresult = $conn->query($sql2);
          ?>
        <tr class="bg-primary">
          <td><?=$cat['category'];?></td>
          <td>Parent</td>
          <td>
            <!-- parent edit and delete button -->
            <a href="categories.php?edit=<?=$cat['id']; ?>" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="categories.php?delete=<?=$cat['id']; ?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a>
          </td>
        </tr>
        <?php while($cat2 = mysqli_fetch_assoc($cresult)) :?>
          <tr class="bg-info">
            <td><?=$cat2['category'];?></td>
            <td><?=$cat['category']; ?></td>
            <td>
              <!-- child edit and delete button -->
              <a href="categories.php?edit=<?=$cat2['id']; ?>" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
              <a href="categories.php?delete=<?=$cat2['id']; ?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a>
            </td>
          </tr>

        <?php endwhile; ?>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>
<?php include 'includes/footer.php'; ?>
