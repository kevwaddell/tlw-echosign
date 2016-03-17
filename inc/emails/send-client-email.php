<?php 
function sendClientEmail() {
	
	global $scheme;
	global $host;
	global $data;
								
	$body = file_get_contents(SITEROOT.'/temps/client-email-notify.php?cref='.$data['ref']);
	
	$mail = new PHPMailer();
	
	//SMTP settings
	$mail->isSMTP();
	include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');		
	
	//Sending options	
	$mail->AddReplyTo("info@tlwsolicitors.co.uk","TLW Solicitors");
	$mail->SetFrom('esign@tlwsolicitors.co.uk', 'TLW Solicitors Esign');
	$mail->AddAddress($data['email']);
	$mail->Subject = "Your TLW Solicitors Client Agreement is ready to sign";
	$mail->MsgHTML($body);
	
	return $mail->Send();
	
}	
?>