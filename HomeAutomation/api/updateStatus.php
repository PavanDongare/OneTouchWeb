<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/api/sendPush.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);
// Takes raw data from the request
$json = file_get_contents('php://input');
// Converts it into a PHP object
$data = json_decode($json);
//echo $json;

if(!empty($data) && isset($data->accountNumber) && isset($data->udnNumber) && isset($data->switchCode) && isset($data->currentStatus)){

    $accountNumber = $data->accountNumber;
    $udnNumber = $data->udnNumber;
    $switchCode = $data->switchCode;
    $currentStatus = $data->currentStatus;
    
    $id = 1;
    $stmtSystemInfo = $db->prepare('SELECT * FROM SystemInfo WHERE id = :id');
    $stmtSystemInfo->execute(array(':id' => $id));
    $systemInfoData = $stmtSystemInfo->fetch(PDO::FETCH_ASSOC);

    $port = $systemInfoData["port"];
    $serverIp = $systemInfoData["serverIp"];

    try {
        $var = "mosquitto_pub -h ";
        $var .= $serverIp;
        $var .= ' -t ';
        $var .= $accountNumber;
        $var .= ' -m "';
        $var .= $udnNumber;
        $var .= '.';
        $var .= $switchCode;
        $var .= '-';
        $var .= $currentStatus;
        $var .= '~"';
        $var .= ' -p ';
        $var .= $port;
        //passthru('echo '.$var);
        passthru($var);
        //$response["var"] = $var;
      }
      
      //catch exception
      catch(Exception $e) {
        //echo 'Message: ' .$e->getMessage();
      }

      $response["Error"] = FALSE;
      $response["Status"] = "Success";
      echo json_encode($response);
      exit;

}else{
    $response["Error"] = TRUE;
    $response["Status"] = "Failed";
    $response["Message"] = "Required parameters missing!";
    echo json_encode($response);
    exit;
}

?>