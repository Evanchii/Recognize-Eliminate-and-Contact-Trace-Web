<?php 
include '../includes/dbconfig.php';
session_start();

date_default_timezone_set('Asia/Manila');

$page = isset($_POST['page'])?$_POST['page']:1;

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
if($logRef->getSnapshot()->hasChildren()) {
    $rawData = $logRef->getValue();
    foreach($rawData as $ts => $info) {
        $data[$ts]['date'] = date('Y/m/d', $ts);
        $data[$ts]['time'] = date('h:i:s a', $ts);
        $data[$ts]['category'] = $info['category'];
        $data[$ts]['description'] = $info['description'];
        $data[$ts]['ip'] = $info['ip'];
    }
}

include 'table-handler.php';

// Search Algo
$search = '';
$sType = '';

if(isset($_POST['search'])) {
    $search = $_POST['search'];
    if($search != '') {
        $sType = isset($_POST['advanced']) ? $_POST['sType'] : '';

        $rawData = $data;
        $data = [];
        
        foreach($rawData as $ts => $info) {
            switch($sType) {
                case 'category':
                    if(str_icontains($info['category'], $search)) {
                        $data[$ts] = $info;
                    }
                    break;
                case 'description':
                    if(str_icontains($info['description'], $search)) {
                        $data[$ts] = $info;
                    }
                    break;
                case 'ip':
                    if(str_icontains($info['ip'], $search)) {
                        $data[$ts] = $info;
                    }
                    break;
                default:
                    if(str_icontains($info['description'].$info['category'].$info['ip'], $search)) {
                        $data[$ts] = $info;
                    }
                    break;
            }
        }
    }
}

createTable($header, $data, $page, $search, $sType);
?>