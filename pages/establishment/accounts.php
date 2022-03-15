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

$extension = "_" . str_replace(' ', '-', $_SESSION['name']);


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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
  <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
  <link rel="stylesheet" type="text/css" href="../../styles/history.css">
  <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
  <title>Accounts | REaCT</title>
  <style>
    .button {
      background: #0C59CF;
      padding: 1% 2%;
      margin-bottom: 1%;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      box-shadow: rgba(100, 100, 111, 0.4) 0px 7px 29px 0px;
      transition: ease-in-out 0.3s;
      cursor: pointer;
    }

    .button:hover {
      box-shadow: rgb(100, 100, 111, 0.6) 0px 0px 4px 2px;
    }

    table {
      display: inline-table;
    }

    .right {
      display: block;
      float: right;
    }

    td>button {
      background: none;
      border: none;
      margin: 2%;
      padding: 2%;
      cursor: pointer;
    }

    tr>td:last-child {
      text-align: center;
    }

    td .fa {
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
        <i class="fa-solid fa-city"></i>
        <!-- PHP Get from RTDB -->
        <h2><?php echo $_SESSION['name']; ?></h2>
        <h3><?php echo $_SESSION['branch']; ?></h3>
      </div>
      <hr class="divider">
      <a href="dashboard.php"><i class="fa fa-th-large" aria-hidden="true"></i>Dashboard</a>
      <a href="cases.php"><i class="fa fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <!-- <a href="status.php"><i class="fa fa-heartbeat" aria-hidden="true"></i>Status</a> -->
      <a href="history.php"><i class="fa fa-lightbulb-o" aria-hidden="true"></i>Visitor History</a>
      <a href="#" class="active"><i class="fa fa-users" aria-hidden="true"></i>Accounts</a>
      <div class="settings">
        <a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i>Setttings</a>
      </div>
    </div>
    <div class="Header">
      <div class="dashboard-date">
        <h2>Accounts</h2>
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
      <div style="display: flex; justify-content: space-between; align-items: flex-end;">
        <button type="button" class="button left" id="btn-account" style="height:fit-content;">Add Account</button>
        <form id="frmSearch" name="userSearch">
          <div>
            <div id="error"></div>
            <br>
            <div style="float: right; margin-bottom: 1%; text-align: right;" id="search">
              <input type="search" name="search" id="search" placeholder="Search" required>
              <style>
                .has-error,
                .has-error:focus {
                  border: red 2px solid;
                  outline: none;
                }

                #error {
                  color: red;
                  /* text-align: right; */
                  float: right;
                }

                #search button {
                  border: none;
                  cursor: pointer;
                }
              </style>
              <label><button onclick="searchData();"><i class="fa-solid fa-magnifying-glass"></i></button></label>
              <br>
              <div id="advancedOptions" class="hide">in
                <select name="sType" id="sType" disabled required>
                  <option value="" id="sTypeDef" selected disabled>Select Category</option>
                  <option value="name">Email</option>
                  <option value="uid">UID</option>
                </select>
              </div>
              <label for="advanced">Advanced Search</label>
              <input type="checkbox" name="advanced" id="advanced" onChange="$('#advancedOptions').toggleClass('hide'); $('#sType').prop('disabled', (i, v) => !v);">
            </div>
          </div>
        </form>
      </div>
      <div id="data">
        <table>
          <tr>
            <th>Username</th>
            <th>UID</th>
            <th>Action</th>
          </tr>
          <tr>
            <td colspan="3">
              <h2 style="text-align: center;">Loading Data...</h2>
            </td>
          <tr>
        </table>
        <div class="pagination">
          <a href="#" class="disabled-link">&laquo;</a>
          <a href="#" class="disabled-link active">1</a>
          <a href="#" class="disabled-link">&raquo;</a>
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
    <!-- JQuery Validate -->
    <script src="../../node_modules/jquery-validation/dist/jquery.validate.js"></script>
    <!-- Common Scripts -->
    <script src="../../scripts/common.js"></script>

    <div class="modal" id="create-account">
      <div class="modal-title">
        <h3>Create Sub-account</h3>
      </div>
      <div class="modal-body">
        <form id="frm-account">
          <label for="username">Username:</label>
          <input type="text" class="username" name="username" placeholder="example" id="username" required />
          <input type="text" name="extension" size="1" value="<?php echo $extension; ?>" style="width: <?php echo strlen($extension) + 5 ?>ch" readonly>
          <!-- <input type="text" name="extension" id="extension" value="_<?php echo $_SESSION["name"]; ?>" readonly /> -->
          <br>
          <label for="password">Password: </label>
          <input type="password" name="password" id="password" placeholder="••••••" required>
          <input type="hidden" name="create">
        </form>
      </div>
      <div class="modal-footer">
        <button class="button" id="btn-create">Create</button>
      </div>
    </div>

    <script>
      loadPage(1);

      $("#btn-account").click(function() {
        $('#create-account').modal('show');
      });

      $("#btn-create").click(function() {
        var frm = $("#frm-account");

        $.ajax({
          type: "POST",
          url: "data/account-handler.php",
          data: frm.serialize(),
          success: function(data) {
            alert("Account created.");
            loadPage(1, '../../functions/account-handler.php');
          },
          error: function(data) {
            alert("An error occured.");
          },
        });
      });

      function loadPage(page) {
        $.ajax({
          url: "../../functions/account-handler.php",
          type: "POST",
          data: {
            "page": page
          }
        }).done(function(data) {
          $("#data").html(data);
        });
      }

      function deleteUser(uid, username) {
        if (confirm('Do you wish to delete ' + username + '? This action is irreversible')) {
          console.log('init delete');
          $.ajax({
            type: "POST",
            url: "data/account-handler.php",
            data: {
              'deleteUser': '',
              'uid': uid,
              'username': username
            }
          }).done(function(data) {
            console.log(data);
            loadPage(1);
          });
        }
      }
    </script>


  </div>

  <div id="common-modal">
    <?php include '../change.php'; ?>
  </div>

</body>

</html>