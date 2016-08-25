<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-IT-zip-email.php');
$now = time();
$log_date = date('Y-m-d', $now);
$log_files = glob($_SERVER['DOCUMENT_ROOT']."/admin/logs/sent-data-*.log");
	
function zip_files($data) {
	global $now;
	global $log_date;

	$ref = $data['ref'];
	$tkn = $data['tkn'];
	$signed = $data['signed'];
			
	if ( file_exists($_SERVER['DOCUMENT_ROOT']."/signed/".$tkn."@".$ref.".zip") && ($signed < $now) ) {
		
		if (sendZipEmail($d)) {
		unlink($_SERVER['DOCUMENT_ROOT']."/signed/".$tkn."@".$ref.".zip");	
		}
		
	}
	
	if ( is_dir($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref) ){
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
	
	return true;
}

if ($_SERVER['HTTP_REFERER']) {
$referer_raw = $_SERVER['HTTP_REFERER'];
$referer_parse = parse_url($referer_raw);
$referer = $referer_parse['scheme']."://".$referer_parse['host'].$referer_parse['path'];
} else {
$referer = $scheme.$host;	
}

//pre($referer);

if (isset($_GET['cref']) && $_GET['cref'] != "") {	

	$cref = $_GET['cref'];
	$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/signed/'.$cref.'/data.txt');
	$data = unserialize($raw_data);
	$redirect = $referer."?cref=".$cref;
	
	if (zip_files($data)) {
		$redirect .= "&zipped=1";
	} else {
		$redirect .= "&zipped=0";	
	}	
	
	header("Location: ". $redirect);	
	
} else {

	if(!empty($log_files)) {
		
		foreach($log_files as $k => $lf) {
		$log_raw_data = file_get_contents($lf);		
		$log_data = unserialize($log_raw_data);
			
			if (!empty($log_data)) {
				
				if (zip_files($log_data)){
					echo "Files zipped successfully!!";	
				} else {
				exit("Files not zipped");	
				}//if files zipped
				
			}//if log data not empty
			
		}//foreach log files
		
		if (isset($_GET['zip']) && $_GET['zip'] == "all") {
			header("Location: ". $referer ."?zipped=1");	
		}
		
	}//if log files not empty
	
}//check if single zip is required 
?>