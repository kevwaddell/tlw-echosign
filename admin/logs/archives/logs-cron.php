<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');

$log_date = date('Y-m-d', time());

pre($log_date);

$email_log_files = glob($_SERVER['DOCUMENT_ROOT'] . "/admin/logs/email-logs-*.log");
$sent_log_files = glob($_SERVER['DOCUMENT_ROOT'] . "/admin/logs/sent-data-*.log");
$unsent_log_files = glob($_SERVER['DOCUMENT_ROOT'] . "/admin/logs/unsigned-*.log");

if (!empty($email_log_files)) {
$email_dates = array();
	foreach($log_files as $file) {
	$e_date = substr($file , -14, 10);
	
		if (!in_array($e_date, $email_dates)) {
		$email_dates[] = $e_date;	
		}
	}	
rsort($email_dates);	 
//pre($dates);
}

?>