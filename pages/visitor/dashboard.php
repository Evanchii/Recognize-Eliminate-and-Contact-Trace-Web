<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
$stats = $database->getReference('Stats/');
$statsData = $stats->orderByKey()->limitToLast(1)->getSnapshot()->getValue();
if ($statsData != NULL) {
    $key = array_keys($statsData)[0];
} else {
    $key = 0;
    $statsData = [
        0 => [
            "tActive" => 0,
            "nCases" => 0,
            "tDeaths" => 0,
            "tRecoveries" => 0,
            "tTested" => 0,
            "tCases" => 0,
            "daily" => null
        ],
    ];
}

if (!isset($_SESSION['fName'])) {
  $_SESSION["lName"] = $infoRef->getChild("lName")->getValue();
  $_SESSION["fName"] = $infoRef->getChild("fName")->getValue();
  $_SESSION["mName"] = $infoRef->getChild("mName")->getValue();
}


// Firebase Storage
$storage = $firebase->createStorage();
$storageClient = $storage->getStorageClient();
$defaultBucket = $storage->getBucket();


$expiresAt = new DateTime('tomorrow', new DateTimeZone('Asia/Manila'));
// echo $expiresAt->getTimestamp();

$imageReference = $defaultBucket->object($infoRef->getChild("faceID")->getValue());
if ($imageReference->exists()) {
  $image = $imageReference->signedUrl($expiresAt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
  <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
  <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
  <title>Dashboard | REaCT</title>
</head>

<body>
  <div class="grid">
    <div class="Navigation">
      <!-- <h2>REaCT</h2> -->
      <img class="text-logo" src="../../assets/text-logo.png" alt="REaCT ">
      <hr class="divider">
      <div class="user-profile">
        <!-- PHP Get from Storage -->
        <img src="<?php echo $image; ?>">
        <!-- PHP Get from RTDB -->
        <span><?php echo $_SESSION['lName'] . ', ' . $_SESSION['fName'] . ' ' . $_SESSION['mName'] ?></span>
      </div>
      <hr class="divider">
      <a href="#" class="active"><i class="fa fa-th-large" aria-hidden="true"></i>Dashboard</a>
      <a href="cases.php"><i class="fa fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <a href="health.php"><i class="fa fa-heartbeat" aria-hidden="true"></i>Health Status</a>
      <a href="history.php"><i class="fa fa-lightbulb-o" aria-hidden="true"></i>Location History</a>
      <div class="settings">
        <a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i>Setttings</a>
      </div>
    </div>
    <div class="Header">
      <div class="dashboard-date">
        <h2>Dashboard</h2>
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
                    Loading Data...
                  </div>
                  <div class="sub_title">
                    Please Wait
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <div class="dashboard-notif">
          <span class="dropdown"><i class="fa fa-user-circle dropbtn" aria-hidden="true"></i>My Account
            <div class="dropdown-content">
              <a href="profile.php"><i class="fa fa-user-circle" aria-hidden="true"></i>Profile</a>
              <a onclick="$('#change-pw').modal('show');"><i class="fa-solid fa-key" aria-hidden="true"></i>Change Password</a>
              <a href="../logout.php"><i class="fas fa-sign-out" aria-hidden="true"></i>Log out</a>
            </div>
          </span>
        </div>
      </div>
    </div>
    <div class="Content">
      <div class="content-title">
        <!-- Change User place holder insert FN -->
        <h2>Welcome, <?php echo $_SESSION['fName']; ?></h2>
        <span>Here's the latest update in COVID-19 STATUS in Dagupan City</span>
      </div>
      <div class="status-image">
        <!-- Get photo/resources from FB -->
        <img src="../../assets/infographics/<?php echo $statsData[$key]['brgy']; ?>" alt="COVID STATUS" onerror="this.src='../../assets/nodata/nd_brgy.png'">
        <img src="../../assets/infographics/<?php echo $statsData[$key]['situationer']; ?>" alt="COVID STATUS" onerror="this.src='../../assets/nodata/nd_situationer.png'">
      </div>
      <div class="loc-history">
        <div class="loc-title">
          <span>Location History</span>
        </div>
        <!-- Get data from RTDB -->
        <div class="list-history">
          <?php
          $userHisRef = $database->getReference('Users/' . $uid . '/history');
          $historyRef = $database->getReference('History');
          if ($userHisRef->getSnapshot()->hasChildren()) {
            // var_dump($userHisRef->getValue());
            $history = $userHisRef->getValue();
            foreach ($history as $date => $keySet) {
              echo '<div class="history date-history">
                              <h2>' . $date . '</h2>
                            </div>';
              foreach ($keySet as $key => $timestamp) {
                echo '<div class="history">
                              <span>' . $historyRef->getChild($date . '/' . $timestamp . '/estName')->getValue() . '</span>
                              <i class="fa fa-caret-right" aria-hidden="true"></i>
                            </div>';
              }
            }
          } else {
            echo '<h2 style="text-align: center; color: white; margin-top: 15px;">No data found!</h2>';
          }
          ?>
        </div>

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
  <!-- Common Scripts -->
  <script src="../../scripts/common.js"></script>

  <div id="common-modal">
    <?php include '../change.php'; ?>
  </div>


</body>

</html>