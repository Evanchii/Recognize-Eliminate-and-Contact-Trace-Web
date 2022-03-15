<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");

if (!isset($_SESSION['name'])) {
  $_SESSION["name"] = $infoRef->getChild("name")->getValue();
  $_SESSION["branch"] = $infoRef->getChild("branch")->getValue();
}

$subRef = $database->getReference('Users/' . $uid . '/sub');
$userHisRef = $database->getReference('Users/' . $uid . '/history');
$historyRef = $database->getReference('History');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
  <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
  <link rel="stylesheet" type="text/css" href="../../styles/establishment/dashboard.css">
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
        <i class="fa-solid fa-city"></i>
        <!-- PHP Get from RTDB -->
        <h2><?php echo $_SESSION['name']; ?></h2>
        <h3><?php echo $_SESSION['branch']; ?></h3>
      </div>
      <hr class="divider">
      <a href="#" class="active"><i class="fa fa-th-large" aria-hidden="true"></i>Dashboard</a>
      <a href="cases.php"><i class="fa fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <!-- <a href="status.php"><i class="fa fa-heartbeat" aria-hidden="true"></i>Status</a> -->
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
        <h2>Overview</h2>
        <canvas id="overview"></canvas>
      </div>
      <div class="stats">
        <h2>Tracking</h2>
        <div class="con-stats">
          <div class="stats-item">
            <div class="icon">
              <i class="fa fa-briefcase" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2><?php echo $subRef->getSnapshot()->hasChildren() ? $subRef->getSnapshot()->numChildren() : 0; ?></h2>
              <h4>Sub-accounts</h4>
            </div>
          </div>
          <div class="stats-item">
            <div class="icon">
              <i class="fa fa-users" aria-hidden="true"></i>
            </div>
            <div class="details">
              <h2><?php echo $userHisRef->getChild(date("Y-m-d"))->getSnapshot()->hasChildren() ? $userHisRef->getChild(date("Y-m-d"))->getSnapshot()->numChildren() : 0 ?></h2>
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
          if ($userHisRef->getSnapshot()->hasChildren()) {
            // var_dump($userHisRef->getValue());
            $history = $userHisRef->getValue();
            foreach ($history as $date => $keySet) {
              echo '<div class="history date-history">
                              <h2>' . $date . '</h2>
                            </div>';
              foreach ($keySet as $key => $timestamp) {
                echo '<div class="history">
                              <span>' . $historyRef->getChild($date . '/' . $timestamp . '/name')->getValue() . '</span>
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

  <!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/a2501cd80b.js" crossorigin="anonymous"></script>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
  <!-- jQuery Modal -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
  <!-- Chart.js -->
  <script src="../../node_modules/chart.js/dist/chart.js"></script>
  <!-- JQuery Validate -->
  <script src="../../node_modules/jquery-validation/dist/jquery.validate.js"></script>
  <!-- Common Scripts -->
  <script src="../../scripts/common.js"></script>

  <script>
    $(".notifications .icon_wrap").click(function() {
      $(this).parent().toggleClass("actived");
      $(".notification_dd").toggleClass("show");
    });

    const currentDate = new Date();

    $.ajax({
      url: "../../functions/notificationHandler.php",
      type: "POST",
      data: {
        "ts": currentDate.getTime() / 1000
      }
    }).done(function(data) {
      $(".notification_ul").html(data);
    });

    const chart = document.getElementById("overview").getContext("2d");
    const labels = [
      <?php
      foreach ($userHisRef->getValue() as $key => $info) {
        echo '\'' . $key . '\',';
      }
      ?>
    ];
    const data = {
      labels: labels,
      datasets: [{
        label: 'Number of Visitors',
        data: [
          <?php
          foreach ($userHisRef->getValue() as $key => $info) {
            echo $userHisRef->getSnapshot()->getChild($key)->numChildren() . ',';
          }
          ?>
        ],
        fill: false,
        borderColor: 'rgb(12, 89, 207)'
      }]
    };
    const myChart = new Chart(chart, {
      type: 'line',
      data: data,

    });
  </script>

  <div id="common-modal">
    <?php include '../change.php'; ?>
  </div>

</body>

</html>