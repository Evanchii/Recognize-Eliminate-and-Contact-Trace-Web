<?php
include '../includes/dbconfig.php';
session_start();

$page = isset($_POST['page'])?$_POST['page']:1;

$userRef = $database->getReference('Users');
$auth = $firebase->createAuth();

$header = [
    'name' => 'Name',
    'uid' => 'UID',
    'type' => 'User Type',
    'actions' => 'Actions',
];

// Cleaning data
$data = [];
if($userRef->getSnapshot()->hasChildren()) {
    $rawData = $userRef->getValue();
    foreach($rawData as $uid => $info) {
        if (str_contains($info['info']['Type'], 'admin')) {
            continue;
        }
        $data[$uid]['uid'] = $uid;
        $data[$uid]['actions'] = '<button onclick="openProfile(\'' . $uid . '\')" title="View User Information">
                <i class="fas fa-eye"></i>
            </button>
            <button onclick="toggleUser(\'';
        $data[$uid]['actions'] .= ($auth->getUser($uid)->__get('disabled')) ? $uid.'\', \'false\');" title="User is Disabled"><i class="fas fa-toggle-off">' : $uid.'\', \'true\');" title="User is Enabled"><i class="fas fa-toggle-on">';
        $data[$uid]['actions'] .= '</i></button>';
        $data[$uid]['type'] = ucfirst($info['info']['Type']);
        switch($data[$uid]['type']) {
            case 'Visitor':
                $data[$uid]['name'] = $info['info']['lName'] . ', ' . $info['info']['fName'] . ' ' . $info['info']['mName'];
                break;
            case 'Establishment':
                $data[$uid]['name'] = $info['info']['name'] . ' ' . $info['info']['branch'];
                break;
            case 'Sub-establishment':
                $data[$uid]['name'] = $info['info']['username'] . '-' . $info['info']['name'];
                break;
        }
    }
}

include 'table-handler.php';

$search = '';
$sType = '';
// Search Algo
if(isset($_POST['search'])) {
    $search = $_POST['search'];
    if($search != '') {
        $sType = isset($_POST['advanced']) ? $_POST['sType'] : '';

        $rawData = $data;
        $data = [];
        
        foreach($rawData as $uid => $info) {
            switch($sType) {
                case 'name':
                    if(str_icontains($info['name'], $search)) {
                        $data[$uid] = $info;
                    }
                    break;
                case 'uid':
                    if(str_icontains($uid, $search)) {
                        $data[$uid] = $info;
                    }
                    break;
                case 'usertype':
                    if(str_icontains($info['type'], $search)) {
                        if(!($search == 'Establishment' && $info['type'] == 'Sub-establishment'))
                            $data[$uid] = $info;
                    }
                    break;
                default:
                    if(str_icontains($info['name'].$info['type'].$uid, $search)) {
                        $data[$uid] = $info;
                    }
                    break;
            }
        }
    }
}

createTable($header, $data, $page, $search, $sType);
