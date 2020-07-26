<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] =='POST'){
    $conn = mysqli_connect('localhost','root', '', 'blog',3308);

    if(! $conn){
        echo "connection ERROR >>>   ".mysqli_connect_error();
        exit;
    }

    //escape any special characters to avoid database injuction
    $mail=mysqli_escape_string($conn,$_POST['mail']);
    $password= sha1($_POST['password']);


    //Grape the user's row from DB by email and pass
    $select = "SELECT * FROM users WHERE mail = '".$mail."' AND password='".$password."'LIMIT 1";
    $result = mysqli_query($conn,$select);

    if( $row = mysqli_fetch_assoc($result) ){
        $_SESSION['mail']=$row['mail'];
        $_SESSION['password']=$row['password'];
        $_SESSION['id']=$row['id'];
        header('Location: ../List.php');
        exit;
    }else{
        echo "invalid user or password";

    }

mysqli_free_result($result);
mysqli_close($conn);

}


?>

<html>
    <head>
        <title>Log in</title>
    </head>

    <body>

        

     <form method="post">
            <label for="mail">User Name</label>
            <input type="email" name="mail" id=""
                value="<?= ( isset($_POST['mail']) ) ? $_POST['mail'] : ''  ?>"
            >
            <br>

            <label for="password">Password</label>
            <input type="password" name="password" id="">
            <br>

            <input type="submit" value="Submit">
     </form>
    </body>
</html>