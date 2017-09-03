<?php 
    require_once'../core/connect.php';
    if(!is_logged_in()){
        header('location:login.php');
    }
    include('includes/head.php'); 
    include('includes/nav.php');
    // session_destroy();
   
?>
<h1>Admin</h1>

<?php include('includes/footer.php');
?>


