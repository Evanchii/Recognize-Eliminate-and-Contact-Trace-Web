<?php
use Firebase\Auth\Token\Exception\RevokedIdToken;
include '../includes/dbconfig.php';

$auth = $firebase->createAuth();
session_start();

$auth->revokeRefreshTokens($_SESSION['uid']);
session_unset();
header('Location: ../');

// try {
//     $verifiedIdToken = $auth->verifyIdToken($_SESSION['token'], $checkIfRevoked = true);
// } catch (RevokedIdToken $e) {
//     echo $e->getMessage();
// }
?>