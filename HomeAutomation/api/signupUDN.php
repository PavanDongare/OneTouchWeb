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
            $udn = $i;
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

    $stmt = $db->prepare('SELECT UM.ssid,UM.wifiPassword,AD.accountNumber,AD.udn FROM UserMaster UM 
    INNER JOIN AccountDetails AD on AD.udn = :udn 
    INNER JOIN AccountAccess AA on AA.accountNumber = AD.accountNumber 
    WHERE AA.adminId = UM.userId');

    $stmt->execute(array(':udn' => $udn));
    $udnData = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty($udnData)){

        $ssid = $udnData["ssid"];
        $wifiPassword = $udnData["wifiPassword"];
        $accountNumber = $udnData["accountNumber"];

        // Status,A0001,admin,Aakrutii,7a5c3a4e28ns92,1883,\/bedroom\/light,no,
        $response = $udn.",Success,".$mode.",".$ssid.",".$wifiPassword.",".$port.",".$accountNumber.",no,";
        
    }else{
        // Status,A0001,admin,Aakrutii,7a5c3a4e28ns92,1883,\/bedroom\/light,no,
        $response = $udn.",Failure,".$mode.",-,-,".$port.",-,yes,";
    }
    echo $response;

}else{
    $response = "Required parameters missing!";
    echo $response;
}



?>