<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/WebService/PushNotification.php');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/WebService/Push.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


if(isset($_GET['status'])){

    $Status = $_GET['status'];
    $StatusList = explode("-", $Status);
    $c = 0;
    foreach($StatusList as $i){
        if($c==0){
            $SwitchCode = $i;
            $c++;
        }else{
            $SwitchStatus = $i;
        }
    }

    $stmt = $db->prepare('SELECT NM.NodeAddress FROM SwitchMaster SM
                        INNER JOIN NodeMaster NM on NM.NodeID = SM.NodeID
                        WHERE SM.SwitchCode = :SwitchCode');
    $stmt->execute(array(':SwitchCode' => $SwitchCode));
    $NodeA = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty($NodeA)){

        $stmt = $db->prepare('UPDATE SwitchMaster SET SwitchStatus = :SwitchStatus WHERE SwitchCode = :SwitchCode');
        $stmt->execute(array(':SwitchStatus' => $SwitchStatus,':SwitchCode' => $SwitchCode));

        //passthru("python test.py ".$Status." ".$NodeA["NodeAddress"]);
         

        $response["Error"] = FALSE;
        $response["Status"] = "Success- ".$NodeA["NodeAddress"];
        $response["Message"] = "Status updated successfully.";
        
        $sql = 'SELECT SM.SwitchID,SM.SwitchCode,SM.SwitchName,SM.SwitchType,SM.SwitchStatus,NM.NodeID,NM.NodeCode,PM.ProductID,PM.ProductCode,PM.ProductName FROM SwitchMaster SM
        INNER JOIN NodeMaster NM on NM.NodeID = SM.NodeID
        INNER JOIN ProductMaster PM on PM.ProductID = NM.ProductID
        where SM.SwitchCode = :SwitchCode';
        //echo $sql;

        $stmt = $db->prepare($sql);
        $stmt->execute(array(':SwitchCode' => $SwitchCode));
        $ProductCode= "";
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $response["Switch"] = $row;
            $ProductCode = $row["ProductCode"];
        }

        /*$serverObject = new SendNotification(); 
        $jsonString = $serverObject->sendPushNotificationToFCMSever( $ProductCode);  
        $jsonObject = json_decode($jsonString);
//return $jsonObject;
        $response["jsonObject"] = $jsonObject; */

    
        $firebase = new Firebase();
        $push = new Push();
        $push->setTitle($Status);
        $push->setMessage("Success");
        $push->setIsBackground(TRUE);
        $json = $push->getPush();
        $responseData = $firebase->sendToTopic($ProductCode,$json);
        $response["responseData"] = $responseData;
    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "Node not available.";    
    }
    


    echo json_encode($response);

}else{
    $response["Error"] = TRUE;
    $response["Status"] = "Failed";
    $response["Message"] = "Required parameters ProductCode is missing!";
    echo json_encode($response);
}



?>