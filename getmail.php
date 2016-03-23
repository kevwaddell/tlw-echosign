<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');

function getFileExtension($fileName){
   $parts = explode(".",$fileName);
   return $parts[count($parts)-1];
}

$imapPath = "{".TLW_SMTP_HOST.":143/imap/notls/novalidate-cert/user=esign}INBOX";
$username = TLW_SOURCE_EMAIL;
$password = TLW_SMTP_PWD;

$inbox = imap_open($imapPath, $username, $password, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'GSSAPI'));
$log_date = date('Y-m-d', time());
$prev_log_date = date('Y-m-d', strtotime($log_date.'- 1 day'));

//pre($inbox);

if ($inbox){
	
	//echo "<br> --> connection successful....<br>";
	
	/* grab emails */
	$emails = imap_search($inbox,'ALL', SE_UID);
	
	if($emails) {

		$emails_counter = 0;
		$check = imap_mailboxmsginfo($inbox);
		$email_logs = array();
		$unsigned_logs = array();
		$signed_logs = array();
		
		//echo "Total Messages: " . $check['Nmsgs'] . "<br />\n";
		//echo "Unread Messages: " . $check['Unread'] . "<br />\n";
		//echo "Deleted Messages: " . $check['Deleted'] . "<br />\n";
		
		/*
		Check if log files exist and if not create 
		one for the current date with empty array.	
		*/
				
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/email-logs-'.$log_date.'.log')) {
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/email-logs-'.$log_date.'.log', serialize($unsigned_logs));
		} else {
			$email_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/email-logs-'.$log_date.'.log'); 
			$email_logs = unserialize($email_logs_raw);
		}
		
		// Check if Unsigned logs for current date extists
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log')) {
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));
		} else {
			$unsigned_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log');
			$unsigned_logs = unserialize($unsigned_logs_raw);	
		}	
		
		// Check if Unsigned logs for prev day extists
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$prev_log_date.'.log')) {
			$prev_unsigned_logs_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$prev_log_date.'.log');
			$prev_unsigned_logs = unserialize($prev_unsigned_logs_raw);	
			if (!empty($prev_unsigned_logs)) {
				foreach($prev_unsigned_logs as $pl) {
					if (!in_array($pl, $unsigned_logs)) {
					$unsigned_logs[] = $pl;
					}
				}
				file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));	; 
			}
		}
		
		// Check if signed logs for current date extists
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/sent-data-'.$log_date.'.log')) {
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/sent-data-'.$log_date.'.log', serialize($signed_logs));	; 
		}
		
		rsort($emails);
		// Check if Emails are Unread
		if ($check->Unread > 0) {
			
		//pre($check->Unread);	
			
		$email_logs[] = array('check-date' => time(), 'Nmsgs' => $check->Nmsgs, 'Unread' => $check->Unread, 'Deleted' => $check->Deleted );
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/email-logs-'.$log_date.'.log', serialize($email_logs));
		
		/* for every email... */
		foreach($emails as $email_number) {
			$emails_counter++;
			
			if ($emails_counter > 2) {
			exit;	
			}
			
			/* get information specific to this email */
			$overview = imap_fetch_overview($inbox, $email_number, 0);
			$message = imap_fetchbody($inbox,$email_number,2);
			$structure = imap_fetchstructure($inbox,$email_number);
			$parts_total = count($structure->parts);
			$parts = $structure->parts;
			$seen_msg = $overview[0]->seen;

			$attachments = array();
			
			if( isset($parts) && $parts_total > 0 ) {
	         
	         foreach($parts as $k => $v) {
	           $attachments[$k] = array('is_attachment' => false,'filename' => '','name' => '','attachment' => '');
			   
			   //pre($v);
			   
	           if( $v->ifdparameters ) {
	             
	             foreach($v->dparameters as $object) {
	             //pre($object);
	               
	               if(strtolower($object->attribute) == 'filename') {
	                 $attachments[$k]['is_attachment'] = true;
	                 $attachments[$k]['filename'] = $object->value;
	               }
	               
	             }
	             
	           } 
	           
	           if ( $v->ifparameters ) {
	             
	             foreach( $v->parameters as $object ) {
	               
	               if(strtolower($object->attribute) == 'name') {
	                 $attachments[$k]['is_attachment'] = true;
	                 $attachments[$k]['name'] = $object->value;
	               }
	               
	             }
	             
	           }
	           
	           //pre($attachments);
	
	           if ( $attachments[$k]['is_attachment'] ) {
		           
	             $attachments[$k]['attachment'] = imap_fetchbody($inbox, $email_number, $k+1);
	            
	             if($v->encoding == 3) { // 3 = BASE64
		             
	               $attachments[$k]['attachment'] = base64_decode($attachments[$k]['attachment']);
	               
	             } elseif ($v->encoding == 4) { // 4 = QUOTED-PRINTABLE
		             
	               $attachments[$k]['attachment'] = quoted_printable_decode($attachments[$k]['attachment']);
	               
	             }
	             
	           } // if( $attachments[$k]['is_attachment'] )  
	                   
	         } //  foreach($parts as $k => $v)
	         
	       } // if(isset($structure['parts']) && count($structure['parts']))
	       
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
						    if ( is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir) ) {

								$php_temp = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/temps/letter-tmp.php');  
						        $new_doc = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/'. $doc_name, $contents); 
						        $new_html = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/'.'sign.php', $php_temp);
						        $new_data = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/data.txt', serialize($data));  
						        $result = "OK";
	
					        } else {
						        
						     	$dir = mkdir($client_ref, 0755);
						     	
						     	if ($dir == 1) {
								$php_temp = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/temps/letter-tmp.php');  
						        $new_doc = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/'. $doc_name, $contents); 
						        $new_html = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/'.'sign.php', $php_temp);
						        $new_data = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/data.txt', serialize($data));
						        $result = "OK";
								}					        }
					        
					       // echo '<pre>';print_r($result);echo '</pre>';
  							
							if ($result == "OK") {

								include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-client-email.php');

								if (sendClientEmail($data)) {
									$data['sent'] = time();
									imap_setflag_full($inbox, $email_number, "\\Seen \\Flagged", ST_UID);
								} else {
									$data['sent'] = NULL;
																
								}// If client email sent
								
								$unsigned_logs[] = $data;
								file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));

							} // If result OK
							
					    } // if file is word doc
					    
				    } //Foreach attachment 
				    
				}// If seen message = 0
				
			} // foreach overview	
					
		} // For every email
		
		} // Check if Emails are Unread

	} //if ther are emails
	
imap_close($inbox);

} else {
	
	if (file_exists('logs/imap-error-logs-'.$log_date.'.log')) {
	$raw_imap_error_logs = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/imap-error-logs-'.$log_date.'.log');
	$imap_error_logs = unserialize($raw_imap_error_logs);		
	$imap_error_logs[] = imap_errors();	
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/imap-error-logs-'.$log_date.'.log', serialize($imap_error_logs));
	} else {
	$imap_error_logs = array();
	$imap_error_logs[] = imap_errors();
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/imap-error-logs-'.$log_date.'.log', serialize($imap_error_logs));	
	}

}
?>