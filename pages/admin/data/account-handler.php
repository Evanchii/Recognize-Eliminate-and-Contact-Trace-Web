<?php
include '../../../includes/dbconfig.php';
session_start();

if(isset($_POST['uid'])) {
    $uid = $_POST['uid'];
    $userRef = $database->getReference('Users/'.$uid); 
}
$auth = $firebase->createAuth();
$logRef = $database->getReference('Logs/');

if(isset($_POST['create'])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $username = $_POST["uName"];
    $fName = $_POST["fName"];
    $mName = $_POST["mName"];
    $lName = $_POST["lName"];
    $cNo = $_POST["cNo"];

    $infoRef = $database->getReference('Users/'.$_SESSION['uid'].'/info');

    $userProperties = [
        "email" => $email,
        "password" => $password,
        'emailVerified' => true
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
        "username" => $username,
        "fName" => $fName,
        "mName" => $mName,
        "lName" => $lName,
        "cNo" => $cNo,
    ]);

    $database->getReference("Users/".$_SESSION['uid']."/sub")->update([
        $createdUser->uid => $email
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