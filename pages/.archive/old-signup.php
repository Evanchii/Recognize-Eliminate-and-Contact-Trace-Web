<?php
// Initialize database
include '../includes/dbconfig.php';

$auth = $firebase->createAuth();
if(isset($_POST['submit'])) {
    $usertype = $_POST['usertype'];
    if($usertype == "visitor") {
        $fname = $_POST['firstName'];
        $mname = $_POST['middleName'];
        $lname = $_POST['lastName'];
        $cno = $_POST['contactNumber'];
        $email = $_POST['emailAddress'];
        $dob = $_POST['dob'];
        $nostreet = $_POST['NoStreet'];
        $ba = $_POST['barangay'];
        $ci = $_POST['city'];
        $pr = $_POST['province'];
        $co = $_POST['country'];
        $zip = $_POST['zip'];
        $pass = $_POST['password'];

        $userProperties = [
            'email' => $email,
            'emailVerified' => false,
            'password' => $pass,
        ];
        
        try {
            #Firebase Auth Register
            $createdUser = $auth->createUser($userProperties);

            #Upload Face Photo
            $img = $_POST['inputFace'];
            $folderPath = "Face/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = $createdUser->uid . '.png'; //INSERT UID HERE
            
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

            $defaultBucket->upload(
                file_get_contents($_FILES['idInput']['tmp_name']),
                [
                    'name' => "ID/" . $createdUser->uid . ".png"
                ]
            );

            #Firebase Realtime Database push user data
            $query = [
                $createdUser->uid => [
                    'info' => [
                        'ID' => 'ID/' . $createdUser->uid . '.png',
                        'faceID' => 'Face/' . $createdUser->uid . '.png',
                        'Type' => 'User',
                        'fName' => $fname,
                        'mName' => $mname,
                        'lName' => $lname,
                        'cNo' => $cno,
                        'DoB' => $dob,
                        'addNo' => $nostreet,
                        'addBa' => $ba,
                        'addCi' => $ci,
                        'addPro' => $pr,
                        'addCo' => $co,
                        'addZip' => $zip,
                        'status' => false,
                    ],
                ],
            ];

            $database->getReference('Users')->update(
                [
                    $createdUser->uid => [
                        'info' => [
                            'Type' => 'User',
                            'fName' => $fname,
                            'mName' => $mname,
                            'lName' => $lname,
                            'cNo' => $cno,
                            'DoB' => $dob,
                            'addNo' => $nostreet,
                            'addBa' => $ba,
                            'addCi' => $ci,
                            'addPro' => $pr,
                            'addCo' => $co,
                            'addZip' => $zip,
                            'status' => false,
                        ],
                    ],
                ],
            );
            
            $auth->sendEmailVerificationLink($email);

            echo '<script>alert("Successfully Registered! Please check your inbox for your email verification link!")</script>';

            header('Location: ../');
        } catch(Exception $e) {
            echo '<script>alert("${e}")</script>';
        }
    }
    elseif ($usertype=="establishment") {
        echo("ESTABLISHMENT UNDER CONSTRUCTION");
    }
}
?>
<!DOCTYPE html>
<html>
    <header>
        <title>Sign up - REaCT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Asap:wght@400;500&family=Quicksand:wght@400;500&display=swap');
            
            * {
                font-family: 'Asap', sans-serif;
                font-family: 'Quicksand', sans-serif;
                text-decoration: none;
            }

            body {
                background-image: radial-gradient(#b5b5b5 10%, transparent 0%);
                background-color: #e0e0e0;
                background-position: 0 0, 50px 50px;
                background-size: 20px 20px;
            }

            .center {
                text-align: center;
            }

            .end {
                text-align: right;
            }

            /* end of common */

            .header {
                display: flex;
                align-items: center;
            }

            .logo {
                width: 5%;
            }

            .text-logo {
                width: 20%;
            }

            .header>p {
                width: auto;
            }

            form {
                background: white;
                width: 60vw;
                margin: auto;
                padding: 20px;
            }

            .hide {
                display: none;
            }

            .faceVideo {
                margin: 0px auto;
                border: 2px #333 solid;
            }
            .faceid {
                width: 50vw;
                background-color: #666;
            }
        </style>
    </header>
    <body>
        <div class="header">
            <a href="../">< Login</a>
            <div class="center">
                <img class="logo" src="../assets/logo.png" alt="">
                <img class="text-logo" src="../assets/text-logo.png" alt="">
            </div>
        </div>
        <form action="old-signup.php" method="POST" enctype="multipart/form-data">
            <input type="radio" name="usertype" value="visitor" id="typeVisitor" onchange="showForm(this);">
            <label for="visitor">Visitor</label><br>
            <input type="radio" name="usertype" value="establishment" id="typeEstablishment" onchange="showForm(this);">
            <label for="establishment">Establishment</label><br>
            <div id="formVisitor" class="hide">
                <h3>Step 1 Basic Data</h3>
                <table>
                    <tr>
                        <th>Full Name</th>
                    </tr>
                    <tr>
                        <td>
                            <label for="firstName">First Name</label><br>
                            <input required type="text" name="firstName">
                        </td>
                        <td>
                            <label for="middleName">Middle Name</label><br>
                            <input required type="text" name="middleName">
                        </td>
                        <td>
                            <label for="lastName">Last Name</label><br>
                            <input required type="text" name="lastName">
                        </td>
                    </tr>
                    <tr>
                        <th>Contact Details</th>
                    </tr>
                    <tr>
                    <td>
                            <label for="contactNumber">Contact Number</label><br>
                            <input required type="tel" name="contactNumber">
                        </td>
                        <td>
                            <label for="emailAddress">Email Address</label><br>
                            <input required type="email" name="emailAddress">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="dob">Date of Birth</label><br>
                            <input required type="date" name="dob">
                        </td>
                    </tr>
                    <tr>
                        <th>Current Address</th>
                    </tr>
                    <tr>
                        <td>
                            <label for="NoStreet">House Number and Street Address</label><br>
                            <input required type="text" name="NoStreet">
                        </td>
                        <td>
                            <label for="barangay">Barangay</label><br>
                            <input required type="text" name="barangay">
                        </td>
                        <td>
                            <label for="city">City</label><br>
                            <input required type="text" name="city">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="province">Province</label><br>
                            <input required type="text" name="province">
                        </td>
                        <td>
                            <label for="country">Country</label><br>
                            <input required type="text" name="country">
                        </td>
                        <td>
                            <label for="zip">Zipcode</label><br>
                            <input required type="number" name="zip">
                        </td>
                    </tr>
                </table>

                <h3>Step 2 Scan Face</h3>
                <h4>Get Ready to take a photo of your face</h4>
                <p>To verify your identity, we need to collect your information</p>
                <div class="faceVideo" id="faceVideo">
                    <video autoplay="true" id="videoElement" class="faceid"></video><br>
                    <button type="button" id="screenshot-button">Take photo</button>
                </div>
                <div class="faceCanvas hide" id="faceCanvas">
                    <img src="">
                    <canvas id="canvas" style="display:none;"></canvas><br>
                    <input type="hidden" name="inputFace" id="inputFace">
                    <button type="button" id="retry-button">Retry</button>
                </div>
                <script>
                    var video = document.querySelector("#videoElement");
                    const screenshotButton = document.querySelector("#screenshot-button");
                    const retryButton = document.querySelector("#retry-button");
                    const img = document.querySelector("#faceCanvas img");
                    const canvas = document.querySelector("#canvas");
                    const faceInput = document.querySelector("#inputFace");

                    if(navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(function (stream) {
                            video.srcObject = stream;

                            screenshotButton.onclick = video.onclick = function () {
                                canvas.width = video.videoWidth;
                                canvas.height = video.videoHeight;
                                canvas.getContext("2d").drawImage(video, 0, 0);
                                // Other browsers will fall back to image/png
                                img.src = canvas.toDataURL("image/webp");
                                faceInput.value = canvas.toDataURL("image/webp");

                                document.getElementById("faceVideo").style.display = "none";
                                document.getElementById("faceCanvas").style.display = "block";
                            };

                            retryButton.onclick = function() {
                                document.getElementById("faceVideo").style.display = "block";
                                document.getElementById("faceCanvas").style.display = "none";
                                faceInput.value = "";
                            }

                        })
                        .catch(function (err0r) {
                        console.log("Something went wrong!");
                        });
                    }
                </script>


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

                <h3>Step 4 Password</h3>
                <table>
                    <tr>
                        <td>
                            <label for="password">Password</label><br>
                            <input required type="password" name="password" id="password" onchange="checkPassword()">
                        </td>
                        <td>
                            <label for="confPass">Confirm Password</label><br>
                            <input required type="password" name="confPass" id="confPass" onchange="checkPassword()">
                        </td>
                    </tr>
                </table>
            </div>
            <div id="formEstablishment" class="hide">
                <p>UNDER CONSTRUCTION</p>
            </div>
            <input type="submit" id="submit" name="submit" class="hide">
        </form>
        <!-- JQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <!-- jQuery Modal -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

        <script>
            document.getElementById('password').addEventListener("input", function (e) {
                checkPassword();
            });
            document.getElementById('confPass').addEventListener("input", function (e) {
                checkPassword();
            });
            
            function showForm(radio) {
                document.getElementById("submit").style.display = "block";
                if(radio.value == "visitor") {
                    document.getElementById("formEstablishment").style.display = "none";
                    document.getElementById("formVisitor").style.display = "block";
                }
                else {
                    document.getElementById("formVisitor").style.display = "none";
                    document.getElementById("formEstablishment").style.display = "block";
                }
            }

            function checkPassword() {
                var pass = document.getElementById("password").value;
                var confPass = document.getElementById("confPass").value;
                if(pass == confPass) {
                    document.getElementById("submit").disabled = false;
                } else
                document.getElementById("submit").disabled = true;
            }
        </script>
    </body>
</html>