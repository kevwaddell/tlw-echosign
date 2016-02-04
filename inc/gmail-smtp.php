<?php
$mail->SMTPOptions = array ('ssl' => array('verify_peer'  => false, 'verify_peer_name'  => false, 'allow_self_signed' => true));
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'kwaddelltlw@gmail.com';
$mail->Password = 'TLW_kevin21';
$mail->SMTPSecure = 'tls';                           
$mail->Port = 587;    
?>