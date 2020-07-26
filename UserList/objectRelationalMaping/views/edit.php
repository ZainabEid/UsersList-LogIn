<?php
    // 1- define error arrary
    $error_arr=array();

    //2-Connecting to DataBas
    $conn=mysqli_connect('localhost','root','','blog', 3308);
    if ( !$conn){
        echo "there is an error...   ".mysqli_connect_error();
        exit;
    }

    //3- Display the user data:
    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    $select = "SELECT * FROM users WHERE users.id =".$id." LIMIT 1";
    $result = mysqli_query($conn,$select);
    $row = mysqli_fetch_assoc($result);

    //4- Executing the update:
    if( $_SERVER['REQUEST_METHOD'] == 'POST'){

        if (! (isset($_POST['name']) && !empty($_POST['name']) ) ){
            $error_arr[]="name";
         }
        if (! (isset($_POST['mail']) && filter_input(INPUT_POST, 'mail' ,FILTER_VALIDATE_EMAIL))){
           $error_arr[]="mail";
        }

        if(!$error_arr){
            // Escape any special charater to avoid any BD injuction
            $name= mysqli_escape_string($conn,$_POST['name']);
            $mail= mysqli_escape_string($conn,$_POST['mail']);
            $password= sha1($_POST['password']);
            $admin= ( isset($_POST['admin']) )? 1 : 0;

     //5- Execution Update:   
            $query = "UPDATE users  
                      SET name ='".$name."' , mail = '".$mail."', password = '".$password."', admin = '".$admin."' 
                      WHERE users.id = ".$id;
            if(mysqli_query($conn,$query)){
                header("Location: List.php");
                exit;
            }else{
                echo "UPDATE ERROR ...  ".mysqli_error($conn);
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

        <label for="admin">Admin <?= $row['admin']?> </label>
        <input type="checkbox" name="admin" id="" 
            <?= ($row['admin'] == 1) ? 'checked' : '' ?>
        />
        <br>

        <input type="submit" value="Submit">
    </form>
    </body>
</html>




