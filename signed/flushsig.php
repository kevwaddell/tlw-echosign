<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/send-IT-zip-email.php');
$now = time();
$log_date = date('Y-m-d', $now);
	
function zip_files($data) {
	
	foreach ($data as $k => $d) {
	$ref = $d['ref'];
	$tkn = $d['tkn'];
	$rdate = $d['rdate'];
					
		if ( file_exists($_SERVER['DOCUMENT_ROOT']."/signed/".$tkn."@".$ref.".zip") && $rdate < $now ) {
			
			if (sendZipEmail($d)) {
			unlink($_SERVER['DOCUMENT_ROOT']."/signed/".$tkn."@".$ref.".zip");	
			unset($data[$k]);
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/sent-data-'.$log_date.'.log', serialize($data));
			}
			
		}
		
		if ( is_dir($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref) && $rdate > $now){
		$raw_client_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref.'/data.txt');	
		$client_data = unserialize($raw_client_data);
		
			
			$zip = new ZipArchive();
			$files = scandir($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref);
			$iterator = new FilesystemIterator($ref);
			
				if ($iterator->valid()) {
				
				$zip->open($_SERVER['DOCUMENT_ROOT'].'/signed/'.$tkn."@".$ref.".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
				
				foreach ($files as $f){
					if ($f != "." && $f != "..") {
					$zip->addFile($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref."/". $f);
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

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/logs/sent-data-'.$log_date.'.log')) {
$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/sent-data-'.$log_date.'.log');	
$data = unserialize($raw_data);	

	if (!empty($data)) {
	zip_files($data);	
	}
	
} else {
exit;	
}	
?>