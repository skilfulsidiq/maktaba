<h3 class="text-center">Shopping Cart</h3>
<div>
    <?php if(empty($cart_id)):?>
    <p>Your Shopping Cart is empty</p>
<?php else: 
     $cartQ = $conn->query("SELECT * FROM cart WHERE id ='{$cart_id}'");
     $cartR = mysqli_fetch_assoc($cartQ);
     $items = json_decode($cartR['items'], true);
     
     $sub_total = 0;
     ?>
     <table class="table table-condensed" id="cart_widget">
        <tbody>
            <?php 
                foreach($items as $item):
                    $productQ = $conn->query("SELECT * FROM product WHERE id ='{$item['id']}'");
                    $productR = mysqli_fetch_assoc($productQ);
                
            ?>
            <tr>
                <td><?=$item['quantity']; ?></td>
                <td><?=substr($productR['title'],0,15);?></td>
                <td><?=money($item['quantity']*$productR['price']);?></td>
            </tr>
            <?php $sub_total +=$item['quantity']*$productR['price'];?>
            <?php endforeach; ?>
            <tr>
                    <td></td>
                    <td>Sub-Total</td>
                    <td><?=money($sub_total);?></td>
            </tr>
        </tbody>
     </table>
     <a href="cart.php" class="btn btn-xs btn-primary pull-right">View Cart</a>
     <div class="clearfix"></div>


<?php endif; ?>
</div>