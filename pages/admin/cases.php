<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
$appDataRef = $database->getReference("appData/");

// Firebase Storage
$storage = $firebase->createStorage();
$storageClient = $storage->getStorageClient();
$defaultBucket = $storage->getBucket();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
    <link rel="stylesheet" type="text/css" href="../../styles/cases.css">
    <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
    <title>COVID Cases | REaCT</title>
</head>

<body>
    <div class="grid">
        <div class="Navigation">
            <!-- <h2>REaCT</h2> -->
            <img class="text-logo" src="../../assets/text-logo.png" alt="REaCT ">
            <hr class="divider">
            <div class="user-profile">
                <!-- PHP Get from Storage -->
                <img src="../../assets/logo.png">
                <!-- PHP Get from RTDB -->
                <span>
                    <?php echo (str_contains($uid, "Uv8vqq4rlrM2ADvfKv6t9KVvndA2")) ? 'Admin Demo' : $infoRef->getChild("addCi")->getValue(); ?>
                </span>
            </div>
            <hr class="divider">
            <a href="dashboard.php"><i class="fas fa-th-large" aria-hidden="true"></i>Dashboard</a>
            <a href="#" class="active"><i class="fas fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
            <a href="applications.php"><i class="far fa-file" aria-hidden="true"></i>Applications</a>
            <a href="users.php"><i class="fas fa-users" aria-hidden="true"></i>Users</a>
            <a href="accounts.php"><i class="fas fa-user-cog" aria-hidden="true"></i>Sub-Accounts</a>
            <div class="settings">
                <a href="settings.php"><i class="fas fa-cog" aria-hidden="true"></i>Setttings</a>
            </div>
        </div>
        <div class="Header">
            <div class="dashboard-date">
                <h2>COVID Cases</h2>
            </div>
            <div class="header-right">
                <div class="notifications">
                    <div class="icon_wrap"><i class="far fa-bell"></i></div>

                    <div class="notification_dd">
                        <ul class="notification_ul">
                            <li class="starbucks success">
                                <div class="notify_icon">
                                    <span class="icon"></span>
                                </div>
                                <div class="notify_data">
                                    <div class="title">
                                        Lorem, ipsum dolor.
                                    </div>
                                    <div class="sub_title">
                                        Lorem ipsum dolor sit amet consectetur.
                                    </div>
                                </div>
                                <div class="notify_status">
                                    <p>Success</p>
                                </div>
                            </li>
                            <li class="baskin_robbins failed">
                                <div class="notify_icon">
                                    <span class="icon"></span>
                                </div>
                                <div class="notify_data">
                                    <div class="title">
                                        Lorem, ipsum dolor.
                                    </div>
                                    <div class="sub_title">
                                        Lorem ipsum dolor sit amet consectetur.
                                    </div>
                                </div>
                                <div class="notify_status">
                                    <p>Failed</p>
                                </div>
                            </li>
                            <li class="mcd success">
                                <div class="notify_icon">
                                    <span class="icon"></span>
                                </div>
                                <div class="notify_data">
                                    <div class="title">
                                        Lorem, ipsum dolor.
                                    </div>
                                    <div class="sub_title">
                                        Lorem ipsum dolor sit amet consectetur.
                                    </div>
                                </div>
                                <div class="notify_status">
                                    <p>Success</p>
                                </div>
                            </li>
                            <li class="pizzahut failed">
                                <div class="notify_icon">
                                    <span class="icon"></span>
                                </div>
                                <div class="notify_data">
                                    <div class="title">
                                        Lorem, ipsum dolor.
                                    </div>
                                    <div class="sub_title">
                                        Lorem ipsum dolor sit amet consectetur.
                                    </div>
                                </div>
                                <div class="notify_status">
                                    <p>Failed</p>
                                </div>
                            </li>
                            <li class="kfc success">
                                <div class="notify_icon">
                                    <span class="icon"></span>
                                </div>
                                <div class="notify_data">
                                    <div class="title">
                                        Lorem, ipsum dolor.
                                    </div>
                                    <div class="sub_title">
                                        Lorem ipsum dolor sit amet consectetur.
                                    </div>
                                </div>
                                <div class="notify_status">
                                    <p>Success</p>
                                </div>
                            </li>
                            <li class="show_all">
                                <p class="link">Show All Activities</p>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="dashboard-notif">
                    <span class="dropdown"><i class="fa fa-user-circle dropbtn" aria-hidden="true"></i>My Account
                        <div class="dropdown-content">
                            <a href="../logout.php"><i class="fas fa-sign-out" aria-hidden="true"></i>Log out</a>
                        </div>
                    </span>
                </div>
            </div>
        </div>
        <div class="Content">
            <div class="content-title">
                <h4>Dagupan City, Pangasinan</h4>
                <span>Covid-19 Status<br>As of <?php echo $appDataRef->getChild('covStatus/time')->getValue(); ?> | <?php echo $appDataRef->getChild('covStatus/date')->getValue(); ?></span>
            </div>
            <div class="stats">
                <div class="box">
                    <div class="cases mini-card">
                        <h3>Total Cases</h3><br><?php echo $appDataRef->getChild('covStatus/cases')->getValue(); ?>
                    </div>
                    <div class="tested mini-card">
                        <h3>Total Tested</h3><br><?php echo $appDataRef->getChild('covStatus/tested')->getValue(); ?>
                    </div>
                    <div class="recoveries mini-card">
                        <h3>Total Recoveries</h3><br><?php echo $appDataRef->getChild('covStatus/recoveries')->getValue(); ?>
                    </div>
                    <div class="deaths mini-card">
                        <h3>Total Deaths</h3><br><?php echo $appDataRef->getChild('covStatus/death')->getValue(); ?>
                    </div>
                    <div class="newCases mini-card">
                        <h3>New Cases</h3><br><?php echo $appDataRef->getChild('covStatus/newCases')->getValue(); ?>
                    </div>
                    <div class="activeCases mini-card">
                        <h3>Total Active</h3><br><?php echo $appDataRef->getChild('covStatus/active')->getValue(); ?>
                    </div>
                </div>
            </div>
            <div class="daily-cases">
                <h2>Daily Cases</h2>
                <p>
                    <a href="https://www.facebook.com/DagupanPIO">
                        <img src="<?php echo $appDataRef->getChild('links/daily')->getValue() ?>" alt="No DATA found" onerror="//this.src='img/undefined.jpg'">
                    </a>
                </p>
            </div>
        </div>
        <div class="Footer">
            © 2021 REaCT. All right reserved
        </div>
    </div>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/a2501cd80b.js" crossorigin="anonymous"></script>

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

</body>

</html>