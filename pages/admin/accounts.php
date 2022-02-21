<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
$userHisRef = $database->getReference('Users/' . $uid . '/history');
$historyRef = $database->getReference('History');

$extension = "_" . str_replace(' ', '-', $infoRef->getChild("addCi")->getValue());

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
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
  <script src="https://kit.fontawesome.com/a2501cd80b.js" crossorigin="anonymous"></script>
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
      margin: 1%;
      padding: 2%;
      cursor: pointer;
    }

    tr>td:last-child {
      text-align: center;
      width: 10%;
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
      <a href="applications.php"><i class="far fa-file" aria-hidden="true"></i>Applications</a>
      <a href="users.php"><i class="fas fa-users" aria-hidden="true"></i>Users</a>
      <a href="#" class="active"><i class="fas fa-user-cog" aria-hidden="true"></i>Sub-Accounts</a>
      <div class="settings">
        <a href="settings.php"><i class="fas fa-cog" aria-hidden="true"></i>Setttings</a>
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
                    Lorem, ipsum dolor.
                  </div>
                  <div class="sub_title">
                    Lorem ipsum dolor sit amet consectetur.
                  </div>
                </div>
                <div class="notify_status">
                  <p>Success</p>
                </div>
              </li>
              <li class="baskin_robbins failed">
                <div class="notify_icon">
                  <span class="icon"></span>
                </div>
                <div class="notify_data">
                  <div class="title">
                    Lorem, ipsum dolor.
                  </div>
                  <div class="sub_title">
                    Lorem ipsum dolor sit amet consectetur.
                  </div>
                </div>
                <div class="notify_status">
                  <p>Failed</p>
                </div>
              </li>
              <li class="mcd success">
                <div class="notify_icon">
                  <span class="icon"></span>
                </div>
                <div class="notify_data">
                  <div class="title">
                    Lorem, ipsum dolor.
                  </div>
                  <div class="sub_title">
                    Lorem ipsum dolor sit amet consectetur.
                  </div>
                </div>
                <div class="notify_status">
                  <p>Success</p>
                </div>
              </li>
              <li class="pizzahut failed">
                <div class="notify_icon">
                  <span class="icon"></span>
                </div>
                <div class="notify_data">
                  <div class="title">
                    Lorem, ipsum dolor.
                  </div>
                  <div class="sub_title">
                    Lorem ipsum dolor sit amet consectetur.
                  </div>
                </div>
                <div class="notify_status">
                  <p>Failed</p>
                </div>
              </li>
              <li class="kfc success">
                <div class="notify_icon">
                  <span class="icon"></span>
                </div>
                <div class="notify_data">
                  <div class="title">
                    Lorem, ipsum dolor.
                  </div>
                  <div class="sub_title">
                    Lorem ipsum dolor sit amet consectetur.
                  </div>
                </div>
                <div class="notify_status">
                  <p>Success</p>
                </div>
              </li>
              <li class="show_all">
                <p class="link">Show All Activities</p>
              </li>
            </ul>
          </div>
        </div>

        <div class="dashboard-notif">
          <span class="dropdown"><i class="fa fa-user-circle dropbtn" aria-hidden="true"></i>My Account
            <div class="dropdown-content">
              <a href="../logout.php"><i class="fas fa-sign-out" aria-hidden="true"></i>Log out</a>
            </div>
          </span>
        </div>
      </div>
    </div>
    <div class="Content">
      <div>
        <button type="button" class="button right" id="btn-account">Add Account</button>
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

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
  <!-- jQuery Modal -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

  <div class="modal" id="create-account">
    <h1>Create Sub-account</h1>
    <hr><br>
    <form id="frm-account">
      <label for="email">Email:</label>
      <input type="email" class="email" name="email" placeholder="example@email.com" id="email" required />
      <br>
      <label for="password">Password: </label>
      <input type="password" name="password" id="password" placeholder="••••••" required>
      <input type="hidden" name="create">
    </form>
    <button class="button right" id="btn-create">Create</button>
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
            loadPage(1);
          },
          error: function(data) {
            alert("An error occured.");
          },
        });
      });

      function loadPage(page) {
        $.ajax({
          url: "data/account-controller.php",
          type: "POST",
          data: {
            "page": page
          }
        }).done(function(data) {
          $("#data").html(data);
        });
      }

      function deleteUser(uid, username) {
        if (confirm('Do you wish to delete ' + username + '?')) {
          $.ajax({
            type: "POST",
            url: "data/account-handler.php",
            data: {
              'delete' : '',
              'uid': uid,
              'username': username
            }
          }).done(function() {
            loadPage(1);
          });
        }
      }
  </script>


</body>

</html>