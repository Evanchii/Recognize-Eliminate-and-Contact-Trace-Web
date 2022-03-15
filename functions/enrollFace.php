<?php
// include '../../functions/checkSession.php';
include ROOT_PATH.'/includes/vendor/autoload.php';
use PhpKairos\PhpKairos;

// if(isset($_POST['img'])) {
//     $encodedImg = $_POST['img'];
//     enrollFace($encodedImg, $_POST['uid']);
// }

function enrollFace($image, $sub_id) {
    $api     = 'http://api.kairos.com/';
    $app_id  = '345b9a6b';
    $app_key = '0ee46186eb4310b5e7936385b2f32a32';
    $client = new PhpKairos( $api, $app_id, $app_key );

    $gallery_name = 'users';
    $subject_id   = $sub_id;

    $response = $client->enroll($image, $subject_id, $gallery_name);
    $result   = $response->getBody()->getContents();
    echo $result;
}

// header("location:javascript://history.go(-1)");
?>