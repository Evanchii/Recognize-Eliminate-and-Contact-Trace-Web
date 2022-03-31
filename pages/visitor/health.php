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

if (!isset($_SESSION['fName'])) {
  $_SESSION["lName"] = $infoRef->getChild("lName")->getValue();
  $_SESSION["fName"] = $infoRef->getChild("fName")->getValue();
  $_SESSION["mName"] = $infoRef->getChild("mName")->getValue();
}


// Firebase Storage
$storage = $firebase->createStorage();
$storageClient = $storage->getStorageClient();
$defaultBucket = $storage->getBucket();


$expiresAt = new DateTime('tomorrow', new DateTimeZone('Asia/Manila'));
// echo $expiresAt->getTimestamp();

$imageReference = $defaultBucket->object($infoRef->getChild("faceID")->getValue());
if ($imageReference->exists()) {
  $image = $imageReference->signedUrl($expiresAt);
}

if (isset($_POST['action'])) {
  $action = $_POST['action'];
  if (str_contains($action, 'covid')) {
    if (str_contains($action, 'positive')) {
      $infoRef->update([
        "status" => true
      ]);

      echo <<<HTML
      <!-- JQuery -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

      <script>
        $.ajax({
          url: "../../functions/covidNotification.php",
          type: "POST",
          data: {
            "uid": '{$uid}'
          }
        }).done(function(data) {
          console.log(data);
        });
      </script>
      HTML;
      createLog('Health Status', ' has set their Health Status as "Positive"', $uid);
    } else {
      $infoRef->update([
        "status" => false
      ]);
      createLog('Health Status', ' has set their Health Status as "Negative"', $uid);
    }
  }
} elseif (isset($_POST['vaccBrand'])) {
  $brand = $_POST['vaccBrand'];
  $status = $_POST['status'];

  $appRef = $database->getReference('Applications');

  $filename = $_FILES['vaccCard']['name'];
  $tmp = (explode(".", $filename));
  $ext = end($tmp);

  $vaccCard = $_FILES['vaccCard']['tmp_name'];

  $defaultBucket->upload(
    file_get_contents($vaccCard),
    [
      'name' => "Vacc/" . $uid . "." . $ext
    ]
  );

  $infoRef->update([
    'vaccine' => 'pending',
    'vaccID' => "Vacc/" . $uid . "." . $ext
  ]);

  $appRef->update([
    time() => [
      'name' => $_SESSION['lName'] . ', ' . $_SESSION['fName'] . ' ' . $_SESSION['mName'],
      'uid' => $uid,
      'usertype' => 'Visitor',
      'type' => 'Vaccination Confirmation',
      'vaccBrand' => $brand,
      'vaccStatus' => $status,
      'vaccID' => "Vacc/" . $uid . "." . $ext
    ]
  ]);

  createLog('Application', ' has submitted their Vaccination Confirmation application', $uid);
}

$vaccine = $infoRef->getChild("vaccine")->getValue();
$status = $infoRef->getChild("status")->getValue();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
  <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
  <link rel="stylesheet" type="text/css" href="../../styles/health.css">
  <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
  <title>Health Status | REaCT</title>
</head>

<body>
  <div class="grid">
    <div class="Navigation">
      <!-- <h2>REaCT</h2> -->
      <img class="text-logo" src="../../assets/text-logo.png" alt="REaCT ">
      <hr class="divider">
      <div class="user-profile">
        <!-- PHP Get from Storage -->
        <img src="<?php echo $image; ?>">
        <!-- PHP Get from RTDB -->
        <span><?php echo $_SESSION['lName'] . ', ' . $_SESSION['fName'] . ' ' . $_SESSION['mName'] ?></span>
      </div>
      <hr class="divider">
      <a href="dashboard.php"><i class="fa fa-th-large" aria-hidden="true"></i>Dashboard</a>
      <a href="cases.php"><i class="fa fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
      <a href="#" class="active"><i class="fa fa-heartbeat" aria-hidden="true"></i>Health Status</a>
      <a href="history.php"><i class="fa fa-lightbulb-o" aria-hidden="true"></i>Location History</a>
      <div class="settings">
        <a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i>Setttings</a>
      </div>
    </div>
    <div class="Header">
      <div class="dashboard-date">
        <h2>Health Status</h2>
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

      <div class="covid-status">

        <h1>Covid-19 Status</h1>

        <div class="covid">
          <button class="c-btn negative <?php echo !$status ? 'selected' : ''; ?>" onclick="changeStatus(this.id);" value="Negative" id="negative">
            <img src="../../assets/ic_negative.png" height="100" width="100" /><br>
            <h2>Negative</h2>
          </button>
          <button class="c-btn positive <?php echo $status ? 'selected' : ''; ?>" onclick="changeStatus(this.id);" value="Positive" id="positive">
            <img src="../../assets/ic_positive.png" height="100" width="100" /><br>
            <h2>Positive</h2>
          </button>

          <!-- <a href="#statusAlert" rel="modal:open"> -->
          <!-- <label class="card-input-parent">
            <button onclick="statusAlert(this);" name="status" status="negative" id="statusNegative" class="card-input-radio covStatus" <?php echo !$status ? 'checked' : ''; ?>>
            <div class="card-input negative">
            </div>
          </label> -->
          <!-- </a> -->
          <!-- </button> -->
          <!-- <label class="card-input-parent">
            <button onclick="statusAlert(this);" name="status" status="positive" id="statusPositive" class="card-input-radio covStatus" <?php echo $status ? 'checked' : ''; ?>>
            <div class="card-input positive">
              <img src="../../assets/ic_positive.png" height="100" width="100" /><br>
              <h2>Positive</h2>
            </div>
          </label> -->
          <!-- </button> -->

        </div>



      </div>

      <div class="vaccine-status">

        <h1>Vaccine Status</h1>

        <div class="vaccine">
          <?php
          if ($vaccine == 'pending') {
            echo <<<HTML
                <button class="v-btn vaccinated selected" style="cursor: not-allowed" value="Pending" id="vaccine">
                  <img src="../../assets/ic_pending.png" height="100" width="100" /><br>
                  <h2>Application Pending</h2>
                </button>

                <button class="v-btn not-vaccinated" style="cursor: not-allowed" value="not-vaccinated" id="not-vaccinated">
                  <img src="../../assets/ic_not-vaccinated.png" height="100" width="100" /><br>
                  <h2>Not Vaccinated</h2>
                </button>
              HTML;
          } else {
            if ($vaccine) {
              echo <<<HTML
                <button class="v-btn vaccinated selected" style="cursor: not-allowed" value="vaccinated" id="vaccine">
                  <img src="../../assets/ic_vaccinated.png" height="100" width="100" /><br>
                  <h2>Vaccinated</h2>
                </button>

                <button class="v-btn not-vaccinated" style="cursor: not-allowed" value="not-vaccinated" id="not-vaccinated">
                  <img src="../../assets/ic_not-vaccinated.png" height="100" width="100" /><br>
                  <h2>Not Vaccinated</h2>
                </button>
              HTML;
            } else {
              echo <<<HTML
                <button class="v-btn vaccinated" style="cursor: pointer" onclick="applyVacc();" value="vaccinated" id="vaccine">
                  <img src="../../assets/ic_vaccinated.png" height="100" width="100" /><br>
                  <h2>Vaccinated</h2>
                </button>

                <button class="v-btn not-vaccinated selected" style="cursor: pointer" value="not-vaccinated" id="not-vaccinated">
                  <img src="../../assets/ic_not-vaccinated.png" height="100" width="100" /><br>
                  <h2>Not Vaccinated</h2>
                </button>
              HTML;
            }
          }
          ?>

        </div>

      </div>

      <div class="health-advisory">
        <img src="../../assets/health-advisory.jpg" class="health-adv">
        <br>
        <button class="btn-primary" style="margin: 20px;" onclick="makeQR('<?php echo $uid; ?>');">Generate QR Code</button>
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
  <!-- QR Code -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script> -->
  <script src="../../node_modules/classyqr/jquery.classyqr.js"></script>
  <!-- Common Scripts -->
  <script src="../../scripts/common.js"></script>

  <script>
    function changeStatus(id) {
      var button = $('#' + id);
      if (!button.hasClass('selected')) {
        $('#statusAlert').modal('show');
        if (button.val() == 'Positive') {
          $('#action').val("covid - positive");
        } else {
          $('#action').val("covid - negative");
        }
        $('#statusYes').click(function() {
          frm = $('#statusFrm');
          frm.submit();
        });
      }
    }


    $('.covStatus').click(function() {
      $('#statusAlert').modal('show');
    });

    function applyVacc() {
      $('#confVacc').modal('show');
    }

    const toDataURL = url => fetch(url)
      .then(response => response.blob())
      .then(blob => new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.onloadend = () => resolve(reader.result)
        reader.onerror = reject
        reader.readAsDataURL(blob)
      }))

    function makeQR(uid) {
      $("#qrcode").ClassyQR({
        create: true,
        size: 500,
        type: 'text',
        text: uid
      });
      $('#qrmodal').modal('show');

      setTimeout(() => {
        let qelem = document.querySelector('#qrcode img');
        let dlink = document.createElement('a');
        toDataURL(qelem.src).then(function(result) {
          let qr = result;
          dlink.setAttribute('href', qr);
          // dlink.setAttribute('target', '_blank');
        dlink.setAttribute('download', 'REaCT-QR_<?php echo $_SESSION['lName']; ?>.png');
        dlink.click();
        });

        // console.log(qelem);

        // var img = qelem.files[0];

        // var reader = new FileReader();

        // reader.onloadend = function() {
        //   dlink.attr("href", reader.result);
        //   dlink.text(reader.result);
        //   dlink.attr("src", reader.result);
        // }
        // reader.readAsDataURL(img);
        // dlink.setAttribute('target', '_blank');
        // dlink.setAttribute('download', 'REaCT-QR_<?php echo $_SESSION['lName']; ?>.png');
        // dlink.click();
      }, 500);
    }

    // const makeQR = (uid) => {
    //   $('#qrcode').empty();
    //   var qrcode = new QRCode(document.getElementById("qrcode"), {
    //     text: uid,
    //     width: 500,
    //     height: 500,
    //     colorDark: "#000000",
    //     colorLight: "#ffffff",
    //     correctLevel: QRCode.CorrectLevel.M
    //   });
    //   qrcode.makeCode(uid);
    //   $('#qrmodal').modal('show');

    //   setTimeout(() => {
    //     let qelem = document.querySelector('#qrcode img');
    //     let dlink = document.createElement('a');
    //     let qr = qelem.getAttribute('src');
    //     dlink.setAttribute('href', qr);
    //     dlink.setAttribute('download', 'REaCT-QR_<?php echo $_SESSION['lName']; ?>.png');
    //     dlink.click();
    //   }, 500);
    // }
  </script>

  <div id="confVacc" class="modal">
    <div class="modal-title">
      <h3>Confirm Vaccination Information</h3>
    </div>
    <form action="health.php" method="post" enctype="multipart/form-data">
      <div class="modal-body">
        <label>Upload Vaccination Card</label>
        <input type="file" name="vaccCard" id="vaccCard" required><br>
        <!-- <img src="" alt=""> -->
        <label for="vaccBrand">Vaccine Brand: </span>
          <select name="vaccBrand" id="vaccBrand" required>
            <option value="null" disabled selected>-Select Vaccine Brand-</option>
            <option value="Pfizer - BioNTech">Pfizer - BioNTech</option>
            <option value="Oxford - AstraZeneca">Oxford - AstraZeneca</option>
            <option value="CoronaVac (Sinovac)">CoronaVac (Sinovac)</option>
            <option value="Gamaleya Sputnik V">Gamaleya Sputnik V</option>
            <option value="Johnson and Johnson - Janssen">Johnson and Johnson - Janssen</option>
            <option value="Bharat BioTech">Bharat BioTech</option>
            <option value="Moderna">Moderna</option>
            <option value="Sinopharm">Sinopharm</option>
          </select><br>
          <span for="status">Status: </label>
        <select name="status" id="status" required>
          <option value="null" disabled selected>-Select Status-</option>
          <option value="Partially Vaccinated (1st Dose)">Partially Vaccinated (1st Dose)</option>
          <option value="Fully Vaccinated">Fully Vaccinated</option>
        </select><br>
      </div>
      <div class="modal-footer">
        <button class="btn-success" type="submit">Submit</button>
      </div>
    </form>
  </div>

  <!-- <div id="editVacc" class="modal">
    <div class="modal-title">
      <h3>Edit Vaccination Information</h3>
    </div>
    <div class="modal-body">
      <span>Upload Vaccination Card</span>
      <input type="file" name="" id="" required><br>
      <span>Vaccine Brand</span>
      <select name="vaccBrand" id="" required>
        <option value="null" disabled selected>-Select Vaccine Brand-</option>
      </select><br>
      <input type="radio" name="dose" id="" value="1st Dose">
      <input type="radio" name="dose" id="" value="Fully Vaccinated">
      </select>
    </div>
  </div> -->

  <div id="qrmodal" class="modal">
    <div class="modal-title">
      <h4>QR Code Instructions</h4>
    </div>
    <div class="modal-body">
      <div id="qrcode"></div>
      <h4>Reminders</h4>
      <ul>
        <li>No need to scan your face when entering an establishment</li>
        <li>The QR Code can be scanned when entering an establishment that supports REaCT</li>
        <li>Once the system validates that the QR Code is valid, it will automatically generate a log or record for you</li>
      </ul>
    </div>
    <div class="modal-footer">
      <button class="btn-primary" onclick="$('#qrmodal .close-modal').click();">Close</button>
    </div>
  </div>

  <div id="statusAlert" class="modal">
    <div class="modal-title">
      <h3>Confirm Action</h3>
    </div>
    <div class="modal-body">
      <p>Do you want to continue with changin your status? This action will send an alert to the LGU and all close contacts in the past 14 days.</p>
    </div>
    <div class="modal-footer" style="display:inline-flex; justify-content: flex-end;">
      <button type="button" class="btn-error" onclick="$('#statusAlert .close-modal').click();">No</button>
      <button type="submit" class="btn-success" id="statusYes">Yes</button>
      <form action="health.php" method="POST" id="statusFrm">
        <input type="hidden" name="action" id="action">
      </form>
    </div>
  </div>

  <div id="common-modal">
    <?php include '../change.php'; ?>
  </div>
</body>

</html>