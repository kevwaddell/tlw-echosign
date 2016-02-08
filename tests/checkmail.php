<?php
set_time_limit(4000);

include_once($_SERVER[DOCUMENT_ROOT].'/inc/pre-function.php');
include_once($_SERVER[DOCUMENT_ROOT].'/classes/PHPMailer/PHPMailerAutoload.php');

function curPageURL() {
 	$pageURL = 'http';
 	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 	$pageURL .= "://";
 	if ($_SERVER["SERVER_PORT"] != "80") {
 		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 	} else {
 		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 	}
 	
 	return $pageURL;
}
$url_array = parse_url(curPageURL());
$host = $url_array[host];
$scheme = $url_array[scheme]."://";

function getFileExtension($fileName){
   $parts=explode(".",$fileName);
   return $parts[count($parts)-1];
}

 
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

?>