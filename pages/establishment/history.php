<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");

if (!isset($_SESSION['name'])) {
  $_SESSION["name"] = $infoRef->getChild("name")->getValue();
  $_SESSION["branch"] = $infoRef->getChild("branch")->getValue();
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
  <link rel="stylesheet" type="text/css" href="../../styles/history.css">
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
        <i class="fa-solid fa-city"></i>
        <!-- PHP Get from RTDB -->
        <h2><?php echo $_SESSION['name']; ?></h2>
        <h3><?php echo $_SESSION['branch']; ?></h3>
      </div>
      <hr class="divider">
      <a href="dashboard.php"><i class="fa fa-th-large" aria-hidden="true"></i>Dashboard</a>
      <a href="cases.php"><i class="fa fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <!-- <a href="status.php"><i class="fa fa-heartbeat" aria-hidden="true"></i>Status</a> -->
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
      <div style="display:flex; justify-content: space-between; align-items: flex-end;">
        <button type="button" class="btn-primary" style="margin-bottom: unset;" onclick="openDocument('data/generate-history.php');">Download Report</button>
        <form id="frmSearch" name="userSearch">
          <div class="">
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
                  <option value="visName">Name</option>
                  <option value="backend">Backend</option>
                </select>
              </div>
              <label for="advanced">Advanced Search</label>
              <input type="checkbox" name="advanced" id="advanced" onChange="$('#advancedOptions').toggleClass('hide'); $('#sType').prop('disabled', (i, v) => !v); $('#sTypeDef').prop('selected', (i,v) => v=true);">
            </div>
          </div>
        </form>
      </div>
      <br>
      <div id="data">
        <table style="width: 100%;">
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Name</th>
            <th>Backend Account</th>
          </tr>
          <tr>
            <td colspan="4">
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
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <!-- Common Scripts -->
    <script src="../../scripts/common.js"></script>

  </div>


  <script>
    loadPage(1);

    // Loads data for the page
    function loadPage(page) {
      $.ajax({
        url: '../../functions/history-handler.php',
        type: "POST",
        data: {
          "page": page
        }
      }).done(function(data) {
        $("#data").html(data);
      });
    }

    // Searches data in database
    function searchData() {
      var frm = $('#frmSearch');
      frm.validate({
        rules: {
          search: "required",
          sType: "required"
        },
        messages: {
          search: '',
          sType: ''
        },
        errorLabelContainer: '#error',
        showErrors: function(errorMap, errorList) {
          $("#error").html("Please enter required information.");
          this.defaultShowErrors();
        },
        highlight: function(element) {
          $(element).addClass('has-error');
        },
        unhighlight: function(element) {
          $(element).removeClass('has-error');
        },
        submitHandler: function(frm) {
          event.preventDefault();
          $.ajax({
            url: '../../functions/history-handler.php',
            type: 'POST',
            data: $('#frmSearch').serialize()
          }).done(function(data) {
            $("#data").html(data);
          });
        }
      });
    }
  </script>

  <div id="common-modal">
    <?php include '../change.php'; ?>
  </div>
</body>

</html>