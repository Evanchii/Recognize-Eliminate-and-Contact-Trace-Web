<table>
          <tr>
            <th>Name</th>
            <th>UID</th>
            <th>User Type</th>
            <th>Actions</th>
          </tr>
<?php
include '../../../includes/dbconfig.php';
session_start();

$userRef = $database->getReference('Users');
$userInfo = $userRef->getValue();

foreach($userInfo as $uid => $data) {
    if($uid == $_SESSION['uid']) {
        continue;
    }
    echo '<tr>';
    if($data['info']['Type'] == 'visitor'){
        echo '
        <td>'.$data['info']['lName'].', '.$data['info']['fName'].' '.$data['info']['mName'].'</td>
        <td>'.$uid.'</td>
        <td>'.$data['info']['Type'].'</td>
        <td>
            <button>
                <i class="fas fa-eye"></i>
            </button>
            <button>
                <i class="fas fa-toggle-on"></i>
            </button>
            <button>
                <i class="fas fa-lock"></i>
            </button>
            <button>
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
        ';
    } elseif($data['info']['Type'] == 'establishment'){
        echo '
        <td>'.$data['info']['name'].' '.$data['info']['branch'].'</td>
        <td>'.$uid.'</td>
        <td>'.$data['info']['Type'].'</td>
        <td>
            <button>
                <i class="fas fa-eye"></i>
            </button>
            <button>
                <i class="fas fa-toggle-on"></i>
            </button>
            <button>
                <i class="fas fa-lock"></i>
            </button>
            <button>
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
        ';
    } elseif($data['info']['Type'] == 'admin'){
        echo '
        <td>'.$data['info']['addCi'].'</td>
        <td>'.$uid.'</td>
        <td>'.$data['info']['Type'].'</td>
        <td>
            <button>
                <i class="fas fa-eye"></i>
            </button>
            <button>
                <i class="fas fa-toggle-on"></i>
            </button>
            <button>
                <i class="fas fa-lock"></i>
            </button>
            <button>
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
        ';
    }
    echo '</tr>';
}
?>

</table>
        <div class="pagination">
          <a href="#" class="disabled-link">&laquo;</a>
          <a href="#" class="disabled-link active">1</a>
          <a href="#" class="disabled-link">&raquo;</a>
        </div>