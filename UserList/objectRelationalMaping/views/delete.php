<?php
    

    $conn=mysqli_connect('localhost','root','','blog',3308);
    if(!$conn){
        echo "DB Connection ERROR >>> ".mysqli_connect_error();
        exit;
    }

    //1- Recieving ID:
    //$id=$_GET['id']; MAY RECIEVE STRING DATA BY WRONG SO WE NEED FILTER INPUT FUNCTION
    $id=filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);

    //2- Execute delete statement
    $delete="DELETE FROM users WHERE users.id = ".$id." LIMIT 1";
    if (mysqli_query($conn,$delete)){
        header("Location: List.php");
        exit;
    }else{
        echo "Delete statment ERROR >>> ".mysqli_error($conn);
    }

    mysqli_close($conn);

    //3- redirect to list.php
?>