<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = array("Error" => FALSE);


if(isset($_GET['ProductCode'])){

    $ProductCode = $_GET['ProductCode'];

    $stmt = $db->prepare('SELECT ProductID,ProductCode,ProductName,AdminID FROM 
                            ProductMaster WHERE ProductCode = :ProductCode');
    $stmt->execute(array(':ProductCode' => $ProductCode));
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($product)){

        $response["Product"] = $product;
        $otp = rand(11111,99999);

        $stmt = $db->prepare('SELECT ProductID FROM 
                            ProductOTPTable WHERE ProductID = :ProductID');
        $stmt->execute(array(':ProductID' => $product["ProductID"]));
        $otpData = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($otpData)){
            $stmt = $db->prepare('UPDATE ProductOTPTable SET OTP=:OTP WHERE ProductID = :ProductID');
            $stmt->execute(array(':OTP' => $otp,':ProductID' => $product["ProductID"]));
       
        }else{
            $stmt = $db->prepare('INSERT INTO ProductOTPTable ( ProductID,OTP) VALUES (:ProductID ,:OTP)');
            $stmt->execute(array(':OTP' => $otp,':ProductID' => $product["ProductID"]));
       
        }
        $stmt = $db->prepare('SELECT EmailID,UserID,MobileNumber FROM 
                            UserMaster WHERE UserID = :AdminID');
        $stmt->execute(array(':AdminID' => $product["AdminID"]));
        $adminData = $stmt->fetch(PDO::FETCH_ASSOC);

        //send email
        $to = $adminData["EmailID"];
        $subject = "OTP Generation";
        $body = "<p>Thank you for Using our service.</p>
        <p>To activate your account, Please enter otp on our mobile app.</p>
        </br>
        <p>OTP for Product Code ".$product["ProductCode"]." is ".$otp."</p>
        <p>Regards</p> <br>Mithilesh Izardar</p>";

        $mailObj = $mail->getMailObj();
        $mailObj->setFrom(SITEEMAIL, 'Home Automation');                    // TCP port to connect to

        $mailObj->addAddress($to);
        $mailObj->subject($subject);
        $mailObj->body($body);
        $response["Product"]["isMailSent"] = TRUE;
        
        try {
            $mailObj->send();
        } catch (Exception $e) {
            $response["Product"]["isMailSent"] = FALSE;
        }

        $response["Product"]["Admin"] = $adminData;
        $response["Message"] = "OTP has been successfully sent to Admin Email ID.";
        echo json_encode($response);
		
    }else{

        
        $response["Error"] = TRUE;
        $response["Status"] = "Failed";
        $response["Message"] = "Product Not Available";
        echo json_encode($response);
    }

}else{
    $response["Error"] = TRUE;
    $response["Status"] = "Failed";
    $response["Message"] = "Required parameters ProductCode is missing!";
    echo json_encode($response);
}


?>