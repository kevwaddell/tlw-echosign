<?php 
function sendITEmail() {
	global $data;
	global $dateTime;
	
	$body = file_get_contents(SITEROOT.'/temps/it-email-attachment.php?cref='.$data['ref']);
	$mail = new PHPMailer();
	
	//SMTP settings
	$mail->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');         
	
	$mail->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	$mail->AddAddress(TLW_IT_EMAIL, TLW_IT_NAME);	
	$mail->addCC(TLW_WEBMASTER);
	$mail->Subject = "A document agreement has been signed on TLW Esign website!!!";
	$mail->MsgHTML($body);
	$mail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/signed/".$data['ref']."/".$data['ref'].".pdf");
	//$send = true;
	
	return $mail->Send();
}

?>