<?php 
function sendZipEmail($d) {
	global $scheme;
	global $host;
	$ref = $d['ref'];
	$tkn = $d['tkn'];
	
	$body = file_get_contents(SITEROOT.'/temps/it-email-zip.php?cref='.$ref);
	
	//SMTP settings
	$mail = new PHPMailer();
	$mail->isSMTP();
	if ($host == 'tlw-esign.dev') {
	include_once('gmail-smtp.php');	
	} else {
	include_once('tlw-smtp.php');	
	}            
	
	$mail->SetFrom( "esign@tlwsolicitors.co.uk", "TLW Solicitors" );
	$address = "webmaster@tlwsolicitors.co.uk";
	$mail->AddAddress($address, "Webmaster");	
	$mail->Subject = "TLW Esign data cleanse client ref: ".$ref;
	$mail->MsgHTML($body);
	$mail->AddAttachment($tkn ."@". $ref .".zip");
	$send = $mail->Send();
	//$send = true;
	
	return $send;
}

?>