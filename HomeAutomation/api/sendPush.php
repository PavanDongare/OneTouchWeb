<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/classes/Push.php');
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/classes/PushNotification.php');

class FireBaseUtil{

    private $firebase;
    private $push;
    function __construct(){
        $this->firebase = new Firebase();
        $this->push = new Push();
    }


    public function sendPush($topic, $message){
        $this->push->setTitle("UPDATE_SWITCH");
        $this->push->setMessage($message);
        $this->push->setIsBackground(TRUE);
        $this->json = $this->push->getPush();
        $this->firebase->sendToTopic($topic,$message);
    }

   


}
?>