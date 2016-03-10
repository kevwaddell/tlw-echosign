<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/send-IT-zip-email.php');
 
function zip_files($data) {
	
	$now = time();
	
	foreach ($data as $k => $d) {
	$ref = $d[ref];
	$tkn = $d[tkn];
	$rdate = $d[rdate];
					
		if ( file_exists($tkn.".zip") && $rdate < $now ) {
			
			if (sendZipEmail($d)) {
			unlink($tkn."@".$ref.".zip");	
			unset($data[$k]);
			file_put_contents('../logs/sent_data.txt', serialize($data));
			}
			
		}
		
		if ( is_dir($ref) && $rdate > $now){
		$raw_client_data = file_get_contents($ref.'/data.txt');	
		$client_data = unserialize($raw_client_data);
		
			
			$zip = new ZipArchive();
			$files = scandir($ref);
			$iterator = new FilesystemIterator($ref);
			
				if ($iterator->valid()) {
				
				$zip->open($tkn."@".$ref.".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
				
				foreach ($files as $f){
					if ($f != "." && $f != "..") {
					$zip->addFile($ref."/". $f);
					}	
				}
				$zip->close();
				
				foreach ($files as $f){
					if ($f != "." && $f != "..") {
					unlink($ref."/". $f);
					}
				}
			
			
			}
			
			rmdir($ref);

		}
	
	}// End foreach $data
}

if(file_exists('../logs/sent_data.txt')) {
$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/sent_data.txt');	
$data = unserialize($raw_data);	
zip_files($data);	
} else {
exit;	
}	
?>