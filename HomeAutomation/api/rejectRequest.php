<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);
// Takes raw data from the request
$json = file_get_contents('php://input');
// Converts it into a PHP object
$data = json_decode($json);

if(!empty($data)){

    if(isset($data->userId)){
        $userId = $data->userId;
    }else{
        $userId = 0;
    }
    
    if(isset($data->accountNumber)){
        $accountNumber = $data->accountNumber;
    }else{
        $accountNumber = "";
    }

    if(!empty($accountNumber) && !empty($userId)){

        $stmtUser = $db->prepare('DELETE FROM AccountAccess WHERE userId = :userId AND accountNumber = :accountNumber');
        $stmtUser->execute(array(
            ':userId' => $userId,
            ':accountNumber' => $accountNumber
        ));

        $stmtUser = $db->prepare('SELECT * FROM AccountAccess WHERE userId = :userId ');
        $stmtUser->execute(array(
            ':userId' => $userId
        ));
        
        $accountAccessArray = array();
        $loop = 0;
        while($accountObj = $stmtUser->fetch(PDO::FETCH_ASSOC)){
            $accountAccessArray[$loop] = $accountObj;
            $loop++;
        }

        if(!empty($accountAccessArray)){

            $response["total"] = count($accountAccessArray);
            $response["accountAccess"] = $accountAccessArray;
            echo json_encode($response);
            exit;

        }else{    
            $response["total"] = 0;
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
}

?>