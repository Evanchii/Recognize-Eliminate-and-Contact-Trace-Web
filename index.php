<?php

use Firebase\Auth\Token\Exception\InvalidToken;
//Initialize Database
include 'includes/dbconfig.php';
$auth = $firebase->createAuth();
session_start();

if (isset($_SESSION['uid'])) {
    if ($_SESSION['type'] == "visitor") {
        header('Location: pages/visitor/dashboard.php');
    } elseif ($_SESSION['type'] == "establishment") {
        header('Location: pages/establishment/dashboard.php');
    } else {
        header('Location: pages/admin/dashboard.php');
    }
}


if (isset($_POST['inSubmit'])) {
    // echo '<pre>';
    // var_dump($_SESSION);
    // echo '</pre>';
    $email = $_POST['inEmail'];
    $password = $_POST['inPassword'];

    try {
        $signInResult = $auth->signInWithEmailAndPassword($email, $password);
        $token = $signInResult->idToken();
        try {
            $verIdToken = $auth->verifyIdToken($token);
            $uid = $verIdToken->claims()->get('sub');

            $reference = $database->getReference("Users/" . $uid . "/info/Type");
            $type = $reference->getValue();

            // echo '<script>alert("'. $type .'");</script>';

            $_SESSION['uid'] = $uid;
            $_SESSION['token'] = $token;
            $_SESSION['type'] = $type;

            if ($_SESSION['type'] == "visitor") {
                header('Location: pages/visitor/dashboard.php');
            } elseif ($_SESSION['type'] == "establishment") {
                header('Location: pages/establishment/dashboard.php');
            } else {
                header('Location: pages/admin/dashboard.php');
            }

            // $_SESSION = "Logged in successfully!";
            // header('Location: pages/dashboard.php');
            exit();
        } catch (InvalidToken $e) {
            echo '<script>alert("The token is invalid!")</script>';
        } catch (\InvalidArgumentException $e) {
            echo '<script>alert("The token could not be parsed!")</script>';
        }
    } catch (Exception $e) {
        echo '<script>alert("Invalid Email and/or Password!")</script>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/public-common.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>Login | REaCT</title>
</head>

<body>
    <div class="container">
        <div class="left center">
            <img src="assets/logo.png" alt="REaCT Logo">
        </div>
        <div class="right">
            <img src="assets/text-logo.png" alt="REaCT Login">
            <form action="index.php" method="POST">
                <p>Email</p>
                <input type="email" id="email" name="inEmail" placeholder="sample@email.com" required>
                <p>Password</p>
                <input type="password" id="password" name="inPassword" placeholder="••••••••" required>
                <p class="end"><a href="#forgot" rel="modal:open">Forgot Password</a></p>
                <p class="center"><input type="submit" name="inSubmit" value="Log in"></p>
                <p>Don't have an account? <a href="pages/signup.php">Sign up now!</a></p>
            </form>

        </div>
    </div>

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    
    <a href="#debugMenu" rel="modal:open" class="float">
        <i class="fa fa-bug my-float"></i>
    </a>

    <div id="forgot" class="modal">
        <form id="resetpw" action="functions/forgot.php" method="post">
            <p>Email Address</p>
            <input type="email" name="resEmail" required>
            <p><input type="submit" id="resBtn" name="resSubmit" value="Reset"></p>
        </form>
    </div>

    <div id="debugMenu" class="modal">
        <h3>Debug Menu</h3>
        <p>Select an account type to auto fill login credentials of demo account</p>
        <br>
        <select name="debugAcct" id="debugAcct">
            <option value="select" selected disabled>--Select an account--</option>
            <option value="vis">Visitor</option>
            <option value="est">Establishment</option>
            <option value="adm">Admin</option>
        </select>
    </div>


        <script>
            // To-do: Remove before production
            $('#debugAcct').on('change', function() {
                if(this.value == "vis") {
                    document.getElementById("email").value = "visitor@react-app.ga";
                    document.getElementById("password").value = "REaCT2021";
                    $("#debugMenu .close-modal").click();
                } 
                else if (this.value == "est") {
                    document.getElementById("email").value = "establishment@react-app.ga";
                    document.getElementById("password").value = "REaCT2021";
                    $("#debugMenu .close-modal").click();
                }
                else if (this.value == "adm") {
                    document.getElementById("email").value = "admin@react-app.ga";
                    document.getElementById("password").value = "REaCT2021";
                    $("#debugMenu .close-modal").click();
                }
                else {
                    alert("Module not yet ready!");
                }
            });
        </script>

    <script type="text/javascript">
        var frm = $('#resetpw');

        frm.submit(function(e) {

            document.getElementById("resBtn").disabled = true;

            e.preventDefault();

            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function(data) {
                    console.log("Data: " + data);
                    frm.html(data);
                },
                error: function(data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        });
    </script>
</body>

</html>