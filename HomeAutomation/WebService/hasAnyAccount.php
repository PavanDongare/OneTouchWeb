<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


    if(isset($_GET["userId"])){

        $userId = $_GET["userId"];
        
    
        $stmt = $db->prepare('SELECT * FROM AccountDetails WHERE adminId = :adminId');
        $stmt->execute(array(':adminId' => $userId));
        $dataObj = $stmt->fetch(PDO::FETCH_ASSOC);

        if($dataObj){
            
            $response["Status"] = "Exist";
            $response["adminAccountDetails"] = $dataObj;
            echo json_encode($response);
            exit;
        }

        $stmt = $db->prepare('SELECT * FROM AccountAccess WHERE userId = :userId and isApproved =1');
        $stmt->execute(array(':userId' => $userId));
        $userObj = $stmt->fetch(PDO::FETCH_ASSOC);
        if($userObj){
            
            $response["Status"] = "Exist";
            $response["userAccountDetails"] = $userObj;
            echo json_encode($response);
            exit;
        }
        
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "UserNotFound";
        echo json_encode($response);
        exit;
        
    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "Required parameters missing!";
        echo json_encode($response);
        exit;
    }

?>