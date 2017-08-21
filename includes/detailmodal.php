<!-- Detail Modal -->
<?php
require_once '../core/connect.php';
$id =(int)$_POST['id'];
$id = sanitize($id);
$sql = "SELECT * FROM product WHERE id ='$id'";
$result = $conn->query($sql);
$product = mysqli_fetch_assoc($result);
 //brand table
$brand_id = $product['brand_id'];
$sql2 ="SELECT brand FROM brand WHERE id ='$brand_id'";
$brand_query = $conn->query($sql2);
$brand = mysqli_fetch_assoc($brand_query);
$sizestring = $product['size'];
$sizestring = rtrim($sizestring,',');
$size_array = explode(',',$sizestring);


?>
  <?php ob_start(); ?>
    <div class="modal fade details" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" onclick="closeModal()" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-center"><?php echo $product['title'];?></h4>
                </div>
                <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6 details">
                            <div class="center-block">
                                <img src="<?php echo $product['image']; ?>" alt="<?= $product['title']; ?>" class="details img-responsive" />
                            </div>
                        </div>
                            <div class="col-sm-6">
                            <h4>Details</h4>
                                <p><?php echo  nl2br($product['description']); ?></p>
                                <hr>
                                <p>Price: #<?php echo $product['price'];?></p>
                                <p>Brand:<?php echo $brand['brand'];?> </p>
                                <form action="add_cart.php" method="post">
                                <div class="form-group">
                                    <div class="col-xs-3">
                                        <label for="quantity">Quantity</label>
                                        <input type="text" class="form-control" id="quantity" name="quantity">
                                    </div>
                                </div><br><br>
                                    <div class="form-group">
                                    <label for="size">Size</label>
                                    <select name="size" id="size" class="form-control">
                                        <option value="">Select Your Size</option>
                                        <?php
                                          foreach ($size_array as $string) {
                                            $str_array = explode(':',$string);
                                            $size = $str_array[0];
                                            $quantity = $str_array[1];
                                              echo '<option value="'.$size.'">'.$size.'  ('.$quantity.' Avaliable)</option>';# code...
                                          }
                                        ?>
                                    </select>
                                    </div>

                                </form>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button class="btn btn-default" onclick="closeModal()" data-dismiss="modal">Close</button>
                    <button class="btn btn-warning" type="submit"><span class="glyphicon glyphicon-shopping-cart"></span>Add To Cart</button>
                </div>
            </div>
        </div>

    </div>
    <script>
      function closeModal(){
        jQuery('#details-modal').modal('hide');
        setTimeout(function(){
          jQuery('#details-modal').remove();
          jQuery('.modal-backdrop').remove();
        },500);
      }
    </script>
<?php echo ob_get_clean(); ?>
