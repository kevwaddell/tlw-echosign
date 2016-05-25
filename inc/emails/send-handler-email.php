<?php 
function sendHandlerEmail($data) {
	$d = $data;
								
	$body = file_get_contents(SITEROOT.'/temps/handler-email-notify.php?cref='.$d['ref']);
	
	$mail = new PHPMailer();
	
	//SMTP settings
	$mail->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');		
	
	//Sending options	
	$mail->AddReplyTo(TLW_REPLY_EMAIL, TLW_REPLY_NAME);
	$mail->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	$mail->AddAddress($data['handler']);
	$mail->Subject = "TLW Solicitors Client Agreement has not been signed";
	$mail->MsgHTML($body);
	
	return $mail->Send();
}	
?>