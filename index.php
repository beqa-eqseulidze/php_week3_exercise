
<?php
require_once './myfunctions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">    
</head>
<body>
<div class="container">
    <form action="" method="POST">
        <label for="user">enter git Hub user name</label> 
        <input class='input'type="text" name='find_user'>

        <input class='btn search-user' type="submit" value="search user">
    </form>
  
    <p><?php echo $not_user ?></p>
    <table>
        <tr>
            <th>user name</th>
            <th> profile photo</th>
            <th> repository</th>
            <th>follower</th>
        </tr>
        <tr>
            <td><?php echo $user_name  ?></td>
            <td> <img src="<?php echo $img_url ?>" alt=""> </td>
            <td><?php echo $repo_quantity ?></td>
            <td><?php echo $follower_quantity ?></td>
        </tr>
    </table>
 </div>
</body>

</html>