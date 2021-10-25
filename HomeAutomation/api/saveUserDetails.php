<?php 
header("Access-Control-Allow-Origin: *");
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
//echo $json;

if(!empty($data)){

    if(isset($data->userId)){
        $userId = $data->userId;
    }else{
        $userId = "";
    }
    if(isset($data->mobileNumber)){
        $mobileNumber = $data->mobileNumber;
    }else{
        $mobileNumber = "";
    }
    if(isset($data->fullName)){
        $fullName = $data->fullName;
    }else{
        $fullName ="";
    }

    if(isset($data->emailId)){
        $emailId = $data->emailId;
    }else{
        $emailId = "";
    }
    if(isset($data->ssid)){
        $ssid = $data->ssid;
    }else{
        $ssid ="";
    }
    if(isset($data->wifiPassword)){
        $wifiPassword = $data->wifiPassword;
    }else{
        $wifiPassword="";
    }
    
    if(!empty($mobileNumber) || !empty($userId)){

        $stmtUser = $db->prepare('SELECT * FROM UserMaster WHERE mobileNumber = :mobileNumber OR userId = :userId');
        $stmtUser->execute(array(
            ':mobileNumber' => $mobileNumber,
            ':userId' => $userId
        ));
        $userDataObj = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if($userDataObj){
            $stmt = $db->prepare('UPDATE UserMaster SET fullName = :fullName, emailId = :emailId, ssid = :ssid, wifiPassword = :wifiPassword WHERE mobileNumber = :mobileNumber OR userId = :userId');
            $stmt->execute(array(
                ':userId' => $userId,
                ':mobileNumber' => $mobileNumber,
                ':fullName' => $fullName,
                ':emailId' => $emailId,
                ':ssid' => $ssid,
                ':wifiPassword' => $wifiPassword
            ));

        }else{
            
            $stmt = $db->prepare('INSERT INTO UserMaster (mobileNumber, fullName, emailId ) 
            VALUES (:mobileNumber, :fullName, :emailId)');
            $stmt->execute(array(
                ':mobileNumber' => $mobileNumber,
                ':fullName' => $fullName,
                ':emailId' => $emailId
            ));
        }

        $stmtUser = $db->prepare('SELECT * FROM UserMaster WHERE mobileNumber = :mobileNumber OR userId = :userId');
        $stmtUser->execute(array(
            ':mobileNumber' => $mobileNumber,
            ':userId' => $userId
        ));
        $userDataObj = $stmtUser->fetch(PDO::FETCH_ASSOC);
        

        $response["userDetails"] = $userDataObj;
        echo json_encode($response);
        exit;

    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "mobile missing";
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