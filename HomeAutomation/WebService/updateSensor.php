<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/WebService/PushNotification.php');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/WebService/Push.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);

//SensorStatus=h01ss01-yes SensorStatus=h01ss01-no
if(isset($_GET['SensorStatus'])){

    $SensorStatus = $_GET['SensorStatus'];
    
    $StatusList = explode("-", $SensorStatus);
    $c = 0;
    foreach($StatusList as $i){
        if($c==0){
            $SensorCode = $i;
            $c++;
        }else{
            $Status = $i;
        }
    }

    $device =  array();
    $nodeIPArray = array();
    $stmt = $db->prepare('UPDATE SensorMaster SET SensorStatus = :SensorStatus WHERE SensorCode = :SensorCode');

    $stmt->execute(array(':SensorStatus' => $Status,':SensorCode' => $SensorCode));

    $stmt = $db->prepare('SELECT * FROM SensorMaster WHERE SensorCode = :SensorCode');
    $stmt->execute(array(':SensorCode' => $SensorCode));
    $SensorData = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty($SensorData)){

        $SensorCode = $SensorData["SensorCode"];
        $SensorStatus = $SensorData["SensorStatus"];
        $SensorCode = $SensorData["DeviceStatus"];
        $device[0] = $SensorData["device1"];
        $device[1] = $SensorData["device2"];
        $device[2] = $SensorData["device3"];
        $device[3] = $SensorData["device4"];
        $device[4] = $SensorData["device5"];
        $NodeAddress = $SensorData["NodeAddress"];

        for($index=0;$index<5;$index++){
            if($device[$index]!='null'){
                $stmt = $db->prepare('SELECT NM.NodeAddress FROM SwitchMaster SM
                Inner Join NodeMaster NM on NM.NodeID = SM.NodeID  
                WHERE SM.SwitchCode = :SwitchCode');
                $stmt->execute(array(':SwitchCode' => $device[$index]));
                $NodeAddressData = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!empty($NodeAddressData)){
                    $nodeIPArray[$index] = $NodeAddressData["NodeAddress"];    
                }else{
                    $nodeIPArray[$index] = 'null';
                }
            }else{
                $nodeIPArray[$index] = 'null';
            }
        }

     
        passthru("python test2.py ".$SensorCode." ".$SensorStatus." ".$device[0]." ".$device[1]." ".$device[2]." ".$device[3]." ".$device[4]." ".$nodeIPArray[0]." ".$nodeIPArray[1]." ".$nodeIPArray[2]." ".$nodeIPArray[3]." ".$nodeIPArray[4]);
         
        $response["Error"] = FALSE;
        $response["Status"] = "python test2.py ".$SensorCode." ".$SensorStatus." ".$device[0]." ".$device[1]." ".$device[2]." ".$device[3]." ".$device[4]." ".$nodeIPArray[0]." ".$nodeIPArray[1]." ".$nodeIPArray[2]." ".$nodeIPArray[3]." ".$nodeIPArray[4];
        $response["Message"] = "Status updated successfully.";
        
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