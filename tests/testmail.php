<?php
include_once('inc/current_pg_function.php'); 
include_once('inc/pre-function.php');
include_once('classes/PHPMailer/PHPMailerAutoload.php');

$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

$mail->IsSMTP(); // telling the class to use SMTP

//pre($host);

try {
	if ($host == "tlw-echosign.dev") {
	$mail->Host = 'tlwserv02.tlwsolicitors.local';
	} else {
	$mail->Host = 'nsgateway.tlwsolicitors.co.uk';
	}
	$mail->SMTPOptions = array ('ssl' => array('verify_peer'  => false, 'verify_peer_name'  => false, 'allow_self_signed' => true));
  //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
  	$mail->SMTPAuth   = true;                  // enable SMTP authentication
  	$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
  	$mail->Username   = "esign"; // SMTP account username
  	$mail->Password   = "document5";        // SMTP account password
  
  $mail->AddReplyTo('info@tlwsolicitors.co.uk', 'TLW Solicitors');
  $mail->AddAddress('kevwaddell@mac.com', 'Kevin Waddell');
  $mail->SetFrom('esign@tlwsolicitors.co.uk', 'TLW Solicitors Esign');
  $mail->Subject = 'Mailer test with TLW server';
  $mail->AltBody = 'If this works. Wey hey!!!!'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML("<p>Yes baby it worked.</p>");
  $mail->Send();
  echo "Message Sent OK<p></p>\n";
  
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
    
?>