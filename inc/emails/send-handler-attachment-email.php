<?php 
function sendHandlerEmail() {
	global $data;
	global $dateTime;
	
	$body = file_get_contents(SITEROOT.'/temps/handler-email-attachment.php?cref='.$data['ref']);
	$mail = new PHPMailer();
	
	//SMTP settings
	$mail->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');         
	
	$mail->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	$mail->AddAddress($data['handler']);	
	$mail->Subject = "A document agreement has been signed on TLW Esign website";
	$mail->MsgHTML($body);
	$mail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/signed/".$data['ref']."/".$data['ref'].".pdf");
	//$send = true;
	
	return $mail->Send();
}

?>