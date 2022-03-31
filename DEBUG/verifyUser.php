<?php 
include '../includes/dbconfig.php';

if(isset($_POST['uid'])) {
    
    $uid = $_POST['uid'];
    $auth = $firebase->createAuth();

    echo "OLD: ";
    echo $auth->getUser($uid)->__get("emailVerified") ? "True" : "False";

    $update = [
        "emailVerified" => true
    ];

    $auth->updateUser($uid, $update);

    echo "<br><br>----------<br><br>NEW: ";
    echo $auth->getUser($uid)->__get("emailVerified") ? "True" : "False";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="verifyUser.php" method="post">
        <input type="text" name="uid" id="uid">
        <input type="submit" value="submit">
    </form>
</body>
</html>