<?php
function encryptIt( $q ) {
$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
$qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
return( $qEncoded );
}

function decryptIt( $q ) {
$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
$qDecoded  = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
return( $qDecoded );
}
	
if (SITEHOST == 'www.tlwsolicitors-esign.co.uk') {
$settings_log = "live_settings.log";
	} else {
$settings_log = "dev_settings.log";
}	

if (SITEHOST == 'tlw-echosign.dev') {
$smtp_log = "smtp_local_settings.log";
	} else {
$smtp_log = "smtp_online_settings.log";
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$settings_log)) {
$settings_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$settings_log); 
	
	if (!empty($settings_raw)) {
	$settings = unserialize($settings_raw);
	
	//Global Email address settings
	DEFINE('TLW_SOURCE_EMAIL', $settings['src_email']);
	DEFINE('TLW_REPLY_EMAIL', $settings['reply_email']);
	DEFINE('TLW_IMPORT_EMAIL', $settings['import_email']);
	DEFINE('TLW_IT_EMAIL', $settings['it_admin_email']);
	}
}	

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$smtp_log)) {
$smtp_settings_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$smtp_log); 
	
	if (!empty($smtp_settings_raw)) {
	$smtp_settings = unserialize($smtp_settings_raw);
	//Global SMTP settings
	DEFINE('TLW_SMTP_HOST', $smtp_settings['smtp_host']);
	DEFINE('TLW_SMTP_POST', $smtp_settings['smtp_port']);
	DEFINE('TLW_SMTP_USER', $smtp_settings['smtp_user']);
	DEFINE('TLW_SMTP_PWD', decryptIt($smtp_settings['smtp_pwd']));
	}
}
?>