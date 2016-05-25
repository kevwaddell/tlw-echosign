<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
$log_date = date('Y-m-d', time());

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log')) {
$unsigned_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log');
$unsigned_logs = unserialize($unsigned_logs_raw);	
	
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
			} //if 2 days gone
		
		} // foreach unsigned log
		
		$mail = new PHPMailer();
	
		//SMTP settings
		$mail->isSMTP();
		include_once($_SERVER['DOCUMENT_ROOT'].'/inc/tlw-smtp.php');		
		
		//Sending options	
		//$mail->SMTPDebug = 2;
		$mail->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
		$mail->Subject = "TLW Solicitors Client Agreement has not been signed - ".rand()." - Testing";
		
		foreach($sendTo as $s){
			$body = file_get_contents(SITEROOT.'/temps/handler-email-notify.php?cref='.$s['ref']);
			$mail->AddAddress($s['handler']);	
			$mail->MsgHTML($body);
			if ($mail->Send()) {
			echo "Mail Sent for ref". $s['ref'] ."</br>";
			} else {
			echo "Mail was not sent for". $s['ref']."</br>";	
			}
			$mail->clearAddresses();
			$mail->clearAttachments();
		}
			
	} else {
	exit;	
	}

} else {
exit;		
}
?>