<?php
ob_start();
session_start();

//set timezone
date_default_timezone_set('Asia/Calcutta');

//database credentials
define('DBHOST','localhost');
define('DBUSER','admin');
define('DBPASS','admin@123');
define('DBNAME','Automation');

//application address
define('DIR','http://localhost:8080/HomeAutomation/');
define('SITEEMAIL','aakrutiitechnology@gmail.com');

try {

	//create PDO connection
	$db = new PDO("mysql:host=".DBHOST.";charset=utf8mb4;dbname=".DBNAME, DBUSER, DBPASS);
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);//Suggested to uncomment on production websites
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Suggested to comment on production websites
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}
try{
//include the user class, pass in the database connection
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/classes/user.php');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/classes/mailclass.php');
$user = new User($db);
$mail = new MailClass();
}catch(Exception $e ){
    $error1 = $e->getMessage();
}

?>
