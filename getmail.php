<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');

function getFileExtension($fileName){
   $parts = explode(".",$fileName);
   return $parts[count($parts)-1];
}

if ($host == "tlw-echosign.dev") {
	$imapPath = "{192.168.12.9:143/imap4/notls/novalidate-cert/user=esign}";	
} else {
	$imapPath = "{nsgateway.tlwsolicitors.co.uk:143/imap/notls/novalidate-cert/user=esign}INBOX";
}
$username = "esign@tlwsolicitors.co.uk";
$password = "document5";

$inbox = imap_open($imapPath, $username, $password, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'GSSAPI'));

pre($inbox);

imap_close($inbox);


?>