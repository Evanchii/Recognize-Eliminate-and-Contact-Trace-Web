<?php
include '../../includes/dbconfig.php';
session_start();
if(!isset($_SESSION['uid'])) {
    session_unset();
    header('Location: ../../');
} elseif($_SESSION['type'] == 'sub-establishment') {
    session_unset();
    header('Location: ../../');
} elseif(!str_icontains($_SERVER['REQUEST_URI'], str_replace('sub-', '', $_SESSION['type']))) {
    // echo $_SERVER['REQUEST_URI'];
    // echo str_icontains($_SERVER['REQUEST_URI'], $_SESSION['type']);
    header('Location: ../../');
}
?>