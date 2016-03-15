<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');

$referer_raw = $_SERVER['HTTP_REFERER'];
$referer_parse = parse_url($referer_raw);
$referer = $referer_parse['scheme']."://".$referer_parse['host'].$referer_parse['path'];
$now = time();
	
if (isset($_GET['cref']) && isset($_GET['log'])) {
$log_date = $_GET['log'];
$cref = $_GET['cref'];

$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/sent-data-'.$log_date.'.log');	
$data = unserialize($raw_data);	
	foreach ($data as $k => $d) {
		if ($d['ref'] == $cref) {
		unset($data[$k]);
		$new_data = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/sent-data-'.$log_date.'.log', serialize($data));	
		}
	}
	
	if ($new_data) {
		header("Location: ". $referer ."?cref=".$ref."&deleted=1");	
	}

} else {
header("Location: ". $referer );	
}	
	
?>