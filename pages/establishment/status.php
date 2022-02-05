<!-- 
  SVG Colors
  Green: filter: invert(32%) sepia(18%) saturate(1987%) hue-rotate(44deg) brightness(92%) contrast(94%);
  Red: filter: invert(10%) sepia(64%) saturate(4179%) hue-rotate(349deg) brightness(112%) contrast(95%);
 -->
<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
$linkRef = $database->getReference("appData/links/");

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

// $vaccine = $infoRef->getChild("vaccine")->getValue();
$status = $infoRef->getChild("status")->getValue();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
  <link rel="stylesheet" type="text/css" href="../../styles/health.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
  <title>Establishment Status | REaCT</title>
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
      <a href="dashboard.php"><i class="fa fa-th-large" aria-hidden="true"></i>Dashboard</a>
      <a href="cases.php"><i class="fa fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <a href="#" class="active"><i class="fa fa-heartbeat" aria-hidden="true"></i>Status</a>
      <a href="history.php"><i class="fa fa-lightbulb-o" aria-hidden="true"></i>Visitor History</a>
      <a href="accounts.php"><i class="fa fa-users" aria-hidden="true"></i>Accounts</a>
      <div class="settings">
        <a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i>Setttings</a>
      </div>
    </div>
    <div class="Header">
      <div class="dashboard-date">
        <h2>Establishment Status</h2>
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

      <div class="covid-status">

        <h1>Establishment Status</h1>

        <div class="e">
          <!-- <button type="submit" class="c-btn negative active" value="Negative" id="negative"> -->
          <!-- <a href="#statusAlert" rel="modal:open"> -->
            <!-- <label class="card-input-parent">
              <input type="radio" onchange="statusAlert(this);" name="status" status="negative" id="statusNegative" class="card-input-radio covStatus" <?php echo !$status ? 'checked' : ''; ?>>
              <div class="card-input negative">
                <img src="../../assets/ic_negative.png" height="100" width="100" /><br>
                <h2>Negative</h2>
              </div>
            </label> -->
          <!-- </a> -->
          <!-- </button> -->
          <!-- <button type="submit" class="c-btn positive" value="Positive" id="positive"> -->
          <!-- </button> -->
          <p>Dev Note</p>
          <p>Insert button to toggle if establishment is under lockdown</p>
          <p>Will reconsider this feature.</p>
        </div>



      </div>

      <div class="health-advisory">
        <img src="../../assets/health-advisory.jpg" class="health-adv">
      </div>

    </div>
    <div class="Footer">
      © 2021 REaCT. All right reserved
    </div>
  </div>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
  <!-- jQuery Modal -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

  <script>
    $('.covStatus').click(function() {
      $('#statusAlert').modal('show');
    });
    $('#vaccine').click(function() {
      $('#confVacc').modal('show');
    });
    $('#change-vaccine').click(function() {
      $('#editVacc').modal('show');
    });
  </script>

  <div id="confVacc" class="modal">
    <h1>Confirm Vaccination Information</h1>
    <span>Upload Vaccination Card</span>
    <input type="image" src="" alt="" required>
    <input type="file" name="" id="">
    <img src="" alt="">
    <span>Vaccine Brand</span>
    <select name="vaccBrand" id="" required>
      <option value="null" disabled selected>-Select Vaccine Brand-</option>
    </select>
    <label for="1d">1st Dose</label>
    <input type="radio" name="dose" id="1d" value="1st Dose">
    <label for="fd">Fully Vaccinated</label>
    <input type="radio" name="dose" id="fd" value="Fully Vaccinated">
    <span>Last dose</span>
    <input type="date" name="lastShot" id="lastShot">
    <p>Booster Shot</p>
    <input type="checkbox" name="booster" id="booster">
    <span>Booster Vaccine Brand</span>
    <select name="vaccBrand" id="" required>
      <option value="null" disabled selected>-Select Vaccine Brand-</option>
    </select>
  </div>
  <div id="editVacc" class="modal">
    <h1>Edit Vaccination Information</h1>
    <span>Upload Vaccination Card</span>
    <input type="image" src="" alt="" required>
    <img src="" alt="">
    <span>Vaccine Brand</span>
    <select name="vaccBrand" id="" required>
      <option value="null" disabled selected>-Select Vaccine Brand-</option>
    </select>
    <input type="radio" name="dose" id="" value="1st Dose">
    <input type="radio" name="dose" id="" value="Fully Vaccinated">
    <span>Last dose</span>
    <input type="date" name="lastShot" id="lastShot">
    <p>Booster Shot</p>
    <input type="checkbox" name="booster" id="booster">
    <span>Booster Vaccine Brand</span>
    <select name="vaccBrand" id="" required>
      <option value="null" disabled selected>-Select Vaccine Brand-</option>
    </select>
  </div>
  <div id="statusAlert" class="modal">
    <h1>Confirm Action</h1>
    <p>Do you want to continue with changin your status? This action will send an alert to the LGU and all close contacts in the past 14 days.</p>
    <button>Yes</button>
    <button>No</button>
  </div>
</body>

</html>