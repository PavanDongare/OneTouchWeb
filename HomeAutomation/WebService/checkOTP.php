<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


if(isset($_POST['ProductID'])&& isset($_POST['OTP'])){

    $ProductID = $_POST['ProductID'];
    $OTP = $_POST['OTP'];

    $stmt = $db->prepare('SELECT ProductID,OTP  FROM 
                            ProductOTPTable WHERE ProductID = :ProductID AND OTP = :OTP');
    $stmt->execute(array(':ProductID' => $ProductID,':OTP' => $OTP));
    $OTPData = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($OTPData)){
        $stmt = $db->prepare('DELETE FROM ProductOTPTable WHERE ProductID = :ProductID');
        $stmt->execute(array(':ProductID' => $ProductID));

        $stmt = $db->prepare('SELECT ProductID,ProductCode,ProductName,AdminID FROM 
                            ProductMaster WHERE ProductID = :ProductID');
        $stmt->execute(array(':ProductID' => $ProductID));
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $response["Product"] = $product;
        $stmt = $db->prepare('SELECT EmailID,UserID,MobileNumber FROM 
                            UserMaster WHERE UserID = :AdminID');
        $stmt->execute(array(':AdminID' => $product["AdminID"]));
        $adminData = $stmt->fetch(PDO::FETCH_ASSOC);
        $response["Product"]["Admin"] = $adminData;
        $response["Message"] = "OTP has been successfully verified";
        echo json_encode($response);
		
    }else{

        
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "Incorrect OTP";
        echo json_encode($response);
    }

}else{
    $response["Error"] = TRUE;
    $response["Status"] = "Failed";
    $response["Message"] = "Required parameters ProductID OR OTP is missing!";
    echo json_encode($response);
}


?>