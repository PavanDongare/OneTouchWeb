<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);

if(isset($_POST["accountNumber"]) && isset($_POST["userId"])){
    $accountNumber = $_POST["accountNumber"];
    $userId = $_POST["userId"];

    $stmtUDNList = $db->prepare('SELECT AD.* FROM AccountAccess AA
    INNER JOIN AccountDetails AD ON AA.accountNumber = AD.accountNumber
    WHERE AA.accountNumber = :accountNumber AND AA.userId = :userId');
    $stmtUDNList->execute(array(
        ':accountNumber' => $accountNumber,
        ':userId' => $userId
    ));
  
    $udnList = array();
    $udnDataCount=0;

    while($udnData = $stmtUDNList->fetch(PDO::FETCH_ASSOC)){
        $udn = $udnData["udn"];
        $udnCode = substr($udn,0,2);

        $stmtModelDetailsList = $db->prepare("SELECT MD.*, SSD.currentStatus FROM ModelDetails MD 
        INNER JOIN UDNModelDetails UMD on UMD.udnCode = :udnCode 
        INNER JOIN SwitchStatusDetails SSD on SSD.udnNumber = :udn 
        AND MD.switchCode = SSD.switchCode 
        WHERE UMD.modelCode = MD.modelCode");
        $stmtModelDetailsList->execute(array(
            ':udnCode' => $udnCode,
            ':udn' => $udn
        ));

        $modelList = array();
        $modelDataCount=0;
        while($modelData =  $stmtModelDetailsList->fetch(PDO::FETCH_ASSOC)){
            $modelList[$modelDataCount] = $modelData;
            $modelDataCount++;
        }
        $udnData["modelData"] = $modelList;
        $udnList[$udnDataCount] = $udnData;
        $udnDataCount++;
    }
    
    $response["UDNData"] = $udnList;
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