<?php 
class Firebase {
 
    // sending push message to single user by firebase reg id
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }
 
    // Sending message to a topic by topic name
    public function sendToTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            // 'notification' => $message,
            'data' => $message
        );
        return $this->sendPushNotification($fields);
    }
 
    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message) {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
        );
 
        return $this->sendPushNotification($fields);
    }
 
    // function makes curl request to firebase servers
    private function sendPushNotification($fields) {
         
        // Set POST variables
        $FIREBASE_API_KEY = "AIzaSyDG58h4plhyIUHlmfDV08QE1_eCuoT1biA";
        $url = 'https://fcm.googleapis.com/fcm/send';
 
        $headers = array(
            'Authorization: key=' . $FIREBASE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
 
        return $result;
    }
}
/*class SendNotification {    
    private static $API_SERVER_KEY = 'AAAAJL4vets:APA91bFAf_7Ds-i0CqStEJfHc-nH83TYHehUBTGbLGt2Ztce0NyBKsnqeTw_7xMDv5yoJz0VcsNxbEQDp571DyKsnCvyUCpsc1Hzf0Gbx7nu-7K38HeYjRXo_IDujn96pU66h06Yib6jBH6d2vT70cxjcIyg5rjYyg';
    private static $is_background = "TRUE";
    public function __construct() {     
     
    }
    public function sendPushNotificationToFCMSever($token) {
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
 
        $fields = array(
            'to' => '/topic/'.$token,
            'notification' => array('status' => 'changed'),
        );
        $headers = array(
            'Authorization:key=' . self::$API_SERVER_KEY,
            'Content-Type:application/json'
        );  
         
        // Open connection  
        $ch = curl_init(); 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post   
        $result = curl_exec($ch); 
        // Close connection      
        curl_close($ch);
        return $result;
    }
}*/
?>