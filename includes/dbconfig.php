<?php

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

if (!defined('ROOT_PATH'))
    define('ROOT_PATH', dirname(__DIR__) . '/');

$url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (file_exists(__DIR__ . '/config.json') && file_exists(__DIR__ . '/database.json')) {
    $json = file_get_contents(__DIR__ . '/config.json');
    $config = json_decode($json, true);

    // $serviceAccount = ServiceAccount::fromJsonFile(__DIR__. 'react-ebe8e-firebase-adminsdk-edc3p-3832332702.json');
    $firebase = (new Factory)
        ->withServiceAccount(__DIR__ . '/database.json')
        ->withDatabaseUri($config['firebase-uri']);

    $database = $firebase->createDatabase();

    $userRef = $database->getReference('Users')->getSnapshot();
    if (!$userRef->hasChildren()) {
        if (strpos($url, 'initialSetup.php') !== false) {
        } else {
            echo '<script>
                window.location.href = "' . 'https://' . $_SERVER['SERVER_NAME'] . '/pages/initialSetup.php";
                </script>';
        }
    }
    unset($userRef);

    include ROOT_PATH . '/functions/common-functions.php';
} else {
    if (strpos($url, 'initialSetup.php') !== false) {
    } else {
        echo '<script>
            window.location.href = "' . 'https://' . $_SERVER['SERVER_NAME'] . '/pages/initialSetup.php";
            </script>';
        die();
    }
}
