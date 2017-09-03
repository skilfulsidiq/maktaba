<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';
  include 'includes/head.php';

//   $password = 123456;
//   $has = password_hash($password, PASSWORD_DEFAULT); echo $has;

    $hashed = $user_data['password'];
    $old_password=((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
    $old_password = trim($old_password);
    $password=((isset($_POST['password']))?sanitize($_POST['password']):'');
    $password = trim($password);
     $confirm=((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
    $confirm = trim($confirm);

    $new_hashed = password_hash($password, PASSWORD_DEFAULT);
    $user_id = $user_data['id'];

    $errors = array();
?>
<style>
     #login-form{
      width:50%;
      height:auto;
      background:#fff;
      border: 2px solid #000;
      box-shadow: 7px 7px 15px rgba(#000);
      border-radius: 15px;
      margin:7% auto;
      padding:15px; 
     }
</style>
    <div id="login-form">
        <div>
            <?php 
                //form validation
                if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
                   $errors[]="filled all the fields.";
                }
                //check the password length
                if(strlen($password)<6){
                    $errors[]="password must be at least 6 character.";
                }
                //check if new password matches the confirm

                if($password != $confirm){
                    $errors[]= "Your new password and confirm password does not match.";
                }

                //hashed password and validate
                if(!password_verify($old_password,$hashed)){
                    $errors[]= "Your old Password does not match our record, please try again.";

                }


                //chack error
                if(!empty($errors)){
                    echo display_error($errors);
                }else{
                   //change password
                   $conn->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
                   $_SESSION['sucess_flash'] = "Your password has been updated!";
                   header('location:index.php'); 
                }
            
            ?>


        </div>
        <h2 class="text-center">Change Password</h2>
        <form action="change_password.php" method="post">
            <div class="form-group">
                <label for="old_password">Old Password:</label>
                <input type="password" name="old_password" id="old_password" class="form-control" placeholder="old password"
                 value="<?=$old_password;?>">
            </div>
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="password" value="<?=$password;?>">
            </div>
             <div class="form-group">
                <label for="confirm">Confirm Password:</label>
                <input type="password" name="confirm" id="confirm" class="form-control" placeholder="confirm password" value="<?=$confirm;?>">
            </div>
            <div class="form-group">
                <a href="index.php" class="btn btn-default">Cancel</a>
                <input type="submit" class="btn btn-primary" value="Change Password">
            </div>
        </form>
        <p class="text-right"><a href="/ecommerce/index.php">visit site</a></p>
        
    </div>

<?php include 'includes/footer.php';?>