<?php 
function sendClientEmail() {
	
	global $scheme;
	global $host;
	global $data;
								
	$body = file_get_contents($scheme.$host.'/temps/client-email-notify.php?cref='.$data['ref']);
	
	$mail = new PHPMailer();
	
	//SMTP settings
	$mail->isSMTP();
	if ($host == 'tlw-esign.dev') {
	include_once('gmail-smtp.php');	
	} else {
	include_once('tlw-smtp.php');	
	}	
	
	//Sending options	
	$mail->AddReplyTo("info@tlwsolicitors.co.uk","TLW Solicitors");
	$mail->SetFrom('esign@tlwsolicitors.co.uk', 'TLW Solicitors');
	$address = $data['email'];
	$mail->AddAddress($address);
	$mail->Subject = "Your TLW Solicitors Client Agreement is ready to sign";
	$mail->MsgHTML($body);
	$send = $mail->Send();
	
	return $send;
	
}	
?>