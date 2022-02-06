<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
$userHisRef = $database->getReference('Users/' . $uid . '/history');
$historyRef = $database->getReference('History');

if (!isset($_SESSION['name'])) {
  $_SESSION["name"] = $infoRef->getChild("name")->getValue();
  $_SESSION["branch"] = $infoRef->getChild("branch")->getValue();
}


// Firebase Storage
// $storage = $firebase->createStorage();
// $storageClient = $storage->getStorageClient();
// $defaultBucket = $storage->getBucket();


// $expiresAt = new DateTime('tomorrow', new DateTimeZone('Asia/Manila'));
// // echo $expiresAt->getTimestamp();

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
  <link rel="stylesheet" type="text/css" href="../../styles/history.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
  <title>Visitor History | REaCT</title>
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
      <a href="cases.php"><i class="fa fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <a href="status.php"><i class="fa fa-heartbeat" aria-hidden="true"></i>Status</a>
      <a href="#" class="active"><i class="fa fa-lightbulb-o" aria-hidden="true"></i>Visitor History</a>
      <a href="accounts.php"><i class="fa fa-users" aria-hidden="true"></i>Accounts</a>
      <div class="settings">
        <a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i>Setttings</a>
      </div>
    </div>
    <div class="Header">
      <div class="dashboard-date">
        <h2>Visitor History</h2>
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
      <div>
        <table>
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Name</th>
            <th>Backend Account</th>
          </tr>
          <tr>
            <td>2022-02-06</td>
            <td>14:23:10</td>
            <td>Dela Cruz, Juan Martinez</td>
            <td>Unit-01</td>
          </tr>
        </table>
        <div class="pagination">
                <a href="#" class="disabled-link">&laquo;</a>
                <a href="#" class="disabled-link active">1</a>
                <a href="#" class="disabled-link">&raquo;</a>
            </div>
          <!-- <php
          if ($userHisRef->getSnapshot()->hasChildren()) {
            // var_dump($userHisRef->getValue());
            $history = $userHisRef->getValue();
            foreach ($history as $date => $keySet) {
              foreach ($keySet as $key => $timestamp) {
                echo '<tr>
                      <td>' . $date . '</td>
                      <td>' . $historyRef->getChild($date . '/' . $timestamp . '/time')->getValue() . '</td>
                      <td>' . $historyRef->getChild($date . '/' . $timestamp . '/name')->getValue() . '</td>
                      <td>' . $historyRef->getChild($date . '/' . $timestamp . '/backend')->getValue() . '</td>
                      </tr>';
              }
            }
            echo "</table>";
            echo '
              <div class="pagination">
                <a href="#" class="disabled-link">&laquo;</a>
                <a href="#" class="disabled-link active">1</a>
                <a href="#" class="disabled-link">&raquo;</a>
            </div>
            ';
          } else {
            echo '<tr><td colspan="4"><h2 style="text-align: center;">No data found!</h2></td><tr>';
            echo "</table>";
            echo '
              <div class="pagination">
                <a href="#" class="disabled-link">&laquo;</a>
                <a href="#" class="disabled-link active">1</a>
                <a href="#" class="disabled-link">&raquo;</a>
            </div>
            ';
          }
          ?> -->
      </div>
    </div>



    <div class="Footer">

      © 2021 REaCT. All right reserved

    </div>


  </div>


</body>

</html>