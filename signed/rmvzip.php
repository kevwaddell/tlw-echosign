<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/send-IT-zip-email.php');

$referer_raw = $_SERVER['HTTP_REFERER'];
$referer_parse = parse_url($referer_raw);
$referer = $referer_parse['scheme']."://".$referer_parse['host'].$referer_parse['path'];
	
if (isset($_GET['tkn']) && $_GET['tkn'] != "") {
$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/sent-data.txt');	
$data = unserialize($raw_data);	

$tkn = $_GET['tkn'];
$ref = $_GET['cref'];
$d = array('ref' => $ref, 'tkn' => $tkn);

	if (file_exists($tkn."@".$ref.".zip")) {
		
		if ( sendZipEmail($d) ) {
		
			foreach ($data as $k => $sd) {
				if ($sd['tkn'] == $tkn) {
				unlink($tkn."@".$ref.".zip");
				unset($data[$k]);
				$new_data = file_put_contents('../logs/sent-data.txt', serialize($data));	
					if ($new_data) {
					header("Location: ". $referer ."?cref=".$ref."&deleted=1");	
					}
				}
			}
		}
		
	} else {
	header("Location: ". $referer ."?cref=".$ref."&deleted=0");
	}
	
} else {
header("Location: ". $referer );	
}	
	
?>