<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/connect.php';
  include 'includes/head.php';

//   $password = 'humble';
//   $hashed = password_hash($password, PASSWORD_DEFAULT);
//   echo $hashed;

    $email=((isset($_POST['email']))?sanitize($_POST['email']):'');
    $email = trim($email);
    $password=((isset($_POST['password']))?sanitize($_POST['password']):'');
    $passsword = trim($password);

    $errors = array();
?>
<style>
     #login-form{
      width:50%;
      height:60%;
      background:#fff;
      border: 2px solid #000;
      box-shadow: 7px 7px 15px rgba(#000);
      border-radius: 15px;
      margin:7% auto;
      padding:15px; 
     }
     body{
         background-image:url("/ecommerce/images/other/background.jpg");
         background-size:100vw 100vh;
         background-attachment:fixed;
     }

</style>
    <div id="login-form">
        <div>
            <?php 
                //form validation
                if(empty($_POST['email']) || empty($_POST['password'])){
                   $errors[]="You must provide email and password.";
                }
                //validate email
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $errors[]="Input must be an email address.";
                }
                //check the password length
                if(strlen($password)<6){
                    $errors[]="password must be at least 6 character.";
                }
                //check if email exist in the database
                $query = $conn->query("SELECT * FROM users WHERE email='$email'");
                $user = mysqli_fetch_assoc($query);
                $userCount = mysqli_num_rows($query);
                if($userCount<1){
                    $errors[]="That email doesn't exist in our Database.";
                }
                //hashed password and validate
                if(!password_verify($password,$user['password'])){
                    $errors[]="Password doesn't match our record, please try again.";
                }


                //chack error
                if(!empty($errors)){
                    echo display_error($errors);
                }else{
                    //log in user
                    echo "U";
                }
            
            ?>


        </div>
        <h2 class="text-center">Login</h2>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="<?=$email;?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="password" value="<?=$passsword;?>">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary" value="login">
            </div>
        </form>
        <p class="text-right"><a href="/ecommerce/index.php">visit site</a></p>
        
    </div>

<?php include 'includes/footer.php';?>