<?php
session_start();

$dbError = false;

try {
    include '../includes/dbconfig.php';

    $userRef = $database->getReference('Users')->getSnapshot();
    if ($userRef->hasChildren()) {
        header('Location: ../');
    }
} catch (Exception $e) {
    echo '<script>Database Error!\n' . $e . '</script>';
    $dbError = true;
}

if (isset($_POST['submit'])) {

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
    <title>Initial Configuration - REaCT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/public-common.css">
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    <style>
        table {
            table-layout: fixed;
            width: 100%;
        }

        td {
            padding: 0px 1rem;
        }

        td input {
            width: 100%;
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