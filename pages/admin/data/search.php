<?php
include '../../../includes/dbconfig.php';
session_start();
$search = $_POST['search'];
$sType = isset($_POST['advanced']) ? $_POST['sType'] : 'na';

echo '
<table>
    <tr>
        <th>Name</th>
        <th>UID</th>
        <th>User Type</th>
        <th>Actions</th>
    </tr>';

$userRef = $database->getReference('Users');
$rawInfo = $userRef->getValue();
$userInfo = [];

foreach($rawInfo as $uid => $data) {
    $info = $data['info'];
    if($info['Type'] == 'visitor')
    switch($sType) {
        case 'name':
            if(str_icontains($info['fName'].$info['mName'].$info['lName'], $search)) {
                $userInfo[$uid] = $data;
            }
            break;
        case 'uid':
            if(str_icontains($uid, $search)) {
                $userInfo[$uid] = $data;
            }
            break;
        case 'usertype':
            if(str_icontains($info['Type'], $search)) {
                $userInfo[$uid] = $data;
            }
            break;
        default:
            if(str_icontains($info['fName'].$info['mName'].$info['lName'].$info['Type'].$uid, $search)) {
                $userInfo[$uid] = $data;
            }

            break;
    }
    else if($info['Type'] == 'establishment')
    switch($sType) {
        case 'name':
            if(str_icontains($info['name'].$info['branch'], $search)) {
                $userInfo[$uid] = $data;
            }
            break;
        case 'uid':
            if(str_icontains($uid, $search)) {
                $userInfo[$uid] = $data;
            }
            break;
        case 'usertype':
            if(str_icontains($info['Type'], $search)) {
                $userInfo[$uid] = $data;
            }
            break;
        default:
            if(str_icontains($info['name'].$info['branch'].$info['Type'].$uid, $search)) {
                $userInfo[$uid] = $data;
            }

            break;
    }
    elseif($info['Type'] == 'sub-establishment') {
        switch($sType) {
            case 'name':
                if(str_icontains($info['username'].$info['name'].$info['branch'], $search)) {
                    $userInfo[$uid] = $data;
                }
                break;
            case 'uid':
                if(str_icontains($uid, $search)) {
                    $userInfo[$uid] = $data;
                }
                break;
            case 'usertype':
                if(str_icontains($info['Type'], $search)) {
                    $userInfo[$uid] = $data;
                }
                break;
            default:
                if(str_icontains($info['username'].$info['name'].$info['branch'].$info['Type'].$uid, $search)) {
                    $userInfo[$uid] = $data;
                }

                break;
        }
    }
}

// Create table
foreach($userInfo as $uid => $data) {
    if($uid == $_SESSION['uid']) {
        continue;
    }
    echo '<tr>';
    if($data['info']['Type'] == 'visitor'){
        echo '
        <td>'.$data['info']['lName'].', '.$data['info']['fName'].' '.$data['info']['mName'].'</td>
        <td>'.$uid.'</td>
        <td>'.ucfirst($data['info']['Type']).'</td>
        <td>
            <button>
                <i class="fas fa-eye"></i>
            </button>
            <button>
                <i class="fas fa-toggle-on"></i>
            </button>
        </td>
        ';
    } elseif($data['info']['Type'] == 'establishment'){
        echo '
        <td>'.$data['info']['name'].' '.$data['info']['branch'].'</td>
        <td>'.$uid.'</td>
        <td>'.ucfirst($data['info']['Type']).'</td>
        <td>
            <button>
                <i class="fas fa-eye"></i>
            </button>
            <button>
                <i class="fas fa-toggle-on"></i>
            </button>
        </td>
        ';
    } elseif($data['info']['Type'] == 'admin'){
        echo '
        <td>'.$data['info']['addCi'].'</td>
        <td>'.$uid.'</td>
        <td>'.ucfirst($data['info']['Type']).'</td>
        <td>
            <button>
                <i class="fas fa-eye"></i>
            </button>
            <button>
                <i class="fas fa-toggle-on"></i>
            </button>
        </td>
        ';
    } elseif($data['info']['Type'] == 'sub-establishment'){
        echo '
        <td>'.$data['info']['username'].'-'.$data['info']['name'].'</td>
        <td>'.$uid.'</td>
        <td>'.ucfirst($data['info']['Type']).'</td>
        <td>
            <button>
                <i class="fas fa-eye"></i>
            </button>
            <button>
                <i class="fas fa-toggle-on"></i>
            </button>
        </td>
        ';
    }
    echo '</tr>';
}

// function str_icontains($haystack, $needle) {
//     $smallhaystack = strtolower($haystack);  // make the haystack lowercase, which essentially makes it case insensitive
//     $smallneedle = strtolower($needle);  // makes the needle lowercase, which essentially makes it case insensitive
//     if (str_contains($smallhaystack, $smallneedle)) {  // compares the lowercase strings
//         return true;  // returns true (wow)
//     } else {
//         return false;  // returns false (wow)
//     }
// }
?>

</table>
        <div class="pagination">
            <a href="#" class="disabled-link">&laquo;</a>
            <a href="#" class="disabled-link active">1</a>
            <a href="#" class="disabled-link">&raquo;</a>
        </div>