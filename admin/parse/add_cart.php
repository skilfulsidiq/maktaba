<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';
    //set the vaiable
    $product_id = sanitize($_POST['product_id']);
    $size = sanitize($_POST['size']);
    $available = sanitize($_POST['available']);
    $quantity = sanitize($_POST['quantity']);

    $items = array();
    $items[]=array(
        'id'         => $product_id,
        'size'       => $size,
        'quantity'   =>$quantity,
    );

    $domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);
    $query = $conn->query("SELECT * FROM product WHERE id = '{$product_id}'");
    $product = mysqli_fetch_assoc($query);

    $_SESSION['success_flash'] = $product['title'].' has been added to your cart.';
    //check if cart exist
    if($cart_id !=''){
        $cartQ = $conn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
        $cart = mysqli_fetch_assoc($cartQ);
        $previous_items = json_decode($cart['items'],true);
        $item_match = 0;
        $new_item = array();
        foreach($previous_items as $pitem){
            if($items[0]['id']==$pitem['id'] && $items[0]['size']==$pitem['size']){
                $pitem['quantity']= $pitem['quantity'] + $items[0]['quanity'];
                if($pitem > $available){
                    $pitem = $available;
                }
                $item_match = 1;
            }
            $new_item[] = $pitem;
        }
        if($item_match != 1){
            $new_item = array_merge($items,$previous_items);
        }
        //ADD new item to the previous in the cart
         $items_json = json_encode($new_item);
        $cart_expire = date("Y-m-d H:s:i",strtotime(" +30 days"));
        $conn->query("UPDATE cart SET items ='{$items_json}',expire_date = '{$cart_expire}' WHERE id= '{$cart_id}'");
        setcookie(CART_COOKIE,'',1,'/',$domain,false);
        setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
    }else{
        //add cart to database and set cookie
        $items_json = json_encode($items);
        $cart_expire = date("Y-m-d H:s:i",strtotime(" +30 days"));
        $conn->query("INSERT INTO cart(items,expire_date) VALUES('{$items_json}','{$cart_expire}')");
        $cart_id = $conn->insert_id;

        setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);

    }

?>