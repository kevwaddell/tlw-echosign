<?php 
	
function sendProclaimEmail() {
	global $host;
	global $data;
	
	$body = '<h1>File Attached</h1>';
	
	$mail = new PHPMailer();
	//SMTP settings
	$mail->isSMTP();
	if ($host == 'tlw-esign.dev') {
	include_once('gmail-smtp.php');	
	} else {
	include_once('tlw-smtp.php');	
	}	
	
	$mail->SetFrom( "esign@tlwsolicitors.co.uk", "TLW Solicitors" );
	$mail->AddAddress("webmaster@tlwsolicitors.co.uk", "Webmaster");	
	$mail->Subject = $data['ref'];
	$mail->MsgHTML($body);
	$mail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/".$data['ref']."/".$data['ref'].".pdf");
	
	return $mail->Send();
}

?>