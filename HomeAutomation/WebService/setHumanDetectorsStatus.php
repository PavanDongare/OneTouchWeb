<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

$response = array("Error" => FALSE);

    if(isset($_POST["ProductID"])){
        $ProductID = $_POST["ProductID"];
        $DeviceStatus = $_POST["DeviceStatus"];
        $SensorCode = $_POST["SensorCode"];

        $sql = 'UPDATE SensorMaster SET DeviceStatus = :DeviceStatus 
         where ProductID = :ProductID AND SensorCode = :SensorCode';
        //echo $sql;

        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            ':DeviceStatus' => $DeviceStatus,
            ':ProductID' => $ProductID,
            ':SensorCode' => $SensorCode
        ));
        
        //echo $row1[Count(*)];
        //$number_of_rows = $stmt->fetchColumn(); 
        $response["Error"] = FALSE;
        $response["Status"] = "Success";
        echo json_encode($response);
    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "Required parameters ProductID is missing!";
        echo json_encode($response);
    }
?>