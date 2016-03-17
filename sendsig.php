<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-proclaim-attachment-email.php');

$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$_POST['cref'].'/data.txt');
$data = unserialize($raw_data);
$dateTime = date('g:ia, jS F, Y');
//pre($dateTime);

if ( sendProclaimEmail() ) {
	
	$data['signed'] = time(); 
	$new_data = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$data['ref'] .'/data.txt', serialize($data));
	
	if ($new_data) {
	header("Location: ". SITEROOT ."/sent/?sent=1&cref=".$data['ref']);
	}

	
} else {
//echo "Proclaim Mailer Error: " . pre($mail->ErrorInfo);	

header("Location: ". SITEROOT ."/sent/?sent=0&cref=".$data['ref']);
}


?>