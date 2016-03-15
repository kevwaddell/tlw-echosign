<?php
if (SITEHOST == 'www.tlwsolicitors-esign.co.uk') {
$settings_log = "live_settings.log";
	} else {
$settings_log = "dev_settings.log";
}	

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$settings_log)) {
$settings_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$settings_log); 
$settings = unserialize($settings_raw);

DEFINE('SOURCE_EMAIL', $settings['src_email']);
DEFINE('REPLY_EMAIL', $settings['reply_email']);
DEFINE('IMPORT_EMAIL', $settings['import_email']);
}	
?>