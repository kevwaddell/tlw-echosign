<?php 
function sendZipEmail($d) {
	$ref = $d['ref'];
	$tkn = $d['tkn'];
	
	$body = file_get_contents(SITEROOT.'/temps/it-email-zip.php?cref='.$ref);
	
	//SMTP settings
	$mail = new PHPMailer();
	$mail->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');   
	
	$mail->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	$mail->AddAddress(TLW_IT_EMAIL, TLW_IT_NAME);	
	$mail->addCC(TLW_WEBMASTER);
	$mail->Subject = "TLW Esign data cleanse for client ref: ".$ref;
	$mail->MsgHTML($body);
	$mail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/signed/".$tkn ."@". $ref .".zip");
	
	return $mail->Send();
}

?>