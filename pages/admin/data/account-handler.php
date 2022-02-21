<?php
include '../../../includes/dbconfig.php';
session_start();
$auth = $firebase->createAuth();
if(isset($_POST['create'])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $userProperties = [
        "email" => $email,
        "password" => $password,
        'emailVerified' => false
    ];

    $createdUser = $auth->createUser($userProperties);

    $database->getReference("Users/".$createdUser->uid."/info")->update([
        "email" => $email,
        "addCi" => $infoRef->getChild("addCi")->getValue(),
        "addPro" => $infoRef->getChild("addPro")->getValue(),
        "addCo" => $infoRef->getChild("addCo")->getValue(),
        "addZip" => $infoRef->getChild("addZip")->getValue(),
        "main" => $_SESSION['uid'],
        "Type" => "sub-admin",
    ]);

    $database->getReference("Users/".$_SESSION['uid']."/sub")->update([
        $email => $createdUser->uid
    ]);

} elseif (isset($_POST['delete'])) {
    $uid = $_POST['uid'];
    $email = $_POST['email'];
    $auth->deleteUser($uid);
    $database->getReference('Users/'.$uid)->remove();
    $database->getReference('Users/'.$_SESSION['uid'].'/sub')->getChild($email)->set(null);;
}
?>