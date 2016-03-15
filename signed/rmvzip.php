<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-IT-zip-email.php');

$referer_raw = $_SERVER['HTTP_REFERER'];
$referer_parse = parse_url($referer_raw);
$referer = $referer_parse['scheme']."://".$referer_parse['host'].$referer_parse['path'];
$now = time();
$log_date = date('Y-m-d', $now);
	
if (isset($_GET['tkn']) && isset($_GET['cref'])) {
$tkn = $_GET['tkn'];
$ref = $_GET['cref'];

$d = array('ref' => $ref, 'tkn' => $tkn);

	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/signed/'.$tkn."@".$ref.".zip")) {
		
		if ( sendZipEmail($d) ) {
			
			if ( unlink($_SERVER['DOCUMENT_ROOT'].'/signed/'.$tkn."@".$ref.".zip") ) {
			header("Location: ". $referer ."?cref=".$ref."&deleted=1");		
			}	
			
		}
		
	} else {
	header("Location: ". $referer ."?cref=".$ref."&deleted=0");
	}
	
} else {
header("Location: ". $referer );	
}	
	
?>