<html>
<?php
if(isset($_POST['visFace'])) {
    echo("DEBUG MODE!<br>");

    $img = $_POST['visFace'];
    $folderPath = "Face/";
    
    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    
    $image_base64 = base64_decode($image_parts[1]);
    echo "<textarea>".$image_base64."</textarea><br>";
    $fileName = 'test-a' . '.png'; //INSERT UID HERE
    
    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);

    print_r($fileName);
}
?>

<head>
    <title>Sample Photo</title>
    <style>
    .hide {
        display: none;
    }

    .faceid {
        margin: 2% auto;
        width: 80%;
        background-color: #0C112D;
        object-fit: contain;
    }

    .idPreview {
        width: 50%;
        margin: auto;
        background-color: #666;
    }
    </style>
</head>

<body>
    <form action="test-photo.php" method="post" enctype="multipart/form-data">
        <div class="faceVideo" id="faceVideo">
            <video autoplay="true" poster="../assets/loading.gif" id="videoElement" class="faceid"></video><br>
            <button type="button" class="camButt" id="screenshot-button"> <img src="../assets/camera.svg" alt=""> Take
                photo</button>
        </div>
        <div class="faceCanvas hide" id="faceCanvas">
            <img class="faceid" src="">
            <canvas id="canvas" style="display:none;"></canvas><br>
            <input type="hidden" name="visFace" id="visInpFace">
            <button type="button" class="camButt" id="retry-button"><img src="../assets/retry.svg" alt="">Retry</button>
            <input type="submit" value="submit" name="submit">
        </div>
        <script>
        var mediaStream;

        var video = document.querySelector("#videoElement");
        const screenshotButton = document.querySelector("#screenshot-button");
        const retryButton = document.querySelector("#retry-button");
        const img = document.querySelector("#faceCanvas img");
        const canvas = document.querySelector("#canvas");
        const faceInput = document.querySelector("#visInpFace");

        if (navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(function(stream) {
                    mediaStream = stream.getTracks();
                    video.srcObject = stream;

                    screenshotButton.onclick = video.onclick = function() {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        canvas.getContext("2d").drawImage(video, 0, 0);
                        // Other browsers will fall back to image/png
                        img.src = canvas.toDataURL("image/webp");
                        faceInput.value = canvas.toDataURL("image/webp");

                        document.getElementById("faceVideo").style.display = "none";
                        document.getElementById("faceCanvas").style.display = "block";
                        document.getElementById("camNext").style.display = "inline";

                        mediaStream.forEach(track => track.stop());
                    };

                    retryButton.onclick = function() {
                        document.getElementById("faceVideo").style.display = "block";
                        document.getElementById("faceCanvas").style.display = "none";
                        document.getElementById("camNext").style.display = "none";
                        faceInput.value = "";

                        initCamera();
                    }

                })
                .catch(function(err0r) {
                    console.log("Something went wrong!");
                });
        }
        </script>
    </form>
</body>

</html>