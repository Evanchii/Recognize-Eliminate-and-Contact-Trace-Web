<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
$userHisRef = $database->getReference('Users/' . $uid . '/history');
$historyRef = $database->getReference('History');

// if (!isset($_SESSION['name'])) {
//   $_SESSION["name"] = $infoRef->getChild("name")->getValue();
//   $_SESSION["branch"] = $infoRef->getChild("branch")->getValue();
// }


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
  <title>Applications | REaCT</title>
  <style>
    td > button {
      background: none;
      border: none;
      margin: 2%;
      padding: 2%;
      cursor: pointer;
    }

    tr>td:last-child {
      text-align: center;
    }

    .fa {
      font-size: 1.8em;
    }
  </style>
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
      <a href="cases.php"><i class="fas fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <a href="#" class="active"><i class="far fa-file" aria-hidden="true"></i>Applications</a>
      <a href="users.php"><i class="fas fa-users" aria-hidden="true"></i>Users</a>
      <a href="accounts.php"><i class="fas fa-user-cog" aria-hidden="true"></i>Sub-Accounts</a>
      <div class="settings">
        <a href="settings.php"><i class="fas fa-cog" aria-hidden="true"></i>Setttings</a>
      </div>
    </div>
    <div class="Header">
      <div class="dashboard-date">
        <h2>Applications</h2>
      </div>
      <div class="dashboard-notif">
        <span class="dropdown"><i class="fa fa-user-circle dropbtn" aria-hidden="true"></i>My Account
          <div class="dropdown-content">
            <a href="../logout.php"><i class="fa fa-sign-out"></i>Log out</a>
          </div>
        </span>
      </div>
    </div>
    <div class="Content">
      <div>
        <table>
          <tr>
            <th>Name</th>
            <th>UID</th>
            <th>User Type</th>
            <th>Application</th>
            <th>Action</th>
          </tr>
          <tr>
            <td>Dela Cruz, Juan Martinez</td>
            <td>78d4aece-3e66-4f1e-9800-22d170869fc3</td>
            <td>Visitor</td>
            <td>Vaccination Confirmation</td>
            <td>
              <button>
              <i class="far fa-eye" aria-hidden="true"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>REaCT-Demo Dagupan-1</td>
            <td>4476a9ee-a343-4fa3-bcc1-625b940d1c9d</td>
            <td>Establishment</td>
            <td>Account Confirmation</td>
            <td>
              <button>
              <i class="far fa-eye" aria-hidden="true"></i>
              </button>
            </td>
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

  <script src="https://kit.fontawesome.com/a2501cd80b.js" crossorigin="anonymous"></script>


</body>

</html>