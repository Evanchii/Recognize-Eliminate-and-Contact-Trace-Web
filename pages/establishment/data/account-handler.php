<?php
include '../../../includes/dbconfig.php';
session_start();
$auth = $firebase->createAuth();
if(isset($_POST['create'])) {
    $username = $_POST["username"];
    $extension = $_POST["extension"];
    $password = $_POST["password"];

    $userProperties = [
        "email" => $username.$extension."@core.react-app.ga",
        "password" => $password,
        'emailVerified' => false
    ];

    $createdUser = $auth->createUser($userProperties);

    $database->getReference("Users/".$createdUser->uid."/info")->update([
        "email" => $username.$extension."@core.react-app.ga",
        "username" => $username,
        "name" => $_SESSION['name'],
        "branch" =>  $_SESSION['branch'],
        "main" => $_SESSION['uid'],
        "Type" => "sub-establishment",
    ]);

    $database->getReference("Users/".$_SESSION['uid']."/sub")->update([
        $username.$extension => $createdUser->uid
    ]);
} elseif (isset($_POST['delete'])) {
    $uid = $_POST['uid'];
    $username = $_POST['username'];
    $auth->deleteUser($uid);
    $database->getReference('Users/'.$uid)->remove();
    $database->getReference('Users/'.$_SESSION['uid'].'/sub')->getChild($username)->set(null);;
}
?>