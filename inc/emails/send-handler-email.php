<?php 
function sendHandlerEmail($data, $key) {
	$d = $data;
								
	$body = file_get_contents(SITEROOT.'/temps/handler-email-notify.php?cref='.$d['ref']);
	
	$key = new PHPMailer();
	
	//SMTP settings
	$key->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');		
	
	//Sending options	
	$key->AddReplyTo(TLW_REPLY_EMAIL, TLW_REPLY_NAME);
	$key->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	$key->AddAddress($data['handler']);
	$key->Subject = "TLW Solicitors Client Agreement has not been signed";
	$key->MsgHTML($body);
	
	return $key->Send();
}	
?>