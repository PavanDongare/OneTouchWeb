<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);

$userId = $_POST["userId"];

if(isset($_POST["userId"])){

    $stmt = $db->prepare('SELECT * FROM AccountAccess WHERE userId = :userId AND isApproved = 1');
    $stmt->execute(array(':userId' => $userId));
    $dataObj = $stmt->fetch(PDO::FETCH_ASSOC);

    if($dataObj){

        $accountNumber = $dataObj["accountNumber"];
        
        $stmt = $db->prepare('SELECT U.*,AD.* FROM AccountDetails AD
        INNER JOIN UDNMaster U on U.UDN = AD.UDN
        WHERE AD.accountNumber = :accountNumber');
        $stmt->execute(array(
            ':accountNumber' => $accountNumber
        ));
        $udnArray = array();
        $i=0;
        while($dataObj2 = $stmt->fetch(PDO::FETCH_ASSOC)){
            $udnArray[$i] = $dataObj2;
            $i++;
        }
        $response["UDNList"] =  $udnArray;
        echo json_encode($response);
        exit;
        // $milliseconds = round(microtime(true) * 1000);


    }else{
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "No account number attached with this number";
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