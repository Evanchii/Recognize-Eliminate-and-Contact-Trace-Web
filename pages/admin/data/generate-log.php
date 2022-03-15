<?php
include '../../../includes/dbconfig.php';
session_start();

$logRef = $database->getReference('Logs/');

$start = (isset($_GET['start'])) ? $_GET['start'] : array_keys($logRef->orderByKey()->limitToFirst(1)->getSnapshot()->getValue())[0];
$end = (isset($_GET['start'])) ? $_GET['start'] : array_keys($logRef->orderByKey()->limitToLast(1)->getSnapshot()->getValue())[0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../styles/private-common.css">
    <link rel="stylesheet" href="../../../styles/history.css">
    <title>Audit Log Report</title>
    <style>
        body {
            padding: 15px;
            background-image: unset;
            background-color: white;
            text-align: center;
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

        table {
            table-layout: fixed;
        }
    </style>
</head>

<body id="toPDF">
    <div class="header center">
        <img src="../../../assets/logo.png" alt="" class="center" style="width: 6vw; padding-right: 15px;">
        <img src="../../../assets/text-logo.png" alt="" class="center" style="width: 15vw;">
    </div>
    <hr>
    <h1>Audit Report</h1>
    <h3>Date: <?php echo date('Y/m/d', $start) . ' - ' . date('Y/m/d', $end); ?></h3>
    <div id="content">
        <?php
        date_default_timezone_set('Asia/Manila');

        $page = isset($_POST['page']) ? $_POST['page'] : 1;

        $logRef = $database->getReference('Logs');

        $header = [
            'date' => 'Date',
            'time' => 'Time',
            'category' => 'Category',
            'description' => 'Description',
            'ip' => 'IP Address',
        ];

        // Clean data
        $data = [];
        if ($logRef->getSnapshot()->hasChildren()) {
            $rawData = $logRef->getValue();
            foreach ($rawData as $ts => $info) {
                $data[$ts]['date'] = date('Y/m/d', $ts);
                $data[$ts]['time'] = date('h:i:s a', $ts);
                $data[$ts]['category'] = $info['category'];
                $data[$ts]['description'] = $info['description'];
                $data[$ts]['ip'] = $info['ip'];
            }
        }

        $numChild = count($data);
        $totalPage = $numChild > 0 ? ceil($numChild / 10) : 1;
        $page = $page <= $totalPage ? $page : 1;

        $pageData = $data;

        // Print table
        echo '
        <table style="width: 100%;">
        <tr>
        ';
        foreach ($header as $key => $head) {
            echo '<th>' . $head . '</th>';
        }
        echo '</tr>';
        // var_dump($pageData);
        if ($numChild > 0) {
            foreach ($pageData as $ts => $entryData) {
                echo '<tr>';
                foreach ($header as $key => $head) {
                    echo '<td> ' . $entryData[$key] . '</td>';
                }
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="' . count($header) . '"><h2 class="center">No data found</h2></td></tr>';
        }
        echo '</table> <div class="pagination">';
        ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var element = document.getElementById('toPDF');
        var opt = {
            filename:     'REaCT-'+Date.now()+'.pdf',
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' },
            mode:         ['avoid-all', 'css', 'legacy']
        };
        html2pdf().set(opt).from(element).outputPdf().save();
    </script>
</body>

</html>