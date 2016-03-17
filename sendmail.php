<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');

$log_date = date('Y-m-d', time());

if ( isset($_GET['cref']) ) {
	$cref = $_GET['cref'];
	$referer_raw = $_SERVER['HTTP_REFERER'];
	
	if ($referer_raw) {
	$referer_parse = parse_url($referer_raw);
	$referer = $referer_parse['scheme']."://".$referer_parse['host'].$referer_parse['path'];
		
		if ( is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$cref) ) {
			$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$cref.'/data.txt');
			$data = unserialize($raw_data);
			$unsigned_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log');
			$unsigned_logs = unserialize($unsigned_logs_raw);
			
			include_once($_SERVER['DOCUMENT_ROOT'].'/inc/send-client-email.php');
			
			if ( sendClientEmail() ) {
				
				foreach($unsigned_logs as $k => $log) {
					
					if ($log['ref'] == $cref) {
					$unsigned_logs[$k]['sent'] = time();
					$new_logs = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));	
					
						if ($new_logs) {
						header("Location: ". $referer ."?cref=".$cref."&sent=1");
						} 
					}	
				}	
				
				
			} else {
			header("Location: ". $referer ."?cref=".$cref."&sent=0");	
			}
		} else {
			header("Location: ". $referer ."?cref=".$cref."&sent=0");	
		}
	}

} else {
header("Location: ". SITEROOT ."/");	
}

?>