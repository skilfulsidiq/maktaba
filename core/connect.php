<?php
$user = 'skilfulsidiq';
$pass = 'humble';
$host = 'localhost';
$db = 'ecommerce';

$conn = mysqli_connect($host,$user,$pass,$db);
if(mysqli_connect_error()){
    echo "Database connection failed: ".mysqli_connect_error();
    die();
}
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/config.php';
require_once BASEURL.'helpers/helpers.php';
// require BASEURL.'vendor/autoload.php';

//set id for th cart
$cart_id = '';
if(isset($_COOKIE[CART_COOKIE])){
    $cart_id = sanitize($_COOKIE[CART_COOKIE]);

}

//get user dtata
if(isset($_SESSION['user'])){
    $user_id = $_SESSION['user'];
    $query = $conn->query("SELECT * FROM users WHERE id='$user_id'");
    $user_data = mysqli_fetch_assoc($query);
    $fn=explode(" ",$user_data['full_name']);
    $user_data['first'] = $fn[0];
    $user_data['last']=$fn[1];
}

//check the success flash session
if(isset($_SESSION['success_flash'])){
    echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
    unset($_SESSION['success_flash']);
}
//check for the erroe session
if(isset($_SESSION['error_flash'])){
    echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
    unset($_SESSION['error_flash']);
}
// session_destroy();

?>
