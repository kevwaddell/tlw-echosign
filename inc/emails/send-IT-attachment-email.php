<?php 
function sendITEmail() {
	global $scheme;
	global $host;
	global $data;
	global $dateTime;
	
	$body = file_get_contents(SITEROOT.'/temps/it-email-attachment.php?cref='.$data['ref']);
	
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
	$mail->Subject = "A document agreement has been signed on TLW Esign website!!!";
	$mail->MsgHTML($body);
	$mail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/signed/".$data['ref']."/".$data['ref'].".pdf");
	//$send = true;
	
	return $mail->Send();
}

?>