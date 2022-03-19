<?php
include '../../functions/checkSession.php';
$auth = $firebase->createAuth();

$uid = $_SESSION["uid"];

$infoRef = $database->getReference("Users/" . $uid . "/info");

if (!isset($_SESSION['name'])) {
  $_SESSION["name"] = $infoRef->getChild("name")->getValue();
  $_SESSION["branch"] = $infoRef->getChild("branch")->getValue();
}

if (isset($_POST['submit'])) {
  $addNo = $_POST['addNo'];
  $addBa = $_POST['addBa'];
  $addCi = $_POST['addCi'];
  $addPro = $_POST['addPro'];
  $addCo = $_POST['addCo'];
  $addZip = $_POST['addZip'];
  $cno = $_POST['cNo'];
  $fName = $_POST['fName'];
  $mName = $_POST['mName'];
  $lName = $_POST['lName'];
  $pos = $_POST['pos'];

  $updates = [
    "addNo" => $addNo,
    "addBa" => $addBa,
    "addCi" => $addCi,
    "addPro" => $addPro,
    "addCo" => $addCo,
    "addZip" => $addZip,
    "cNo" => $cno,
    "fName" => $fName,
    "mName" => $mName,
    "lName" => $lName,
    "pos" => $pos,
  ];

  $infoRef // this is the root reference
    ->update($updates);
} else {
  $addNo = $infoRef->getChild("addNo")->getValue();
  $addBa = $infoRef->getChild("addBa")->getValue();
  $addCi = $infoRef->getChild("addCi")->getValue();
  $addPro = $infoRef->getChild("addPro")->getValue();
  $addCo = $infoRef->getChild("addCo")->getValue();
  $addZip = $infoRef->getChild("addZip")->getValue();
  $cno = $infoRef->getChild("cNo")->getValue();
  $fName = $infoRef->getChild("fName")->getValue();
  $mName = $infoRef->getChild("mName")->getValue();
  $lName = $infoRef->getChild("lName")->getValue();
  $pos = $infoRef->getChild("pos")->getValue();
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
  <link rel="stylesheet" type="text/css" href="../../styles/profile.css">
  <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
  <title>Profile | REaCT</title>
  <style>
    table {
      table-layout: fixed;
    }

    th {
      margin-left: 20px;
    }

    td:nth-child(odd) {
      padding: 0px 30px;
    }

    .modal {
      max-width: 500px !important;
    }

    .modal input {
      padding: unset;
      margin: unset;
      width: 60%;
      margin-right: unset;
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
        <i class="fa-solid fa-city"></i>
        <!-- PHP Get from RTDB -->
        <h2><?php echo $_SESSION['name']; ?></h2>
        <h3><?php echo $_SESSION['branch']; ?></h3>
      </div>
      <hr class="divider">
      <a href="dashboard.php"><i class="fa fa-th-large" aria-hidden="true"></i>Dashboard</a>
      <a href="cases.php"><i class="fa fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <a href="history.php"><i class="fa fa-lightbulb-o" aria-hidden="true"></i>Visitor History</a>
      <a href="accounts.php"><i class="fa fa-users" aria-hidden="true"></i>Accounts</a>
      <div class="settings">
        <a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i>Setttings</a>
      </div>
    </div>
    <div class="Header">
      <div class="dashboard-date">
        <h2>Profile</h2>
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
              <a href="#"><i class="fa fa-user-circle" aria-hidden="true"></i>Profile</a>
              <a onclick="$('#change-pw').modal('show');"><i class="fa-solid fa-key" aria-hidden="true"></i>Change Password</a>
              <a href="../logout.php"><i class="fas fa-sign-out" aria-hidden="true"></i>Log out</a>
            </div>
          </span>
        </div>
      </div>
    </div>
    <div class="Content" style="display: block;">

      <!-- <div class="profile">
        <h3>Update Facial Information</h3><br>
        <a href="#modalFace" onclick="initCamera();" rel="modal:open" id="retry-button">
          <img src="../../assets/ic_upload.png" id="imgFace" alt="Avatar" class="avatar">
        </a>
        <canvas id="canvas" style="display:none;"></canvas><br>

        <h1>
          <span>
            <?php echo $_SESSION['lName'] . ', ' . $_SESSION['fName'] . ' ' . $_SESSION['mName'] ?>
          </span>
        </h1>
        <hr>
        </hr>
        <span><?php echo $auth->getUser($uid)->__get('email'); ?></span>
      </div> -->

      <div class="profile-data">

        <h2>Profile Data</h2>

        <form action="profile.php" method="POST">

          <input type="hidden" name="updateFace" id="updateFace">
          <table>
            <tr>
              <td>Establishment Name:</td>
              <td><input type="text" value="<?php echo $_SESSION['name']; ?>" required disabled></td>
              <td>Branch:</td>
              <td><input type="text" value="<?php echo $_SESSION['branch']; ?>" required disabled></td>
            </tr>
            <tr>
              <th colspan="4">Current Address</th>
            </tr>
            <tr>
              <td>Establishment Number/Street</td>
              <td><input type="text" name="addNo" value="<?php echo $addNo; ?>" required></td>
              <td>Barangay</td>
              <td><input type="text" name="addBa" value="<?php echo $addBa; ?>" required></td>
            </tr>
            <tr>
              <td>City</td>
              <td><input type="text" name="addCi" value="<?php echo $addCi; ?>" required></td>
              <td>Province</td>
              <td><input type="text" name="addPro" value="<?php echo $addPro; ?>" required></td>
            </tr>
            <tr>
              <td>Country</td>
              <td><input type="text" name="addCo" value="<?php echo $addCo; ?>" required></td>
              <td>Zipcode</td>
              <td><input type="number" name="addZip" value="<?php echo $addZip; ?>" required></td>
            </tr>
            <th colspan="4">Contact Details</th>
            <tr>
              <td>Contact/Telephone Number</td>
              <td><input type="tel" name="cNo" value="<?php echo $cno; ?>" required></td>
              <td>Email Address</td>
              <td><input type="email" value="<?php echo $auth->getUser($uid)->__get('email'); ?>" required disabled></td>
            </tr>
            <tr>
              <th colspan="4">Account Holder's Information</th>
            </tr>
            <tr>
              <td>First Name</td>
              <td><input type="text" name="fName" value="<?php echo $fName; ?>" required></td>
              <td>Middle Name</td>
              <td><input type="text" name="mName" value="<?php echo $mName; ?>" required></td>
            </tr>
            <tr>
              <td>Last Name</td>
              <td><input type="text" name="lName" value="<?php echo $lName; ?>" required></td>
              <td>Position</td>
              <td><input type="text" name="pos" value="<?php echo $pos; ?>" required></td>
            </tr>

          </table>

          <input class="update-button" type="submit" value="Update" name="submit"><a></a>

        </form>

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
  <!-- JQuery Validate -->
  <script src="../../node_modules/jquery-validation/dist/jquery.validate.js"></script>
  <!-- Common Scripts -->
  <script src="../../scripts/common.js"></script>

  <div id="modalFace" class="modal">
    <div class="modal-title">
      <h3>Update Face</h3>
    </div>
    <div class="faceVideo" id="faceVideo">
      <div class="modal-body">
        <video autoplay="true" poster="../../assets/loading.gif" id="videoElement" class="faceid"></video><br>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="camButt" id="screenshot-button"> <img src="../../assets/ic_camera.svg" alt="">Take photo</button>
    </div>
  </div>

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

    function initCamera() {
      var video = document.querySelector("#videoElement");
      const screenshotButton = document.querySelector("#screenshot-button");
      const retryButton = document.querySelector("#retry-button");
      const img = document.querySelector("#imgFace");
      const canvas = document.querySelector("#canvas");
      const faceInput = document.querySelector("#updateFace");

      if (navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({
            video: true
          })
          .then(function(stream) {
            mediaStream = stream.getTracks();
            video.srcObject = stream;

            screenshotButton.onclick = video.onclick = function() {
              canvas.width = video.videoWidth;
              canvas.height = video.videoHeight;
              canvas.getContext("2d").drawImage(video, 0, 0);
              // Other browsers will fall back to image/png
              img.src = canvas.toDataURL("image/webp");
              faceInput.value = canvas.toDataURL("image/webp");

              mediaStream.forEach(track => track.stop());
              $("#modalFace .close-modal").click()
            };

            retryButton.onclick = function() {
              faceInput.value = "";

              initCamera();
            }

          })
          .catch(function(err0r) {
            console.log("Something went wrong!");
          });
      }
    }
  </script>

  <div id="common-modal">
    <?php include '../change.php'; ?>
  </div>

</body>

</html>