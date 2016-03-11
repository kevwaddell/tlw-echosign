<?php 
function sendClientEmail() {
	
	global $scheme;
	global $host;
	global $data;
	
	$site_root = $scheme . $host;
								
	$body = file_get_contents($site_root.'/temps/client-email-notify.php?cref='.$data['ref']);
	
	pre($body);
	
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