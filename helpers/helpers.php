<?php
function display_error($errors){
    $display ='<ul class="bg-danger">';
    foreach($errors as $error){
        $display .='<li class="text-danger">'.$error.'</li>';
    }
 $display .='</ul>';
return $display;
}
function sanitize($dirty){
  return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}
function money($number){
  return '# '.number_format($number, 2);
}

function login($user_id){
  $_SESSION['user']=$user_id;
  global $conn;
  $date = date("Y-m-d H:i:s");
  $conn->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
  $_SESSION['success_flash']="You are logged in.";
  header('location:index.php');
}
function is_logged_in(){
  if(isset($_SESSION['user']) && $_SESSION['user'] > 0){
    return true;
  }
  return false;
}

function loggin_error_redirect($url = 'login.php'){
  $_SESSION['error_flash'] = "You must be loggin to access this page";
  header('location:'.$url);
}

//permission functions
function permission_error_redirect($url = 'login.php'){
  $_SESSION['error_flash'] = "You don't have access to that page";
  header('location:'.$url);
}


 function has_permission($permission = 'admin'){
   global $user_data;
   $permissions = explode(',', $user_data['permission']);
   if(in_array($permission,$permissions,true)){
     return true;
   }
   return false;
 }

 function pretty_date($date){
   return date("M,d Y h:i A",strtotime($date));
 }
 //get category
 function get_category($id){
   $id = sanitize($id);
   global $conn;
   $sql = "SELECT p.id AS 'pid', p.category AS 'parent',c.id AS 'cid', c.category AS 'child'
            FROM categories c
            INNER JOIN categories p
            ON c.parent = p.id
            WHERE c.id = '$id'";
    $query = $conn->query($sql);
    $category = mysqli_fetch_assoc($query);
    return $category;
 }

 function sizesToArray($string){
   $sizeArray = explode(',',$string);
   $returnArray = array();
   foreach($sizeArray as $size){
     $s = explode(':',$size);
     $returnArray[]= array('size'=> $s[0],'quantity' =>$s[1]);
   }
   return $returnArray;
 }

 function sizesToString($sizes){
   $sizeString = '';
   foreach($sizes as $size){
     $sizeString = $size['size'].':'.$size['quantity'].',';
   }
   $trimmed = rtrim($sizeString,',');
   return $trimmed;
 }


?>
