<table>
    <tr>
        <th>Username</th>
        <th>UID</th>
        <th>Action</th>
    </tr>
    <?php
    include '../../../includes/dbconfig.php';
    session_start();
    $page = $_POST['page'];
    $uid = $_SESSION['uid'];

    $accountRef = $database->getReference("Users/" . $uid . "/sub");

    $totalChild = $accountRef->getSnapshot()->numChildren();
    if ($totalChild == 0) {
        echo '<tr><td colspan="4"><h2 style="text-align: center;">No data found!</h2></td><tr>';
        echo "</table>";
        echo '
          <div class="pagination">
            <a href="#" class="disabled-link">&laquo;</a>
            <a href="#" class="disabled-link active">1</a>
            <a href="#" class="disabled-link">&raquo;</a>
        </div>
        ';
    } else {
        $totalPage = ceil($totalChild / 20);

        // If page requested exceeds total n pages, then return to page 1
        $page = $page <= $totalPage ? $page : 1;

        $data = $accountRef->getValue();
        $pageData = array_slice($data, (($page - 1) * 20), ($page * 20 - 1));

        foreach ($pageData as $username => $uid) {
            echo '<tr>
            <td>' . $username . '</td>
            <td>' . $uid . '</td>
            <td><button onclick="deleteUser(\'' . $uid . '\',\'' . $username . '\')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
        }
    }
    ?>

</table>
<div class="pagination">
    <?php
    if (isset($totalPage)) {
        echo '<a href="#" ';
        if ($page == 1) {
            echo 'class="disabled-link" ';
        } else {
            echo 'onclick="loadPage(' . ($page - 1) . ');" ';
        }
        echo '>&laquo;</a>';
        for ($i = 1; $i <= $totalPage; $i++) {
            echo '<a href="#" ';
            if ($i == $page) {
                echo 'class="disabled-link active" ';
            }
            echo '>' . $i . '</a> ';
        }
        echo '<a href="#" ';
        if ($page == $totalPage) {
            echo 'class="disabled-link" ';
        } else {
            echo 'onclick="loadPage(' . ($page + 1) . ');" ';
        }
        echo '>&raquo;</a>';
    }
    ?>
</div>