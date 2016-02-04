<?php
$mail->SMTPOptions = array ('ssl' => array('verify_peer'  => false, 'verify_peer_name'  => false, 'allow_self_signed' => true));
$mail->SMTPAuth   = true;  
if ($host == "tlw-echosign.dev") {
	$mail->Host = 'tlwserv02.tlwsolicitors.local';
} else {
	$mail->Host = 'nsgateway.tlwsolicitors.co.uk';
}
$mail->Username = 'esign';
$mail->Password = 'document5';                         
$mail->Port = 25; 	
?>