<?php
function curPageURL() {
 	$pageURL = 'http';
 	
 	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
	 	$pageURL .= "s";
	}
	 
	$pageURL .= "://";	
 	
 	if ($_SERVER['SERVER_PORT'] != "80") {
 		$pageURL .= $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
 	} else {
 		$pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
 	}
 	
 	return $pageURL;
}
$url_array = parse_url(curPageURL());
$host = $url_array['host'];
$scheme = $url_array['scheme']."://";
DEFINE('SITEROOT', $scheme . $host);
DEFINE('SITEHOST', $host);
DEFINE('SITESCHEME', $scheme);
date_default_timezone_set('Europe/London');
?>