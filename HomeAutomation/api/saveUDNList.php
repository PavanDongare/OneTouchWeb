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
$saveUDNData = json_decode($json);

if(isset($saveUDNData)){
    $accountNumber = $saveUDNData->accountNumber;
    $udnList = $saveUDNData->udnList;

    $successfulUDNList =  array();
    $failedUDNList =  array();
    $successCount = 0;
    $failCount = 0;

    foreach($udnList as $udnData){
        $udn = $udnData->udn;
        $roomName = $udnData->roomName;
        $stmtAccountDetails = $db->prepare('SELECT * FROM AccountDetails WHERE udn = :udn');
        $stmtAccountDetails->execute(array(':udn' => $udn));
        $UDNDataObj = $stmtAccountDetails->fetch(PDO::FETCH_ASSOC);
        if($UDNDataObj){
            $failedUDNList[$failCount] = $udn;
            $failCount++;
        }else{
            $stmtAccount = $db->prepare('INSERT INTO AccountDetails (accountNumber,udn,roomName)
            VALUES(:accountNumber,:udn,:roomName)');
            $stmtAccount->execute(array(
                ':accountNumber' => $accountNumber,
                ':udn' => $udn,
                'roomName' => $roomName
            ));

            $udnCode = substr($udn, 0, 2);
    
            $stmtModelDetailsList = $db->prepare('SELECT MD.* FROM ModelDetails MD
            INNER JOIN UDNModelDetails UMD ON UMD.udnCode = :udnCode
            WHERE MD.modelCode = UMD.modelCode');
            $stmtModelDetailsList->execute(array(
            ':udnCode' => $udnCode,
            ));

            while($modelDetails= $stmtModelDetailsList->fetch(PDO::FETCH_ASSOC)){
                $switchCode = $modelDetails["switchCode"];
                $stmtModelDetail = $db->prepare('INSERT INTO SwitchStatusDetails (udnNumber,switchCode,currentStatus)
                VALUES(:udnNumber,:switchCode,0)');
                $stmtModelDetail->execute(array(
                    ':udnNumber' => $udn,
                    ':switchCode' => $switchCode
                ));
            }
            $successfulUDNList[$successCount] = $udn;
            $successCount++;
        }

    }

    $result["failedList"] = $failedUDNList;
    $result["successList"] = $successfulUDNList;

    $response["result"] = $result;
    $response["status"] = "Done";
    echo json_encode($response);
    exit;
}else{
    $response["Error"] = TRUE;
    $response["status"] = "Failed";
    $response["message"] = "Required parameters missing!";
    echo json_encode($response);
    exit;
}



?>