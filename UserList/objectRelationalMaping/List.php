<?php

require 'models/User.php';

    //WElCOM user name;
    session_start();

    if(isset($_SESSION['id'])){
        echo '<p> Wlecome '.$_SESSION['mail'].' <a href="logout.php" > Log Out </a> </p>';
    }else{
        header('Location: views/login.php');
    }

    $user = new User();
    $users = $user->getUsers();

    //search by the name or email 
    if (isset($_GET['search'])){
        $users->searchUsers($_GET['search']);
    }
    

    
    

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
                //loop on the rowset to get rows data
                foreach($users as $row){
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
                 //closing foreach loop
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:center" > <?= count($users) ?> users</td>
                    <td colspan="3" style="text-align:center" > <a href="views/Add.php">Add User</a></td>
                
                </tr>
            </tfoot>

        </table>
    </body>
</html>