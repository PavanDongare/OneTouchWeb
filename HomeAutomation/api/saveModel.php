<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);

$json = file_get_contents('php://input');
$data = json_decode($json);

if(!empty($data)){

    $udnCode = $data->udnCode;
    $modelCode = $data->modelCode;
    $modelName = $data->modelName;
    $modelType = $data->modelType;
    $modelDetails = $data->modelDetails;
    
    
    if(!empty($udnCode) && !empty($modelCode) && !empty($modelName) 
        && !empty($modelType) && !empty($modelDetails)){

        $stmt = "SELECT * FROM UDNModelDetails WHERE udnCode = :udnCode";
        $stmtModels = $db->prepare($stmt);
        $stmtModels->execute(array(
            ':udnCode' => $udnCode
        ));
        $udnModelDetails = $stmtModels->fetch(PDO::FETCH_ASSOC);

        if($udnModelDetails){
            $response["Error"] = TRUE;
            $response["message"] = "already added";
            echo json_encode($response);
            exit;
        }else{

            $stmt = 'INSERT INTO UDNModelDetails (udnCode, modelCode, modelName, modelType, modelDetails) 
            VALUES (:udnCode, :modelCode, :modelName, :modelType, :modelDetails)';
            $stmtModels = $db->prepare($stmt);
            $stmtModels->execute(array(
                ':udnCode' => $udnCode,
                ':modelCode' => $modelCode,
                ':modelName' => $modelName,
                ':modelType' => $modelType,
                ':modelDetails' => $modelDetails
            ));
            $response["Error"] = FALSE;
            $response["Status"] = "Success";
            echo json_encode($response);
            exit;
        }
        
        
    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "data missing";
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