<?php 
    require_once'../core/connect.php';
    if(!is_logged_in()){
        loggin_error_redirect();
    }
    if(!has_permission('admin')){
        permission_error_redirect('brand.php');
    }
    include 'includes/head.php'; ?>
    <style> #add-user-btn{margin-top:-35px;}</style>

    <?php include('includes/nav.php'); 
    //delete button
    if(isset($_GET['delete'])){
        $delete_id = sanitize($_GET['delete']);
        $conn->query("DELETE FROM users WHERE id = '$delete_id'");
        $_SESSION['success_flash'] = "User has been deleted";
        header('location:users.php');
    }

    if(isset($_GET['add']) || isset($_GET['edit'])){
        $name =((isset($_POST['name']))?sanitize($_POST['name']) : '') ;
        $email =((isset($_POST['email']))?sanitize($_POST['email']) : '') ;
        $password =((isset($_POST['password']))?sanitize($_POST['password']) : '') ;
        $confirm =((isset($_POST['confirm']))?sanitize($_POST['confirm']) : '') ;
        $permission =((isset($_POST['permission']))?sanitize($_POST['permission']) : '') ;
        $photo =((isset($_POST['photo']))?sanitize($_POST['photo']) : '') ;
        $hashed = password_hash($password, PASSWORD_DEFAULT);


        //edit
        if(isset($_GET['edit'])){
            $edit_id = (int)$_GET['edit'];
            $sqledit = $conn->query("SELECT * FROM users WHERE id = '$edit_id'");
            $user = mysqli_fetch_assoc($sqledit);
            $name = ((isset($_POST['name']) && $_POST['name'] != '')?sanitize($_POST['name']): $user['full_name']);
            $email = ((isset($_POST['email']) && $_POST['email'] != '')?sanitize($_POST['email']): $user['email']);
            $password = ((isset($_POST['password']) && $_POST['password'] != '')?sanitize($_POST['password']): $user['password']);
            $confirm = ((isset($_POST['confirm']) && $_POST['confirm'] != '')?sanitize($_POST['confirm']): $user['password']);
            $permission = ((isset($_POST['permission']) && $_POST['permission'] != '')?sanitize($_POST['permission']): $user['permission']);
            $photo = (($user['passport'] !='')?$user['passport']:'');

        }
        //form validation
        $errors = array();
        if($_POST){
             if(isset($_GET['edit'])){
                
            }else{
            //check if email exist in the database
            $query = $conn->query("SELECT * FROM users WHERE email='$email'");
            $user = mysqli_fetch_assoc($query);
            $userCount = mysqli_num_rows($query);
                if($userCount != 0){
                    $errors[]="That email already exist in our Database.";
                }
            }
                

            
            $required = array('name','email','password','confirm','permission');
            foreach($required as $f){
                if(empty($_POST[$f])){
                    $errors[] = "You must filled all fields";
                    break;
                }
            }
             //check the password length
            if(strlen($password)<6){
                $errors[]="password must be at least 6 character.";
            }
            //check if new password matches the confirm

            if($password != $confirm){
                $errors[]= "Your password does not match.";
            }
            //email validation
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $errors[]="you must enter a valid email address.";
                }
               //file upload validation
            if(!empty($_FILES)){
                // var_dump($_FILES);
                $photo= $_FILES['photo'];
                $name = $photo['name'];
                $nameArray = explode('.',$name);
                $filename = $nameArray[0];
                $fileExt = $nameArray[1];
                $mine = explode('/',$photo['type']);
                $mineType = $mine[0];
                $mineExt = $mine[0][1];
                $location = $photo['tmp_name'];
                $fileSize = $photo['size'];
                $allowedImage = array('jpg','PNG','gif','jpeg','png');
                $uploadname = $name;
                $dbpath = '/ecommerce/images/users/'.$uploadname;
                if($mineType != 'image'){
                $errors[] = "File must be an image";
                }
                if (!in_array($fileExt,$allowedImage)) {
                $errors[]="The photo must either be jpg, png, jpeg or gif";
                }
                if ($fileSize > 10000000) {
                    $errors[]="The file must not be greater than 10MB";
                }
                if ($fileExt != $mineExt && ($mineExt == 'jpeg' && $fileExt !='jpg')) {
                    $errors[] = "File extension does not match the file";
                }
            }
            if(!empty($errors)){
                echo display_error($errors);
            }else{
                //add to database
                if(!empty($_FILES)){
                move_uploaded_file($location, $dbpath);
                }
                $query = "INSERT INTO users(full_name,email,password,permission,passport)VALUES('$name','$email','$hashed','$permission','$photo')";
                
                if(isset($_GET['edit'])){
                    $query = "UPDATE users SET full_name='$name',email='$email',password='$password',permission='$permission' WHERE id = '$edit_id' ";
                $_SESSION['success_flash'] = "User has been editted!";
                }
                $conn->query($query);
                $_SESSION['success_flash'] = "User has been added!";
                header('location:users.php');

            }
        }
        
        

    ?>
    <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add New');?> User</h2><hr>
    <form action="users.php?add=1" method="post">
        <div class="form-group col-md-6">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
        </div>
        <div class="form-group col-md-6">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
        </div>
        <div class="form-group col-md-6">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
        </div>
        <div class="form-group col-md-6">
            <label for="confirm">Confirm password:</label>
            <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
        </div>
        <div class="form-group col-md-6">
            <label for="permission">Permission:</label>
            <select class="form-control" name="permission">
                <option value=""<?=(($permission == '')?' selected':'') ;?>></option>
                <option value="editor"<?=(($permission == 'editor')?' selected':''); ?>>Editor</option>
                <option value="admin,editor"<?=(($permission == 'admin,editor')?' selected':'') ;?>>Admin</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="photo">Passport:</label>
            <input type="file" name="photo" id="photo" class="form-control" value="<?=$photo;?>">
        </div>
        <div class="form-group col-md-6 text-right" style="margin-top:25px;">
            <a href = "users.php" class="btn btn-default">Cancel</a>
            <input type="submit" class="btn btn-success" value="<?=((isset($_GET['edit']))?'Edit':'Add '); ?> User">
        </div>
    </form>

    <?php



        //view USERS
    }else{
     $userquery = $conn->query("SELECT * FROM users ORDER BY full_name");
    ?>
   

<h2 class="text-center">USERS</h2><hr>
<a href = "users.php?add=1" class="btn btn-primary pull-right" id="add-user-btn">Add New User</a>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-condensed">
        <thead>
        <th></th><th>Name</th><th>Email</th><th>Join Date</th><th>Last Login</th><th>Permission</th><th>Passport</th>
        </thead>
        <tbody>
            <?php while($user =mysqli_fetch_assoc($userquery) ) :?>
            <tr>
                <td><a href="users.php?edit=<?=$user['id'];?>" class="btn btn-success btn-xs">
                <span class="glyphicon glyphicon-pencil"></span></a>&nbsp &nbsp
                <?php if($user['id'] != $user_data['id']):?>
                <a href="users.php?delete=<?=$user['id'];?>" class="btn btn-danger btn-xs">
                <span class="glyphicon glyphicon-remove"></span></a>
                 <?php endif; ?>
                </td>
                <td><?php echo $user['full_name'];  ?></td>
                <td><?php echo $user['email'];  ?></td>
                <td><?php echo pretty_date($user['join_date']);  ?></td>
                <td><?php echo (($user['last_login'] == '0000-00-00 00:00:00')?'Never':pretty_date($user['last_login'])); ?></td>
                <td><?php echo $user['permission'];  ?></td>
                <td><img src="<?php echo $user['passport'];  ?>" style="width:30px; heigth:30px" class="img-circle img-responsive text-center"></td>
            </tr>
    <?php endwhile; ?>
        </tbody>
    </table>
</div>

    <?php } include('includes/footer.php');
?>