<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
$php_temp 	= 	file_get_contents($_SERVER['DOCUMENT_ROOT'].'/temps/letter-tmp.php'); 

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
// Check if Unsigned logs for prev day extists
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$prev_log_date.'.log')) {
	$prev_unsigned_src = $_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$prev_log_date.'.log';
	$prev_unsigned_dest = $_SERVER['DOCUMENT_ROOT'].'/admin/logs/archives/unsent-logs-archive/unsigned-'.$prev_log_date.'.log';
	
	$prev_unsigned_logs_raw = file_get_contents($prev_unsigned_src);
	$prev_unsigned_logs = unserialize($prev_unsigned_logs_raw);	
	
	rename($prev_unsigned_src, $prev_unsigned_dest);
	
	if (!empty($prev_unsigned_logs)) {
		foreach($prev_unsigned_logs as $pl) {
			if (!in_array($pl, $unsigned_logs)) {
			$unsigned_logs[] = $pl;
			}
		}
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs)); 
	}
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/sent-data-'.$prev_log_date.'.log')) {
	$prev_signed_src = $_SERVER['DOCUMENT_ROOT'].'/admin/logs/sent-data-'.$prev_log_date.'.log';
	$prev_signed_dest = $_SERVER['DOCUMENT_ROOT'].'/admin/logs/archives/sent-logs-archive/sent-data-'.$prev_log_date.'.log';	
	rename($prev_signed_src, $prev_signed_dest);
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/email-logs-'.$prev_log_date.'.log')) {
	$prev_emails_src = $_SERVER['DOCUMENT_ROOT'].'/admin/logs/email-logs-'.$prev_log_date.'.log';
	$prev_emails_dest = $_SERVER['DOCUMENT_ROOT'].'/admin/logs/archives/email-logs-archive/email-logs-'.$prev_log_date.'.log';	
	rename($prev_emails_src, $prev_emails_dest);
}

if ($inbox){

	echo "<br> --> connection successful....<br>";
	
	/* grab emails */
	$emails = imap_search($inbox,'UNSEEN', SE_UID);
	
	$check = imap_mailboxmsginfo($inbox);
	$email_logs = array();
	$unsigned_logs = array();
	$signed_logs = array();

	echo "Total Messages: " . $check->Nmsgs . "<br />\n";
	echo "Unread Messages: " . $check->Unread . "<br />\n";
	echo "Deleted Messages: " . $check->Deleted . "<br />\n";
	
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
	
	// Check if signed logs for current date extists
	if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/sent-data-'.$log_date.'.log')) {
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/sent-data-'.$log_date.'.log', serialize($signed_logs));	; 
	}
	
	// Check if Emails are Unread
	$email_logs[] = array('check-date' => time(), 'Nmsgs' => $check->Nmsgs, 'Unread' => $check->Unread, 'Deleted' => $check->Deleted );
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/email-logs-'.$log_date.'.log', serialize($email_logs));

	//$emails = false;
	
	if($emails) {
		
		rsort($emails);
		$id = $emails[0];	
		/* for every email... */
		
		/* get information specific to this email */
		$overview = imap_fetch_overview($inbox, $id, FT_UID);
		$message = imap_fetchbody($inbox, $id, 2, FT_UID);
		$structure = imap_fetchstructure($inbox, $id, FT_UID);
		$parts_total = count($structure->parts);
		$parts = $structure->parts;
		
		foreach($parts as $k => $v) {
			if ($v->subtype == 'PLAIN') {
			unset($parts[$k]);  
           	}
		}
		
		rsort($parts);

		$attachments = array();
		
		if( isset($parts) && $parts_total > 0 ) {
         
         foreach($parts as $k => $v) {
	         	
		 	if ($parts_total == 1) {
		 	$k = 0;	
			}
		   
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
	           
             $attachments[$k]['attachment'] = imap_fetchbody($inbox, $id, $k+2, FT_UID);
            
             if($v->encoding == 3) { // 3 = BASE64
	             
               $attachments[$k]['attachment'] = base64_decode($attachments[$k]['attachment']);
               
             } elseif ($v->encoding == 4) { // 4 = QUOTED-PRINTABLE
	             
               $attachments[$k]['attachment'] = quoted_printable_decode($attachments[$k]['attachment']);
               
             }
             
           } // if( $attachments[$k]['is_attachment'] )  
                   
         } //  foreach($parts as $k => $v)
         
       } // if(isset($structure['parts']) && count($structure['parts']))

       foreach ($overview as $ov) {
		$seen_msg = $ov->seen;
		$subject = $ov->subject;
		$subject_parts = explode('&', $subject);
		$client_ref = strtolower($subject_parts[0]);
		$client_fname = strtolower($subject_parts[1]);
		$client_lname = strtolower($subject_parts[2]);
		$client_email = $subject_parts[3];
		$handler_email = $subject_parts[4];
		$result = null;
		$data = array();
		
			if (count($attachments) == 1) {
				$name 		= 	$attachments[0]['name'];
				$contents 	= 	$attachments[0]['attachment'];
				$doc_dir 	= 	$client_ref;
				$doc_ext 	= 	getFileExtension($name);
				$doc_name 	= 	$client_ref.'-letter.'. $doc_ext;

				
				if ( !is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir)  ) {	

					$dir = mkdir($client_ref, 0755);
				
					if ($dir == 1) {
						//DATA ARRAY VALUES					
						$data['ref'] 		= 	$client_ref;
						$data['handler'] 	=	$handler_email;
						$data['email'] 		=	$client_email;
						$data['firstname'] 	=	ucwords($client_fname);
						$data['lastname'] 	=	ucwords($client_lname);
						$data['tkn'] 		=	md5( uniqid(rand(), true) );
						$data['sent']		=	time();
						
						//FILES CREATED	
						$new_doc 	= 	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/'. $doc_name, $contents); 
						$new_html 	= 	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/sign.php', $php_temp);
						$new_data 	= 	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/data.txt', serialize($data));	
						
						//pre($data);
						
						include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-client-email.php');
						
						if (sendClientEmail($data)) { 								
							imap_delete($inbox, $overview[0]->msgno);
							echo "Email sent successfully!";
						} else {
							$raw_data 	= 	file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/data.txt');
							$data 		= 	unserialize($raw_data);
							$data['sent'] = NULL;
							$new_data 	= 	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$doc_dir .'/data.txt', serialize($data));
							imap_clearflag_full($inbox, $overview[0]->msgno, "\\Seen", ST_UID);	
						}
					
						$unsigned_logs[] = $data;
						file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log', serialize($unsigned_logs));
					}								
					
			} else { // If Folder already exists delete email
				imap_delete($inbox, $overview[0]->msgno);
			}
								
				
			} else { // If no attachments delete email
				imap_delete($inbox, $overview[0]->msgno);
			}

		} // foreach overview	

	} //if ther are emails
	
	$imap_error_logs[] = imap_errors();	
	$imap_alert_logs[] = imap_alerts();
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