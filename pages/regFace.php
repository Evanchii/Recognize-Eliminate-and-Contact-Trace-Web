<?php
include '../includes/dbconfig.php';
session_start();

if (isset($_GET['uid'])) {
    $_SESSION['uid'] = $_GET['uid'];
    $uid = $_GET['uid'];
}

if (isset($_POST['visFace'])) {
    #Upload Face Photo
    $img = $_POST['visFace'];
    $folderPath = "Face/";

    // image/jpg;base64kfjglkfjgklfbklfbfbnfbl
    $image_parts = explode(";base64,", $img);
    // image/jpg kfjglkfjgklfbklfbfbnfbl

    // $image_type_aux = explode("image/", $image_parts[0]);
    // $image_type = $image_type_aux[1];

    $image_base64 = base64_decode($image_parts[1]);
    $fileName = $_SESSION['uid'] . '.png'; //INSERT UID HERE

    include '../functions/enrollFace.php';
    enrollFace($img, $_SESSION['uid']);

    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);

    // print_r($fileName);

    $storage = $firebase->createStorage();
    $storageClient = $storage->getStorageClient();
    $defaultBucket = $storage->getBucket();

    $defaultBucket->upload(
        file_get_contents($file),
        [
            'name' => $file
        ]
    );

    unlink($file);

    $database->getReference('Users/' . $_SESSION['uid'])->update(
        [
            'info' => [
                'faceID' => 'Face/' . $createdUser->uid . '.png',
            ]
        ]
    );

    if (!isset($_SESSION['token']))
        unset($_SESSION['uid']);
    header('Location: ../');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link rel="stylesheet" href="../styles/public-common.css">
    <link rel="stylesheet" href="../styles/signup.css">
    <script src="../scripts/signup.js"></script>
    <title>Face Registration | REaCT</title>
</head>

<body>
    <header>
        <img src="../assets/text-logo.png" alt="REaCT">
        <h2>Register Face</h2>
    </header>
    <div class="content">
        <form action="regFace.php" method="post" id="regFace">
            <div id="visFace" class="tabcontent center">
                <h3>Step 3</h3>
                <h1>Scan Face</h1>
                <h4>Get Ready to take a photo of your face</h4>
                <p>To verify your identity, we need to collect your information</p>
                <div class="faceVideo" id="faceVideo">
                    <video autoplay="true" poster="../assets/loading.gif" id="videoElement" class="faceid"></video><br>
                    <button type="button" class="camButt" id="screenshot-button"> <img src="../assets/ic_camera.svg" alt=""> Take photo</button>
                </div>
                <div class="faceCanvas hide" id="faceCanvas">
                    <img class="faceid" src="">
                    <canvas id="canvas" style="display:none;"></canvas><br>
                    <input type="hidden" name="visFace" id="visInpFace">
                    <button type="button" class="camButt" id="retry-button"><img src="../assets/ic_retry.svg" alt="" style="width: 3%; margin-right: 8px;">Retry</button>
                    <button type="button" class="camButt" id="camNext" onclick="verFace();">Submit</button>
                </div>
                <script>
                    initCamera();

                    function verFace() {
                        $('#loading').modal('show');
                        $.ajax({
                            type: "POST",
                            url: "../functions/recogFace.php",
                            data: {
                                "img": $('#visInpFace').val(),
                            },
                            // success: function(data) {},
                            // error: function(data) {}
                        }).done(function(data) {
                            console.log(data);
                            $('#loading').modal('hide');
                            $('#feedback').modal('show');
                            $('#feedback-container').html(data);
                            const jsonData = JSON.parse(data);
                            console.log(jsonData);
                            if (data.includes('gallery name not found')) {
                                $('#regFace').submit();
                            } else if (jsonData.images.at(0).transaction.confidence * 100 >= 75) {
                                if (confirm('A match has been found with a confidence level of 75% and above. Do you still wish to proceed with the registration?\n\nPlease be aware that registering multiple accounts is against the system\'s terms of service.')) {
                                    $('#regFace').submit();
                                }
                            } else {
                                $('#regFace').submit();
                            }
                        });
                    }
                </script>
            </div>
        </form>
    </div>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/a2501cd80b.js" crossorigin="anonymous"></script>

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>

    <div id="loading" class="modal">
        <div class="modal-title">
            <h3>Processing request</h3>
        </div>
        <div class="modal-body">
            <h4 class="center">Please wait as we process your request. Thank you!</h4>
            <style>
                .loader {
                    border: 16px solid #f3f3f3;
                    border-radius: 50%;
                    border-top: 16px solid #3498db;
                    width: 120px;
                    height: 120px;
                    -webkit-animation: spin 2s linear infinite;
                    /* Safari */
                    animation: spin 2s linear infinite;
                }

                /* Safari */
                @-webkit-keyframes spin {
                    0% {
                        -webkit-transform: rotate(0deg);
                    }

                    100% {
                        -webkit-transform: rotate(360deg);
                    }
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }
            </style>
            <div class="loader center"></div>
        </div>
    </div>
</body>

</html>