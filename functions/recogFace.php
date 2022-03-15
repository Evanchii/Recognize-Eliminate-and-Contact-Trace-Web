<?php
// include '../../functions/checkSession.php';
include '../includes/vendor/autoload.php';
use PhpKairos\PhpKairos;

$api     = 'http://api.kairos.com/';
$app_id  = '345b9a6b';
$app_key = '0ee46186eb4310b5e7936385b2f32a32';
$client = new PhpKairos( $api, $app_id, $app_key );

$encodedImg = $_POST['img'];

$gallery_name = 'users';

$response = $client->recognize($encodedImg, $gallery_name);
$result   = $response->getBody()->getContents();
echo $result;
?>