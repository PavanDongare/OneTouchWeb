<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


if(isset($_POST["query"])){

    $query = $_POST["query"];
    
    if(!empty($query)){
        $query = "%".$query."%";

        $stm = "SELECT * FROM UserMaster WHERE mobileNumber LIKE :mobileNumber";
        $stmtUsers = $db->prepare($stm);
        $stmtUsers->execute(array(':mobileNumber' => $query));

        $userDetailsList = array();
        $loop = 0;
        // $userObj = $stmtUsers->fetch(PDO::FETCH_ASSOC);
        // echo json_encode($userObj);

        while($userObj = $stmtUsers->fetch(PDO::FETCH_ASSOC)){
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