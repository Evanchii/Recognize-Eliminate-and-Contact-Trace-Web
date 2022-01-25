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

<head>
    <title>Sign up - REaCT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Asap:wght@400;500&family=Quicksand:wght@400;500&display=swap');

    *,
    *:before,
    *:after {
        font-family: 'Asap', sans-serif;
        font-family: 'Quicksand', sans-serif;
        text-decoration: none;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
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

    .start {
        text-align: left;
    }

    .end {
        text-align: right;
    }

    .imgCircle {
        border-radius: 50%;
    }

    /* end of common */

    header {
        color: white;
        display: flex;
        justify-content: space-between;
        background: #0C112D;
        padding: 0.5% 2%;
        align-items: center;
    }

    header img {
        width: 15vw;
        filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(91deg) brightness(104%) contrast(104%);
    }

    .content {
        background: white;
        width: 60vw;
        margin: 5% auto;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.75);
        -webkit-box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.75);
    }

    .hide {
        display: none;
    }

    #userType {
        min-height: 50vh;
    }

    .type {
        cursor: pointer;
        height: fit-content;
        border-radius: 10px;
        box-shadow: 0px 0px 3px 0px rgba(0, 0, 0, 0.75);
        -webkit-box-shadow: 0px 0px 3px 0px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 3px 0px rgba(0, 0, 0, 0.75);
        padding: 2% 2%;
        transition: 0.5s ease-in-out;
    }

    .type:hover {
        box-shadow: 0px 0px 15px 2px rgba(0, 0, 0, 0.75);
        -webkit-box-shadow: 0px 0px 15px 2px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 15px 2px rgba(0, 0, 0, 0.75);
        border: none;
    }

    .radioCard:checked~.radDesc {
        border: 2px solid #0C112D;
        box-shadow: 0px 0px 12px 1px rgba(0, 0, 0, 0.75);
        -webkit-box-shadow: 0px 0px 12px 1px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 12px 1px rgba(0, 0, 0, 0.75);
    }

    table {
        table-layout: fixed;
        width: 100%;
        margin-top: 2%;
        border-spacing: 2rem 0rem;
        border-collapse: separate;
    }

    table td input {
        // display: block;
        width: 100%;
    }

    th {
        padding-top: 0.5rem;
    }

    .type>img {
        width: 10vw;
    }



    button img {
        width: 5%;
    }

    .camButt {
        padding: 1% 3%;
    }

    .containerCard {
        margin-top: 5%;
        width: 100%;
        display: inline-flex;
        justify-content: space-around;
        align-content: center;
    }

    .navigationButton {
        padding: 1.5% 3%;
        font-size: 1em;
        margin: 1% 3%;
        margin-top: 4%;
    }

    .faceVideo {
        margin: 2% auto;
    }

    .faceid {
        width: 25vw;
        margin: 0 auto;
        background-color: #0C112D;
        object-fit: none;
    }

    .nav {
        text-align: start;
    }
    </style>
</head>

<body>
    <header>
        <img src="../assets/text-logo.png" alt="REaCT - CORE">
        <h2>Sign up</h2>
    </header>
    <div class="content">
        <div id="userType" class="tabcontent">
            <div class="sectionTitle center">
                <h3>Step 1</h3>
                <h1>Choose User Type</h1>
            </div>
            <div class="containerCard">
                <label class="type center" id="typeVisitor" onclick="select(this.id);">
                    <input type="radio" class="radioCard" name="usertype" value="visitor"
                        onclick="select(this.id);"><br>
                    <img src="../assets/ic_visitor.svg" alt="Visitor" class="imgCircle">
                    <h3>Visitor</h3>
                </label>
                <label class="type center" id="typeEst" onclick="select(this.id);">
                    <input type="radio" class="radioCard" name="usertype" value="establishment"
                        onclick="select(this);"><br>
                    <img src=" ../assets/ic_establishment.svg" alt="Establishment" class="imgCircle">
                    <h3>Establishment</h3>
                </label>
            </div>
            <button class="navigationButton" id="s1Next" onclick="changeForm(event, 'step1' ,'')">Next</button>
            <script>
            function select(radio) {
                var vist = document.getElementById('typeVisitor');
                var est = document.getElementById('typeEst');
                var next = document.getElementById('s1Next');

                if (radio == "typeVisitor") {
                    vist.style.boxShadow = "0px 0px 12px 1px rgba(0, 0, 0, 0.75)";
                    vist.style.border = "2px solid #0C112D";
                    est.style.boxShadow = "0px 0px 3px 0px rgba(0, 0, 0, 0.75)";
                    est.style.border = "none";
                    next.setAttribute('name', 'visInfo')
                } else {
                    est.style.boxShadow = "0px 0px 12px 1px rgba(0, 0, 0, 0.75)";
                    est.style.border = "2px solid #0C112D";
                    vist.style.boxShadow = "0px 0px 3px 0px rgba(0, 0, 0, 0.75)";
                    vist.style.border = "none";
                    next.setAttribute('name', 'estInfo')
                }
            }
            </script>
        </div>

        <form action="signup.php" method="POST" enctype="multipart/form-data">
            <div id="formVisitor">
                <!-- Visitor Form -->
                <div id="visInfo" class="tabcontent hide">
                    <!-- Insert progress indicator -->
                    <div class="sectionTitle center">
                        <h3>Step 2</h3>
                        <h1>Basic Data</h1>
                    </div>
                    <table>
                        <tr>
                            <th colspan="3">Full Name</th>
                        </tr>
                        <tr>
                            <td>
                                <label for="visFName">First Name</label><br>
                                <input required type="text" name="visFName">
                            </td>
                            <td>
                                <label for="visMName">Middle Name</label><br>
                                <input required type="text" name="visMName">
                            </td>
                            <td>
                                <label for="visLName">Last Name</label><br>
                                <input required type="text" name="visLName">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">Contact Details</th>
                            <th colspan="1">Date of Birth</th>
                        </tr>
                        <tr>
                            <td>
                                <label for="visCNo">Contact Number</label><br>
                                <input required type="tel" name="visCNo">
                            </td>
                            <td>
                                <label for="visEmail">Email Address</label><br>
                                <input required type="email" name="visEmail">
                            </td>
                            <td>
                                <input required type="date" name="visDOB">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3">Current Address</th>
                        </tr>
                        <tr>
                            <td>
                                <label for="visNo">House Number and Street</label><br>
                                <input required type="text" name="visNo">
                            </td>
                            <td>
                                <label for="visBa">Barangay</label><br>
                                <input required type="text" name="visBa">
                            </td>
                            <td>
                                <label for="visCi">City</label><br>
                                <input required type="text" name="visCi">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="visPro">Province</label><br>
                                <input required type="text" name="visPro">
                            </td>
                            <td>
                                <label for="visCo">Country</label><br>
                                <input required type="text" name="visCo">
                            </td>
                            <td>
                                <label for="visZip">Zipcode</label><br>
                                <input required type="number" name="visZip">
                            </td>
                        </tr>
                    </table>
                    <!-- Insert next button -->
                    <button class="navigationButton" onclick="changeForm(event, 'userType', '')">Back</button>
                    <button class="navigationButton" onclick="changeForm(event, 'visFace', 'visInfo')">Next</button>
                </div>
                <div id="visFace" class="tabcontent hide center">
                    <h3>Step 3</h3>
                    <h1>Scan Face</h1>
                    <h4>Get Ready to take a photo of your face</h4>
                    <p>To verify your identity, we need to collect your information</p>
                    <div class="faceVideo" id="faceVideo">
                        <video autoplay="true" poster="../assets/loading.gif" id="videoElement"
                            class="faceid"></video><br>
                        <button type="button" class="camButt" id="screenshot-button"> <img src="../assets/camera.svg"
                                alt=""> Take photo</button>
                    </div>
                    <div class="faceCanvas hide" id="faceCanvas">
                        <img src="">
                        <canvas id="canvas" style="display:none;"></canvas><br>
                        <input type="hidden" name="visFace" id="visFace">
                        <button type="button" class="camButt" id="retry-button"><img src="../assets/retry.svg"
                                alt="">Retry</button>
                    </div>
                    <script>
                    var mediaStream;

                    function initCamera() {
                        var video = document.querySelector("#videoElement");
                        const screenshotButton = document.querySelector("#screenshot-button");
                        const retryButton = document.querySelector("#retry-button");
                        const img = document.querySelector("#faceCanvas img");
                        const canvas = document.querySelector("#canvas");
                        const faceInput = document.querySelector("#visFace");

                        if (navigator.mediaDevices.getUserMedia) {
                            navigator.mediaDevices.getUserMedia({
                                    video: true
                                })
                                .then(function(stream) {
                                    mediaStream = stream.getTracks();
                                    video.srcObject = stream;

                                    screenshotButton.onclick = video.onclick = function() {
                                        mediaStream.forEach(track => track.stop());

                                        canvas.width = video.videoWidth;
                                        canvas.height = video.videoHeight;
                                        canvas.getContext("2d").drawImage(video, 0, 0);
                                        // Other browsers will fall back to image/png
                                        img.src = canvas.toDataURL("image/webp");
                                        faceInput.value = canvas.toDataURL("image/webp");

                                        document.getElementById("faceVideo").style.display = "none";
                                        document.getElementById("faceCanvas").style.display = "block";
                                        document.getElementById("camNext").style.display = "inline";
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
                    }
                    </script>
                    <div class="nav">
                        <button class="navigationButton" onclick="changeForm(event, 'visInfo', '')">Back</button>
                        <button class="navigationButton hide" id="camNext"
                            onclick="changeForm(event, 'visId', 'visFace')">Next</button>
                    </div>
                </div>
                <div id="visId" class="tabcontent hide center">
                    <h3>Step 4</h3>
                    <h1>Upload ID</h1>
                    <h4>Make sure your ID is valid and is not expired</h4>
                    <input accept="image/*" type='file' id="visID" name="visID" />
                    <img id="IDPrev" class="hide faceid" src="#" />

                    <script>
                    let visID = document.getElementById("visID");
                    visID.onchange = evt => {
                        const idPreview = document.getElementById("IDPrev");
                        const [file] = visID.files
                        if (file) {
                            idPreview.style.display = "block";
                            idPreview.src = URL.createObjectURL(file)
                        }
                    }
                    </script>
                    <div class="nav">
                        <button class="navigationButton" onclick="changeForm(event, 'visFace', '')">Back</button>
                        <button class="navigationButton"
                            onclick="changeForm(event, 'visPassword', 'visId')">Next</button>
                    </div>
                </div>
                <div id="visPassword" class="tabcontent hide center">
                    <h3>Step 5</h3>
                    <h1>Password</h1>
                    <table>
                        <tr>
                            <td class="start">
                                <label for="visPass">Password</label><br>
                                <input required type="password" name="visPass" id="password" onchange="checkPassword()">
                            </td>
                            <td class="start">
                                <label for="visCPass">Confirm Password</label><br>
                                <input required type="password" name="confPass" id="visCPass"
                                    onchange="checkPassword()">
                            </td>
                        </tr>
                    </table>
                    <br>
                    <input type="checkbox" required name="ToS" id="">
                    <label for="ToS">I agree to the <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy
                            Policy</a></label>
                    <div class="nav">
                        <button class="navigationButton" onclick="changeForm(event, 'visId')">Back</button>
                        <input type="submit" value="Submit" class="navigationButton" name="visSubmit">
                    </div>
                </div>
            </div>
        </form>

        <!-- Establishment Form -->
        <div id="formEstablishment">
            <div id="estInfo" class="tabcontent hide">
                <!-- Insert progress indicator -->
                <div class="sectionTitle center">
                    <h3>Step 2</h3>
                    <h1>Basic Data</h1>
                </div>
                <table>
                    <tr>
                        <th colspan="6">Establishment Information</th>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <label for="estName">Name</label><br>
                            <input required type="text" name="estName">
                        </td>
                        <td colspan="2">
                            <label for="estBra">Branch</label><br>
                            <input required type="text" name="estBra">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="6">Current Address</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label for="estNo">House Number and Street</label><br>
                            <input required type="text" name="estNo">
                        </td>
                        <td colspan="2">
                            <label for="estBa">Barangay</label><br>
                            <input required type="text" name="estBa">
                        </td>
                        <td colspan="2">
                            <label for="estCi">City</label><br>
                            <input required type="text" name="estCi">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label for="estPro">Province</label><br>
                            <input required type="text" name="estPro">
                        </td>
                        <td colspan="2">
                            <label for="estCo">Country</label><br>
                            <input required type="text" name="estCo">
                        </td>
                        <td colspan="2">
                            <label for="estZip">Zipcode</label><br>
                            <input required type="number" name="estZip">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="6">Contact Details</th>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label for="estCNo">Contact Number</label><br>
                            <input required type="tel" name="estCNo">
                        </td>
                        <td colspan="3">
                            <label for="estEmail">Email Address</label><br>
                            <input required type="email" name="estEmail">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="6">Representative Information</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label for="estFName">First Name</label><br>
                            <input required type="text" name="estFName">
                        </td>
                        <td colspan="2">
                            <label for="estMName">Middle Name</label><br>
                            <input required type="text" name="estMName">
                        </td>
                        <td colspan="2">
                            <label for="estLName">Last Name</label><br>
                            <input required type="text" name="estLName">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label for="estPos">Position</label><br>
                            <input required type="text" name="estPos">
                        </td>
                    </tr>
                </table>
                <!-- Insert next button -->
                <button class="navigationButton" onclick="changeForm(event, 'userType', '')">Back</button>
                <button class="navigationButton" onclick="changeForm(event, 'estDocu', 'estInfo')">Next</button>
            </div>
            <div id="estDocu" class="tabcontent hide center">
                <h3>Step 3</h3>
                <h1>Upload Document</h1>
                <h4>Make sure your document is valid and is not expired</h4>
                <input accept="image/*" type='file' id="docInp" name="estDoc" />
                <img id="docPrev" class="hide faceid" src="#" />

                <script>
                let docInp = document.getElementById("docInp");
                docInp.onchange = evt => {
                    const idPreview = document.getElementById("idPreview");
                    const [file] = docInp.files
                    if (file) {
                        idPreview.style.display = "block";
                        idPreview.src = URL.createObjectURL(file)
                    }
                }
                </script>
                <div class="nav">
                <button class="navigationButton" onclick="changeForm(event, 'estInfo', '')">Back</button>
                <button class="navigationButton" onclick="changeForm(event, 'estPassword', 'estDocu')">Next</button>
                </div>
            </div>
            <div id="estPassword" class="tabcontent hide center">
                <h3>Step 4</h3>
                <h1>Password</h1>
                <table>
                    <tr>
                        <td>
                            <label for="password">Password</label><br>
                            <input required type="password" name="estPass" id="estPass" onchange="checkPassword()">
                        </td>
                        <td>
                            <label for="confPass">Confirm Password</label><br>
                            <input required type="password" name="estCPass" id="estCPass" onchange="checkPassword()">
                        </td>
                    </tr>
                </table>
                <br>
                <input type="checkbox" required name="ToS" id="">
                <label for="ToS">I agree to the <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy
                        Policy</a></label>
                        <div class="nav">
                <button class="navigationButton" onclick="changeForm(event, 'estDocu', '')">Back</button>
                <input type="submit" value="Submit" name="estSubmit" class="navigationButton">
                </div>
            </div>
        </div>
        <!-- <input type="submit" id="submit" value="Submit" class="hide"> -->
    </div>

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="../scripts/topper/topper.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />


    <script>
    function changeForm(evt, form, currForm) {
        console.log("TO: " + form);
        console.log("FROM: " + currForm);
        // Check which form to go to [Visitor/Establishment]
        if (form == "step1") {
            var form = document.getElementById("s1Next").name;
            var currForm = "userType";
            if (form == "") {
                return;
            }
        }
        // TO-DO: DEBUG REMOVE && FALSE
        // Check if all form data is filled before going to next form 
        else if (currForm != '' && false) {
            if (checkData(currForm)) {
                // alert("Please input all required data!");
                return;
            }
        }

        //From VS2->3
        if (form == 'visFace' && currForm == 'visInfo') {
            console.log("initCam");
            initCamera();
        }
        // From VS3->2
        if (form == 'visInfo' && currForm == '') {
            // stop cam
            mediaStream.forEach(track => track.stop());
        }

        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(form).style.display = "block";
        evt.currentTarget.className += " active";
    }

    function checkData(formID) {
        let allAreFilled = true;
        document.getElementById(formID).querySelectorAll("[required]").forEach(function(i) {
            if (!allAreFilled) return;
            if (!i.value) allAreFilled = false;
            if (i.type === "radio") {
                let radioValueCheck = false;
                document.getElementById(formID).querySelectorAll(`[name=${i.name}]`).forEach(function(r) {
                    if (r.checked) radioValueCheck = true;
                })
                allAreFilled = radioValueCheck;
            }
        })
        return (!allAreFilled);
    }

    // document.getElementById('password').addEventListener("input", function(e) {
    //     checkPassword();
    // });
    // document.getElementById('confPass').addEventListener("input", function(e) {
    //     checkPassword();
    // });

    /*
     * DEPRECATED
     */

    // function showForm(radio) {
    //     document.getElementById("submit").style.display = "block";
    //     if (radio.value == "visitor") {
    //         document.getElementById("formEstablishment").style.display = "none";
    //         document.getElementById("formVisitor").style.display = "block";
    //     } else {
    //         document.getElementById("formVisitor").style.display = "none";
    //         document.getElementById("formEstablishment").style.display = "block";
    //     }
    // }

    // TO-DO: Need to change variables
    function checkPassword() {
        var pass = document.getElementById("password").value;
        var confPass = document.getElementById("confPass").value;
        if (pass == confPass) {
            document.getElementById("submit").disabled = false;
        } else
            document.getElementById("submit").disabled = true;
    }
    </script>
</body>

</html>