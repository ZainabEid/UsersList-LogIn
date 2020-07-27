<!-- 2-Validation -->
<?php
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
            // connect ot DB
            $conn=mysqli_connect('localhost','root','','blog', 3308);
            if ( !$conn){
                echo "there is an error...   ".mysqli_connect_error();
                exit;
            }
            
            // Escape any special charater to avoid any BD injuction
            $name= mysqli_escape_string($conn,$_POST['name']);
            $mail= mysqli_escape_string($conn,$_POST['mail']);
            $password= sha1($_POST['password']);
            $admin= ( isset($_POST['admin']) )? 1 : 0;

            //Upload FILE here:
            $uploads_dir= 'uploads/';
            $avatar='';
            $target_file_name='';
            if( $_FILES['avatar']['error'] == 0 ){

                $tmp_name= $_FILES['avatar']['tmp_name'];
                $avatar = basename($_FILES['avatar']['name']);
                $target_file_name = $name.$avatar;

                if(move_uploaded_file($tmp_name,"$uploads_dir/$target_file_name")){
                    $err='No Error';
                }
                $err='Error';
            }else{
                echo 'file can not be uploaded';
                
                exit;
            }

        
            //insert statement
            $query="INSERT INTO users ( name, mail, password, avatar, admin) VALUES ('".$name."','".$mail."','".$password."','".$target_file_name."','".$admin."')";
            if(mysqli_query($conn,$query)){
                header("Location: List.php?file=".$err);
                exit;
            }else{
                echo mysqli_error($conn);
            }
            mysqli_close($conn);
        
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





<!-- e -->

<?php


?>

