<?php
include '../includes/dbconfig.php';
session_start();

$uid = (isset($_POST['uid'])) ? $_POST['uid'] : $_SESSION['uid'];
$page = isset($_POST['page'])?$_POST['page']:1;
$userType = $database->getReference('Users/'.$uid.'/info/Type')->getValue();

$keyRef = $database->getReference('Users/'.$uid.'/history');
$historyRef = $database->getReference('History');

if($userType == 'visitor') {
    $header = [
        'date' => 'Date',
        'time' => 'Time',
        'estName' => 'Establishment',
        'branch' => 'Branch',
    ];
} else {
    $header = [
        'date' => 'Date',
        'time' => 'Time',
        'name' => 'Name',
        'sub' => 'Backend Account',
    ];
}

$data = [];

if($keyRef->getSnapshot()->hasChildren()) {
    $keys = $keyRef->getValue();
    $counter = 0;
    foreach($keys as $date => $tsEntry) {
        // var_dump($tsEntry);
        foreach($tsEntry as $tmp => $ts) {
            // echo $ts.'<br>';
            // array_push($data, $historyRef->getChild($date)->getValue($ts));
            $data[$ts] = $historyRef->getChild($date. '/'.$ts)->getValue();
            // echo ++$counter;
        }
    }
}

// var_dump($data);
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
        
        foreach($rawData as $uid => $info) {
            switch($sType) {
                case 'visName':
                    if(str_icontains($info['name'], $search)) {
                        $data[$uid] = $info;
                    }
                    break;
                case 'estName':
                    if(str_icontains($info['estName'], $search)) {
                        $data[$uid] = $info;
                    }
                    break;
                case 'branch':
                    if(str_icontains($info['branch'], $search)) {
                        $data[$uid] = $info;
                    }
                    break;
                case 'backend':
                    if(str_icontains($info['sub'], $search)) {
                        $data[$uid] = $info;
                    }
                    break;
                default:
                    if($userType == 'visitor') {
                        if(str_icontains($info['estName'].$info['branch'], $search)) {
                            $data[$uid] = $info;
                        }
                    } elseif($userType == 'establishment') {
                        if(str_icontains($info['name'].$info['sub'], $search)) {
                            $data[$uid] = $info;
                        }
                    }
            }
        }
    }
}

createTable($header, $data, $page, $search, $sType);
?>