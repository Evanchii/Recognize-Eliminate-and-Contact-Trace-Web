<?php

    require __DIR__.'/vendor/autoload.php';

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\ServiceAccount;

    // $serviceAccount = ServiceAccount::fromJsonFile(__DIR__. 'react-ebe8e-firebase-adminsdk-edc3p-3832332702.json');
    $firebase = (new Factory)
        ->withServiceAccount(__DIR__. '/react-ebe8e-firebase-adminsdk-edc3p-3832332702.json')
        ->withDatabaseUri('https://react-ebe8e-default-rtdb.asia-southeast1.firebasedatabase.app/');

    $database = $firebase->createDatabase();

?>