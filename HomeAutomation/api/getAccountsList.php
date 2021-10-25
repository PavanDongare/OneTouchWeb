<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


if(isset($_POST["userId"])){

    $userId = $_POST["userId"];
    
    if(!empty($userId)){

        $stmtUser = $db->prepare('SELECT * FROM AccountAccess WHERE userId = :userId');
        $stmtUser->execute(array(':userId' => $userId));
        
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