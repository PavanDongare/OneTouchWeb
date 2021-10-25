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

    $modelCode = $data->modelCode;
    $switchList = $data->switchList;
    
    if(!empty($modelCode) && !empty($switchList)){

        $rememberStatus = getRememberStatusObject();
        $deviceLock = getDeviceLockObject();

        array_push($switchList,$rememberStatus,$deviceLock);

        $successSwitchCodeList = array();
        $failedSwitchCodeList = array();
        $failedCount = 0;
        $successCount = 0;

        foreach($switchList as $switchDetails){
            $switchCode = $switchDetails->switchCode;
            $switchName = $switchDetails->switchName;
            $switchType = $switchDetails->switchType;
            $other = $switchDetails->other;

            $stmt = "SELECT * FROM ModelDetails WHERE modelCode = :modelCode AND switchCode = :switchCode";
            $stmtModels = $db->prepare($stmt);
            $stmtModels->execute(array(
                ':modelCode' => $modelCode,
                ':switchCode' => $switchCode
            ));
            $modelDetails = $stmtModels->fetch(PDO::FETCH_ASSOC);
            if($modelDetails){
                $failedSwitchCodeList[$failedCount] = $switchCode;
                $failedCount++;
            }else{
                $successSwitchCodeList[$successCount] = $switchCode;
                $successCount++;

                $stmt = 'INSERT INTO ModelDetails (modelCode, switchCode, switchName, switchType, other) 
                VALUES (:modelCode, :switchCode, :switchName, :switchType, :other)';
                $stmtModels = $db->prepare($stmt);
                $stmtModels->execute(array(
                    ':modelCode' => $modelCode,
                    ':switchCode' => $switchCode,
                    ':switchName' => $switchName,
                    ':switchType' => $switchType,
                    ':other' => $other
                ));

            }
        }

        $response["Error"] = FALSE;
        $response["successSwitchCodeList"] = $successSwitchCodeList;
        $response["failedSwitchCodeList"] = $failedSwitchCodeList;
        echo json_encode($response);
        exit;
        
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

function getRememberStatusObject(){
    $rememberStatus = array();
    $rememberStatus["switchCode"] = "REM_STATUS";
    $rememberStatus["switchName"] = "Remember Settings";
    $rememberStatus["switchType"] = "single";
    $rememberStatus["other"] = "-";
    $json = json_encode($rememberStatus);
    return json_decode($json);
}

function getDeviceLockObject(){
    $deviceLock = array();
    $deviceLock["switchCode"] = "LOCK_DEVICE";
    $deviceLock["switchName"] = "Lock Device";
    $deviceLock["switchType"] = "single";
    $deviceLock["other"] = "-";
    $json = json_encode($deviceLock);
    return json_decode($json);
}


?>