<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-IT-zip-email.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-IT-all-zips-email.php');

if ( $_SERVER['HTTP_REFERER'] ){
$referer_raw = $_SERVER['HTTP_REFERER'];
$referer_parse = parse_url($referer_raw);
$referer = $referer_parse['scheme']."://".$referer_parse['host'].$referer_parse['path'];
} else {
$referer = $scheme.$host;
}

$now = time();
$log_date = date('Y-m-d', $now);
$zip_files = glob($_SERVER['DOCUMENT_ROOT']."/signed/*.zip");
//pre($_SERVER);

if (isset($_GET['tkn']) && isset($_GET['cref'])) {

$tkn = $_GET['tkn'];
$ref = $_GET['cref'];
$redirect = $referer."?cref=".$ref;

$d = array('ref' => $ref, 'tkn' => $tkn);

	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/signed/'.$tkn."@".$ref.".zip")) {
		
		if ( sendZipEmail($d) ) {
			
			if ( unlink($_SERVER['DOCUMENT_ROOT'].'/signed/'.$tkn."@".$ref.".zip") ) {
			$redirect .= "&deleted=1";		
			}	
			
		}
		
	} else {
	$redirect .= "&deleted=0";	
	}
	
	header("Location: ". $redirect);
	
} 

if (isset($_GET['rmv']) && $_GET['rmv'] == "all" && !empty($zip_files)) {
$redirect = $referer;

	if (sendAllZipsEmail($zip_files)) {

		foreach($zip_files as $k => $zf){
		unlink($zf);
		}	
		
		$redirect .= "?zips-deleted=1";	
	} else {
		$redirect .= "?zips-deleted=0";	
	}
	
	header("Location: ". $redirect );	
	
}

if (!isset($_GET['rmv']) || !isset($_GET['tkn'])) {
	foreach($zip_files as $k => $zf){
	unlink($zf);
	echo $zf. " zip file removed.";
	}		
}	
	
?>