<?php
// include '../includes/dbconfig.php';
session_start();

// $userSnapshot = $database->getReference('Users')->getSnapshot();

$dbError = false;

if (!(file_exists('../includes/config.json') && file_exists('../includes/database.json'))) {
} else {
    try {
        include '../includes/dbconfig.php';

        $userRef = $database->getReference('Users')->getSnapshot();
        if ($userRef->hasChildren()) {
            header('Location: ../');
        }
    } catch (Exception $e) {
        // Database Error
        echo '<script>Database Error!\n' . $e . '</script>';
        $dbError = true;
    }
}

if (isset($_POST['submit'])) {
    if (isset($_FILES['fbKey'])) {
        $dbJson = $_FILES['fbKey'];

        // File Naming and Pathing
        $info = pathinfo($dbJson['name']);
        $filetype = $info['extension']; // get the extension of the file
        $filepath = '../includes/';
        if (!file_exists($filepath)) {
            mkdir($filepath, 0777, true);
        }
        $filename = 'database' . $filetype;
        $temp_name = $dbJson['tmp_name'];
        $file = $filepath . $filename;

        if (file_exists($file)) {
            unlink($file);
        }

        if (move_uploaded_file($temp_name, $file)) {
            // echo 'OK';
        }
    }
    if (isset($_POST['fbUri'])) {
        $uri = $_POST['fbUri'];
        $api = $_POST['kaiAPI'];
        $id = $_POST['kaiAppId'];
        $key = $_POST['kaiAppKey'];
        $confJson = [
            "firebase-uri" => $uri,
            "kairos-api" => $api,
            "kairos-id" => $id,
            "kairos-key" => $key,
        ];

        $confName = "../includes/config.json";

        if (!file_exists($confName)) {
            unlink($confName);
        }

        $confFile = fopen($confName, "w") or die("Unable to open file!");
        fwrite($confFile, json_encode($confJson));
    }

    include '../includes/dbconfig.php';

    $userRef = $database->getReference('Users');
    if (!$userRef->getSnapshot()->hasChildren()) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $username = $_POST['username'];
        $city = $_POST['city'];
        $province = $_POST['province'];
        $country = $_POST['country'];
        $zip = $_POST['zip'];
        $fName = $_POST['fName'];
        $mName = $_POST['mName'];
        $lName = $_POST['lName'];
        $cNo = $_POST['cNo'];

        $auth = $firebase->createAuth();

        $userProperties = [
            "email" => $email,
            "password" => $password,
            "emailVerified" => true,
        ];

        $createdUser = $auth->createUser($userProperties);

        $userRef->getChild($createdUser->uid.'/info')->update([
            'Type' => 'admin',
            'addCi' => $city,
            'addPro' => $province,
            'addCo' => $country,
            'addZip' => $zip,
            'cNo' => $cNo,
            'email' => $email,
            'fName' => $fName,
            'mName' => $mName,
            'lName' => $lName,
            'username' => $username,
        ]);
        
        header('Location: ../');

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Terms of Service - REaCT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/public-common.css">
    <style>
        table {
            table-layout: fixed;
        }
    </style>
</head>

<body>
    <header>
        <img src="../assets/text-logo.png" alt="REaCT - CORE">
        <h2>Set up</h2>
    </header>
    <div class="content">
        <h2 class="center">Initial Configuration</h2>
        <form action="initialSetup.php" method="POST" enctype="multipart/form-data">
            <div class="dropMenu">
                <div class="menuTitle">
                    <h3>Database and API Connection</h3>
                </div>
                <div class="menuContent">
                    <table>
                        <tr>
                            <td>
                                <h4>Firebase</h4>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="fbKey">Firebase Private Key (.json)</label></td>
                            <td><input type="file" name="fbKey" id="fbKey" <?php echo file_exists('../includes/database.json') ? 'disabled' : ''; ?>></td>
                        </tr>
                        <tr>
                            <td><label for="fbUri">Firebase URI</label></td>
                            <td><input type="text" name="fbUri" id="fbUri" <?php
                                                                            if (!file_exists('../includes/config.json')) {
                                                                            } else echo $dbError ? '' : 'disabled';
                                                                            ?>></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Kairos</h4>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="kaiAPI">API</label></td>
                            <td><input type="text" name="kaiAPI" id="kaiAPI" <?php echo file_exists('../includes/config.json') ? 'disabled' : '' ?>></td>
                            <td><label for="kaiAppId">Application ID</label></td>
                            <td><input type="text" name="kaiAppId" id="kaiAppId" <?php echo file_exists('../includes/config.json') ? 'disabled' : '' ?>></td>

                        </tr>
                        <tr>
                            <td><label for="kaiAppKey">Application Key</label></td>
                            <td><input type="text" name="kaiAppKey" id="kaiAppKey" <?php echo file_exists('../includes/config.json') ? 'disabled' : '' ?>></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="dropMenu">
                <div class="menuTitle">
                    <h3>Admin Account</h3>
                </div>
                <div class="menuContent">
                    <table>
                        <tr>
                            <td>
                                <h4>Credentials</h4>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="email">Email Address</label></td>
                            <td><label for="password">Password</label></td>
                        </tr>
                        <tr>
                            <td><input type="email" name="email" id="email"></td>
                            <td><input type="password" name="password" id="password"></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Personal Information</h4>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="username">Username</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="username" id="username"></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Address</h4>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="city">City</label></td>
                            <td><label for="province">Province</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="city" id="city"></td>
                            <td><input type="text" name="province" id="province"></td>
                        </tr>
                        <tr>
                            <td><label for="country">Country</label></td>
                            <td><label for="zip">Zipcode</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="country" id="country"></td>
                            <td><input type="number" name="zip" id="zip"></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Account Holder's Information</h4>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="fName">First Name</label></td>
                            <td><label for="mName">Middle Name</label></td>
                            <td><label for="lName">Last Name</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="fName" id="fName"></td>
                            <td><input type="text" name="mName" id="mName"></td>
                            <td><input type="text" name="lName" id="lName"></td>
                        </tr>
                        <tr>
                            <td><label for="cNo">Contact Number</label></td>
                        </tr>
                        <tr>
                            <td><input type="tel" name="cNo" id="cNo"></td>
                        </tr>
                    </table>
                    <br>
                    <input type="submit" class="btn-primary" name="submit" value="Submit">
                </div>
            </div>
    </div>
</body>
<html>