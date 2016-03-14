<?php 
function sendClientPDFEmail() {
	global $scheme;
	global $host;
	global $data;
	global $dateTime;

	$body = file_get_contents(SITEROOT.'/temps/client-email-pdf.php?cref='.$data['ref']);
	
	$pdfmail = new PHPMailer();
	//SMTP settings
	$pdfmail->isSMTP();
	if ($host == 'tlw-esign.dev') {
	$pdfmail->SMTPOptions = array ('ssl' => array('verify_peer'  => false, 'verify_peer_name'  => false, 'allow_self_signed' => true));
	$pdfmail->Host = 'smtp.gmail.com';
	$pdfmail->SMTPAuth = true;
	$pdfmail->Username = 'kwaddelltlw@gmail.com';
	$pdfmail->Password = 'TLW_kevin21';
	$pdfmail->SMTPSecure = 'tls';                           
	$pdfmail->Port = 587;   	
	} else {
	$pdfmail->SMTPOptions = array ('ssl' => array('verify_peer'  => false, 'verify_peer_name'  => false, 'allow_self_signed' => true));
	$pdfmail->SMTPAuth   = true;  
		if ($host == "tlw-echosign.dev") {
		$pdfmail->Host = 'tlwserv02.tlwsolicitors.local';
		} else {
		$pdfmail->Host = 'nsgateway.tlwsolicitors.co.uk';
		}
	$pdfmail->Username = 'esign';
	$pdfmail->Password = 'document5';                         
	$pdfmail->Port = 25; 	      
	}
	
	$pdfmail->AddReplyTo("info@tlwsolicitors.co.uk","TLW Solicitors");
	$pdfmail->SetFrom("esign@tlwsolicitors.co.uk", "TLW Solicitors");
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