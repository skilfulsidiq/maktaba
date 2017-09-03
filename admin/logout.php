<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';

unset($_SESSION['user']);
header('location:login.php');

?>