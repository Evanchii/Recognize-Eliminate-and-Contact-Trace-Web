<table style="width: 100%;">
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Name</th>
            <th>Backend Account</th>
          </tr>
<?php
include '../../../includes/dbconfig.php';
session_start();

$uid = $_SESSION['uid'];

$page = isset($_POST['page'])?$_POST['page']:1;

$keyRef = $database->getReference('Users/'.$uid.'/history');
$historyRef = $database->getReference('History');

$keys = $keyRef->getValue();
$rawKeys = array();

// var_dump(array_values($keys));
// echo "<br>";

// Get number of Children
$numChild = 0;
foreach($keys as $date => $key) {
    $numChild += count($key);
    foreach($key as $tmp => $ts) {
    array_push($rawKeys, $ts);
    }
    // echo("Key#: ".count($key)."<br>");
}
// echo("Total: ".$numChild."<br>");
// var_dump($rawKeys);
// echo "<br>";

$totalPage = ceil($numChild/20);
// get all page numbers
$page = $page<=$totalPage?$page:1;
// $valKey = array_values($rawKeys);
// var_dump($valKey);
$pageData = array_slice($rawKeys, (($page-1)*20), ($page*20-1));

foreach($pageData as $tmp => $key) {
    // $ts = ;
    $date = date('Y-m-d', $key/1000);
//     echo date('m/d/Y H:i:s', $key/1000);
// echo "<br>";
    // $keyData = $historyRef->getChild($date)->getValue();
    // foreach($ketData as $name => $data) {
        
    // }
    echo '<tr>
        <td>' . $date . '</td>
        <td>' . $historyRef->getChild($date . '/' . $key . '/time')->getValue() . '</td>
        <td>' . $historyRef->getChild($date . '/' . $key . '/name')->getValue() . '</td>
        <td>' . $historyRef->getChild($date . '/' . $key . '/sub')->getValue() . '</td>
        </tr>';
}
?>

</table>
<div class="pagination">
<?php
echo '<a href="#" ';
if($page==1) {
    echo 'class="disabled-link" ';
} else {
    echo 'onclick="loadPage('. ($page-1) .');" ';
}
echo '>&laquo;</a>';
for($i = 1; $i <= $totalPage; $i++) {
    echo '<a href="#" ';
    if($i == $page) {
        echo 'class="disabled-link active" ';
    }
    echo '>'. $i .'</a> ';
}
echo '<a href="#" ';
if($page==$totalPage) {
    echo 'class="disabled-link" ';
} else {
    echo 'onclick="loadPage('. ($page+1) .');" ';
}
echo '>&raquo;</a>';
?>
</div>
