<?php
require '../models/User.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] =='POST'){
    $mail =$_POST['mail'];
    $password=sha1($_POST['password']);
    $user = new user();
    $row = $user->loginUser($mail,$password);

    if( isset($row) ){
        $_SESSION['mail']=$row['mail'];
        $_SESSION['password']=$row['password'];
        $_SESSION['id']=$row['id'];
        header("Location: ../List.php");
        exit;
    }else{
        echo "invalid user or password";

    }


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