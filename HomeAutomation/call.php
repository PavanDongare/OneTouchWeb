<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
$start_word = "Mithilesh";
$end_word = "Izardar";
$output = passthru("python test.py ".$start_word." ".$end_word);

echo $output;


?>