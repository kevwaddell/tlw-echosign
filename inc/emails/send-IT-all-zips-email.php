<?php 
function sendAllZipsEmail($zips) {
	
	$body = file_get_contents(SITEROOT.'/temps/it-email-all-zips.php');
	
	//SMTP settings
	$mail = new PHPMailer();
	$mail->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');   
	
	$mail->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	$mail->AddAddress(TLW_IT_EMAIL, TLW_IT_NAME);	
	$mail->Subject = "TLW Esign data cleanse client ref: ".$ref;
	$mail->MsgHTML($body);
	foreach($zips as $z){
	$mail->AddAttachment($z);	
	}
	
	return $mail->Send();
}

?>