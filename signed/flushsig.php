<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-IT-zip-email.php');
$now = time();
$signed_folders = glob($_SERVER['DOCUMENT_ROOT']."/signed/*");
	
function zip_files($data) {
	global $now;
	global $log_date;

	$ref = $data['ref'];
	$tkn = $data['tkn'];
	$signed = $data['signed'];
	
	if ( is_dir($_SERVER['DOCUMENT_ROOT'].'/signed/'.$ref) ){
			
		if ( file_exists($_SERVER['DOCUMENT_ROOT'].'/signed/'.$tkn."@".$ref.".zip") ) {
		unlink($_SERVER['DOCUMENT_ROOT'].'/signed/'.$tkn."@".$ref.".zip");	
		}
			
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
		
		return true;

	} else {
		return false;	
	}
	
}

if ( isset($_SERVER['HTTP_REFERER']) ) {
$referer_raw = $_SERVER['HTTP_REFERER'];
$referer_parse = parse_url($referer_raw);
$referer = $referer_parse['scheme']."://".$referer_parse['host'].$referer_parse['path'];
} else {
$referer = $scheme.$host;	
}

//pre($referer);
if ( isset($_SERVER['HTTP_REFERER']) ) {
	
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
		
	} 
	
	if (isset($_GET['zip']) && $_GET['zip'] == "all") {
			
	$redirect = $referer;
	$no_signed_folders = true;
	
		if ( !empty($signed_folders) ) {
			
		    foreach ($signed_folders as $f) {
			//pre(filetype($f));	
			
			if (filetype($f) == "dir") {
			$raw_data = file_get_contents($f.'/data.txt');	
			$data = unserialize($raw_data);
			$no_signed_folders = false;
				
				if ( zip_files($data) ){
				$zip_error = false;
				} else {
				$zip_error = true;
				}//check if folder zipped or not

			}//check if is a dir
			
			}//loop through all files and folders in dir
		}
		
		if ($no_signed_folders) {
		$redirect .= "?zipped=0";
		} elseif ($zip_error) {
		$redirect .= "?zipped=1";	
		} else {
		$redirect .= "?zipped=2";	
		}
		
	//pre($redirect);
		
	header("Location: ". $redirect);
		
	}

} else {
	
	//pre($signed_folders);
	$no_signed_folders = true;
	
	if ( !empty($signed_folders) ) {
		
		foreach ($signed_folders as $f) {
		//pre(filetype($f));	
			
			if (filetype($f) == "dir") {
			$raw_data = file_get_contents($f.'/data.txt');	
			$data = unserialize($raw_data);
			$no_signed_folders = false;
				
				if ( zip_files($data) ){
				echo "Zip file created: <strong>".$data['tkn']."@".$data['ref'].".zip</strong>";
				} else {
				echo "Error creating zip file for REF : <strong>".$data['ref']."</strong>";	
				}//check if folder zipped or not

			}//check if is a dir
			
		}//loop through all files and folders in dir
		
		if ($no_signed_folders) {
		echo "No folders to zip.";	
		}
		
	} // Check if there a signed 
	
}//check if sent from a http referer 
?>