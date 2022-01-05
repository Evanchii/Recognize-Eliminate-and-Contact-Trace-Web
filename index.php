<?php
use Firebase\Auth\Token\Exception\InvalidToken;
//Initialize Database
include 'includes/dbconfig.php';
$auth = $firebase->createAuth();
session_start();

if(isset($_SESSION['uid'])) {
    if($_SESSION['type']=="User") {
        header('Location: modules/visitor/dashboard.php');
    } elseif ($_SESSION['type']=="Establishment") {
        header('Location: modules/establishment/dashboard.php');
    } else {
        header('Location: modules/admin/dashboard.php');
    }
}


if(isset($_POST['inSubmit'])) {
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

            if($_SESSION['type']=="User") {
                header('Location: pages/visitor/dashboard.php');
            } elseif ($_SESSION['type']=="Establishment") {
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
    <header>
        <title>Login - REaCT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Asap:wght@400;500&family=Quicksand:wght@400;500&display=swap');
            
            * {
                font-family: 'Asap', sans-serif;
                font-family: 'Quicksand', sans-serif;
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

            .container {
                background: white;
                width: 50vw;
                height: auto;
                margin: 20vh auto;
                padding: 3% 1%;
                box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                -webkit-box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                display: grid;
                grid-template-columns: 50% 50%;
                justify-items: stretch;
                align-items: center;
                justify-content: space-evenly;
                border-radius: 15px;
            }

            .left {
                padding: 5%;
            }

            .left>img {
                width: 75%;
            }

            .right {
                padding: 3%;
                display: flex;
                flex-wrap: nowrap;
                flex-direction: column;
                align-items: center;
            }

            form>input {
                width: 100%;
            }

            .right>img {
                width: 50%;
            }

            a {
                text-decoration: none;
            }

            @media (width: 480px), (orientation: portrait) {
                .container {
                    width: 75vw;
                    margin: 15vh auto;
                    display: block;
                }
                
                .left>img {
                    width: 20vw;
                }
            }
        </style>
    </header>
    <body>
        <div class="container">
            <div class="left center">
                <img src="assets/logo.png" alt="REaCT Logo">
            </div>
            <div class="right">
                <img src="assets/text-logo.png" alt="REaCT Login">
                <form action="index.php" method="POST">
                    <p>Email</p>
                    <input type="email" name="inEmail" placeholder="sample@email.com" required>
                    <p>Password</p>
                    <input type="password" name="inPassword"placeholder="••••••••" required>
                    <p class="end"><a href="#forgot" rel="modal:open">Forgot Password</a></p>
                    <p class="center"><input type="submit" name="inSubmit" value="Log in"></p>
                    <p>Don't have an account? <a href="pages/signup.php">Sign up now!</a></p>
                </form>

            </div>
        </div>

        <div id="forgot" class="modal">
        <form id="resetpw" action="functions/forgot.php" method="post">
            <p>Email Address</p>
            <input type="email" name="resEmail" required>
            <p><input type="submit" id="resBtn" name="resSubmit" value="Reset"></p>
        </form>
        </div>

        <!-- JQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <!-- jQuery Modal -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

        <script type="text/javascript">
            var frm = $('#resetpw');

            frm.submit(function (e) {

                document.getElementById("resBtn").disabled = true;

                e.preventDefault();

                $.ajax({
                    type: frm.attr('method'),
                    url: frm.attr('action'),
                    data: frm.serialize(),
                    success: function (data) {
                        console.log("Data: "+ data);
                        frm.html(data);
                    },
                    error: function (data) {
                        console.log('An error occurred.');
                        console.log(data);
                    },
                });
            });
        </script>
    </body>
</html>