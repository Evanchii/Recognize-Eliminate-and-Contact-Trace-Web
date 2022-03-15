<?php
include '../../functions/enrollFace.php';

if(isset($_POST['visFace'])) {
    echo('testA');
    echo $_POST['visFace'];
    echo '<br>';
    enrollFace($_POST['visFace'], $_POST['sub_id']);
    echo('testB');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug - Face Enroll</title>
    <script src="../../scripts/signup.js"></script>
</head>

<body>
    <form action="debugFace.php" method="POST">
        <div class="faceVideo" id="faceVideo">
            <video autoplay="true" poster="../assets/loading.gif" id="videoElement" class="faceid"></video><br>
            <button type="button" class="camButt" id="screenshot-button"> <img src="../assets/ic_camera.svg" alt=""> Take photo</button>
        </div>
        <div class="faceCanvas hide" id="faceCanvas">
            <img class="faceid" src="">
            <canvas id="canvas" style="display:none;"></canvas><br>
            <input type="hidden" name="visFace" id="visInpFace">
            <button type="button" class="camButt" id="retry-button"><img src="../assets/ic_retry.svg" alt="">Retry</button>
        </div>
        <input type="text" name="sub_id" id="sub_id">
        <button type="submit">Submit</button>
    </form>
    <script>initCamera();</script>
</body>

</html>