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
    $mobileNumber = $userDetails->mobileNumber;
    $userId = $userDetails->userId;
    $accountNumber = time();  

    $wifiDetails = $data->wifiDetails;
    $SSID = $wifiDetails->SSID;
    $WifiPassword = $wifiDetails->WifiPassword;

    
    if(!empty($SSID) || !empty($Password) || !empty($mobileNumber)){

        $stmt = $db->prepare('UPDATE UserMaster SET SSID = :SSID, WifiPassword = :WifiPassword
        WHERE userId = :userId');
        $stmt->execute(array(
            ':SSID' => $SSID,
            ':WifiPassword' => $WifiPassword,
            ':userId' => $userId
        ));

        $stmtAccount = $db->prepare('INSERT INTO AccountAccess (accountNumber,userId,isApproved)
        VALUES(:accountNumber,:userId,1)');
        $stmtAccount->execute(array(
            ':accountNumber' => $accountNumber,
            ':userId' => $userId
        ));


        $UDNList =  $data->UDNList;

        $successfulUDNList =  array();
        $failedUDNList =  array();
        $successCount = 0;
        $failCount = 0;

        foreach($UDNList as $UDNData){
            $deviceType = $UDNData->deviceType;
            $deviceModel = $UDNData->deviceModel;
            $UDN = $UDNData->deviceId;
            $stmt1 = $db->prepare('SELECT UDN FROM AccountDetails WHERE UDN = :UDN');
            $stmt1->execute(array(':UDN' => $UDN));
            $accountDataObj = $stmt1->fetch(PDO::FETCH_ASSOC);
            if($accountDataObj){
                $stmtUDN = $db->prepare('SELECT UDN FROM UDNMaster WHERE UDN = :UDN');
                $stmtUDN->execute(array(':UDN' => $UDN));
                $UDNDataObj = $stmtUDN->fetch(PDO::FETCH_ASSOC);
    
                if(empty($UDNDataObj)){
                    $stmt = $db->prepare('INSERT INTO UDNMaster ( UDN, deviceType, deviceModel ) 
                                    VALUES (:UDN, :deviceType, :deviceModel)');
                    $stmt->execute(array(
                        ':UDN' => $UDN,
                        ':deviceType' => $deviceType,
                        ':deviceModel' => $deviceModel
                    ));
                    $successfulUDNList[$successCount]["UDN"] = $UDN;
                    $successCount++;
                }else{
                    $failedUDNList[$failCount]["UDN"] = $UDN;
                    $failedUDNList[$failCount]["Reason"] = "AlreadyExist";
                    $failCount++;
                }
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