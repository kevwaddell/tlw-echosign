<?php 
function sendClientEmail() {
	global $data;
								
	$body = file_get_contents(SITEROOT.'/temps/client-email-notify.php?cref='.$data['ref']);
	
	$mail = new PHPMailer();
	
	//SMTP settings
	$mail->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');		
	
	//Sending options	
	$mail->AddReplyTo(TLW_REPLY_EMAIL, TLW_REPLY_NAME);
	$mail->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	$mail->AddAddress($data['email']);
	$mail->Subject = "Your TLW Solicitors Client Agreement is ready to sign";
	$mail->MsgHTML($body);
	
	return $mail->Send();
}	
?>