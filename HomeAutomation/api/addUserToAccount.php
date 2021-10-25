<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


// // Takes raw data from the request
$json = file_get_contents('php://input');
// // Converts it into a PHP object
$data = json_decode($json);

if(!empty($data)){

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

    if(isset($data->accountNumber)){
        $accountNumber = $data->accountNumber;
    }else{
        $accountNumber = "";
    }

    if(isset($data->adminId)){
        $adminId = $data->adminId;
    }else{
        $adminId = 0;
    }

    
    if(!empty($mobileNumber) && !empty($accountNumber)){


        $stmtUser = $db->prepare('SELECT * FROM UserMaster WHERE mobileNumber = :mobileNumber');
        $stmtUser->execute(array(
            ':mobileNumber' => $mobileNumber
        ));
        $userDataObj = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if(!$userDataObj){
            
            $stmt = $db->prepare('INSERT INTO UserMaster (mobileNumber, fullName, emailId ) 
            VALUES (:mobileNumber, :fullName, :emailId)');
            $stmt->execute(array(
                ':mobileNumber' => $mobileNumber,
                ':fullName' => $fullName,
                ':emailId' => $emailId
            ));
        }

        $stmtUser = $db->prepare('SELECT * FROM UserMaster WHERE mobileNumber = :mobileNumber');
        $stmtUser->execute(array(
            ':mobileNumber' => $mobileNumber
        ));
        $userDataObj = $stmtUser->fetch(PDO::FETCH_ASSOC);

        $userId = $userDataObj["userId"];
        //add user to account
        $stmtAccount = $db->prepare('SELECT * FROM AccountAccess WHERE userId = :userId AND accountNumber = :accountNumber');
        $stmtAccount->execute(array(
            ':userId' => $userId,
            ':accountNumber' => $accountNumber
        ));
        $accountAccessObj = $stmtAccount->fetch(PDO::FETCH_ASSOC);

        if(!$accountAccessObj){
            $stmt = $db->prepare('INSERT INTO AccountAccess (accountNumber, userId, isApproved, adminId ) 
            VALUES (:accountNumber, :userId,0,:adminId)');
            $stmt->execute(array(
                ':accountNumber' => $accountNumber,
                ':userId' => $userId,
                ':adminId' => $adminId
            ));
            $response["userDetails"] = $userDataObj;
            echo json_encode($response);
            exit;
        }

        

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

echo json_encode($response);
exit;
?>