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

if(!empty($data) && isset($data->udnNumber) && isset($data->switchCode) && isset($data->currentStatus)){

    $udnNumber = $data->udnNumber;
    $switchCode = $data->switchCode;
    $currentStatus = $data->currentStatus;

    $stmtStatus = $db->prepare('SELECT * FROM SwitchStatusDetails WHERE udnNumber = :udnNumber AND switchCode = :switchCode');
    $stmtStatus->execute(array(
        ':udnNumber' => $udnNumber,
        ':switchCode' => $switchCode
    ));
    $statusDetail = $stmtStatus->fetch(PDO::FETCH_ASSOC);

        if($statusDetail){

            $stmtUpdateStatus = $db->prepare('UPDATE SwitchStatusDetails SET currentStatus = :currentStatus WHERE udnNumber = :udnNumber AND switchCode = :switchCode');
            $stmtUpdateStatus->execute(array(
                ':udnNumber' => $udnNumber,
                ':switchCode' => $switchCode,
                ':currentStatus' => $currentStatus 
            ));

            $stmtUpdated = $db->prepare('SELECT SSD.*,AD.accountNumber FROM SwitchStatusDetails SSD 
            INNER JOIN AccountDetails AD on AD.udn = SSD.udnNumber 
            WHERE udnNumber = :udnNumber AND switchCode = :switchCode');
            $stmtUpdated->execute(array(
                ':udnNumber' => $udnNumber,
                ':switchCode' => $switchCode
            ));

            $statusDetail = $stmtUpdated->fetch(PDO::FETCH_ASSOC);
            $response["switchStatus"] = $statusDetail;
            
            $topic = $statusDetail["accountNumber"];

            $fireBaseUtil = new FireBaseUtil();
            $notificationMessage["notificationData"] = json_encode($statusDetail);
            $fireBaseUtil->sendPush($topic,$notificationMessage);

            echo json_encode($response);
            exit;

        }else{
            $response["Error"] = TRUE;
            $response["Message"] = "Device switch not available";
            echo json_encode($response);
            exit;
        }
}else{
    $response["Error"] = TRUE;
    $response["Status"] = "Failed";
    $response["Message"] = "Required parameters missing!";
    echo json_encode($response);
    exit;
}

?>