<?php
include '../includes/dbconfig.php';
session_start();
date_default_timezone_set('Asia/Manila');

$uid = $_POST['uid'];
// $uid = 'VTG17U39YNTrG2swl7SCZzX4Q2I3';
// var_dump($_POST);

// Get 14days ago with 00H 00M 00S
$tmpA = strtotime('-14 days');
$startDate = $tmpA - ($tmpA %  86400 + 28800);
// 86400 = 24 HRS and 28800 = 8HRS
echo $startDate;

$userHisRef = $database->getReference('Users/' . $uid . '/history');
$hisRef = $database->getReference('History');
$userRef = $database->getReference('Users');
$notifs = $database->getReference('Notifications');

$tsNow = time();
$notifs->getChild($tsNow)->update([
    'type' => 'health-alert',
    'title' => 'Reported Positive Contact',
    'message' => 'A user that you\'ve been in contact in the last 14 days has reported that they\'ve tested positive. Please be cautious!'
]);

$notifs->getChild($tsNow+1)->update([
    'type' => 'health-alert',
    'title' => 'Reported Positive Contact',
    'message' => 'A user that visited your establishment has tested positive. Please be cautious!'
]);

$notifs->getChild($tsNow+2)->update([
    'type' => 'health-alert',
    'title' => 'Reported Positive Contact',
    'message' => $uid . ' has tested positive. Please be cautious!'
]);
$adminUID = $database->getReference('AppInfo/Admin')->getValue();
$database->getReference('Users/'.$adminUID.'/notifs')->update([
    $tsNow+2 => $tsNow+2
]);

$userHisData = $userHisRef->getValue();
foreach ($userHisData as $date => $arrTS) {
    $newDate = str_replace('/', '-', $date);
    if (strtotime($newDate) >= $startDate) {
        foreach ($arrTS as $tmp => $ts) {
            $estUID = $hisRef->getChild($date . '/' . $ts . '/estUID')->getValue();

            $database->getReference('Users/'.$estUID.'/notifs')->update([
                $tsNow+1 => $tsNow+1
            ]);

            $estData = array_keys($userRef->getChild($estUID . '/history/' . $date)->getValue());
            foreach ($estData as $i => $estTS) {
                $conUID = $hisRef->getChild($date . '/' . $estTS . '/uid')->getValue();

                if($conUID == $uid)
                    continue;
                $userRef->getChild($conUID . '/notifs')->update([
                    $tsNow => $tsNow
                ]);
            }
        }
    }
}
