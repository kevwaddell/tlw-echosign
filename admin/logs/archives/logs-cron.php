<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');

function search_array($needle, $haystack) {
     if(in_array($needle, $haystack)) {
          return true;
     }
     foreach($haystack as $element) {
          if(is_array($element) && search_array($needle, $element))
               return true;
     }
   return false;
}

$today = time();
$end_last_week = strtotime('last Sunday');
$start_last_week = strtotime('last week');

$unsent_log_files = glob($_SERVER['DOCUMENT_ROOT'] . "/admin/logs/archives/unsent-logs-archive/unsigned-*.log");

//pre($email_log_files);

function checkEmailLogs(){
$email_log_files = glob($_SERVER['DOCUMENT_ROOT'] . "/admin/logs/archives/email-logs-archive/email-logs-*.log");
	
	if (!empty($email_log_files)) {
	$emails = array();
	$zip_logs = array();
		
		foreach($email_log_files as $file) {
		$e_date = substr($file , -14, 10);
		
			if (!search_array($e_date, $emails)) {
			$emails[] = array('date' => $e_date, 'file' => $file);	
			}
		}	
		
		if (date('N', $today) == 3 && !empty($emails)) {
	
			foreach($emails as $em){
			
				$date_raw = strtotime($em['date']);
				
				if ($date_raw >= $start_last_week && $date_raw <= $end_last_week) {
				$file_name = end( split('/', $em['file']) );
				
				if (!file_exists($em['file'].".csv")) {
				$csv = fopen($em['file'].".csv", 'w');
				fputcsv($csv, array("Date checked","Num messages","Unread emails","Deleted emails"));
				$raw_log_data = file_get_contents($em['file']);	
					
					if ($raw_log_data != "") {
						
						$log_data = unserialize($raw_log_data);
					
						foreach($log_data as $ld){
							$ld['check-date'] = date('D jS M, Y', $ld['check-date']);
							fputcsv($csv, $ld);
						}
					
						fclose($csv);
					
						$zip_logs[] = array($em['file'].'.csv', $file_name.'.csv');
						unlink($em['file']);
						//pre("Add File");
						
					} else {
					
						unlink($em['file']);
						//pre("Remove File");
						
					}
				}
				
				//pre( date('Y-m-d', $date_raw) );	
				
				}	
	
			}
			
			if ( !empty($zip_logs) ) {
				
					$emails_zip = new ZipArchive();
					
					$emails_zip->open("email-logs-archive/email-logs-".date('Y-m-d', $start_last_week)."*-*".date('Y-m-d', $end_last_week).".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
						
							foreach ($zip_logs as $zl){
							$emails_zip->addFile($zl[0], $zl[1]);
							}
							
					
					if ( $emails_zip->close() ) {
						foreach ($zip_logs as $zl){
						unlink($zl[0]);
						}
						
						
					}	
			}
		}
	}
}

function checkSentLogs(){
$sent_log_files = glob($_SERVER['DOCUMENT_ROOT'] . "/admin/logs/archives/sent-logs-archive/sent-data-*.log");
	
	if (!empty($sent_log_files)) {
	$sent_logs = array();
	$zip_logs = array();
		
		foreach($sent_log_files as $file) {
		$sl_date = substr($file , -14, 10);
		
			if (!search_array($sl_date, $emails)) {
			$sent_logs[] = array('date' => $sl_date, 'file' => $file);	
			}
		}	
		
		if (date('N', $today) == 1 && !empty($sent_logs)) {
			//UP TO HERE !!!!!!!!!!!!!!!!!!!
			foreach($sent_logs as $em){
			
				$date_raw = strtotime($em['date']);
				
				if ($date_raw >= $start_last_week && $date_raw <= $end_last_week) {
				$file_name = end( split('/', $em['file']) );
				
				if (!file_exists($em['file'].".csv")) {
				$csv = fopen($em['file'].".csv", 'w');
				fputcsv($csv, array("Date checked","Num messages","Unread emails","Deleted emails"));
				$raw_log_data = file_get_contents($em['file']);	
					
					if ($raw_log_data != "") {
						
						$log_data = unserialize($raw_log_data);
					
						foreach($log_data as $ld){
							$ld['check-date'] = date('D jS M, Y', $ld['check-date']);
							fputcsv($csv, $ld);
						}
					
						fclose($csv);
					
						$zip_logs[] = array($em['file'].'.csv', $file_name.'.csv');
						unlink($em['file']);
						//pre("Add File");
						
					} else {
					
						unlink($em['file']);
						//pre("Remove File");
						
					}
				}
				
				//pre( date('Y-m-d', $date_raw) );	
				
				}	
	
			}
			
			if ( !empty($zip_logs) ) {
				
					$emails_zip = new ZipArchive();
					
					$emails_zip->open("email-logs-archive/email-logs-".date('Y-m-d', $start_last_week)."*-*".date('Y-m-d', $end_last_week).".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
						
							foreach ($zip_logs as $zl){
							$emails_zip->addFile($zl[0], $zl[1]);
							}
							
					
					if ( $emails_zip->close() ) {
						foreach ($zip_logs as $zl){
						unlink($zl[0]);
						}
						
						
					}	
			}
		}
	}
}

//pre( date('l jS F', $start_last_week) );	
//pre( date('l jS F', $end_last_week) );	
?>