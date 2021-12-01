<?php
    include '../includes/dbconfig.php';
    
    $email = $_POST['resEmail'];
    $auth = $firebase->createAuth();
    try {
        $auth->sendPasswordResetLink($email);
        echo('<b>Password Reset Link has been sent to your email address! <br/>Can\'t find it? Check your SPAM folder!</b>');
    } catch (Exception $e) {
        if(str_contains($e->getMessage(), "EMAIL_NOT_FOUND")) {
            echo('<b>Email not found! Kindly double check the email.</b><br>
            <p>Email Address</p>
            <input type="email" name="resEmail" required>
            <p><input type="submit" name="resSubmit" value="Reset"></p>');
        }
    }
?>