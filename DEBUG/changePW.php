<?php
if(isset($_POST['old-pw'])) {
    include '../includes/dbconfig.php';
    session_start();
    $auth = $firebase->createAuth();

    $old = $_POST['old-pw'];
    $new = $_POST['new-pw'];

    try {
        $auth->changeUserPassword($old, $new);
    } catch (Exception $e) {
        // echo 'Invalid Email and/or Password!';
        echo 'Error> '.$e;
    }

    exit();
}
?>

<div id="change-pw" class="modal">
    <div class="modal-title"><h3>Change Password</h3></div>
    <div class="modal-body">
        <form action="changePW.php" method="POST" id="frmPass">
            <label>UID:</label>
            <input type="password" name="old-pw" id="old-pw"><br>
            <label>New Password:</label>
            <input type="password" name="new-pw" id="new-pw"><br>
            <button type="submit" class="btn-primary">Change Password</button>
        </form>
    </div>
    <div class="modal-footer">
    </div>
</div>