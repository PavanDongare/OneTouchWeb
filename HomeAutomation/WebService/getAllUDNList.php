<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);

if(isset($_GET["userId"])){

    $userId = $_GET["userId"];

    $accountDetails = array();

    $stmt = $db->prepare('SELECT * FROM UserMaster WHERE userId = :userId');
    $stmt->execute(array(':userId' => $userId));
    $userObj = $stmt->fetch(PDO::FETCH_ASSOC);

    if($userObj){

        $accountNumber = $userObj["AccountNumber"];
        $accountDetails["accountNumber"] = $accountNumber;

        $stmtUDN = $db->prepare('SELECT * FROM AccountDetails WHERE accountNumber = :accountNumber and adminId = :adminId');
        $stmtUDN->execute(
            array(
                ':accountNumber' => $accountNumber,
                'adminId' => $userId
            ));
        
        $UDNList = array();
        $udnLoop = 0;
        while($UDNObj = $stmtUDN->fetch(PDO::FETCH_ASSOC)){
            
            $UDN = $UDNObj["UDN"];
            $stmt1 = $db->prepare('SELECT * FROM UDNMaster WHERE UDN = :UDN');
            $stmt1->execute( array(':UDN' => $UDN));
            $UDNDetailsObj = $stmt1->fetch(PDO::FETCH_ASSOC);
            $deviceModel = $UDNDetailsObj["deviceModel"];

            $stmt2 = $db->prepare('SELECT * FROM ModelDetails WHERE deviceModel = :deviceModel');
            $stmt2->execute( array(':deviceModel' => $deviceModel));
            $deviceModelDetailsList = array();
            $i = 0;
            while($deviceModelObj = $stmt2->fetch(PDO::FETCH_ASSOC)){
                $deviceModelDetailsList[$i] = $deviceModelObj;
                $i++;
            }
            $UDNDetailsObj["modelSwitches"] = $deviceModelDetailsList;
            $UDNList[$udnLoop] = $UDNDetailsObj;
            $udnLoop++;
        }

        $accountDetails["UDNList"] = $UDNList;

        $response["accountDetails"] = $accountDetails;
        echo json_encode($response);
        exit;

    }else{
        
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "AccountNotFound";
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