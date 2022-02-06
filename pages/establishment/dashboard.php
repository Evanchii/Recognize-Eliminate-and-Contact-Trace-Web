<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");

if (!isset($_SESSION['name'])) {
  $_SESSION["name"] = $infoRef->getChild("name")->getValue();
  $_SESSION["branch"] = $infoRef->getChild("branch")->getValue();
}


// Firebase Storage
// $storage = $firebase->createStorage();
// $storageClient = $storage->getStorageClient();
// $defaultBucket = $storage->getBucket();


// $expiresAt = new DateTime('tomorrow', new DateTimeZone('Asia/Manila'));
// echo $expiresAt->getTimestamp();

// $imageReference = $defaultBucket->object($infoRef->getChild("faceID")->getValue());
// if ($imageReference->exists()) {
//   $image = $imageReference->signedUrl($expiresAt);
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
  <link rel="stylesheet" type="text/css" href="../../styles/establishment/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        <!-- PHP Get from RTDB -->
        <h2><?php echo $_SESSION['name'];?></h2>
        <h3><?php echo $_SESSION['branch'];?></h3>
      </div>
      <hr class="divider">
      <a href="#" class="active"><i class="fa fa-th-large" aria-hidden="true"></i>Dashboard</a>
      <a href="cases.php"><i class="fa fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <a href="status.php"><i class="fa fa-heartbeat" aria-hidden="true"></i>Status</a>
      <a href="history.php"><i class="fa fa-lightbulb-o" aria-hidden="true"></i>Visitor History</a>
      <a href="accounts.php"><i class="fa fa-users" aria-hidden="true"></i>Accounts</a>
      <div class="settings">
        <a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i>Setttings</a>
      </div>
    </div>
    <div class="Header">
      <div class="dashboard-date">
        <h2>Dashboard</h2>
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
      <div class="graph">
        <h2>Overview</h2>
        <canvas id="overview"></canvas>
        <script src="../../node_modules/chart.js/dist/chart.js"></script>
        <script>
          const chart = document.getElementById("overview").getContext("2d");
          const labels = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
          ];
          const data = {
            labels: labels,
            datasets: [{
              label: 'Number of Visitors',
              data: [65, 59, 80, 81, 56, 55, 40],
              fill: false,
              borderColor: 'rgb(12, 89, 207)'
            }]
          };
          const myChart = new Chart(chart, {
            type: 'line',
            data: data,

          });
        </script>
      </div>
      <div class="stats">
        <h2>Tracking</h2>
        <div class="con-stats">
          <div class="stats-item">
            <div class="icon">
              <i class="fa fa-briefcase" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2>405</h2>
              <h4>Sub-accounts</h4>
            </div>
          </div>
          <div class="stats-item">
            <div class="icon">
              <i class="fa fa-users" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2>505</h2>
              <h4>Visitors</h4>
            </div>
          </div>
          <div class="stats-item">
            <div class="icon">
              <i class="fa fa-line-chart" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2>910</h2>
              <h4>Total People</h4>
            </div>
          </div>
        </div>
      </div>
      <div class="loc-history">
        <div class="loc-title">
          <span>Visitor History</span>
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
            echo '<h2 style="text-align: center; color: white;">No data found!</h2>';
          }
          ?>
        </div>

      </div>

    </div>



    <div class="Footer">

      © 2021 REaCT. All right reserved

    </div>


  </div>


</body>

</html>