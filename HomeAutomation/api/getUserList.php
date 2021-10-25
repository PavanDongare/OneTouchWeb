<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


if(isset($_POST["accountNumber"])){

    $accountNumber = $_POST["accountNumber"];
    
    if(!empty($accountNumber)){

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
            $userObj["adminId"] = $accountObj["adminId"];
            $userDetailsList[$loop] = $userObj;
            $loop++;
        }
        $response["userList"] = $userDetailsList;
        echo json_encode($response);
        exit;

    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "Required parameters missing!";
        echo json_encode($response);
        exit;
    }
}

?>