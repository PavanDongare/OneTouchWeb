<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


if(isset($_POST['SwitchCode']) && isset($_POST['RemoteCode'])){

    $SwitchCode = $_POST['SwitchCode'];
    $RemoteCode = $_POST['RemoteCode'];
    
    $stmt = $db->prepare('SELECT NM.NodeAddress FROM SwitchMaster SM
        INNER JOIN NodeMaster NM on NM.NodeID = SM.NodeID
        WHERE SM.SwitchCode = :SwitchCode');

    $stmt->execute(array(':SwitchCode' => $SwitchCode));
    $NodeA = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty($NodeA)){

        passthru("python test1.py ".$SwitchCode." ".$RemoteCode." ".$NodeA["NodeAddress"]);
        $response["Message"] = "python test1.py ".$SwitchCode." ".$RemoteCode." ".$NodeA["NodeAddress"];   
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