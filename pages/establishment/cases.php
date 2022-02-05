<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
$appDataRef = $database->getReference("appData/");

if (!isset($_SESSION['fName'])) {
    $_SESSION["lName"] = $infoRef->getChild("lName")->getValue();
    $_SESSION["fName"] = $infoRef->getChild("fName")->getValue();
    $_SESSION["mName"] = $infoRef->getChild("mName")->getValue();
}


// Firebase Storage
// $storage = $firebase->createStorage();
// $storageClient = $storage->getStorageClient();
// $defaultBucket = $storage->getBucket();


// $expiresAt = new DateTime('tomorrow', new DateTimeZone('Asia/Manila'));
// echo $expiresAt->getTimestamp();

// $imageReference = $defaultBucket->object($infoRef->getChild("faceID")->getValue());
// if ($imageReference->exists()) {
//     $image = $imageReference->signedUrl($expiresAt);
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
    <link rel="stylesheet" type="text/css" href="../../styles/cases.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                <!-- PHP Get from RTDB -->
                <h2><?php echo $_SESSION['name']; ?></h2>
                <h3><?php echo $_SESSION['branch']; ?></h3>
            </div>
            <hr class="divider">
            <a href="dashboard.php"><i class="fa fa-th-large" aria-hidden="true"></i>Dashboard</a>
            <a href="#" class="active"><i class="fa fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
            <a href="status.php"><i class="fa fa-heartbeat" aria-hidden="true"></i>Status</a>
            <a href="history.php"><i class="fa fa-lightbulb-o" aria-hidden="true"></i>Visitor History</a>
            <a href="accounts.php"><i class="fa fa-users" aria-hidden="true"></i>Accounts</a>
            <div class="settings">
                <a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i>Setttings</a>
            </div>
        </div>
        <div class="Header">
            <div class="dashboard-date">
                <h2>COVID Cases</h2>
            </div>
            <div class="dashboard-notif">
                <span class="dropdown"><i class="fa fa-user-circle dropbtn" aria-hidden="true"></i>My Account
                    <div class="dropdown-content">
                        <a href="profile.php"><i class="fa fa-user-circle" aria-hidden="true"></i>Profile</a>
                        <a href="../logout.php"><i class="fa fa-sign-out"></i>Log out</a>
                    </div>
                </span>
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


</body>

</html>