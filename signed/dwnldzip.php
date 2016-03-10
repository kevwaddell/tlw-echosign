<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
$referer_raw = $_SERVER['HTTP_REFERER'];
$referer_parse = parse_url($referer_raw);
$referer = $referer_parse['scheme']."://".$referer_parse['host'].$referer_parse['path'];
	
if (isset($_GET['tkn']) && $_GET['tkn'] != "") {

$tkn = $_GET['tkn'];
$ref = $_GET['cref'];
	if (file_exists($tkn."@".$ref.".zip")) {
	header("Location: ". $referer ."?tkn=".$tkn."&cref=".$ref."&dwnld=1");		
	} else {
	header("Location: ". $referer ."?tkn=".$tkn."&cref=".$ref."&dwnld=0");
	}
} else {
exit;	
}	
	
?>