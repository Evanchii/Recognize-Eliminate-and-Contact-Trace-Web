<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");

$stats = $database->getReference('Stats');
$statsData = $stats->orderByKey()->limitToLast(7)->getSnapshot()->getValue();
if ($statsData != NULL) {
  $keys = array_keys($statsData);
}

// Firebase Storage
$storage = $firebase->createStorage();
$storageClient = $storage->getStorageClient();
$defaultBucket = $storage->getBucket();


$expiresAt = new DateTime('tomorrow', new DateTimeZone('Asia/Manila'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
  <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
  <link rel="stylesheet" type="text/css" href="../../styles/admin/dashboard.css">
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
        <img src="../../assets/logo.png" class="admin">
        <!-- PHP Get from RTDB -->
        <span>
          <h3><?php echo $_SESSION['type'] == 'admin' ? 'Admin Module' : $infoRef->getChild("addCi")->getValue(); ?></h3>
        </span>
      </div>
      <hr class="divider">
      <a href="#" class="active"><i class="fas fa-th-large" aria-hidden="true"></i>Dashboard</a>
      <a href="cases.php"><i class="fas fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <a href="applications.php"><i class="far fa-file" aria-hidden="true"></i>Applications</a>
      <a href="users.php"><i class="fas fa-users" aria-hidden="true"></i>Users</a>
      <?php
      if ($_SESSION['type'] == 'admin') {
        echo '
          <a href="accounts.php"><i class="fas fa-user-cog" aria-hidden="true"></i>Sub-Accounts</a>
          <a href="logs.php"><i class="fa-solid fa-receipt" aria-hidden="true"></i>Audit Logs</a>
          ';
      }
      ?>
      <div class="settings">
        <a href="settings.php"><i class="fas fa-cog" aria-hidden="true"></i>Setttings</a>
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
      <div class="graph">
        <h3>COVID Cases Overview (7-day)</h3>
        <?php
        if ($statsData == NULL) {
          echo '<h2 class="center">No data found...</h2>';
        } else {
          echo '
          <!-- Chart.js -->
          <script src="../../node_modules/chart.js/dist/chart.js"></script>
          ';
          echo '<canvas id="overview"></canvas>
            <script>
              const chart = document.getElementById("overview").getContext("2d");
              const labels = [';
          foreach ($keys as $tmp => $key) {
            echo '\'' . $statsData[$key]['date'] . '\',';
          }
          echo '];
            const data = {
              labels: labels,
              datasets: [{
                  label: \'New Cases\',
                  data: [';
          foreach ($keys as $tmp => $key) {
            echo $statsData[$key]['tCases'] . ',';
          }
          echo '],
              fill: true,
              backgroundColor: \'rgb(254, 81, 5)\'
            },
            {
              label: \'New Recoveries\',
              data: [';
          foreach ($keys as $tmp => $key) {
            echo $statsData[$key]['tRecoveries'] . ',';
          }
          echo '],
              fill: true,
              backgroundColor: \'rgb(28, 125, 228)\'
            },
            {
              label: \'New Deaths\',
              data: [';
          foreach ($keys as $tmp => $key) {
            echo $statsData[$key]['tDeaths'] . ',';
          }
          echo '],
                    fill: true,
                    backgroundColor: \'rgb(168, 12, 16)\'
                  }
                ]
              };
              const myChart = new Chart(chart, {
                type: \'bar\',
                data: data,

              });
            </script>
          ';
        }
        ?>
      </div>
      <div class="stats">
        <h2>Tracking</h2>
        <div class="con-stats">
          <div class="stats-item">
            <div class="icon">
              <i class="far fa-file" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2><?php echo $database->getReference('Applications')->getSnapshot()->numChildren(); ?></h2>
              <h4>Applications Pending</h4>
            </div>
          </div>
          <div class="stats-item">
            <div class="icon">
              <i class="fas fa-users" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2><?php echo $database->getReference('Users')->getSnapshot()->numChildren(); ?></h2>
              <h4>Users</h4>
            </div>
          </div>
          <div class="stats-item">
            <div class="icon">
              <i class="fas fa-chart-line" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2><?php echo $database->getReference('Users/' . $uid . '/sub')->getSnapshot()->numChildren(); ?></h2>
              <h4>Accounts</h4>
            </div>
          </div>
        </div>
      </div>
    </div>



    <div class="Footer">

      © 2021 REaCT. All right reserved

    </div>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/a2501cd80b.js" crossorigin="anonymous"></script>

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <!-- Common Scripts -->
    <script src="../../scripts/common.js"></script>


  </div>

  <div id="common-modal">
    <?php include '../change.php'; ?>
  </div>


</body>

</html>