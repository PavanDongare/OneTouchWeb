<?php 
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('includes/config.php');
// json response array
$response = "";

if(isset($_GET['status'])){

    $Status = $_GET['status'];
    $StatusList = explode("-", $Status);
    $c = 0;
    foreach($StatusList as $i){
        if($c==0){
            $UDN = $i;
            $c++;
        }else{
            $requestType = $i;
        }
    }


    $id = 1;
    $stmtSystemInfo = $db->prepare('SELECT * FROM SystemInfo WHERE id = :id');
    $stmtSystemInfo->execute(array(':id' => $id));
    $systemInfoData = $stmtSystemInfo->fetch(PDO::FETCH_ASSOC);

    $mode = $systemInfoData["mode"];
    $port = $systemInfoData["port"];
   

    $stmt = $db->prepare('SELECT * FROM UDNMaster
                        WHERE UDN = :UDN');
    $stmt->execute(array(':UDN' => $UDN));
    $udnData = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty($udnData)){

        $UDNNumber = $udnData["UDN"];
        $adminId = $udnData["adminId"];
        
        
        $stmtAdmin = $db->prepare('SELECT * FROM AdminUserMaster
                        WHERE adminId = :adminId');
        $stmtAdmin->execute(array(':adminId' => $adminId));
        $adminData = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
        $userSSID = $adminData["SSID"];
        $userPassword = $adminData["Password"];
        $accountNumber = $adminData["adminMobileNumber"];

        // Status,A0001,admin,Aakrutii,7a5c3a4e28ns92,1883,\/bedroom\/light,no,
        $response = $UDNNumber.",Success,".$mode.",".$userSSID.",".$userPassword.",".$port.",".$accountNumber.",no,";
        
    }else{
        // Status,A0001,admin,Aakrutii,7a5c3a4e28ns92,1883,\/bedroom\/light,no,
        $response = $UDN.",Failure,".$mode.",-,-,".$port.",-,yes,";
    }
    echo $response;

}else{
    $response = "Required parameters missing!";
    echo $response;
}



?>