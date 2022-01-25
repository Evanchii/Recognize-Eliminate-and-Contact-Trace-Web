<?php
// Initialize database
include '../includes/dbconfig.php';

if(isset($_POST['submit'])) {
    // $img = $_POST['idInput'];
    // $folderPath = "Test/";
    // $target_file = $folderPath . basename($_FILES["idInput"]["name"]);
    
    // $image_parts = explode(";base64,", $target_file);
    // $image_type_aux = explode("image/", $image_parts[0]);
    // $image_type = $image_type_aux[1];
    
    // $image_base64 = base64_decode($image_parts[1]);
    // $fileName = 'INSERTUID.png'; //INSERT UID HERE
    
    // $file = $folderPath . $fileName;
    // file_put_contents($target_file, $image_base64);
    
    // print_r($fileName);

    $storage = $firebase->createStorage();
    $storageClient = $storage->getStorageClient();
    $defaultBucket = $storage->getBucket();

    $defaultBucket->upload(
        file_get_contents($_FILES['idInput']['tmp_name']),
        [
            'name' => $_FILES['idInput']['name']
        ]
    );
}
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            .faceVideo {
                margin: 0px auto;
                border: 2px #333 solid;
            }
            #videoElement {
                width: 50vw;
                background-color: #666;
            }
        </style>
    </head>
    <body>
        <form action="storageTest.php" method="POST" enctype="multipart/form-data">
        <h3>Step 2-3 START TEST</h3>
                <div class="faceVideo">
                    <video autoplay="true" id="videoElement"></video><br>
                    <button type="button" id="screenshot-button">Take screenshot</button>
                </div>
                <div class="faceCanvas" id="faceCanvas">
                    <img src="">
                    <canvas id="canvas" style="display:none;"></canvas><br>
                    <input type="hidden" name="inputFace" id="inputFace" required>
                    <button type="button" id="retry-button">Retry Photo</button>
                </div>
                <script>
                    var video = document.querySelector("#videoElement");

                    if (navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(function (stream) {
                            video.srcObject = stream;

                            const screenshotButton = document.querySelector("#screenshot-button");
                            const img = document.querySelector("#faceCanvas img");
                            const faceInput = document.querySelector("#inputFace");
                                screenshotButton.onclick = video.onclick = function () {
                                canvas.width = video.videoWidth;
                                canvas.height = video.videoHeight;
                                canvas.getContext("2d").drawImage(video, 0, 0);
                                // Other browsers will fall back to image/png
                                img.src = canvas.toDataURL("image/webp");
                                faceInput.value = canvas.toDataURL("image/webp");
                            };
                        })
                        .catch(function (err0r) {
                        console.log("Something went wrong!");
                        });
                    }
                </script>
                <h3>Step 2-3 END TEST</h3>
                <h3>Step 3 Upload ID</h3>
                <h4>Make sure your ID is valid and is not expired</h4>
                <input accept="image/*" type='file' id="imgInp" name="idInput" />
                <img id="idPreview" class="hide faceid" src="#"/>

                <script>
                    imgInp.onchange = evt => {
                        const idPreview = document.getElementById("idPreview");
                        const [file] = imgInp.files
                        if (file) {
                            idPreview.style.display = "block";
                            idPreview.src = URL.createObjectURL(file)
                        }
                    }
                </script>
                <input type="submit" name="submit"/>
        </form>
    </body>
</html>