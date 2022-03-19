<?php
include '../../../includes/dbconfig.php';
session_start();

date_default_timezone_set('Asia/Manila');

$userHisRef = $database->getReference('Users/' . $_SESSION['uid'] . '/history');

if (!$userHisRef->getSnapshot()->hasChildren()) {
    echo '<h1>No data found<h1>';
    exit;
}

$hisRef = $database->getReference('History');

$start = (isset($_GET['start'])) ? $_GET['start'] : array_keys($userHisRef->orderByKey()->limitToFirst(1)->getSnapshot()->getValue())[0];
$end = (isset($_GET['start'])) ? $_GET['start'] : array_keys($userHisRef->orderByKey()->limitToLast(1)->getSnapshot()->getValue())[0];

$userHisRef = $userHisRef->orderByKey()->startAt($start)->endAt($end)->getSnapshot();
$data = $userHisRef->getValue();

$hCount = -1;
$lCount = 999;

$total = 0;

$subs = [];
$dates = [];

foreach ($data as $date => $keys) {
    $dates[$date] = $date;
    $cnt = count($keys);
    $total += $cnt;
    if ($cnt > $hCount) {
        $hDate = $date;
        $hCount = $cnt;
    }
    if ($cnt < $lCount) {
        $lDate = $date;
        $lCount = $cnt;
    }
    foreach ($keys as $tmp => $key) {
        $entry = $hisRef->getChild($date . '/' . $key)->getValue();
        $subs[$entry['sub']][$date][$key] = $entry;
    }
}

function rand_color()
{
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

$ave = round($total / count($data), 2);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../styles/private-common.css">
    <link rel="stylesheet" href="../../../styles/history.css">
    <title>Establishment Report</title>
    <style>
        body {
            padding: 15px;
            background-image: unset;
            background-color: white;
            /* text-align: center; */
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        #content {
            margin-top: 15px;
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

        table:last-child {
            table-layout: fixed;
        }

        #details tr td {
            background-color: unset;
        }

        h4,
        p {
            line-height: 25px;
        }

        canvas {
            display: none;
        }

        .detailsImg {
            width: 90vw;
        }

        @media print {
            .pagebreak {
                page-break-before: always;
            }

            /* page-break-after works, as well */
        }
    </style>
</head>

<body id="toPDF">
    <div class="header">
        <img src="../../../assets/logo.png" alt="" class="center" style="width: 6vw; padding-right: 15px;">
        <img src="../../../assets/text-logo.png" alt="" class="center" style="width: 15vw;">
    </div>
    <hr>
    <div class="center">
        <h1>Generated Report</h1>
        <h3>Date: <?php echo $start . ' - ' . $end; ?></h3>
    </div>
    <h4>Basic Information</h4>
    <table id="details">
        <tr>
            <td>Establishment:</td>
            <td><?php echo $_SESSION['name']; ?></td>
            <td>Branch:</td>
            <td><?php echo $_SESSION['branch']; ?></td>
        </tr>
        <tr>
            <td>Date Generated:</td>
            <td><?php echo date('Y/m/d'); ?></td>
            <td>Time:</td>
            <td><?php echo date('h:i:s a') ?></td>
        </tr>
    </table>
    <hr>
    <h4>Report</h4>
    <p>Daily Average Population: <?php echo $ave; ?></p>
    <p>Highest Traffic: <?php echo $hDate; ?></p>
    <p>Lowest Traffic: <?php echo $lDate; ?></p>
    <br><br><br>
    <h4>Traffic</h4>
    <!-- Graph -->
    <canvas id="traffic"></canvas>
    <img src="" class="detailsImg" alt="" id="imgTraf">
    <div class="pagebreak"> </div>

    <h4>Traffic Per Account(/day)</h4>
    <!-- Graph -->
    <canvas id="perAcct"></canvas>
    <img src="" class="detailsImg" alt="" id="imgAcct">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Chart.js -->
    <script src="../../../node_modules/chart.js/dist/chart.js"></script>

    <script>
        var element = document.getElementById('toPDF');
        var opt = {
            filename: 'REaCT-' + Date.now() + '.pdf',
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            },
            mode: ['avoid-all', 'css', 'legacy']
        };
        // html2pdf().set(opt).from(element).outputPdf().save();

        const traffic = document.getElementById("traffic").getContext("2d");
        const trafLab = [
            <?php
            foreach ($data as $key => $info) {
                echo '\'' . $key . '\',';
            }
            ?>
        ];

        const trafDat = {
            labels: trafLab,
            datasets: [{
                label: 'Number of Traffic',
                data: [
                    <?php
                    foreach ($data as $key => $info) {
                        echo $userHisRef->getChild($key)->numChildren() . ',';
                    }
                    ?>
                ],
                fill: false,
                borderColor: 'rgb(12, 89, 207)'
            }]
        };

        var trafOpt = {
            bezierCurve: false,
            animation: {
                onComplete: traf2Img
            }
        };

        const myChart = new Chart(traffic, {
            type: 'line',
            data: trafDat,
            options: trafOpt
        });

        function traf2Img() {
            var canvas = document.getElementById('traffic'),
                dataUrl = canvas.toDataURL(),
                imageFoo = document.getElementById('imgTraf');
            canvas.style.display = "none";
            imageFoo.src = dataUrl;
        }

        function acct2Img() {
            var canvas = document.getElementById('perAcct'),
                dataUrl = canvas.toDataURL(),
                imageFoo = document.getElementById('imgAcct');
            canvas.style.display = "none";
            imageFoo.src = dataUrl;
        }

        const acct = document.getElementById("perAcct").getContext("2d");
        const acctLab = [
            <?php
            foreach ($data as $key => $info) {
                echo '\'' . $key . '\',';
            }
            ?>
        ];

        const acctDat = {
            labels: acctLab,
            datasets: [
                <?php
                foreach ($subs as $name => $date) {
                    echo '{
                            label: \'' . $name . '\',
                            data: [
                            ';
                    foreach ($dates as $tmp => $strDate) {
                        if (isset($date[$strDate])) {
                            echo count($date[$strDate]) . ',';
                        } else {
                            echo '0, ';
                        }
                    }
                    echo '],
                    fill: true,
                    backgroundColor: \'' . rand_color() . '\'
                    },';
                }
                ?>
            ]
        };

        var acctOpt = {
            bezierCurve: false,
            animation: {
                onComplete: acct2Img
            }
        };

        const chart2 = new Chart(acct, {
            type: 'bar',
            data: acctDat,
            options: acctOpt
        });

        function getRandomColor() {
            var letters = '0123456789ABCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
</body>

</html>