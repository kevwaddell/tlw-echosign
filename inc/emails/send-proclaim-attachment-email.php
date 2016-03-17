<?php 
function sendProclaimEmail() {
	global $data;
	
	$body = '<h1>File Attached</h1>';
	
	$mail = new PHPMailer();
	//SMTP settings
	$mail->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php')
	
	$mail->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	$mail->AddAddress(TLW_IMPORT_EMAIL);	
	$mail->Subject = $data['ref'];
	$mail->MsgHTML($body);
	$mail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/".$data['ref']."/".$data['ref'].".pdf");
	
	return $mail->Send();
}
?>