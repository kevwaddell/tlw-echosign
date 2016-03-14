<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');

$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$_POST['cref'].'/data.txt');
$data = unserialize($raw_data);
$dateTime = gmdate('g:ia, jS F, Y');
//pre($data);

//Email functions to send to client, proclaim and IT
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/send-proclaim-attachment-email.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/send-client-attachemt-email.php');


if ( sendProclaimEmail() ) {
	
	$data['signed'] = time(); 
	$new_data = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$data['ref'] .'/data.txt', serialize($data));
	
	if ($new_data && sendClientPDFEmail()) {
	header("Location: ". SITEROOT ."/sent/?sent=1&cref=".$data['ref']);
	}

	
} else {
//echo "Proclaim Mailer Error: " . pre($mail->ErrorInfo);	

header("Location: ". SITEROOT ."/sent/?sent=0&cref=".$data['ref']);
}


?>