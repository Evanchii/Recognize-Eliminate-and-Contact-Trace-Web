<?php
include '../../functions/checkSession.php';
$sampleRef = $database->getReference("SAMPLE/");

var_dump($sampleRef
->orderByKey()
->limitToFirst(10)
->getSnapshot()
->getValue());

echo("<br><br>");

var_dump($sampleRef
->orderByKey()
->startAt('11')
->limitToFirst(10)
->getSnapshot()
->getValue());
?>