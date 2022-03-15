<?php 
function createTable($header, $data, $page, $search, $sType) {
    // Slice data to per page
    $numChild = count($data);
    $totalPage = $numChild>0?ceil($numChild/10):1;
    $page = $page<=$totalPage?$page:1;

    $pageData = array_slice($data, (($page-1)*10), 10);

    // Print table
    echo '
    <table style="width: 100%;">
    <tr>
    ';
    foreach($header as $key => $head) {
        echo '<th>'.$head.'</th>';
    }
    echo '</tr>';
    // var_dump($pageData);
    if($numChild > 0) {
        foreach($pageData as $ts => $entryData) {
            echo '<tr>';
            foreach($header as $key => $head) {
                echo '<td> '.$entryData[$key].'</td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="'.count($header).'"><h2 class="center">No data found</h2></td></tr>';
    }
    echo '</table> <div class="pagination">';

    // Pagination
    echo '<a href="#" ';
    if($page==1) {
        echo 'class="disabled-link" ';
    } else {
        echo 'onclick="loadPage('. ($page-1) .', \''.$search.'\', \''.$sType.'\');" ';
    }
    echo '>&laquo;</a>';
    for($i = 1; $i <= $totalPage; $i++) {
        echo '<a href="#" ';
        if($i == $page) {
            echo 'class="disabled-link active" ';
        }
        echo 'onclick="loadPage('. ($i) .', \''.$search.'\', \''.$sType.'\');" ';
        echo '>'. $i .'</a> ';
    }
    echo '<a href="#" ';
    if($page==$totalPage) {
        echo 'class="disabled-link" ';
    } else {
        echo 'onclick="loadPage('. ($page+1) .', \''.$search.'\', \''.$sType.'\');" ';
    }
    echo '>&raquo;</a>';
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