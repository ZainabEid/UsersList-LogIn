<?php
    //WElCOM user name;
    session_start();
    if(isset($_SESSION['id'])){
        echo '<p> Wlecome '.$_SESSION['mail'].' <a href="logout.php" > Log Out </a> </p>';
    }else{
        header('Location: login.php');
    }

    //connecting mysql
    $conn =mysqli_connect('localhost', 'root','','blog',3308);
    if  (! $conn){
        echo "Connection Error:     ".mysqli_connect_error();
         exit();
    }

    //select all users
    $query= "SELECT * FROM users";

    //sellect where searched fits
    if (isset($_GET['search'])){
        $search= mysqli_escape_string($conn,$_GET['search']);
        $query.= " WHERE `users`.`name` LIKE '%".$search."%' OR `users`.`mail` LIKE '%".$search."%'";
    }

    //Executing the query
    $result = mysqli_query($conn,$query);

?>
<!-- design the page -->
<html>
    <head>
        <title>Admin :: users list</title>
    </head>
    <body>
        <h1>User Lists: </h1>

        <form action="" method="get">
            <label for="search">Search</label>
            <input type="search" name="search" id="" placeholder="Search by name or email">
            <button type="submit">Submit</button>
            <br><br>
        </form>
        
        <!-- disply a table containing all users -->
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Avatar</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //loop on table rowset to get rows data
                while( $row = mysqli_fetch_assoc($result)){
                ?>
                    <tr>
                        <td> <?= $row['id']  ?> </td>
                        <td> <?= $row['name']  ?> </td>
                        <td> <?= $row['mail']  ?> </td>
                        <td> 
                            <img src="<?=
                                    ($row['avatar']) ? "uploads/".$row['avatar'] : "uploads/no_img.png" ; 
                                ?>" 
                                alt="Image" 
                                height="50"
                            > 
                            <?= "uploads/".$row['avatar'] ?>
                        </td>
                        <td> <?= ($row['admin']) ? 'Yes' : 'No'  ?> </td>
                        <td> <a href="edit.php?id=<?=$row['id']?>">Edit</a> | <a href="delete.php?id=<?=$row['id']?>">Delete</a> </td>

                    </tr>
                <?php
                 //closing while loop
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:center" > <?= mysqli_num_rows($result) ?> users</td>
                    <td colspan="3" style="text-align:center" > <a href="Add.php">Add User</a></td>
                
                </tr>
            </tfoot>

        </table>
    </body>
</html>
<?php
    //closing connection and free results
    mysqli_free_result($result);
    mysqli_close($conn);
?>