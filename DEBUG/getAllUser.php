<?php
include '../includes/dbconfig.php';

$storage = $firebase->createStorage();
$storageClient = $storage->getStorageClient();
$defaultBucket = $storage->getBucket();

// $files = $defaultBucket->objects();
// foreach($files as $obj) {
//     $obj->delete();
// }

// $auth = $firebase->createAuth();

// $users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);

// foreach ($users as $user) {
//     echo $user->__get('uid');
// }
?>