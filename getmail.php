<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');

function getFileExtension($fileName){
   $parts = explode(".",$fileName);
   return $parts[count($parts)-1];
}

if ($host == "tlw-echosign.dev") {
	$imapPath = "{192.168.12.9:143/imap4/notls/novalidate-cert/user=esign}";	
} else {
	$imapPath = "{nsgateway.tlwsolicitors.co.uk:143/imap/notls/novalidate-cert/user=esign}INBOX";
}
$username = "esign@tlwsolicitors.co.uk";
$password = "document5";

$inbox = imap_open($imapPath, $username, $password, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'GSSAPI'));
$log_date = date('Y-m-d', time());
$prev_log_date = date('Y-m-d', strtotime($log_date.'- 1 day'));

if ($inbox){
	
	//echo "<br> --> connection successful....<br>";
	
	/* grab emails */
	$emails = imap_search($inbox,'ALL');
	
	if($emails) {

		$emails_counter = 0;
		$check = imap_mailboxmsginfo($inbox);
		
		//echo "Total Messages: " . $check->Nmsgs . "<br />\n";
		//echo "Unread Messages: " . $check->Unread . "<br />\n";
		//echo "Deleted Messages: " . $check->Deleted . "<br />\n";
		
		rsort($emails);
		// Check if Email logs for current date extists
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/logs/email-logs-'.$log_date.'.log')) {
		$email_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/email-logs-'.$log_date.'.log'); 
		$email_logs = unserialize($email_logs_raw);
		$email_logs[] = array('check-date' => time(), 'Nmsgs' => $check->Nmsgs, 'Unread' => $check->Unread, 'Deleted' => $check->Deleted );
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/email-logs-'.$log_date.'.log', serialize($email_logs)); 	
		} else {
		//If file does not exist create it
		$email_logs = array();
		$email_logs[] = array('check-date' => time(), 'Nmsgs' => $check->Nmsgs, 'Unread' => $check->Unread, 'Deleted' => $check->Deleted );
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/email-logs-'.$log_date.'.log', serialize($email_logs));
		}
		
		// Check if Unsigned logs for current date extists
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned-'.$log_date.'.log')) {
		$unsigned_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned-'.$log_date.'.log');
		$unsigned_logs = unserialize($unsigned_logs_raw);
		} else {
		//If file does not exist create it check yesterdays logs and add to todays logs
			$unsigned_logs = array();
			
			if (file_exists($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned-'.$prev_log_date.'.log')) {
			$prev_unsigned_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned-'.$prev_log_date.'.log');
			$unsigned_logs = unserialize($prev_unsigned_logs_raw);	
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));	; 	
			} else {
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));	
			}
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
						    if ( !is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir) ) {
							$dir = mkdir($client_ref, 0755);
							
								if ($dir == 1) {
								$php_temp = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/temps/letter-tmp.php');  
						        $new_doc = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/'. $doc_name, $contents); 
						        $new_html = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/'.'sign.php', $php_temp);
						        $new_data = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/data.txt', serialize($data));
								}
							
					        $result = "OK";
					        } else {
						      	$php_temp = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/temps/letter-tmp.php');  
						        $new_doc = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/'. $doc_name, $contents); 
						        $new_html = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/'.'sign.php', $php_temp);
						        $new_data = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/data.txt', serialize($data));  
						        $result = "OK";
					        }
					        
					        //echo '<pre>';print_r($result);echo '</pre>';
  							
							if ($result == "OK") {
	
								include_once($_SERVER['DOCUMENT_ROOT'].'/inc/send-client-email.php');
								
								if (sendClientEmail()) {
								$data['sent'] = time();
								$unsigned_logs[] = $data;
								file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));
									
								imap_setflag_full($inbox, $email_number, "\\Seen \\Flagged", ST_UID);
								} else {
									
									$data['sent'] = NULL;
									$unsigned_logs[] = $data;
									file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));
									
									if (file_exists($_SERVER['DOCUMENT_ROOT'].'/logs/email-error-logs-'.$log_date.'.log')) {
									$raw_error_logs = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/email-error-logs-'.$log_date.'.log');
									$error_logs = unserialize($raw_error_logs);		
									$error_logs[] = $mail->ErrorInfo;	
									file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/email-error-logs-'.$log_date.'.log', serialize($error_logs));
									} else {
									$error_logs = array();
									$error_logs[] = $mail->ErrorInfo;
									file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/email-error-logs-'.$log_date.'.log', serialize($error_logs));	
									}
								}// If client email sent
								
							} // If result OK
							
					    } // if file is word doc
					    
				    } //Foreach attachment 
				    
				}// If seen message = 0
				
			} // foreach overview	
					
		} // For every email

	} //if ther are emails
	
imap_close($inbox);

} else {
	
	if (file_exists('logs/imap-error-logs-'.$log_date.'.log')) {
	$raw_imap_error_logs = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/imap-error-logs-'.$log_date.'.log');
	$imap_error_logs = unserialize($raw_imap_error_logs);		
	$imap_error_logs[] = imap_errors();	
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/imap-error-logs-'.$log_date.'.log', serialize($imap_error_logs));
	} else {
	$imap_error_logs = array();
	$imap_error_logs[] = imap_errors();
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/imap-error-logs-'.$log_date.'.log', serialize($imap_error_logs));	
	}

}
?>