<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

$response = array("Error" => FALSE);

    if(isset($_POST["ProductID"])){
        $ProductID = $_POST["ProductID"];
        $device1 = $_POST["device1"];
        $device2 = $_POST["device2"];
        $device3 = $_POST["device3"];
        $device4 = $_POST["device4"];
        $device5 = $_POST["device5"];
        $SensorCode = $_POST["SensorCode"];

        $sql = 'UPDATE SensorMaster SET device1 = :device1, device2 = :device2
         ,device3 = :device3, device4 = :device4, device5 = :device5 
         where ProductID = :ProductID AND SensorCode = :SensorCode';
        //echo $sql;

        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            ':device1' => $device1,
            ':device2' => $device2,
            ':device3' => $device3,
            ':device4' => $device4,
            ':device5' => $device5,
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