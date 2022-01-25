<?php
include '../../includes/dbconfig.php';
session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: ../../');
} else if(!str_contains($_SERVER['REQUEST_URI'], $_SESSION['type'])) {
    echo $_SERVER['REQUEST_URI'];
    echo str_contains($_SERVER['REQUEST_URI'], $_SESSION['type']);
    header('Location: ../../');
}
?>