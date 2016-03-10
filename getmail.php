<?php
set_time_limit(4000);

include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');

function curPageURL() {
 	$pageURL = 'http';
 	if ($_SERVER['HTTPS'] == "on") {$pageURL .= "s";}
 	$pageURL .= "://";
 	if ($_SERVER['SERVER_PORT'] != "80") {
 		$pageURL .= $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
 	} else {
 		$pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
 	}
 	
 	return $pageURL;
}
$url_array = parse_url(curPageURL());
$host = $url_array[host];
$scheme = $url_array[scheme]."://";

function getFileExtension($fileName){
   $parts=explode(".",$fileName);
   return $parts[count($parts)-1];
}
 
// Connect to gmail
/*
$imapPath = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
$username = "kwaddelltlw@gmail.com";
$password = "TLW_kevin21";
*/

// Connect to TLW imap
if ($host == "tlw-echosign.dev") {
	$imapPath = "{192.168.12.9:143/imap4/notls/novalidate-cert/user=esign}";	
} else {
	$imapPath = "{nsgateway.tlwsolicitors.co.uk:143/imap4/notls/novalidate-cert/user=esign}";	
}
$username = "esign@tlwsolicitors.co.uk";
$password = "document5";

$inbox = imap_open($imapPath, $username, $password, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'GSSAPI'));

//pre($inbox);

if ($inbox){
	//echo "<br> --> connection successful....<br>";
	
	/* grab emails */
	$emails = imap_search($inbox,'UNSEEN');
	
	//print_r($emails);
	
	/* if emails are returned, cycle through each... */
	if($emails) {
		$emails_counter = 0;
		$check = imap_mailboxmsginfo($inbox);
		//echo "Total Messages: " . $check->Nmsgs . "<br />\n";
		//echo "Unread Messages: " . $check->Unread . "<br />\n";
		//echo "Deleted Messages: " . $check->Deleted . "<br />\n";
		
		//echo '<pre>';print_r($check);echo '</pre>';
		
		/* put the newest emails on top */
		rsort($emails);
		
		if (file_exists('logs/email-logs.txt')) {
		$email_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/email-logs.txt'); 
		$email_logs = unserialize($email_logs_raw);
		$email_logs[] = array('check-date' => time(), 'Nmsgs' => $check->Nmsgs, 'Unread' => $check->Unread, 'Deleted' => $check->Deleted );
		file_put_contents('logs/email-logs.txt', serialize($email_logs)); 	
		} else {
		$email_logs = array();
		$email_logs[] = array('check-date' => time(), 'Nmsgs' => $check->Nmsgs, 'Unread' => $check->Unread, 'Deleted' => $check->Deleted );
		file_put_contents('logs/email-logs.txt', serialize($email_logs));
		}
		
		if (file_exists('logs/unsigned.txt')) {
		$unsigned_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned.txt');
		$unsigned_logs = unserialize($unsigned_logs_raw);
		} else {
		$unsigned_logs = array();
		}
		
		/* for every email... */
		foreach($emails as $email_number) {
			$emails_counter++;
			if ($emails_counter > 1) {
			exit;	
			}
			/* get information specific to this email */
			$overview = imap_fetch_overview($inbox,$email_number,0);
			$message = imap_fetchbody($inbox,$email_number,2);
			$structure = imap_fetchstructure($inbox,$email_number);
			$seen_msg = $overview->seen;
			
			//echo '<pre>';print_r($overview);echo '</pre>';
			
			$attachments = array();
			
			if(isset($structure->parts) && count($structure->parts)) {
	         
	         for($i = 0; $i < count($structure->parts); $i++) {
	           $attachments[$i] = array('is_attachment' => false,'filename' => '','name' => '','attachment' => '');
	
	           if($structure->parts[$i]->ifdparameters) {
	             foreach($structure->parts[$i]->dparameters as $object) {
	               if(strtolower($object->attribute) == 'filename') {
	                 $attachments[$i]['is_attachment'] = true;
	                 $attachments[$i]['filename'] = $object->value;
	               }
	             }
	           }
	
	           if($structure->parts[$i]->ifparameters) {
	             foreach($structure->parts[$i]->parameters as $object) {
	               if(strtolower($object->attribute) == 'name') {
	                 $attachments[$i]['is_attachment'] = true;
	                 $attachments[$i]['name'] = $object->value;
	               }
	             }
	           }
	
	           if($attachments[$i]['is_attachment']) {
	             $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
	             if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
	               $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
	             }
	             elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
	               $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
	             }
	           }             
	         } // for($i = 0; $i < count($structure->parts); $i++)
	       } // if(isset($structure->parts) && count($structure->parts))
	       
	       // 
	       foreach ($overview as $ov) {
			$seen_msg = $ov->seen;
			$subject = $ov->subject;
			$subject_parts = explode('&', $subject);
			$client_ref = strtolower($subject_parts[0]);
			$client_name = strtolower($subject_parts[1]);
			$client_email = $subject_parts[2];
			$result = null;
	
				if ($seen_msg == 0) {
						
					foreach ($attachments as $key => $attachment) {
				        
				        $name = $attachment['name'];
				        $contents = $attachment['attachment'];
					    
					    if (getFileExtension($name) == "doc") {
						    $doc_dir = $client_ref;
						    $doc_ext = getFileExtension($name);
							$doc_name = $client_ref.'-letter.'. $doc_ext;
							
							//Add the data from the email subject line to an array
							$data = array(
						    'ref' => $client_ref,
							'email' => $client_email,
							'firstname' => ucwords($client_name),
							'tkn'	=> md5( uniqid(rand(), true) )
							);
	
						    //Check if the directory exists if not create it and add data files
						    if ( !is_dir($doc_dir) ) {
							$dir = mkdir($client_ref, 0755);
							
								if ($dir == 1) {
								$php_temp = file_get_contents('temps/letter-tmp.php');  
						        $new_doc = file_put_contents($doc_dir .'/'. $doc_name, $contents); 
						        $new_html = file_put_contents($doc_dir .'/'.'sign.php', $php_temp);
						        $new_data = file_put_contents($doc_dir .'/data.txt', serialize($data));
								}
							
					        $result = "OK";
					        } else {
						      	$php_temp = file_get_contents('temps/letter-tmp.php');  
						        $new_doc = file_put_contents($doc_dir .'/'. $doc_name, $contents); 
						        $new_html = file_put_contents($doc_dir .'/'.'sign.php', $php_temp);
						        $new_data = file_put_contents($doc_dir .'/data.txt', serialize($data));  
						        $result = "OK";
					        }
					        
					        //echo '<pre>';print_r($result);echo '</pre>';
  							
							if ($result == "OK") {
	
								include_once($_SERVER['DOCUMENT_ROOT'].'/inc/send-client-email.php');
								
								if (sendClientEmail()) {
								$data['sent'] = time();
								$unsigned_logs[] = $data;
								file_put_contents('logs/unsigned.txt', serialize($unsigned_logs));
									
								imap_setflag_full($inbox, $email_number, "\\Seen \\Flagged", ST_UID);
								} else {
									
									$data['sent'] = NULL;
									$unsigned_logs[] = $data;
									file_put_contents('logs/unsigned.txt', serialize($unsigned_logs));
									
									if (file_exists('logs/email-error-logs.txt')) {
									$raw_error_logs = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/email-error-logs.txt');
									$error_logs = unserialize($raw_error_logs);		
									$error_logs[] = $mail->ErrorInfo;	
									file_put_contents('logs/email-error-logs.txt', serialize($error_logs));
									} else {
									$error_logs = array();
									$error_logs[] = $mail->ErrorInfo;
									file_put_contents('logs/email-error-logs.txt', serialize(	$error_logs));	
									}
								}// If client email sent
								
							} // If result OK
							
					    } // if file is word doc
					    
				    } //Foreach attachment 
				    
				}// If seen message = 0
				
			} // foreach overview	
					
		} // For every email
		
	} // If there are emails

/* close the connection */
imap_close($inbox);

} else {
	
	//echo "<br> --> connection to server failed...<br>";
	
	if (file_exists('logs/imap-error-logs.txt')) {
	$raw_imap_error_logs = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/imap-error-logs.txt');
	$imap_error_logs = unserialize($raw_imap_error_logs);		
	$imap_error_logs[] = imap_errors();	
	file_put_contents('logs/imap-error-logs.txt', serialize($imap_error_logs));
	} else {
	$imap_error_logs = array();
	$imap_error_logs[] = imap_errors();
	file_put_contents('logs/imap-error-logs.txt', serialize($imap_error_logs));	
	}
}
?>