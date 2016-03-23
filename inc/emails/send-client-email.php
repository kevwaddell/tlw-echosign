<?php 
function sendClientEmail($data) {
	$d = $data;
								
	$body = file_get_contents(SITEROOT.'/temps/client-email-notify.php?cref='.$d['ref']);
	
	$mail_.$d['ref'] = new PHPMailer();
	
	//SMTP settings
	$mail_.$d['ref']->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');		
	
	//Sending options	
	$mail_.$d['ref']->AddReplyTo(TLW_REPLY_EMAIL, TLW_REPLY_NAME);
	$mail_.$d['ref']->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	$mail_.$d['ref']->AddAddress($data['email']);
	$mail_.$d['ref']->Subject = "Your TLW Solicitors Client Agreement is ready to sign";
	$mail_.$d['ref']->MsgHTML($body);
	
	pre($mail_.$d['ref']);
	
	return $mail_.$d['ref']->Send();
}	
?>