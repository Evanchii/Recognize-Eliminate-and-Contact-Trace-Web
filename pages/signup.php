<?php
// Initialize database
include '../includes/dbconfig.php';

$auth = $firebase->createAuth();
if (isset($_POST['visSubmit'])) {
    $fname = $_POST['visFName'];
    $mname = $_POST['visMName'];
    $lname = $_POST['visLName'];
    $cno = $_POST['visCNo'];
    $email = $_POST['visEmail'];
    $dob = $_POST['visDOB'];
    $nostreet = $_POST['visNo'];
    $ba = $_POST['visBa'];
    $ci = $_POST['visCi'];
    $pr = $_POST['visPro'];
    $co = $_POST['visCo'];
    $zip = $_POST['visZip'];
    $pass = $_POST['visPass'];

    $userProperties = [
        'email' => $email,
        'emailVerified' => false,
        'password' => $pass,
    ];

    try {
        #Firebase Auth Register
        $createdUser = $auth->createUser($userProperties);

        #Upload Face Photo
        $img = $_POST['visFace'];
        $folderPath = "Face/";

        // image/jpg;base64kfjglkfjgklfbklfbfbnfbl
        $image_parts = explode(";base64,", $img);
        // image/jpg kfjglkfjgklfbklfbfbnfbl

        // $image_type_aux = explode("image/", $image_parts[0]);
        // $image_type = $image_type_aux[1];

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

        unlink($file);

        $idFile = $_FILES['visID']['tmp_name'];

        $defaultBucket->upload(
            file_get_contents($idFile),
            [
                'name' => "ID/" . $createdUser->uid . '.png'
            ]
        );

        #Firebase Realtime Database push user data
        $database->getReference('Users')->update(
            [
                $createdUser->uid => [
                    'info' => [
                        'faceID' => 'Face/' . $createdUser->uid . '.png',
                        'ID' => 'ID/' . $createdUser->uid . '.png',
                        'Type' => 'visitor',
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
                        'vaccine' => false,
                    ],
                ],
            ],
        );

        include '../functions/enrollFace.php';
        enrollFace($_POST['visFace'], $createdUser->uid);
        
        createLog('Account', ' has created their own account', $createdUser->uid);

        $auth->sendEmailVerificationLink($email);

        echo '<script>alert("Successfully Registered! Please check your inbox for your email verification link!");  window.location = \'../\';</script></script>';

        // header('Location: ../');
    } catch (Exception $e) {
        echo '<script>alert("' . $e . '")</script>';
    }
} elseif (isset($_POST['estSubmit'])) {
    $name = $_POST['estName'];
    $branch = $_POST['estBra'];
    $nostreet = $_POST['estNo'];
    $ba = $_POST['estBa'];
    $ci = $_POST['estCi'];
    $pr = $_POST['estPro'];
    $co = $_POST['estCo'];
    $zip = $_POST['estZip'];
    $cno = $_POST['estCNo'];
    $email = $_POST['estEmail'];
    $fname = $_POST['estFName'];
    $mname = $_POST['estMName'];
    $lname = $_POST['estLName'];
    $pos = $_POST['estPos'];
    $pass = $_POST['estPass'];

    $userProperties = [
        'email' => $email,
        'emailVerified' => false,
        'password' => $pass,
        'disabled' => true,
    ];

    try {
        #Firebase Auth Register
        $createdUser = $auth->createUser($userProperties);

        $storage = $firebase->createStorage();
        $storageClient = $storage->getStorageClient();
        $defaultBucket = $storage->getBucket();

        $defaultBucket->upload(
            file_get_contents($_FILES['estDoc']['tmp_name']),
            [
                'name' => "Doc/" . $createdUser->uid . ".png"
            ]
        );

        #Firebase Realtime Database push user data
        $database->getReference('Users')->update(
            [
                $createdUser->uid => [
                    'info' => [
                        'doc' => 'Doc/' . $createdUser->uid . '.png',
                        'Type' => 'establishment',
                        'name' => $name,
                        'branch' => $branch,
                        'addNo' => $nostreet,
                        'addBa' => $ba,
                        'addCi' => $ci,
                        'addPro' => $pr,
                        'addCo' => $co,
                        'addZip' => $zip,
                        'cNo' => $cno,
                        'fName' => $fname,
                        'mName' => $mname,
                        'lName' => $lname,
                        'pos' => $pos,
                        'status' => false,
                    ],
                ],
            ],
        );

        $database->getReference('Applications')->update([
            time() => [
                'doc' => 'Doc/' . $createdUser->uid . '.png',
                'type' => 'Account Verification',
                'name' => $name,
                'branch' => $branch,
                'addNo' => $nostreet,
                'addBa' => $ba,
                'addCi' => $ci,
                'addPro' => $pr,
                'addCo' => $co,
                'addZip' => $zip,
                'cNo' => $cno,
                'repName' => $lname . ', ' . $fname . ' ' . $mname,
                'repPos' => $pos,
                'usertype' => 'Establishment',
                'uid' => $createdUser->uid,
                'email' => $email
            ]
        ]);

        $auth->sendEmailVerificationLink($email);
        $auth->disableUser($createdUser->uid);
        
        createLog('Account', ' has created their own account', $createdUser->uid);
        createLog('Application', ' has submitted their Account Verification application', $createdUser->uid);
        echo '<script>alert("Registration sent! We will immediately send an email after we review it."); window.location = \'../\';</script>';

        // header('Location: ../');
    } catch (Exception $e) {
        echo '<script>alert("${e}")</script>';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link rel="stylesheet" href="../styles/public-common.css">
    <link rel="stylesheet" href="../styles/signup.css">
    <title>Sign up | REaCT</title>
</head>

<body>
    <header>
        <a href="../"><img src="../assets/text-logo.png" alt="REaCT"></a>
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
                    <input type="radio" class="radioCard" name="usertype" value="visitor" onclick="select(this.id);"><br>
                    <img src="../assets/ic_visitor.svg" alt="Visitor" class="imgCircle">
                    <h3>Visitor</h3>
                </label>
                <label class="type center" id="typeEst" onclick="select(this.id);">
                    <input type="radio" class="radioCard" name="usertype" value="establishment" onclick="select(this);"><br>
                    <img src=" ../assets/ic_establishment.svg" alt="Establishment" class="imgCircle">
                    <h3>Establishment</h3>
                </label>
            </div>
            <button type="button" class="navigationButton" id="s1Next" onclick="changeForm(event, 'step1' ,'')">Next</button>
        </div>

        <form action="signup.php" name="visForm" method="POST" enctype="multipart/form-data">
            <div id="formVisitor">
                <!-- Visitor Form -->
                <div id="visInfo" class="tabcontent hide">
                    <!-- Insert progress indicator -->
                    <div class="sectionTitle center">
                        <h3>Step 2</h3>
                        <h1>Basic Data</h1>
                    </div>
                    <div class="sectTitle">Full Name</div>
                    <div class="section">
                        <span>
                            <label for="visFName">First Name</label><br>
                            <input required type="text" name="visFName">
                        </span>
                        <span>
                            <label for="visMName">Middle Name</label><br>
                            <input required type="text" name="visMName">
                        </span>
                        <span>
                            <label for="visLName">Last Name</label><br>
                            <input required type="text" name="visLName">
                        </span>
                    </div>
                    <div class="section">
                        <span>
                            <label for="visCNo">Contact Number</label><br>
                            <input required type="tel" name="visCNo">
                        </span>
                        <span>
                            <label for="visEmail">Email Address</label><br>
                            <input required type="email" name="visEmail">
                        </span>
                        <span>
                            <label for="visDOB">Date of Birth</label><br>
                            <input required type="date" name="visDOB">
                        </span>
                    </div>
                    <div class="sectTitle">Current Address</div>
                    <div class="section">
                        <span>
                            <label for="visNo">House Number and Street</label><br>
                            <input required type="text" name="visNo">
                        </span>
                        <span>
                            <label for="visBa">Barangay</label><br>
                            <input required type="text" name="visBa">
                        </span>
                        <span>
                            <label for="visCi">City</label><br>
                            <input required type="text" name="visCi">
                        </span>
                    </div>
                    <div class="section">
                        <span><label for="visPro">Province</label><br>
                            <input required type="text" name="visPro"></span>
                        <span><label for="visCo">Country</label><br>
                            <input required type="text" name="visCo"></span>
                        <span><label for="visZip">Zipcode</label><br>
                            <input required type="number" name="visZip"></span>
                    </div>
                    <!-- Insert next button -->
                    <button type="button" class="navigationButton" onclick="changeForm(event, 'userType', '')">Back</button>
                    <button type="button" class="navigationButton" onclick="changeForm(event, 'visFace', 'visInfo')">Next</button>
                </div>
                <div id="visFace" class="tabcontent hide center">
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
                        <button type="button" class="camButt" id="retry-button"><img src="../assets/ic_retry.svg" alt="">Retry</button>
                    </div>
                    <div class="nav">
                        <button type="button" class="navigationButton" onclick="changeForm(event, 'visInfo', '')">Back</button>
                        <button type="button" class="navigationButton hide" id="camNext" onclick="verifyFace();">Next</button>
                    </div>
                </div>
                <div id="visId" class="tabcontent hide center">
                    <h3>Step 4</h3>
                    <h1>Upload ID</h1>
                    <h4>Make sure your ID is valid and is not expired</h4>
                    <input accept="image/*" type='file' id="visID" name="visID" />
                    <img id="IDPrev" class="hide idPreview" src="#" />
                    
                    <div class="nav">
                        <button type="button" class="navigationButton" onclick="changeForm(event, 'visFace', '')">Back</button>
                        <button type="button" class="navigationButton" onclick="changeForm(event, 'visPassword', 'visId')">Next</button>
                        <button type="button" class="navigationButton btn-primary" style="float:right;"onclick="$('#valid-id').modal('show');">List of Valid IDs</button>
                    </div>
                </div>
                <div id="visPassword" class="tabcontent hide center">
                    <h3>Step 5</h3>
                    <h1>Password</h1>
                    <table>
                        <tr>
                            <td class="start">
                                <label for="visPass">Password</label><br>
                                <input required type="password" name="visPass" id="visPass" onkeyup="checkPassword('vis')">
                            </td>
                            <td class="start">
                                <label for="visCPass">Confirm Password</label><br>
                                <input required type="password" name="visCPass" id="visCPass" onkeyup="checkPassword('vis')">
                            </td>
                        </tr>
                    </table>
                    <br>
                    <input type="checkbox" required name="ToS" id="">
                    <label for="ToS">I agree to the <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy
                            Policy</a></label>
                    <div class="nav">
                        <button type="button" class="navigationButton" onclick="changeForm(event, 'visId', '')">Back</button>
                        <input type="submit" disabled id="visSubmit" value="Submit" class="navigationButton" name="visSubmit">
                    </div>
                </div>
            </div>
        </form>

        <!-- Establishment Form -->
        <form action="signup.php" name="estForm" method="POST" enctype="multipart/form-data">
            <div id="formEstablishment">
                <div id="estInfo" class="tabcontent hide">
                    <!-- Insert progress indicator -->
                    <div class="sectionTitle center">
                        <h3>Step 2</h3>
                        <h1>Basic Data</h1>
                    </div>
                    <div class="sectTitle">Establishment Information</div>
                    <div class="section">
                        <span><label for="estName">Name</label><br>
                            <input required type="text" name="estName"></span>
                        <span><label for="estBra">Branch</label><br>
                            <input required type="text" name="estBra"></span>
                    </div>
                    <div class="sectTitle">Current Address</div>
                    <div class="section">
                        <span><label for="estNo">Establishment Number and Street</label><br>
                            <input required type="text" name="estNo"></span>
                        <span><label for="estBa">Barangay</label><br>
                            <input required type="text" name="estBa"></span>
                        <span><label for="estCi">City</label><br>
                            <input required type="text" name="estCi"></span>
                    </div>
                    <div class="section">
                        <span><label for="estPro">Province</label><br>
                            <input required type="text" name="estPro"></span>
                        <span><label for="estCo">Country</label><br>
                            <input required type="text" name="estCo"></span>
                        <span><label for="estZip">Zipcode</label><br>
                            <input required type="number" name="estZip"></span>
                    </div>
                    <div class="sectTitle">Contact Details</div>
                    <div class="section">
                        <span><label for="estCNo">Contact Number</label><br>
                            <input required type="tel" name="estCNo"></span>
                        <span><label for="estEmail">Email Address</label><br>
                            <input required type="email" name="estEmail"></span>
                    </div>
                    <div class="sectTitle">Representative Information</div>
                    <div class="section">
                        <span><label for="estFName">First Name</label><br>
                            <input required type="text" name="estFName"></span>
                        <span><label for="estMName">Middle Name</label><br>
                            <input required type="text" name="estMName"></span>
                        <span><label for="estLName">Last Name</label><br>
                            <input required type="text" name="estLName"></span>
                    </div>
                    <div class="section">
                        <span><label for="estPos">Position</label><br>
                            <input required type="text" name="estPos" style="width: 30%"></span>
                    </div>
                    <!-- Insert next button -->
                    <button type="button" class="navigationButton" onclick="changeForm(event, 'userType', '')">Back</button>
                    <button type="button" class="navigationButton" onclick="changeForm(event, 'estDocu', 'estInfo')">Next</button>
                </div>
                <div id="estDocu" class="tabcontent hide center">
                    <h3>Step 3</h3>
                    <h1>Upload Document</h1>
                    <h4>Make sure your document is valid and is not expired</h4>
                    <input accept="image/*" type='file' id="docInp" name="estDoc" />
                    <img id="docPrev" class="hide idPreview" src="#" />

                    <div class="nav">
                        <button type="button" class="navigationButton" onclick="changeForm(event, 'estInfo', '')">Back</button>
                        <button type="button" class="navigationButton" onclick="changeForm(event, 'estPassword', 'estDocu')">Next</button>
                    </div>
                </div>
                <div id="estPassword" class="tabcontent hide center">
                    <h3>Step 4</h3>
                    <h1>Password</h1>
                    <table>
                        <tr>
                            <td>
                                <label for="password">Password</label><br>
                                <input required type="password" name="estPass" id="estPass" onkeyup="checkPassword('est')">
                            </td>
                            <td>
                                <label for="confPass">Confirm Password</label><br>
                                <input required type="password" name="estCPass" id="estCPass" onkeyup="checkPassword('est')">
                            </td>
                        </tr>
                    </table>
                    <br>
                    <input type="checkbox" required name="ToS" id="">
                    <label for="ToS">I agree to the <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy
                            Policy</a></label>
                    <div class="nav">
                        <button type="button" class="navigationButton" onclick="changeForm(event, 'estDocu', '')">Back</button>
                        <input type="submit" disabled id="estSubmit" value="Submit" name="estSubmit" class="navigationButton">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="../scripts/signup.js"></script>
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

    <div id="feedback" class="modal">
        <div class="modal-title">
            <h3>System Message</h3>
        </div>
        <div class="modal-body" id="feedback-container">
            <p>information here</p>
        </div>
        <div class="modal-footer">
            <button class="btn-primary" onclick="$('#feedback .close-modal').click();">Okay</button>
        </div>
    </div>

    <div id="valid-id" class="modal">
        <div class="modal-title">
            <h3>List of Valid IDs</h3>
        </div>
        <div class="modal-body" style="padding: 15px 50px; line-height: 25px">
            <ol>
            <li>e-Card / UMID</li>
            <li>Employee’s ID / Office Id</li>
            <li>Driver’s License</li>
            <li>Professional Regulation Commission (PRC) ID </li>
            <li>Passport </li>
            <li>Senior Citizen ID</li>
            <li>SSS ID</li>
            <li>COMELEC / Voter’s ID / COMELEC Registration Form</li>
            <li>Philippine Identification (PhilID)</li>
            <li>NBI Clearance </li>
            <li>Integrated Bar of the Philippines (IBP) ID</li>
            <li>Firearms License </li>
            <li>AFPSLAI ID </li>
            <li>PVAO ID</li>
            <li>AFP Beneficiary ID</li>
            <li>BIR (TIN)</li>
            <li>Pag-ibig ID</li>
            <li>Person’s With Disability (PWD) ID</li>
            <li>Solo Parent ID</li>
            <li>Pantawid Pamilya Pilipino Program (4Ps) ID </li>
            <li>Barangay ID </li>
            <li>Philippine Postal ID </li>
            <li>Phil-health ID</li>
            <li>School ID </li>
            <li>Other valid government-issued IDs or</li>
            <li>Documents with picture and address</li>
            </ol>
        </div>
        <div class="modal-footer">
            <button class="btn-primary" onclick="$('#valid-id .close-modal').click()">Okay</button>
        </div>
    </div>
</body>

</html>