<?php
include '../../../includes/dbconfig.php';
session_start();

if(isset($_POST['uid'])) {
    $uid = $_POST['uid'];
    $userRef = $database->getReference('Users/'.$uid); 
}
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
        $createdUser->uid => $username.$extension
    ]);

    createLog('Accounts', 'has created sub-user ', $createdUser->uid);
} elseif (isset($_POST['deleteUser'])) {
    $uid = $_POST['uid'];
    $type = $userRef->getChild('info/Type')->getValue();
    $username = $userRef->getChild('info/username')->getValue();

    if(str_contains($type, 'sub')) {
        $database->getReference('Users/'.$_SESSION['uid'].'/sub/'.$uid)->set(null);

        echo $uid;
        
        createLog('Accounts', 'has deleted sub-user ', $uid);
    } else {
        createLog('Accounts', 'has deleted user ', $uid);
    }

    $auth->deleteUser($uid);
    $userRef->set(null);
}
?>