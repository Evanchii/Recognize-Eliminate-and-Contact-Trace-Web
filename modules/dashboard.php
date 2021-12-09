<?php
session_start();
include '../includes/dbconfig.php';
echo($_SESSION['uid']);

echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
?>