<?php
if(isset($_POST['old-pw'])) {
    include '../includes/dbconfig.php';
    session_start();
    $auth = $firebase->createAuth();

    $uid = $_SESSION['uid'];
    $old = $_POST['old-pw'];
    $new = $_POST['new-pw'];

    try {
        $auth->signInWithEmailAndPassword($auth->getUser($uid)->__get('email'), $old);
        
        $auth->changeUserPassword($uid, $new);
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
        <form action="../change.php" method="POST" id="frmPass">
            <label>Old Password:</label>
            <input type="password" name="old-pw" id="old-pw"><br>
            <label>New Password:</label>
            <input type="password" name="new-pw" id="new-pw"><br>
            <label>Confirm New Password:</label>
            <input type="password" name="conf-pw" id="conf-pw">
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn-primary" onclick="change()">Change Password</button>
    </div>
</div>