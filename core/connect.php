<?php
$user = 'root';
$pass = '';
$host = 'localhost';
$db = 'ecommerce';

$conn = mysqli_connect($host,$user,$pass,$db);
if(mysqli_connect_error()){
    echo "Database connection failed: ".mysqli_connect_error();
    die();
}

require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/config.php';
require_once (BASEURL.'helpers/helpers.php');
?>
