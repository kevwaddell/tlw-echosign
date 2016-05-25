<?php
if ( isset($_GET['cref']) ) {
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-client-email.php');

//pre($_SERVER);

$log_date = date('Y-m-d', time());

	$cref = $_GET['cref'];
	$referer = false;	
	
	if ( isset($_SERVER['HTTP_REFERER']) ) {
	$referer_raw = $_SERVER['HTTP_REFERER'];	
	$referer_parse = parse_url($referer_raw);
	$referer = $referer_parse['scheme']."://".$referer_parse['host'].$referer_parse['path'];
	} else {
	$referer = SITEROOT."/notificationsent/";
	}
	
	//pre($referer);
	
	if ( is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$cref) ) {
		$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$cref.'/data.txt');
		$data = unserialize($raw_data);
		$unsigned_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log');
		$unsigned_logs = unserialize($unsigned_logs_raw);

		foreach($unsigned_logs as $k => $log) {
				
			if ($log['ref'] == $cref) {
			$unsigned_logs[$k]['sent'] = time();
			$data['sent'] = time();
			}	
		}	

		if ( sendClientEmail($data) ) {
			
			$new_logs = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));
			$new_data = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$cref.'/data.txt', serialize($data));
			
			if ($new_logs) {
			header("Location: ". $referer ."?cref=".$cref."&sent=1");
			} 
			
			
		} else {
			// Email not sent
			header("Location: ". $referer ."?cref=".$cref."&sent=0&error=email");	
		}
		
	} else {
		header("Location: ". $referer ."?cref=".$cref."&sent=0&error=nodir");	
	}

} else {
header("Location: ". SITEROOT ."/");	
}

?>