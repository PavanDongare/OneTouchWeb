<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/HomeAutomation/classes/phpmailer/mail.php');
class MailClass {


    function __construct(){}

	public function getMailObj(){

		try {
            $mail = new Mail();
            $mail->SMTPDebug = false;        // Enable verbose debug output
            $mail->isSMTP();             // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';    // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;      // Enable SMTP authentication
            $mail->Username = 'aakrutiitechnology@gmail.com';                 // SMTP username
            $mail->Password = 'annand10';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;    

			return $mail;

		} catch(Exception $e) {
		   // echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
}
?>
