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

    $mobileNumber = $data->mobileNumber;
    $fullName = $data->fullName;
    $emailId = $data->emailId;
    if(isset($data->AccountNumber)){
        $accountNumber = $data->AccountNumber;
    }

    // $mobileNumber = $_POST["mobileNumber"];
    // $fullName = $_POST["fullName"];
    // $emailId = $_POST["emailId"];
    // if(isset($_POST["AccountNumber"])){
    //     $accountNumber = $_POST["AccountNumber"];
    // }
    
    
    if(!empty($mobileNumber)){

        $stmtUser = $db->prepare('SELECT * FROM UserMaster WHERE MobileNumber = :MobileNumber');
        $stmtUser->execute(array(':MobileNumber' => $mobileNumber));
        $userDataObj = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if($userDataObj){
            $stmt = $db->prepare('UPDATE UserMaster SET FullName = :FullName, EmailID = :EmailID WHERE MobileNumber = :MobileNumber');
            $stmt->execute(array(
                ':MobileNumber' => $mobileNumber,
                ':FullName' => $fullName,
                ':EmailID' => $emailId
            ));

        }else{
            if(empty($accountNumber)){
                $accountNumber = time();
            }
            
            $stmt = $db->prepare('INSERT INTO UserMaster (MobileNumber, FullName, EmailID, AccountNumber ) 
            VALUES (:MobileNumber, :FullName, :EmailID, :AccountNumber)');
            $stmt->execute(array(
                ':MobileNumber' => $mobileNumber,
                ':FullName' => $fullName,
                ':EmailID' => $emailId,
                ':AccountNumber' => $accountNumber
            ));
        }

        $stmtUser = $db->prepare('SELECT * FROM UserMaster WHERE MobileNumber = :MobileNumber');
        $stmtUser->execute(array(':MobileNumber' => $mobileNumber));
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