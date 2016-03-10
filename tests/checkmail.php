<?php
//set_time_limit(4000);
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
 
// Connect to gmail
/*
$imapPath = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
$username = "kwaddelltlw@gmail.com";
$password = "TLW_kevin21";
*/


// Connect to TLW imap
if ($host == "tlw-echosign.dev") {
	$imapPath = "{192.168.12.9:143/imap/notls/novalidate-cert/user=esign}INBOX";	
} else {
	$imapPath = "{nsgateway.tlwsolicitors.co.uk:143/imap/notls/novalidate-cert/user=esign}INBOX";	
}
$username = "esign@tlwsolicitors.co.uk";
$password = "document5";

$inbox = imap_open($imapPath, $username, $password, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'GSSAPI'));

//pre($inbox);

?>