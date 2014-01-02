<?php
$time = (($_GET['time']+5*60)-time())/60;

echo $time;
?>