<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


if(isset($_POST["mobileNumber"])){

    $mobileNumber = $_POST["mobileNumber"];
    
    if(!empty($mobileNumber)){

        $stmtUser = $db->prepare('SELECT * FROM UserMaster WHERE mobileNumber = :mobileNumber');
        $stmtUser->execute(array(':mobileNumber' => $mobileNumber));
        $userDataObj = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if($userDataObj){

            $response["userDetails"] = $userDataObj;
            echo json_encode($response);
            exit;

        }else{
            $stmtUser = $db->prepare('INSERT INTO UserMaster( mobileNumber) VALUES ( :mobileNumber)');
            $stmtUser->execute(array(':mobileNumber' => $mobileNumber));
            $userDataObj = $stmtUser->fetch(PDO::FETCH_ASSOC);

            $stmtUser = $db->prepare('SELECT * FROM UserMaster WHERE mobileNumber = :mobileNumber');
            $stmtUser->execute(array(':mobileNumber' => $mobileNumber));
            $userDataObj = $stmtUser->fetch(PDO::FETCH_ASSOC);
            
            $response["userDetails"] = $userDataObj;
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