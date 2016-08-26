<?php
//set_time_limit(4000);
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');

// Connect to TLW imap
$imapPath = "{".TLW_SMTP_HOST.":143/imap/notls/novalidate-cert/user=esign}INBOX";
$username = TLW_SOURCE_EMAIL;
$password = TLW_SMTP_PWD;

$inbox = imap_open($imapPath, $username, $password, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'GSSAPI'));

//pre($inbox);

?>