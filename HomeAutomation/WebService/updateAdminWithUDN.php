<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);

// Takes raw data from the request
$json = file_get_contents('php://input');
// Converts it into a PHP object
$data = json_decode($json);

if(isset($data)){

    $userDetails = $data->userDetails;
    $adminMobileNumber = $userDetails->mobileNumber;

    $wifiDetails = $data->wifiDetails;
    $SSID = $wifiDetails->SSID;
    $Password = $wifiDetails->Password;

    
    if(!empty($SSID) || !empty($Password) || !empty($adminMobileNumber)){

        $stmt = $db->prepare('INSERT INTO AdminUserMaster ( SSID, Password, adminMobileNumber ) 
        VALUES (:SSID, :Password, :adminMobileNumber)');
        $stmt->execute(array(
            ':SSID' => $SSID,
            ':Password' => $Password,
            ':adminMobileNumber' => $adminMobileNumber
        ));

        $adminId = $db->lastInsertId('adminId');

        $UDNList =  $data->UDNList;

        $successfulUDNList =  array();
        $failedUDNList =  array();
        $successCount = 0;
        $failCount = 0;

        foreach($UDNList as $UDNData){
            $deviceType = $UDNData->deviceType;
            $deviceModel = $UDNData->deviceModel;
            $UDN = $UDNData->deviceId;

            $stmtUDN = $db->prepare('SELECT UDN FROM UDNMaster WHERE UDN = :UDN');
            $stmtUDN->execute(array(':UDN' => $UDN));
            $UDNDataObj = $stmtUDN->fetch(PDO::FETCH_ASSOC);

            if(empty($UDNDataObj)){
                $stmt = $db->prepare('INSERT INTO UDNMaster ( UDN, deviceType, deviceModel, adminId ) 
                                VALUES (:UDN, :deviceType, :deviceModel, :adminId)');
                $stmt->execute(array(
                    ':UDN' => $UDN,
                    ':deviceType' => $deviceType,
                    ':deviceModel' => $deviceModel,
                    ':adminId' => $adminId
                ));
                $successfulUDNList[$successCount]["UDN"] = $UDN;
                $successCount++;
            }else{
                $failedUDNList[$failCount]["UDN"] = $UDN;
                $failedUDNList[$failCount]["Reason"] = "AlreadyExist";
                $failCount++;
            }

            $response["FailedList"] = $failedUDNList;
            $response["SuccessList"] = $successfulUDNList;

        }

        $response["Error"] = FALSE;
        $response["Status"] = "SUCCES";
        echo json_encode($response);

    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "Required parameters missing!";
        echo json_encode($response);
    }

}else{
    $response = "Required parameters missing!";
    echo $response;
}



?>