<?php
include '../includes/dbconfig.php';
session_start();

$tsNow = $_POST['ts'];
$uid = $_SESSION['uid'];

$userRef = $database->getReference('Users/' . $uid . '/notifs');
$notifRef = $database->getReference('Notifications/');

if ($userRef->getSnapshot()->hasChildren()) {
    $userKeys = $userRef->getValue();
    foreach ($userKeys as $tmp => $ts) {
        $notif = $notifRef->getChild($ts)->getValue();
        switch ($notif['type']) {
            case 'health-alert':
                echo '
                    <li>
                        <div class="notify_icon">
                            <i class="fa-solid fa-virus-covid"></i>
                        </div>
                        <div class="notify_data">
                        <div class="title">
                            ' . $notif['title'] . '
                        </div>
                        <div class="sub_title">
                            ' . $notif['message'] . '
                        </div>
                        </div>
                        <div class="notify_date">'.$date = date('m/d', $ts).'</div>
                    </li>
                ';
                break;
            case 'app-status':
                echo '
                    <li>
                        <div class="notify_icon">
                            <i class="fa-solid fa-file"></i>
                        </div>
                        <div class="notify_data">
                        <div class="title">
                            ' . $notif['title'] . '
                        </div>
                        <div class="sub_title">
                            ' . $notif['message'] . '
                        </div>
                        </div>
                        <div class="notify_date">'.$date = date('m/d', $ts).'</div>
                    </li>
                ';
                break;
            case 'health-status':
                echo '
                    <li>
                        <div class="notify_icon">
                            <i class="fa-solid fa-heart"></i>
                        </div>
                        <div class="notify_data">
                        <div class="title">
                            ' . $notif['title'] . '
                        </div>
                        <div class="sub_title">
                            ' . $notif['message'] . '
                        </div>
                        </div>
                        <div class="notify_date">'.$date = date('m/d', $ts).'</div>
                    </li>
                ';
                break;
            default:
                echo '
                    <li>
                        <div class="notify_icon">
                            <i class="fa-solid fa-message"></i>
                        </div>
                        <div class="notify_data">
                        <div class="title">
                            ' . $notif['title'] . '
                        </div>
                        <div class="sub_title">
                            ' . $notif['message'] . '
                        </div>
                        </p></div>
                        <div class="notify_date"><p>'.$date = date('m/d', $ts).'</div>
                    </li>
                ';
                break;
        }
    }
} else {
    echo '
    <li>
        <div class="notify_data">
            <div class="title">
                Oops! No data found.
            </div>
            <div class="sub_title">
                Check again later
            </div>
        </div>
        </li>
    ';
}
