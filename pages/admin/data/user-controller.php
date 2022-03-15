<style>
.photoName {
    display: flex;
    justify-content: space-around;
}
.modal-body img {
    width: 100%;
    aspect-ratio: 1/1;
    object-fit: cover;
}

.info {
    line-height: 2em;
    margin: auto 0;
}

.modal-body table {
    table-layout: fixed;
    width: 100%;
}

.address-table tr td {
    width: calc(100%/3);
    text-align: center;
    background-color: unset;
}

/* .address-table tr td {
    background-color: grey;
} */

.modal-body table {
    margin-bottom: 1em;
}

.table-title {
    margin: 1em;
}

.photo i {
    font-size: 8em;
}

.modal-body td>button {
    font-size: unset;
}
</style>

<?php
include '../../../includes/dbconfig.php';
session_start();

// Firebase Storage
$storage = $firebase->createStorage();
$storageClient = $storage->getStorageClient();
$defaultBucket = $storage->getBucket();

$expiresAt = new DateTime('tomorrow', new DateTimeZone('Asia/Manila'));

$uid = $_POST['uid'];
$auth = $firebase->createAuth();
$userRef = $database->getReference('Users/'.$uid);
$logRef = $database->getReference('Logs/');

if(isset($_POST['openInfo'])) {
    $data = $userRef->getChild('info')->getValue();
    if($data['Type'] == 'visitor') {
        $faceRef = $defaultBucket->object($data['faceID']);
        $idRef = $defaultBucket->object($data['ID']);
        if(isset($data['vaccID'])) {
            $vaccRef = $defaultBucket->object($data['vaccID']);
            if ($vaccRef->exists()) {
                $vacc = $vaccRef->signedUrl($expiresAt);
            }
        }
        if ($faceRef->exists()) {
            $image = $faceRef->signedUrl($expiresAt);
            $img = '<img src="'.$image.'" alt="">';
        }
        if ($idRef->exists()) {
            $id = $idRef->signedUrl($expiresAt);
        }

        $name = $data['lName'] . ', '. $data['fName'] . ' ' . $data['mName'];
    }
    else {
        $img = '<i class="fa-solid fa-city"></i>';
        $name = ($data['Type'] == 'sub-establishment') ? $data['username'] . '-' . $data['name'] : $data['name'] .'-'. $data['branch'];
    }
    $cNo = (isset($data['cNo'])) ? $data['cNo'] : 'No Record';
    $DoB = (isset($data['DoB'])) ? $data['DoB'] : 'No Record';

    echo <<<HTML
        <div class="modal-body">
            <div class="photoName">
                <div class="photo center" style="width: 30%">
                    {$img}
                </div>
                <div class="info" style="width: 60%">
                    <p>Name: {$name}</p>
                    <p>UID: {$uid}</p>
                    <span>Birthday: {$DoB}</span> \t|\t 
                    <span>Contact Number: {$cNo}</span>
                    <p>Email: {$auth->getUser($uid)->__get('email')}</p>
                </div>
            </div>
    HTML;
    if($data['Type'] == 'sub-establishment') {
        $mainEst = $database->getReference('Users/'.$data['main'].'/info')->getValue();
        $data['addNo'] = $mainEst['addNo'];
        $data['addBa'] = $mainEst['addBa'];
        $data['addCi'] = $mainEst['addCi'];
        $data['addPro'] = $mainEst['addPro'];
        $data['addCo'] = $mainEst['addCo'];
        $data['addZip'] = $mainEst['addZip'];
        $uid = $data['main'];

        echo <<<HTML
        <div class="info"><br>
            <h4>Parent Account Details</h4>
            <p>Parent UID: {$data['main']}</p>
            <p>Parent Establishment: {$data['name']}</p>
            <p>Parent Branch: {$data['branch']}</p>
            <button class="btn-success" onclick="window.open('users.php?uid={$data['main']}', '_blank');">View Parent Profile</button>
        </div>
        HTML;
    }
    echo <<<HTML
            <h4 class="table-title">Address</h4>
            <table class="address-table">
                <tr>
                    <th class="center">House Number/Street Name</th>
                    <th class="center">Barangay</th>
                    <th class="center">City</th>
                </tr>
                <tr>
                    <td>{$data['addNo']}</td>
                    <td>{$data['addBa']}</td>
                    <td>{$data['addCi']}</td>
                </tr>
                <tr><td><br></td></tr>
                <tr>
                    <th class="center">Province</th>
                    <th class="center">Country</th>
                    <th class="center">Zip code</th>
                </tr>
                </tr>
                <tr>
                    <td>{$data['addPro']}</td>
                    <td>{$data['addCo']}</td>
                    <td>{$data['addZip']}</td>
                </tr>
            </table>
        HTML;
        if($data['Type'] == 'visitor') {
            echo '
            <span>Health Status: '.($data['status']?'Positive':'Negative').'</span>
            <button id="healthToggle" class="btn-primary" onclick="toggleStatus(\''.$uid.'\', \''. (!$data['status']?'positive':'negative') .'\')">Toggle</button> <br>
            <span>Vaccination Status: '.($data['vaccine'] == 'pending'?'Pending Application':($data['vaccine']?'Vaccinated':'Not Vaccinated')).'</span>
            ';
            if($data['vaccine'] == 'pending') {
                $getApp = $database->getReference('Applications')->orderByChild('uid')->equalTo($uid)->getSnapshot();
            $appTS = array_keys($getApp->getValue())[0];
            echo '<button id="vaccToggle" class="btn-primary" onclick="window.open(\'applications.php?viewApp='.$appTS.'\', \'_blank\').focus();">View Application</button>';
            }
            echo '<br>';
        }

        echo '
        <h4 class="table-title">User files</h4>
        <table>
            <tr>
                <th class="center">File Name</th>
                <th class="center">Category</th>
                <th class="center">View</th>
            </tr>
            <tr>';

        if($data['Type'] == 'visitor') {
            echo ' <tr>
            <td>'.str_replace('Face/', '', $data['faceID']).'</td>
            <td>Face Photo</td>
            <td><button class="btn-primary" onclick="openDocument(\''.$image.'\');">
                    Open Photo
            </button></td></tr>
            ';
            echo '<tr>

            <td>'.str_replace('ID/', '', $data['ID']).'</td>
            <td>ID Card</td>
            <td><button class="btn-primary" onclick="openDocument(\''.$id.'\');">
                    Open Photo
            </button></td></tr>
            ';
            if($data['vaccine'] == 'pending' || $data['vaccine']) {
                echo '<tr>
                <td>'.str_replace('VacID/', '', $data['vaccID']).'</td>
                <td>Vaccination Card</td>
                <td><button class="btn-primary" onclick="openDocument(\''.$vacc.'\');">
                    Open Photo
            </button></td></tr>
                ';
            }
        } elseif($data['Type'] == 'establishment') {
            $doc = $defaultBucket->object($data['doc']);
            if ($doc->exists()) {
                $docImg = $doc->signedUrl($expiresAt);
            }
            echo '<tr>
            <td>'.str_replace('Doc/', '', $data['doc']).'</td>
            <td>Document</td>
            <td><button class="btn-primary" onclick="openDocument(\''.$docImg.'\');">
                    Open Photo
            </button></td></tr>
            ';
        } else {
            echo '<tr><td colspan="3"><h2 class="center">No Data Found</h2></td></tr>';
        }

        echo '</tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-primary" onclick="openDocument(\'history.php?uid='.$uid.'\')">View History</button>
            <button type="button" ';
        echo ($auth->getUser($uid)->__get('disabled')) ? 'class="btn-success" onclick="toggleUser(\''.$uid.'\', \'false\');">Enable' : 'class="btn-error" onclick="toggleUser(\''.$uid.'\', \'true\');">Disable';
        echo '</button></div>';
} elseif(isset($_POST['toggleUser'])) {
    $uid = $_POST['uid'];
    $disable = $_POST['disable'];

    if($disable == 'true') {
        $res = $auth->disableUser($uid);
        createLog('Accounts', 'has disabled user ', $uid);
    } else {
        $res = $auth->enableUser($uid);  
        createLog('Accounts', 'has enabled user ', $uid);
    }

    // if($_SESSION['type'] == 'admin') {
    //     $name = 'Admin';
    //     createLog('Accounts', 'has toggled user ', $uid);
    // } else {
    //     $name = $database->getReference('Users/'.$_SESSION['uid'].'/info/username')->getValue();
    // }
        
    // $logRef->update([
    //     time() => [
    //         'description' => $name.' ('.$_SESSION['uid'].') has toggled user - '.$uid,
    //         'ip' => $_SERVER['REMOTE_ADDR'],
    //         'category' => 'Accounts'
    //     ]
    // ]);
} elseif(isset($_POST['deleteUser'])) {
    $uid = $_POST['uid'];
    $type = $userRef->getChild('Type')->getValue();

    $auth->deleteUser($uid);

    $userRef->set('');

    if($type == "sub-admin") {
        $database->getReference('Users/'.$_SESSION['uid'].'/sub/'.$uid)->set('');

        createLog('Accounts', 'has deleted sub-user ', $uid);
        // $logRef->update([
        //     time() => [
        //         'description' => 'Admin ('.$_SESSION['uid'].') has deleted Sub-user '.$uid,
        //         'ip' => $_SERVER['REMOTE_ADDR'],
        //         'category' => 'Accounts'
        //     ]
        // ]);
    }
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
    if (str_contains($action, 'positive')) {
    $userRef->getChild('info')->update([
        "status" => true
    ]);

    echo <<<HTML
    <script>
        sendCovNotif({$uid}});
    </script>
    HTML;
    createLog('Health Status', ' has change the Health Status(Postive) of', $uid);
    } else {
    $userRef->getChild('info')->update([
        "status" => false
    ]);
    createLog('Health Status', ' has change the Health Status(Negative) of"', $uid);
    }
  }
exit();
?>s