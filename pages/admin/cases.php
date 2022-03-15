<?php
include '../../functions/checkSession.php';

$uid = $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
$appDataRef = $database->getReference("appData/");
$stats = $database->getReference('Stats/' . $infoRef->getChild('addCi')->getValue());
$statsData = $stats->orderByKey()->limitToLast(1)->getSnapshot()->getValue();
if($statsData != NULL) {
    $key = array_keys($statsData)[0];
} else {
    $key = 0;
    $statsData = [
        0 => [
            "tActive" => 0,
            "nCases" => 0,
            "tDeaths" => 0,
            "tRecoveries" => 0,
            "tTested" => 0,
            "tCases" => 0,
            "daily" => null
        ],
    ];
}

// var_dump($statsData);

// Firebase Storage
$storage = $firebase->createStorage();
$storageClient = $storage->getStorageClient();
$defaultBucket = $storage->getBucket();

if (isset($_POST['submit'])) {
    $time = $_POST['time'];
    $date = $_POST['date'];
    $tCases = $_POST['tCases'];
    $tTested = $_POST['tTested'];
    $tRecoveries = $_POST['tRecoveries'];
    $tDeaths = $_POST['tDeaths'];
    $nCases = $_POST['nCases'];
    $tActive = $_POST['tActive'];
    $daily = '#';
    $brgy = '#';
    $situationer = '#';

    if ($_FILES['daily']['name'] != '') {
        $img = $_FILES['daily'];

        // File Naming and Pathing
        $info = pathinfo($img['name']);
        $filetype = $info['extension']; // get the extension of the file
        $filepath = '../../assets/inforgraphics/' . $infoRef->getChild('addCi')->getValue() . "/";
        if (!file_exists($filepath)) {
            mkdir($filepath, 0777, true);
        }
        $filename = $date . "-" . str_replace(':', '-', $time) . '-daily.' . $filetype;
        $temp_name = $img['tmp_name'];
        $file = $filepath . $filename;

        if (file_exists($file)) {
            unlink($file);
            // file_put_contents($file, $testecho);
            $daily = $infoRef->getChild('addCi')->getValue() . "/" . $filename;
        }

        if (move_uploaded_file($temp_name, $file)) {
            // echo 'OK';
        }
    }
    if ($_FILES['brgy']['name'] != '') {
        $img = $_FILES['brgy'];

        // File Naming and Pathing
        $info = pathinfo($img['name']);
        $filetype = $info['extension']; // get the extension of the file
        $filepath = '../../assets/inforgraphics/' . $infoRef->getChild('addCi')->getValue() . "/";
        if (!file_exists($filepath)) {
            mkdir($filepath, 0777, true);
        }
        $filename = $date . "-" . str_replace(':', '-', $time) . '-brgy.' . $filetype;
        $temp_name = $img['tmp_name'];
        $file = $filepath . $filename;

        if (file_exists($file)) {
            unlink($file);
            // file_put_contents($file, $testecho);
            $brgy = $infoRef->getChild('addCi')->getValue() . "/" . $filename;
        }

        if (move_uploaded_file($temp_name, $file)) {
            // echo 'OK';
        }
    }
    if ($_FILES['situationer']['name'] != '') {
        $img = $_FILES['situationer'];

        // File Naming and Pathing
        $info = pathinfo($img['name']);
        $filetype = $info['extension']; // get the extension of the file
        $filepath = '../../assets/inforgraphics/' . $infoRef->getChild('addCi')->getValue() . "/";
        if (!file_exists($filepath)) {
            mkdir($filepath, 0777, true);
        }
        $filename = $date . "-" . str_replace(':', '-', $time) . '-situationer.' . $filetype;
        $temp_name = $img['tmp_name'];
        $file = $filepath . $filename;

        if (file_exists($file)) {
            unlink($file);
            // file_put_contents($file, $testecho);
        }

        if (move_uploaded_file($temp_name, $file)) {
            // echo 'OK';
            $situationer = $infoRef->getChild('addCi')->getValue() . "/" . $filename;
        }
    }

    $stats->getChild($date . "-" . str_replace(':', '-', $time))->update([
        'time' => $time,
        'date' => $date,
        'tCases' => $tCases,
        'tTested' => $tTested,
        'tRecoveries' => $tRecoveries,
        'tDeaths' => $tDeaths,
        'nCases' => $nCases,
        'tActive' => $tActive,
        'daily' => $daily,
        'brgy' => $brgy,
        'situationer' => $situationer,
    ]);
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
    <link rel="stylesheet" type="text/css" href="../../styles/cases.css">
    <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
    <title>COVID Cases | REaCT</title>
    <style>
        #update {
            width: 90%;
            float: right;
            padding: 2% 5%;
            border-radius: 10px;
            border: none;
            margin: 1% 5%;
            cursor: pointer;
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
            <a href="#" class="active"><i class="fas fa-line-chart" aria-hidden="true"></i>Covid Cases</a>
            <a href="applications.php"><i class="far fa-file" aria-hidden="true"></i>Applications</a>
            <a href="users.php"><i class="fas fa-users" aria-hidden="true"></i>Users</a>
            <?php
            if ($_SESSION['type'] == 'admin') {
                echo '
                <a href="accounts.php"><i class="fas fa-user-cog" aria-hidden="true"></i>Sub-Accounts</a>
                <a href="logs.php"><i class="fa-solid fa-receipt" aria-hidden="true"></i>Audit Logs</a>
                ';
            }
            ?>
            <div class="settings">
                <a href="settings.php"><i class="fas fa-cog" aria-hidden="true"></i>Setttings</a>
            </div>
        </div>
        <div class="Header">
            <div class="dashboard-date">
                <h2>COVID Cases</h2>
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
        <form action="cases.php" method="post" enctype="multipart/form-data">
            <div class="Content">
                <div class="content-title">
                    <h4>Dagupan City, Pangasinan</h4>
                    <span id=datetime>Covid-19 Status<br>As of <?php echo $appDataRef->getChild('covStatus/time')->getValue(); ?> | <?php echo $appDataRef->getChild('covStatus/date')->getValue(); ?></span>
                </div>
                <div class="stats">
                    <div class="box">
                        <div class="cases mini-card">
                            <h3>Total Cases</h3><br>
                            <div id="tCases"><?php echo $statsData[$key]['tCases']; ?></div>
                        </div>
                        <div class="tested mini-card">
                            <h3>Total Tested</h3><br>
                            <div id="tTested"><?php echo $statsData[$key]['tTested']; ?></div>
                        </div>
                        <div class="recoveries mini-card">
                            <h3>Total Recoveries</h3><br>
                            <div id="tRecoveries"><?php echo $statsData[$key]['tRecoveries']; ?></div>
                        </div>
                        <div class="deaths mini-card">
                            <h3>Total Deaths</h3><br>
                            <div id="tDeaths"><?php echo $statsData[$key]['tDeaths']; ?></div>
                        </div>
                        <div class="newCases mini-card">
                            <h3>New Cases</h3><br>
                            <div id="nCases"><?php echo $statsData[$key]['nCases']; ?></div>
                        </div>
                        <div class="activeCases mini-card">
                            <h3>Total Active</h3><br>
                            <div id="tActive"><?php echo $statsData[$key]['tActive']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="daily-cases">
                    <h2>Daily Cases</h2>
                    <p>
                        <a href="https://www.facebook.com/DagupanPIO">
                            <img src="../../assets/inforgraphics/<?php echo $statsData[$key]['daily'] ?>" class="right-data" alt="No DATA found" onerror="//this.src='img/undefined.jpg'">
                        </a>
                    </p>
                    <button id="update">Update</button>
                </div>
                <button type="submit" id="save" class="float hide" name="submit">
                    <i class="fa-solid fa-floppy-disk"></i>
                    </a>
            </div>
        </form>
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

    <!-- Common Scripts -->
    <script src="../../scripts/common.js"></script>

    <script>
        $('#update').click(function() {
            $('#datetime').html('Covid-19 Status<br>As of <input type="time" name="time" id="time" required> | <input type="date" name="date" id="date" required>');
            var stats = ['tCases', 'tTested', 'tRecoveries', 'tDeaths', 'nCases', 'tActive'];
            stats.forEach(function(val, index) {
                $('#' + val).html('<input type="number" name="' + val + '" id="inp' + val + '" required>');
            });

            var end = `<script>
            input = ['daily', 'brgy', 'situationer'];
                input.forEach(function(val, index) {
                    $('#'+val).change(function() {
                        $('#img'+val).removeClass('hide');
                        $('#img'+val).attr("src",$('#'+val).val());
                    });
                });
            <` + '/script>';


            $('.daily-cases').html(
                `<h2>Infographics</h2>
                <div class="right-data">
                <label for="daily">Daily Cases</label>
                <input type="file" onchange="changeImg('daily', this)" name="daily" accept="image/*" id="daily"><br>
                <img src="#" class="hide" id="imgdaily" alt=""><br>
                <label for="brgy">Per Barangay</label>
                <input type="file" onchange="changeImg('brgy', this)" name="brgy" accept="image/*" id="brgy"><br>
                <img src="#" class="hide" id="imgbrgy" alt=""><br>
                <label for="situationer">Situationer</label>
                <input type="file" onchange="changeImg('situationer', this)" name="situationer" accept="image/*" id="situationer"><br>
                <img src="#" class="hide" id="imgsituationer" alt=""><br>
                </div>`
            );

            $('#save').removeClass('hide');
        });

        // $('#save').click(function() {
        //     var stats = ['tCases', 'tTested', 'tRecoveries', 'tDeaths', 'nCases', 'tActive'];
        //     stats.forEach(function(val, index) {
        //         if($('#inp'+val).val() == '') {

        //         }
        //     });
        // });

        function changeImg(type, input) {
            const preview = document.getElementById("img" + type);
            const [file] = input.files;
            if (file) {
                preview.style.display = "block";
                preview.src = URL.createObjectURL(file)
            }
        }
    </script>

    <div id="common-modal">
        <?php include '../change.php'; ?>
    </div>

</body>

</html>