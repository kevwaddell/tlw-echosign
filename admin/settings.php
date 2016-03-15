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
$settings = array();	
$errors = array();
$src_email = "";
$reply_email = "";
$import_email = "";
$it_admin_email = "";

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/inc/live_settings.log')) {
$settings_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/live_settings.log'); 
	if (!empty($settings_raw)) {
	$settings = unserialize($settings_raw);	
	}
}

if ( !empty($_POST) ) {	
	
	if ( trim($_POST['src_email']) == "") {
	$errors[] = "Please enter a <b>Source</b> email address.";
	} else {
	$settings['src_email'] = trim($_POST['src_email']);
	}
	
	if ( trim($_POST['reply_email']) == "") {
	$errors[] = "Please enter a <b>Reply</b> email address.";	
	} else {
	$settings['reply_email'] = trim($_POST['reply_email']);	
	}
	
	if ( trim($_POST['import_email']) == "") {
	$errors[] = "Please enter a <b>Import</b> email address.";	
	} else {
	$settings['import_email'] = trim($_POST['import_email']);	
	}
	
	if ( trim($_POST['it_admin_email']) == "") {
	$errors[] = "Please enter a <b>IT Administrator</b> email address.";	
	} else {
	$settings['it_admin_email'] = trim($_POST['it_admin_email']);	
	}
	
	if (!empty($settings) && empty($errors)) {
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/live_settings.log', serialize($settings));	
	
	$settings_raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/inc/live_settings.log'); 
	$settings = unserialize($settings_raw);	
	}

}
	
$src_email = $settings['src_email'];
$reply_email = $settings['reply_email'];
$import_email = $settings['import_email'];
$it_admin_email = $settings['it_admin_email'];

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
						<h3 class="caps text-center" style="margin-top: 0px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid gray;">Live settings</h3>
						<form method="post" action="">
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="src_email">Source email:</label>
									<input type="text" name="src_email" class="form-control input-lg text-center" placeholder="source@email.com" value="<?php echo $src_email; ?>">
									<span id="helpBlock" class="help-block">The email address of the inbox that will collect the documents to be processed.</span>
								</div>
								<div class="form-group">
									<label for="reply_email">Reply email:</label>
									<input type="text" name="reply_email" class="form-control input-lg text-center" placeholder="replyto@email.com" value="<?php echo $reply_email; ?>">
									<span id="helpBlock" class="help-block">The Reply email address that will be set in the confirmation emails to the clients.</span>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="import_email">Import email:</label>
									<input type="text" name="import_email" class="form-control input-lg text-center" placeholder="import@email.com" value="<?php echo $import_email; ?>">
									<span id="helpBlock" class="help-block">The import email address is where the PDF of the signed document will be sent to.</span>
								</div>
								<div class="form-group">
									<label for="import_email">IT email:</label>
									<input type="text" name="it_admin_email" class="form-control input-lg text-center" placeholder="IT@email.com" value="<?php echo $it_admin_email; ?>">
									<span id="helpBlock" class="help-block">The IT Administrator email address where archived zipped client files will be sent to.</span>
								</div>
							</div>
							<button class="btn btn-success btn-lg btn-block caps"><i class="glyphicon glyphicon-search pull-left"></i>Update</button>
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