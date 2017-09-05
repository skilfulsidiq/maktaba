<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';
    //sanitize all the field input
     $name = sanitize($_POST['full_name']);
     $email = sanitize($_POST['email']);
     $address = sanitize($_POST['address']);
     $busstop = sanitize($_POST['busstop']);
     $state = sanitize($_POST['state']);
     $phone = sanitize($_POST['phone']);

     $error= array();

     $required = array(
         'full_name'  => 'Full Name',
         'email'      => 'Email',
         'address'    =>  'Address',
         'busstop'    =>  'Bus-Stop',
         'state'      =>  'State',
         'phone'      =>  'Mobile Number',
     );

     //check for empty field
     foreach($required as $f => $d){
         if(empty($_POST[$f]) || $_POST[$f] == ''){
             $error[] = $d.' is requred';
         }
     }

     //check for email address
     if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error[] = "Enter a valid Email";
     }


    //display error
    if(!empty($error)){
       echo display_error($error);
    }else{
        echo "passed";
    } 
?>