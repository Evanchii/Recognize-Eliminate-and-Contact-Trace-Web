<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
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
  <title>Audit Logs | REaCT</title>
  <style>
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

    .fa {
      font-size: 1.8em;
    }

    th,
    td {
      padding: 15px;
    }

    th:first-child,
    th:nth-child(2),
    th:last-child {
      width: 15ch;
    }

    th:nth-child(3) {
      width: 20ch;
    }

    table {
      table-layout: fixed;
    }

    .Content {
      height: 85vh;
      overflow: auto;
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
      <a href="applications.php"><i class="far fa-file" aria-hidden="true"></i>Applications</a>
      <a href="users.php"><i class="fas fa-users" aria-hidden="true"></i>Users</a>
      <?php
      if ($_SESSION['type'] == 'admin') {
        echo '
          <a href="accounts.php"><i class="fas fa-user-cog" aria-hidden="true"></i>Sub-Accounts</a>
          <a href="#" class="active"><i class="fa-solid fa-receipt" aria-hidden="true"></i>Audit Logs</a>
          ';
      }
      ?>
      <div class="settings">
        <a href="settings.php"><i class="fas fa-cog" aria-hidden="true"></i>Setttings</a>
      </div>
    </div>
    <div class="Header">
      <div class="dashboard-date">
        <h2>Audit Logs</h2>
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
    <div class="Content" style="display: flex; flex-direction: column;">
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
                <option value="category">Catergory</option>
                <option value="description">Description</option>
                <option value="ip">IP Address</option>
              </select>
            </div>
            <label for="advanced">Advanced Search</label>
            <input type="checkbox" name="advanced" id="advanced" onChange="$('#advancedOptions').toggleClass('hide'); $('#sType').prop('disabled', (i, v) => !v); $('#sTypeDef').prop('selected', (i,v) => v=true);">
          </div>
        </div>
      </form>
      <div id="data">
        <table>
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Category</th>
            <th>Description</th>
            <th>IP Address</th>
          </tr>
          <tr>
            <td colspan="5">
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

  <script>
    loadPage(1);

    // Loads data for the page
function loadPage(page, url) {
    $.ajax({
        url: '../../functions/logs-handler.php',
        type: "POST",
        data: {
            "page": page
        }
    }).done(function (data) {
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
        showErrors: function (errorMap, errorList) {
            $("#error").html("Please enter required information.");
            this.defaultShowErrors();
        },
        highlight: function (element) {
            $(element).addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).removeClass('has-error');
        },
        submitHandler: function (frm) {
            event.preventDefault();
            $.ajax({
                url: '../../functions/logs-handler.php',
                type: 'POST',
                data: $('#frmSearch').serialize()
            }).done(function (data) {
                $("#data").html(data);
            });
        }
    });
}
  </script>

  <div id="appInfo" class="modal" style="max-width: 45vw;">
    <div class="modal-title">
      <h3>Application Information</h3>
    </div>
    <div id="modal-content">
      <div class="modal-body">
        <h5>Loading...</h5>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>

  <div id="common-modal">
    <?php include '../change.php'; ?>
  </div>

</body>

</html>