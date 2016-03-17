<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-client-email.php');
$log_date = date('Y-m-d', time());

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log')) {
$unsigned_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log');
$unsigned_logs = unserialize($unsigned_logs_raw);	
	
	if (!empty($unsigned_logs)) {
	$now = time();
	//pre($unsigned_logs);
		
		foreach ($unsigned_logs as $k => $ul) {
		$datePlus2Days = strtotime("+2 days", $ul['sent']);
		
		//pre(gmdate('jS F, Y, g:ia', $ul['sent']));
		
			if ($datePlus2Days < $now) {
			$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$ul['ref'].'/data.txt');	
			$data = unserialize($raw_data);	
			$new_tkn = md5( uniqid(rand(), true) );
			$unsigned_logs[$k]['old_tkn'] = $raw_data['tkn'];
			$unsigned_logs[$k]['tkn'] = $new_tkn
			$data['tkn'] = $new_tkn;
			
				if ( sendClientEmail() ) {
				$unsigned_logs[$k]['sent'] = time();
				$new_logs = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));	
				$new_data = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$data['ref'].'/data.txt', serialize($data));	
				}
				
			} //if 2 days gone
		
		} // foreach unsigned log
			
	} else {
	exit;	
	}

} else {
exit;		
}
?>