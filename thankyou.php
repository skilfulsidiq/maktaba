<?php
    require_once 'core/connect.php';

// stripe/stripe::setApikey(STRIPE_PRIVATE);
//get the post data
$fullname = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$address = sanitize($_POST['address']);
$busstop = sanitize($_POST['busstop']);
$state = sanitize($_POST['state']);
$phone = sanitize($_POST['phone']);
$tax = sanitize($_POST['tax']);
$cart_id = sanitize($_POST['cart_id']);
$description = sanitize($_POST['description']);
$sub_total = sanitize($_POST['sub_total']);
$grand_total = sanitize($_POST['grand_total']);
$charge_amount = number_format($grand_total, 2) * 100;
$metadate = array(
    "cart_id" => $cart_id,
    "tax"     => $tax,
    "sub_total" => $sub_total,
);
// $transactionDetails = $paystackLibObject->transactionDetails('transaction_id');
//creat paystack lib object
// $paystack_lib_object = \MAbiola\Paystack\Paystack::make();
try {
    // $authorization = $paystack_lib_object->startOneTimeTransaction('$charge_amount', $email);
    //we should probably save the reference and email here so we can match/update records
    //redirect to payment authorization URL

    //update inventory
    $itemq = $conn->query("SELECT * FROM cart WHERE id = '{cart_id}'");
    $iresult = mysqli_fetch_assoc($itemq);
    $items = json_decode($iresult['items'],true);
    foreach($items as $item){
        $newSizes = array();
        $item_id = $item['id'];
        $productQ = $conn->query("SELECT size FROM product WHERE id='{$item_id}'");
        $presult = mysqli_fetch_assoc($productQ);
        $sizes = sizesToArray($product['size']);
        foreach($sizes as $size){
            if($size['size']== $item['size']){
                $q = $size['quantity']-$item['quantity'];
                $newsizes = array('size'=> $size['size'], 'quantity'=>$q);
            }else{
                $newsizes = array('size'=>$size['size'], 'quantity'=>$size['quanity']);
            }
        }
        $sizeString = sizesToString($newsizes);
        $conn->query("UPDATE product SET size ='{$sizeString}' WHERE id='{$item_id}'");
    }



    //update cart
    $conn->query("UPDATE cart SET paid = 1 WHERE id = '{cart_id}'");
    $conn->query("INSERT INTO transaction
    (charged_id,cart_id,full_name,email,address,busstop,state,sub_total,tax,grand_total,description,txn_type,txn_date)VALUES
    ('$charged_id',$cart_id','$fullname','$email','$address','$busstop','$state','$sub_total','$tax','$grand_total','$description'
    '$transactionDetails')");

     $domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);
     setcookie(CART_COOKIE,'',1,'/',$domain,false);
     include 'includes/head.php';
     include 'includes/nav.php';
     include 'includes/headerPartial.php';
     ?>
        <h1 class="text-center text-success">Thank You !</h1>
        <p>Your Card has been successfully charged <?=money($grand_total);?>. Check your email for receipt.</p>
        <p>Your Receipt Number is <strong><?=$cart_id;?></strong></p>
        <p>Your order will shipped to the below address:</p>
        <address>
            <?=$fullname;?><br>
            <?=$address .' '.$busstop; ?><br>
            <?=$state; ?><br>
            <?=$phone; ?>
        </address>
      

    <?php
     include 'includes/footer.php';
    header('Location: ' . $authorization['authorization_url']);
} catch (Exception $e) {
    echo $e;
    // header("Location: error.php?error={$e->getMessage()}");
}


?>