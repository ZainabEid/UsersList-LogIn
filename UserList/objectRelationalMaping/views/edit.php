<?php

require '../models/User.php';
    // 1- define error arrary
    $error_arr=array();
    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);

    $user = new User();
    $row = $user->getUser($id);
    

    //4- Executing the update:
    if( $_SERVER['REQUEST_METHOD'] == 'POST'){

        if (! (isset($_POST['name']) && !empty($_POST['name']) ) ){
            $error_arr[]="name";
         }
        if (! (isset($_POST['mail']) && filter_input(INPUT_POST, 'mail' ,FILTER_VALIDATE_EMAIL))){
           $error_arr[]="mail";
        }

        if(!$error_arr){

            
            $name= $_POST['name'];
            $mail= $_POST['mail'];
            $password= sha1($_POST['password']);
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
                }
            }else{
                echo 'file can not be uploaded';
                
                exit;
            }


           
            $user_data = array(
                "name" => $name,
                "mail" => $mail,
                "password" => $password,
                "avatar" => $target_file_name,
                "admin" => $admin
            
            );

     //5- Execution Update:   
            
            if($user->updateUser($user_data,$id)){
                header("Location: ../List.php");
                exit;
            }else{
                echo "UPDATE ERROR ...  ";
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
    <form method="post">
        <input type="hidden" name="id" id="id" value=
            "<?=
                isset($row['id'])? $row['id'] : '';
            ?>"
        >

        <label for="name">Name</label>
        <input type="text" name="name" id="" 
            value="<?=
                isset($row['name'])? $row['name']:"";
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
                isset($row['mail'])? $row['mail'] : "" ;
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

        <label for="admin">Admin </label>
        <input type="checkbox" name="admin" id="" 
            <?= ($row['admin'] == 1) ? 'checked' : '' ?>
        />
        <br>

        <input type="submit" value="Submit">
    </form>
    </body>
</html>




