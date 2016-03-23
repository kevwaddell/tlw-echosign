<?php 
function sendClientEmail() {
	global $data;
								
	$body = file_get_contents(SITEROOT.'/temps/client-email-notify.php?cref='.$data['ref']);
	
	$mail_$data['ref'] = new PHPMailer();
	
	//SMTP settings
	$mail_$data['ref']->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');		
	
	//Sending options	
	$mail_$data['ref']->AddReplyTo(TLW_REPLY_EMAIL, TLW_REPLY_NAME);
	$mail_$data['ref']->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	$mail_$data['ref']->AddAddress($data['email']);
	$mail_$data['ref']->Subject = "Your TLW Solicitors Client Agreement is ready to sign";
	$mail_$data['ref']->MsgHTML($body);
	
	return $mail_$data['ref']->Send();
}	
?>