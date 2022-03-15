<?php
include '../includes/dbconfig.php';
session_start();

$page = isset($_POST['page'])?$_POST['page']:1;

$appRef = $database->getReference('Applications');

$header = [
    'date' => 'Date',
    'name' => 'Name',
    'uid' => 'UID',
    'type' => 'User Type',
    'application' => 'Application',
    'action' => 'Action',
];

// Cleaning Data
$data = [];
if($appRef->getSnapshot()->hasChildren()) {
    $rawData = $appRef->getValue();
    foreach($rawData as $ts => $info) {
        $data[$ts]['date'] = date('Y/m/d', $ts);
        $data[$ts]['name'] = $info['name'];
        $data[$ts]['uid'] = $info['uid'];
        $data[$ts]['type'] = $info['usertype'];
        $data[$ts]['application'] = $info['type'];
        $data[$ts]['action'] = '<button  onclick="showData(\'' . $ts . '\')">
        <i class="far fa-eye" aria-hidden="true"></i>
        </button>';
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
                case 'name':
                    if(str_icontains($info['name'], $search)) {
                        $data[$ts] = $info;
                    }
                    break;
                case 'uid':
                    if(str_icontains($info['uid'], $search)) {
                        $data[$ts] = $info;
                    }
                    break;
                case 'usertype':
                    if(str_icontains($info['type'], $search)) {
                        if(!($search == 'Establishment' && $info['type'] == 'Sub-establishment'))
                            $data[$ts] = $info;
                    }
                    break;
                case 'application':
                    if(str_icontains($info['application'], $search)) {
                        $data[$ts] = $info;
                    }
                    break;
                default:
                    if(str_icontains($info['name'].$info['type'].$info['application'].$info['uid'], $search)) {
                        $data[$ts] = $info;
                    }
                    break;
            }
        }
    }
}

createTable($header, $data, $page, $search, $sType);
?>