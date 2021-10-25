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

    if(isset($data->userId)){
        $userId = $data->userId;
    }else{
        $userId = "";
    }
    
    if(isset($data->accountNumber)){
        $accountNumber = $data->accountNumber;
    }else{
        $accountNumber = "";
    }
    
    if(!empty($userId) && !empty($accountNumber)){

        $stmtAccount = $db->prepare('SELECT * FROM AccountAccess WHERE userId = :userId AND accountNumber = :accountNumber');
        $stmtAccount->execute(array(
            ':userId' => $userId,
            ':accountNumber' => $accountNumber
        ));
        $accountAccessObj = $stmtAccount->fetch(PDO::FETCH_ASSOC);

        if($accountAccessObj){
            $stmt = $db->prepare('DELETE FROM AccountAccess WHERE accountNumber = :accountNumber AND userId = :userId');
            $stmt->execute(array(
                ':accountNumber' => $accountNumber,
                ':userId' => $userId
            ));
           
        }

        $stmtAccount = $db->prepare('SELECT * FROM AccountAccess WHERE accountNumber = :accountNumber');
        $stmtAccount->execute(array(':accountNumber' => $accountNumber));

        $userDetailsList = array();
        $loop = 0;

        while($accountObj = $stmtAccount->fetch(PDO::FETCH_ASSOC)){
            $userId = $accountObj["userId"];
            $stmtUser = $db->prepare('SELECT * FROM UserMaster WHERE userId = :userId');
            $stmtUser->execute(array(':userId' => $userId));
            $userObj = $stmtUser->fetch(PDO::FETCH_ASSOC);
            $userObj["isApproved"] = $accountObj["isApproved"] == 1;
            $userDetailsList[$loop] = $userObj;
            $loop++;
        }
        $response["userList"] = $userDetailsList;
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