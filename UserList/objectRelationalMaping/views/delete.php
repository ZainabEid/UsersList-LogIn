<?php
    
    require '../models/User.php';

    //$id=$_GET['id']; MAY RECIEVE STRING DATA BY WRONG SO WE NEED FILTER INPUT FUNCTION
    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    
    $user = new User();
    

    if ($user-> deleteUser($id)){
        header("Location: ../List.php");
        exit;
    }else{
        echo "Delete statment ERROR >>> ".mysqli_error($conn);
    }


?>