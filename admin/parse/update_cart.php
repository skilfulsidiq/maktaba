<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';

    $mode = sanitize($_POST['mode']);
    $edit_size = sanitize($_POST['edit_size']);
    $edit_id = sanitize($_POST['edit_id']);

    $updateQ = $conn->query("SELECT * FROM cart WHERE id='{$cart_id}'");
    $result = mysqli_fetch_assoc($updateQ);
    $items_decode = json_decode($result['items'],true);

    $updated_item = array();
  $domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);

    if($mode == 'removeone'){
        foreach($items_decode as $item){
            if($item['id']== $edit_id && $item['size']==$edit_size){
                $item['quantity'] = $item['quantity'] - 1;
            }
            if($item['quantity'] > 0){
                $updated_item[] = $item;
            }
        }
    }
     if($mode == 'addone'){
        foreach($items_decode as $item){
            if($item['id']== $edit_id && $item['size']==$edit_size){
                $item['quantity'] = $item['quantity'] + 1;
            }
           $updated_item[] = $item;
           
        }
    }
  // if updated item is not empty
  if(!empty($updated_item)){
      $updated_json = json_encode($updated_item);
      $conn->query("UPDATE cart SET items='{$updated_item}' WHERE id = '{$cart_id}'");
      $_SESSION['success_flash']="Your shopping cart is being updated.";
  }
  //if updated item is empty
  if(empty($updated_item)){
      $conn->query("DELETE FROM cart WHERE id ='{$cart_id}'");
      setcookie(CART_COOKIE,'',1,'/',$domain,false);
  }




?>