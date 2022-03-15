<?php
include '../includes/dbconfig.php';
session_start();

$uid = (isset($_POST['uid'])) ? $_POST['uid'] : $_SESSION['uid'];
$page = isset($_POST['page'])?$_POST['page']:1;
$userType = $database->getReference('User/'.$uid.'/info')->getValue('Type');

$accountRef = $database->getReference("Users/" . $uid . "/sub");

if($userType=="establishment") {
    $header = [
        'username' => 'Username',
        'uid' => 'UID',
        'action' => 'Action',
    ];
} else {
    $header = [
        'email' => 'Email',
        'uid' => 'UID',
        'action' => 'Action',
    ];
}

$data = [];
if($accountRef->getSnapshot()->hasChildren()) {
    $rawData = $accountRef->getValue();
    // var_dump($rawData);
    foreach($rawData as $entryUID => $key) {
        $data[$entryUID]['email'] = '';
        $data[$entryUID]['username'] = '';
        if($userType=="establishment")
            $data[$entryUID]['username'] = $key;
        else
            $data[$entryUID]['email'] = $key;
        // $data[$entryUID][$userType=="establishment"?'username':'email'] = $key;
        $data[$entryUID]['uid'] = $entryUID;
        $data[$entryUID]['action'] = '<button onclick="deleteUser(\'' . $entryUID . '\',\'' . $key . '\')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
    }
}

// var_dump($data);

include 'table-handler.php';

$search = '';
$sType = '';

if(isset($_POST['search'])) {
    $search = $_POST['search'];
    if($search != '') {
        $sType = isset($_POST['advanced']) ? $_POST['sType'] : '';

        $rawData = $data;
        $data = [];
        
        foreach($rawData as $uid => $info) {
            switch($sType) {
                case 'name':
                    if(str_icontains($info['username'].$info['email'], $search)) {
                        $data[$uid] = $info;
                    }
                    break;
                case 'uid':
                    if(str_icontains($uid, $search)) {
                        $data[$uid] = $info;
                    }
                    break;
                default:
                    if(str_icontains($info['username'].$info['email'].$uid, $search)) {
                        $data[$uid] = $info;
                    }
                    break;
            }
        }
    }
}

createTable($header, $data, $page, $search, $sType);
?>