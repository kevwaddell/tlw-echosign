<?php 
function sendClientPDFEmail() {
	global $data;
	global $dateTime;

	$body = file_get_contents(SITEROOT.'/temps/client-email-pdf.php?cref='.$data['ref']);
	
	$pdfmail = new PHPMailer();
	//SMTP settings
	$pdfmail->isSMTP();
	$pdfmail->SMTPOptions = array ('ssl' => array('verify_peer'  => false, 'verify_peer_name'  => false, 'allow_self_signed' => true));
	$pdfmail->SMTPAuth   = true;  
	$pdfmail->Host = TLW_SMTP_HOST;
	$pdfmail->Username = TLW_SMTP_USER;
	$pdfmail->Password = TLW_SMTP_PWD;                         
	$pdfmail->Port = TLW_SMTP_PORT; 	      
	
	$pdfmail->AddReplyTo(TLW_REPLY_EMAIL, TLW_REPLY_NAME);
	$pdfmail->SetFrom(TLW_SOURCE_EMAIL, TLW_SOURCE_NAME);
	if (!$data['fullname']) {
	$pdfmail->AddAddress($data['email']);	
	} else {
	$pdfmail->AddAddress($data['email'], $data['fullname']);	
	}
	$pdfmail->Subject = "Thank you for signing our agreement";
	$pdfmail->MsgHTML($body);
	$pdfmail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/signed/".$data['ref']."/".$data['ref'].".pdf");
	
	return $pdfmail->Send();

}	
?>