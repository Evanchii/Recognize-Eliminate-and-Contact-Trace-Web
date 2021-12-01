<?php
use Firebase\Auth\Token\Exception\InvalidToken;
//Initialize Database
include 'includes/dbconfig.php';
// use 'includes/vendor/kreait/firebase-php/src/Firebase/Auth/SignIn/FailedToSignIn.php';
$auth = $firebase->createAuth();

if(isset($_POST['inSubmit'])) {
    session_start();
    $email = $_POST['inEmail'];
    $password = $_POST['inPassword'];

    try {
        $signInResult = $auth->signInWithEmailAndPassword($email, $password);
        $token = $signInResult->idToken();
        try {
            $verIdToken = $auth->verifyIdToken($token);
            $uid = $verIdToken->claims()->get('sub');

            $_SESSION['uid'] = $uid;
            $_SESSION['token'] = $token;

            // $_SESSION = "Logged in successfully!";
            header('Location: pages/dashboard.php');
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
        <style>
            a {
                text-decoration: none;
            }
            a.btn {
                color: #fff;
                background: #FF0066;
                padding: 0.5rem 1rem;
                display: inline-block;
                border-radius: 4px;
                transition-duration: .25s;
                border: none;
                font-size: 14px;
            }
        </style>
    </header>
    <body>
        <h1>Login</h1>
        <form action="index.php" method="POST">
            <p>Email</p>
            <input type="text" name="inEmail" placeholder="sample@email.com" required>
            <p>Password</p>
            <input type="Password" name="inPassword"placeholder="password" required>
            <p><input type="submit" name="inSubmit" value="Log in"></p>
        </form>
        <!-- Link to open the modal -->
        <p><a class="btn" href="#forgot" rel="modal:open">Open Modal</a></p>

        <div id="forgot" class="modal">
        <form id="resetpw" action="functions/forgot.php" method="post">
            <p>Email Address</p>
            <input type="email" name="resEmail" required>
            <p><input type="submit" name="resSubmit" value="Reset"></p>
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

                e.preventDefault();

                $.ajax({
                    type: frm.attr('method'),
                    url: frm.attr('action'),
                    data: frm.serialize(),
                    success: function (data) {
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