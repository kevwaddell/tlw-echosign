<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="robots" content="noindex,nofollow">
<title>TLW Solicitors | esign administration area</title>
<link rel="stylesheet" href="<?php echo SITEROOT; ?>/assets/css/global-css.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<?php
function encryptIt( $q ) {
$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
$qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
return( $qEncoded );
}

function decryptIt( $q ) {
$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
$qDecoded  = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
return( $qDecoded );
}

$settings = array();
$smtp_settings = array();	
$errors = array();
$src_email = "";
$reply_email = "";
$import_email = "";
$it_admin_email = "";
$smtp_host = "";
$smtp_port = 0;
$smtp_user = "";
$smtp_pwd = "";

if (SITEHOST == 'www.tlwsolicitors-esign.co.uk') {
$settings_log = "live_settings.log";
	} else {
$settings_log = "dev_settings.log";
}

if (SITEHOST == 'tlw-echosign.dev') {
$smtp_log = "smtp_local_settings.log";
	} else {
$smtp_log = "smtp_online_settings.log";
}	

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$settings_log)) {
$settings_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$settings_log); 
	if (!empty($settings_raw)) {
	$settings = unserialize($settings_raw);	
	
	$src_email = $settings['src_email'];
	$reply_email = $settings['reply_email'];
	$import_email = $settings['import_email'];
	$it_admin_email = $settings['it_admin_email'];
	}
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$smtp_log)) {
$smtp_settings_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$smtp_log); 
	if (!empty($smtp_settings_raw)) {
	
	$smtp_settings = unserialize($smtp_settings_raw);	
	
	$smtp_host = $smtp_settings['smtp_host'];
	$smtp_port = $smtp_settings['smtp_port'];
	$smtp_user = $smtp_settings['smtp_user'];
	$smtp_pwd = decryptIt($smtp_settings['smtp_pwd']);
	}
}

if ( isset($_POST['update_email_settings']) ) {	
	
	if ( trim($_POST['src_email']) == "") {
	$errors['src_email'] = "Please enter a <b>Source</b> email address.";
	} else {
	$settings['src_email'] = trim($_POST['src_email']);
	}
	
	if ( trim($_POST['reply_email']) == "") {
	$errors['reply_email'] = "Please enter a <b>Reply</b> email address.";	
	} else {
	$settings['reply_email'] = trim($_POST['reply_email']);	
	}
	
	if ( trim($_POST['import_email']) == "") {
	$errors['import_email'] = "Please enter a <b>Import</b> email address.";	
	} else {
	$settings['import_email'] = trim($_POST['import_email']);	
	}
	
	if ( trim($_POST['it_admin_email']) == "") {
	$errors['it_admin_email'] = "Please enter a <b>IT Administrator</b> email address.";	
	} else {
	$settings['it_admin_email'] = trim($_POST['it_admin_email']);	
	}
	
	if (!empty($settings) && empty($errors)) {
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$settings_log, serialize($settings));	
	
	$settings_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$settings_log); 
	$settings = unserialize($settings_raw);	
	}

}

if ( isset($_POST['update_smtp_settings']) ) {	

	if ( trim($_POST['smtp_host']) == "") {
	$errors['smtp_host'] = "Please enter a SMTP <b>Host</b> e.g smtp.gamil.com.";
	} else {
	$smtp_settings['smtp_host'] = trim($_POST['smtp_host']);
	}
	
	if ( trim($_POST['smtp_port']) == "") {
	$errors['smtp_port'] = "Please enter a SMTP <b>Port</b> e.g 25.";	
	} else {
	$smtp_settings['smtp_port'] = trim($_POST['smtp_port']);	
	}
	
	if ( trim($_POST['smtp_user']) == "") {
	$errors['smtp_user'] = "Please enter the <b>Username</b> for the SMPT account.";	
	} else {
	$smtp_settings['smtp_user'] = trim($_POST['smtp_user']);	
	}
	
	if ( trim($_POST['smtp_pwd']) == "") {
	$errors['smtp_pwd'] = "Please enter the <b>Password</b> for the SMPT account.";		
	} else {
	$smtp_settings['smtp_pwd'] = encryptIt($_POST['smtp_pwd']);
	}
	
	if (!empty($smtp_settings) && empty($smtp_errors)) {
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$smtp_log, serialize($smtp_settings));	
	
	$smtp_settings_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/'.$smtp_log); 
	$smtp_settings = unserialize($smtp_settings_raw);	
	}

}

//pre($settings);	

?>
</head>
<body>
	
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/col-strip.php'); ?>
	
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/inc/html/admin-header.inc'); ?>
	
	<main class="main-content">
		
		<div class="container">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<div class="well well-lg">
						<form method="post" action="">
							<h3 class="caps text-center" style="margin-top: 0px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid gray;">Email Settings</h3>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="src_email"><span>*</span>Source email:</label>
									<input type="text" name="src_email" class="form-control input-lg text-center" placeholder="source@email.com" value="<?php echo $src_email; ?>">
									<span id="helpBlock" class="help-block">The email address of the inbox that will collect the documents to be processed.</span>
								</div>
								<div class="form-group required">
									<label for="reply_email"><span>*</span>Reply email:</label>
									<input type="text" name="reply_email" class="form-control input-lg text-center" placeholder="replyto@email.com" value="<?php echo $reply_email; ?>">
									<span id="helpBlock" class="help-block">The Reply email address that will be set in the confirmation emails to the clients.</span>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group required">
									<label for="import_email"><span>*</span>Import email:</label>
									<input type="text" name="import_email" class="form-control input-lg text-center" placeholder="import@email.com" value="<?php echo $import_email; ?>">
									<span id="helpBlock" class="help-block">The import email address is where the PDF of the signed document will be sent to.</span>
								</div>
								<div class="form-group required">
									<label for="import_email"><span>*</span>IT email:</label>
									<input type="text" name="it_admin_email" class="form-control input-lg text-center" placeholder="IT@email.com" value="<?php echo $it_admin_email; ?>">
									<span id="helpBlock" class="help-block">The IT Administrator email address where archived zipped client files will be sent to.</span>
								</div>
							</div>
							<input type="submit" name="update_email_settings" value="Update" class="btn btn-success btn-lg btn-block caps">
						</form>
						
						<form method="post" action="">
							<h3 class="caps text-center" style="margin-top: 0px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid gray;">SMTP Settings</h3>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="smtp_host"><span>*</span>Host:</label>
									<input type="text" name="smtp_host" class="form-control input-lg text-center" placeholder="smtp.host.com" value="<?php echo $smtp_host; ?>">
									<span id="helpBlock" class="help-block">The host addret of the SMPT server.</span>
								</div>
								<div class="form-group required">
									<label for="smtp_port"><span>*</span>Port:</label>
									<input type="text" name="smtp_port" class="form-control input-lg text-center" placeholder="e.g 25" value="<?php echo $smtp_port; ?>">
									<span id="helpBlock" class="help-block">SMTP Port e.g 25.</span>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group required">
									<label for="smtp_user"><span>*</span>Username:</label>
									<input type="text" name="smtp_user" class="form-control input-lg text-center" placeholder="SMTP Username" value="<?php echo $smtp_user; ?>">
									<span id="helpBlock" class="help-block">The Username for the SMTP Host.</span>
								</div>
								<div class="form-group required">
									<label for="smtp_pwd"><span>*</span>Password:</label>
									<input type="password" name="smtp_pwd" class="form-control input-lg text-center" value="<?php echo $smtp_pwd; ?>">
									<span id="helpBlock" class="help-block">The Password for the SMTP Host</span>
								</div>
							</div>
							<input type="submit" name="update_smtp_settings" value="Update" class="btn btn-success btn-lg btn-block caps">
						</form>
					</div>
					
				</div>
			</div>
		</div>
			
	</main>

	<footer class="app-info">
		<div class="container">
			<small>&copy; TLW Solicitors 2016. All rights reserved.</small>
		</div>
	</footer>

</body>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="<?php echo SITEROOT; ?>/assets/js/app.js"></script>
</html>