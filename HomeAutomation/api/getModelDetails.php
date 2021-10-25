<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
    $response = array("Error" => FALSE);
   
if(isset($_POST["udnNumber"])){

    $udnNumber = $_POST["udnNumber"];
    $roomName = $_POST["roomName"];
    
    if(!empty($udnNumber)){

        $udnCode = substr($udnNumber, 0, 2);
    
        $stmt = "SELECT * FROM UDNModelDetails WHERE udnCode = :udnCode";
        $stmtUsers = $db->prepare($stmt);
        $stmtUsers->execute(array(':udnCode' => $udnCode));

        $modelDetails = $stmtUsers->fetch(PDO::FETCH_ASSOC);
        if($modelDetails){
            $modelDetails["udnNumber"] = $udnNumber;
            $modelDetails["roomName"] = $roomName;
            $response["deviceModelDetails"] = $modelDetails;
        }else{
            $response["Error"] = TRUE;
            $response["Status"] = "Failed";
            $response["Message"] = "UDN Code not found";
        }
       
        echo json_encode($response);
        exit;

    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "Required parameters missing!";
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