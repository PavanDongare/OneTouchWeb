<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

$response = array("Error" => FALSE);

    if(isset($_GET["ProductID"])){
        $ProductID = $_GET["ProductID"];

        $sql = 'SELECT * FROM SensorMaster where ProductID = :ProductID';
        //echo $sql;

        $stmt = $db->prepare($sql);
        $stmt->execute(array(':ProductID' => $ProductID));
        $record= 0;
        $Result = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $Result[$record] = $row;
            $record++;
        }
        //echo $row1[Count(*)];
        $number_of_rows = $stmt->fetchColumn(); 
        $response["Error"] = FALSE;
        $response["Status"] = "Success";
        $response["Sensors"] = $Result; 
        echo json_encode($response);
    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "Required parameters ProductID is missing!";
        echo json_encode($response);
    }
?>