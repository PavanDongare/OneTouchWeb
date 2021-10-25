<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

$response = array("Error" => FALSE);

    if(isset($_GET["ProductID"])){
        $ProductID = $_GET["ProductID"];

        $sql = 'SELECT SM.SwitchID,SM.SwitchCode,SM.SwitchName,SM.SwitchType,SM.SwitchStatus,
        NM.NodeID,NM.NodeCode,PM.ProductID,PM.ProductCode,PM.ProductName
        FROM SwitchMaster SM
        INNER JOIN NodeMaster NM on NM.NodeID = SM.NodeID
        INNER JOIN ProductMaster PM on PM.ProductID = NM.ProductID
        where PM.ProductID = :ProductID';
        //echo $sql;

        $stmt = $db->prepare($sql);
        $stmt->execute(array(':ProductID' => $ProductID));
        $record= 0;
        $Result = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $Result[$record] = $row;
            if($row["SwitchType"] == "Remote"){
                $sql1 = 'SELECT RemoteID,SwitchCode,RemoteButtonCode,ButtonType,RemoteType 
                FROM RemoteDetails
                where SwitchCode = :SwitchCode';

                $stmt1 = $db->prepare($sql1);
                $stmt1->execute(array(':SwitchCode' => $row["SwitchCode"]));
                $record1= 0;
                $Result1 = array();
                $remoteType;
                while($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
                    $Result1[$record1] = $row1;
                    $remoteType = $row1["RemoteType"];
                    $record1++;
                }
                $Result[$record]["Remote"] = $Result1;
                $Result[$record]["RemoteType"] = $remoteType;
            }
            
            $record++;
        }
        //echo $row1[Count(*)];
        $number_of_rows = $stmt->fetchColumn(); 
        $response["Error"] = FALSE;
        $response["Status"] = "Success";
        $response["Switches"] = $Result; 
        echo json_encode($response);
    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "Required parameters ProductID is missing!";
        echo json_encode($response);
    }
?>