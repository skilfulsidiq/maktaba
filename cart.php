<?php
    require_once 'core/connect.php';
    include 'includes/head.php';
    include 'includes/nav.php';
    include 'includes/headerPartial.php';

    //check if cart is not empty
    if($cart_id != ''){
        $cartQ = $conn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
        $result = mysqli_fetch_assoc($cartQ);
        //decode item json
        $items = json_decode($result['items'],true);
        $i = 1;
        $sub_total = 0;
        $item_count = 0;

       
    }
    // if(isset($_POST['checkoutbtn'])){
    //     header('location:thankyou.php');
    // }
    
?>
<div class="col-md-12">
    <div class="row">
        <h2 class="text-center">My Shopping Cart</h2><hr>
        <?php if($cart_id == ''): ?>
            <div class="bg-danger">
                <p class="text-danger text-center">Your Shopping Cart is empty!</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <th>#</th><th>Item</th><th>Price</th><th>Quantity</th><th>Size</th><th>Sub-Total</th>
                    </thead>
                    <tbody>
                    <?php
                        foreach($items as $item){
                            $product_id = $item['id'];
                            $productQ = $conn->query("SELECT * FROM product WHERE id='{$product_id}'");
                            $product = mysqli_fetch_assoc($productQ);
                            $sArray = explode(',',$product['size']);
                            foreach($sArray as $sizeString){
                                $s = explode(':',$sizeString);
                                if($s[0] == $item['size']){
                                    $available = $s[1];
                                }
                            } ?>
                            <tr>
                                <td><?=$i; ?> </td>
                                <td><?=$product['title']; ?>   </td>
                                <td><?=money($product['price']); ?>  </td>
                                <td>
                                    <button class="btn btn-xs btn-warning" onclick="update_cart('removeone','<?=$product['id'];?>','<?=$item['size'];?>')" >-</button>
                                    <?=$item['quantity']; ?>
                                    <?php if($item['quantity']< $available):?>
                                         <button class="btn btn-xs btn-info" onclick="update_cart('addone','<?=$product['id'];?>','<?=$item['size'];?>')" >+</button>
                                    <?php else:?>
                                        <span class="text-danger">Max.</span>
                                    <?php endif;?>
                                
                                </td>
                                <td><?=$item['size']; ?></td>
                                <td><?=money($item['quantity'] * $product['price']); ?></td>
                            </tr>


                    <?php $i++;
                        $item_count += $item['quantity'];
                        $sub_total +=  ($item['quantity'] * $product['price']);
                        } 
                            $tax = TAXRATE * $sub_total;
                            // $tax = number_format($tax, 2);
                            $grand_total = $tax + $sub_total;
                        ?>
                    </tbody>
                </table>
            
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed table-striped text-right">
                        <legend class="text-center">Totals</legend>
                    <thead >
                        <th style="text-align:center;">Total Items</th>
                        <th style="text-align:center;">Sub-Total</th>
                        <th style="text-align:center;">Tax</th>
                        <th style="text-align:center;">Grand Total</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=$item_count;?></td>
                            <td> <?=money($sub_total); ?> </td>
                            <td><?=money($tax); ?></td>
                            <td class="bg-success"><?=money($grand_total); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- CHECKOUT BUTTON -->
            <button type="button" class="btn btn-primary btn-md pull-right" data-toggle="modal" data-target="#checkoutModal">
            <span class="glyphicon glyphicon-shopping-cart"></span> Check Out <span class="glyphicon glyphicon-forward"></span>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="thankyou.php" method="post" id="payment-form">
                            <span class="bg-danger" id="payment-error"></span>
                            <input type="hidden" name="tax" value="<?=$tax;?>">
                            <input type="hidden" name="sub_total" value="<?=$sub_total;?>">
                            <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
                            <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
                            <input type="hidden" name="description" value="<?=$item_count.' item'.(($item_count > 1)?'s':'').'from Maktaba Store.' ;?>">
                            <div id="step1" style="display:block">
                                <div class="form-group col-md-6">
                                    <label for="full_name">Full Name:</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email:</label>
                                    <input type="text" name="email" id="email" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address">Address:</label>
                                    <textarea rows ="4" name="address" id="address" class="form-control"></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="busstop">Nearest Bus-Stop:</label>
                                    <input type="text" name="busstop" id="busstop" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="state">State:</label>
                                    <input type="text" name="state" id="state" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Mobile Number:</label>
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <div id="step2" style="display:none">
                                <div class="form-group col-md-12">
                                    <!-- <form action="" method="post"> -->
                                        <fieldset>
                                            <div class="form-group  col-md-4">
                                                <label for="cod">Cash on Delivery:</label>
                                                <input type="radio" id="cod" name="radio" value="cod1" class="form-control">
                                            </div>
                                            <div class="form-group  col-md-4">
                                                <label for="op">Online Payment:</label>
                                                <input type="radio" id="op" name="radio" value="op1" class="form-control">
                                            </div>
                                           
                                        </fieldset>
                                    <!-- </form> -->
                                </div>
                                <div id="cod1" class="cod1 desc" style="display:none;">
                                    <h3 class="text-center text-primary">Cash on Delivery Selected</h3>
                                </div>
                                <div id="op1" class="op1 desc" style="display:none;">
                                        <h3><h3 class="text-center text-primary">Enter Your Card Details</h3>
                                        <div class="form-group col-md-3">
                                            <label for="name">Name On Card:</label>
                                            <input type="text" id="name" class="form-control">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="number">Card Number:</label>
                                            <input type="text" id="number" class="form-control">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="cvc">CVC:</label>
                                            <input type="text" id="cvc" class="form-control">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exp_month">Expire Month:</label>
                                            <select  id="exp_month" class="form-control">
                                                <option value=""></option>
                                                <?php for($i=1; $i <13; $i++):?>
                                                <option value="<?=$i;?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exp_year">Expire year:</label>
                                            <select  id="exp_year" class="form-control">
                                                <option value=""></option>
                                                <?php 
                                                $yr = date("Y");
                                                for($i=0; $i <11; $i++):?>
                                                <option value="<?=$yr +$i;?>"><?php echo $yr+$i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" onclick ="back_address();" id="back_btn"style="display:none;"><span class="glyphicon glyphicon-backward"></span> Back </button>
                    <button type="button" class="btn btn-primary" onclick ="check_address();" id= "next_btn">Next <span class="glyphicon glyphicon-forward"></span></button>
                    <button type="submit" name = "checkoutbtn" class="btn btn-success" id ="checkout_btn" style="display:none;"><span class="glyphicon glyphicon-shopping-cart"></span> Check Out</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<script>
    function back_address(){
         jQuery('#payment-error').html("");
            jQuery('#step1').css("display","block");
            jQuery('#step2').css("display","none");
            jQuery('#next_btn').css("display","inline-block");
            jQuery('#back_btn').css("display","none");
            jQuery('#checkout_btn').css("display","none");
            jQuery('#checkoutModalLabel').html("Shipping Address");
    }
    function check_address(){
        var data ={
            'full_name' : jQuery('#full_name').val(),
            'email' : jQuery('#email').val(),
            'address' : jQuery('#address').val(),
            'busstop' : jQuery('#busstop').val(),
            'state' : jQuery('#state').val(),
            'phone' : jQuery('#phone').val(),
        };
        jQuery.ajax({
            url : '/ecommerce/admin/parse/check_address.php',
            method: 'post',
            data: data,
            success:function(data){
                if(data != 'passed'){
                    jQuery('#payment-error').html(data); 
                }
                if(data == 'passed'){
                    jQuery('#payment-error').html("");
                    jQuery('#step1').css("display","none");
                    jQuery('#step2').css("display","block");
                    jQuery('#next_btn').css("display","none");
                    jQuery('#back_btn').css("display","inline-block");
                    jQuery('#checkout_btn').css("display","inline-block");
                    jQuery('#checkoutModalLabel').html("Select Your Payment Method");

                }
            },
            error:function(){alert("something went wrong");}
        });
    }
    //Payment method selection
    jQuery(document).ready(function(){
        $('input[type="radio"]').click(function(){
            var inputvalue = $(this).attr("value");
            var targetBox = $("." + inputvalue);
            $(".desc").not(targetBox).hide();
            $(targetBox).show();
        });
    });
</script>

<?php include 'includes/footer.php'; ?>