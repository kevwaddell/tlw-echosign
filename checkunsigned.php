<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
$log_date = date('Y-m-d', time());

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log')) {
$unsigned_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log');
$unsigned_logs = unserialize($unsigned_logs_raw);	
$sent = 0;

	if (!empty($unsigned_logs)) {
	$now = time();
	$sendTo = array();
		
		foreach ($unsigned_logs as $k => $ul) {
		$datePlus2Days = strtotime("+12 hours", $ul['sent']);
		
			if ($now > $datePlus2Days) {
			$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$ul['ref'].'/data.txt');	
			$data = unserialize($raw_data);	
			$sendTo[$k]['ref'] = $ul['ref'];
			$sendTo[$k]['handler'] = $ul['handler'];
			$sendTo[$k]['message']= file_get_contents(SITEROOT.'/temps/handler-email-notify.php?cref='.$ul['ref']);
			} //if 2 days gone
		
		} // foreach unsigned log
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: TLW Esign <webmaster@tlwsolicitors.co.uk>' . "\r\n";
		
		foreach($sendTo as $k => $s){
		//pre($s);
		$mail = mail($s['handler'], 'Testing - '. $s['ref'], $s['message'], $headers);
		
			if ($mail) {
			$sent = 1;
			pre($sent);
			}
	
		}
		
			
	} else {
	exit;	
	}

} else {
exit;		
}
?>