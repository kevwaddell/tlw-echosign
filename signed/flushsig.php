<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-IT-zip-email.php');
$now = time();
$log_date = date('Y-m-d', $now);
$log_files = glob($_SERVER['DOCUMENT_ROOT'] . "/logs/sent-data-*.log");
	
function zip_files($data) {
	
	foreach ($data as $k => $d) {
	$ref = $d['ref'];
	$tkn = $d['tkn'];
	$rdate = $d['rdate'];
	
	pre("Remove date: ".date('d-m-Y', $rdate)."<br> Today: ".date('d-m-Y', $now)."<br> Log date: ".$log_date);
					
		if ( file_exists($_SERVER['DOCUMENT_ROOT']."/signed/".$tkn."@".$ref.".zip") && ($rdate < $now) ) {
			
			if (sendZipEmail($d)) {
			unlink($_SERVER['DOCUMENT_ROOT']."/signed/".$tkn."@".$ref.".zip");	
			}
			
		}
		
		if ( is_dir($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref) && ($rdate > $now) ){
		$raw_client_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref.'/data.txt');	
		$client_data = unserialize($raw_client_data);
		
			
			$zip = new ZipArchive();
			$files = scandir($ref);
			$iterator = new FilesystemIterator($ref);
			
				if ($iterator->valid()) {
				
					$zip->open($tkn."@".$ref.".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
				
					foreach ($files as $f){
						if ($f != "." && $f != "..") {
						$zip->addFile($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref."/".$f, $f);
						}	
					}
					
					$zip->close();
				
				foreach ($files as $f){
					if ($f != "." && $f != "..") {
					unlink($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref."/". $f);
					}
				}
			
			
			}
			
			rmdir($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref);

		}
	
	}// End foreach $data
}

if(!empty($log_files)) {
	foreach($log_files as $k => $lf) {
	$log_raw_data = file_get_contents($lf);		
	$log_data = unserialize($log_raw_data);
		if (!empty($log_data)) {
		zip_files($log_data);
		}
	}
} else {
exit;	
}

?>