<?php
$mail->SMTPOptions = array ('ssl' => array('verify_peer'  => false, 'verify_peer_name'  => false, 'allow_self_signed' => true));
$mail->SMTPAuth   = true;  
$mail->Host = TLW_SMTP_HOST;
$mail->Username = TLW_SMTP_USER;
$mail->Password = TLW_SMTP_PWD;                         
$mail->Port = TLW_SMTP_PORT; 	
?>