<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
$linkRef = $database->getReference("appData/links/");

// Firebase Storage
$storage = $firebase->createStorage();
$storageClient = $storage->getStorageClient();
$defaultBucket = $storage->getBucket();


$expiresAt = new DateTime('tomorrow', new DateTimeZone('Asia/Manila'));
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
  <link rel="stylesheet" type="text/css" href="../../styles/admin/dashboard.css">
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
        <!-- PHP Get from Storage -->
        <img src="../../assets/logo.png">
        <!-- PHP Get from RTDB -->
        <span>
          <?php echo (str_contains($uid, "Uv8vqq4rlrM2ADvfKv6t9KVvndA2"))? 'Admin Demo' : $infoRef->getChild("addCi")->getValue(); ?>
        </span>
      </div>
      <hr class="divider">
      <a href="#" class="active"><i class="fas fa-th-large" aria-hidden="true"></i>Dashboard</a>
      <a href="cases.php"><i class="fas fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <a href="applications.php"><i class="far fa-file" aria-hidden="true"></i>Applications</a>
      <a href="users.php"><i class="fas fa-users" aria-hidden="true"></i>Users</a>
      <a href="accounts.php"><i class="fas fa-user-cog" aria-hidden="true"></i>Sub-Accounts</a>
      <div class="settings">
        <a href="settings.php"><i class="fas fa-cog" aria-hidden="true"></i>Setttings</a>
      </div>
    </div>
    <div class="Header">
      <div class="dashboard-date">
        <h2>Dashboard</h2>
      </div>
      <div class="dashboard-notif">
        <span class="dropdown"><i class="fa fa-user-circle dropbtn" aria-hidden="true"></i>My Account
          <div class="dropdown-content">
            <a href="../logout.php"><i class="fas fa-sign-out" aria-hidden="true"></i>Log out</a>
          </div>
        </span>
      </div>
    </div>
    <div class="Content">
      <div class="graph">
        <h3>COVID Cases Overview (7-day)</h3>
      <canvas id="overview"></canvas>
      </div>
      <div class="stats">
        <h2>Tracking</h2>
        <div class="con-stats">
          <div class="stats-item">
            <div class="icon">
              <i class="far fa-file" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2>11</h2>
              <h4>Applications Pending</h4>
            </div>
          </div>
          <div class="stats-item">
            <div class="icon">
              <i class="fas fa-users" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2>1,985</h2>
              <h4>Users</h4>
            </div>
          </div>
          <div class="stats-item">
            <div class="icon">
              <i class="fas fa-chart-line" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2>16</h2>
              <h4>Accounts</h4>
            </div>
          </div>
        </div>
      </div>
    </div>



    <div class="Footer">

      © 2021 REaCT. All right reserved

    </div>

    <script src="https://kit.fontawesome.com/a2501cd80b.js" crossorigin="anonymous"></script>
    <script src="../../node_modules/chart.js/dist/chart.js"></script>

    <script>
      const chart = document.getElementById("overview").getContext("2d");
      const labels = [
        '01/28',
        '01/29',
        '01/30',
        '01/31',
        '02/01',
        '02/02',
        '02/03',
      ];
      const data = {
        labels: labels,
        datasets: [{
          label: 'New Cases',
          data: [42, 36, 8, 26, 10, 16, 16],
          fill: true,
          backgroundColor: 'rgb(254, 81, 5)'
        },
        {
          label: 'New Recoveries',
          data: [52, 85, 94, 68, 34, 32, 37],
          fill: true,
          backgroundColor: 'rgb(28, 125, 228)'
        },
        {
          label: 'New Deaths',
          data: [1, 0, 0, 0, 0, 0, 0],
          fill: true,
          backgroundColor: 'rgb(168, 12, 16)'
        }]
      };
      const myChart = new Chart(chart, {
        type: 'bar',
        data: data,

      });
    </script>


  </div>


</body>

</html>