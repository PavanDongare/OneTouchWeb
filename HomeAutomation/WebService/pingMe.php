<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


    $response["Error"] = FALSE;
    $response["Status"] = "Success";
    $response["Message"] = "Ping is success";
    echo json_encode($response);

?>