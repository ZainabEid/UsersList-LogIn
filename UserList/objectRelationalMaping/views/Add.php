<!-- 2-Validation -->
<?php
require '../models/User.php';
    $error_arr=array();
    if( $_SERVER['REQUEST_METHOD'] == 'POST'){

        if (! (isset($_POST['name']) && !empty($_POST['name']) ) ){
            $error_arr[]="name";
         }
        if (! (isset($_POST['mail']) && filter_input(INPUT_POST, 'mail' ,FILTER_VALIDATE_EMAIL))){
           $error_arr[]="mail";
        }
        if (! (isset($_POST['password']) && strlen($_POST['password']) > 5 ) ){
            $error_arr[]="password";
        }
                
        //3-Connecting to DataBas

        if(!$error_arr){
            $err='';

            //read user data from the form:
            $name = $_POST['name'];
            $admin= ( isset($_POST['admin']) )? 1 : 0;

            //Upload FILE here:
            $uploads_dir= '../../uploads/';
            $avatar='';
            $target_file_name='';
            if( $_FILES['avatar']['error'] == 0 ){

                $tmp_name= $_FILES['avatar']['tmp_name'];
                $avatar = basename($_FILES['avatar']['name']);
                $target_file_name = $name.$avatar;

                if(move_uploaded_file($tmp_name,"$uploads_dir/$target_file_name")){
                    $err='No Error';
                }lse{$err='Error'};
            }else{
                echo 'file can not be uploaded';
                
                exit;
            }
            
            $user_data= array(
                    "name" => $name,
                    "mail" => $_POST['mail'],
                    "password" => $_POST['password'],
                    "avatar" => $target_file_name,
                    "admin" => $admin);

            $user = new User();
            $user_id = $user->addUser($user_data);

            if(isset($user_id)){
                header("Location: ../List.php?file=".$err);
                exit;
            }
        }
    }
        

?>

<!-- Drawing form -->
<html>
    <head>
        <title>Add User</title>
    </head>
    <body>
    <form method="post" enctype="multipart/form-data">
        <label for="name">Name</label>
        <input type="text" name="name" id="" 
            value="<?=
                isset($_POST['name'])? $_POST['name']:"";
            ?>">
        <?php
            if(in_array('name', $error_arr)){
                echo "* Please Enter your Name";
            }
        ?>
        <br>

        <label for="mail">Email</label>
        <input type="email" name="mail" id=""
            value="<?=
                isset($_POST['mail'])? $_POST['mail'] : "" ;
            ?>">
        <?php
            if(in_array('mail', $error_arr)){
                echo "* Please Enter your Email";
            }
        ?>
        <br>


        <label for="password">Password</label>
        <input type="password" name="password" id="">
        <?php
            if(in_array('password', $error_arr)){
                echo "* Please Enter at least 6 letters Password";
            }
        ?>
        <br>

        <label for="avatar">Add Avatar</label>
        <input type="file" name="avatar" id="avatar">
        <br>

        <label for="admin">Admin</label>
        <input type="checkbox" name="admin" id="" 
            <?= (isset($_POST['admin'])) ? 'checked' : '' ?>
        />
        <br>

        <input type="submit" value="Submit">
    </form>
    </body>
</html>


