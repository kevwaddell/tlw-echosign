<?php
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/inc/live_settings.log')) {
$settings_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/live_settings.log'); 
$settings = unserialize($settings_raw);

DEFINE('SOURCE_EMAIL', $settings['src_email']);
DEFINE('REPLY_EMAIL', $settings['reply_email']);
}	
?>