<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
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
$rmv_date = strtotime("this month");
//pre($_SERVER);
if (isset($_GET['rmv-email-logs'])) { 
$email_logs = glob($_SERVER['DOCUMENT_ROOT']."/admin/logs/archives/email-logs-archive/email-logs-".$_GET['rmv-email-logs']."-*.log");
	
	if (!empty($email_logs)) {
	$email_logs_zip = new ZipArchive();	
	$email_logs_zip->open($_SERVER['DOCUMENT_ROOT']."/admin/logs/archives/email-logs-archive/email-logs-".$_GET['rmv-email-logs'].".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
		
		foreach($email_logs as $el) {
		$parts = split('/', $el);
		//pre($parts);
		$email_logs_zip->addFile($el, $parts[count($parts) - 1]);
		}	
		
		if ( $email_logs_zip->close() ){
			foreach($email_logs as $log) {
			unlink($log);	
			}
			header("Location: ". $_SERVER['[HTTP_REFERER]'] ."?email-logs-rmvd=1");	
		}
	} else {
	header("Location: ". $_SERVER['[HTTP_REFERER]'] ."?email-logs-rmvd=0");		
	}
}

if (isset($_GET['rmv-sent-logs'])) { 
$sent_logs = glob($_SERVER['DOCUMENT_ROOT']."/admin/logs/archives/sent-logs-archive/sent-data-".$_GET['rmv-sent-logs']."-*.log");
	
	if (!empty($sent_logs)) {
	$sent_logs_zip = new ZipArchive();	
	$sent_logs_zip->open($_SERVER['DOCUMENT_ROOT']."/admin/logs/archives/sent-logs-archive/sent-data-".$_GET['rmv-sent-logs'].".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
		
		foreach($sent_logs as $sl) {
		$parts = split('/', $sl);
		//pre($parts);
		$sent_logs_zip->addFile($sl, $parts[count($parts) - 1]);
		}	
		
		if ( $sent_logs_zip->close() ){
			foreach($sent_logs as $log) {
			unlink($log);	
			}
			header("Location: ". $_SERVER['[HTTP_REFERER]'] ."?sent-logs-rmvd=1");	
		}
	} else {
	header("Location: ". $_SERVER['[HTTP_REFERER]'] ."?sent-logs-rmvd=0");		
	}
}

?>
</head>
<body>
	
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/col-strip.php'); ?>
	
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/inc/html/admin-header.inc'); ?>
	
	<main class="main-content">
		
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<?php if (isset($_GET['zipped']) && $_GET['zipped'] == 1) { ?>
					<div class="alert alert-success text-center alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<p>All signed folders have been converted to zip files successfully.</p>
					</div>			
					<?php } ?>
					<?php if (isset($_GET['zips-deleted']) && $_GET['zips-deleted'] == 1) { ?>
					<div class="alert alert-success text-center alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<p>All signed converted zip files have been removed and sent to <a href="mailto:<?php echo TLW_IT_EMAIL; ?>"><?php echo TLW_IT_EMAIL; ?></a>.</p>
					</div>			
					<?php } ?>
					
					<?php if (isset($_GET['email-logs-rmvd']) && $_GET['email-logs-rmvd'] == 1) { ?>
					<div class="alert alert-success text-center alert-dismissible" role="alert">
						<p>All email logs for <strong><?php echo date('M Y', $rmv_date); ?></strong> have been archived and removed.</p><br>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-success btn-block">Continue</a>
					</div>			
					<?php } ?>
					
					<?php if (isset($_GET['email-logs-rmvd']) && $_GET['email-logs-rmvd'] == 0) { ?>
					<div class="alert alert-danger text-center alert-dismissible" role="alert">
						<p>There are no logs to remove for <strong><?php echo date('M Y', $rmv_date); ?></strong>.</p><br>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-danger btn-block">Continue</a>
					</div>			
					<?php } ?>
					
					<?php if (isset($_GET['sent-logs-rmvd']) && $_GET['sent-logs-rmvd'] == 1) { ?>
					<div class="alert alert-success text-center alert-dismissible" role="alert">
						<p>All sent data logs for <strong><?php echo date('M Y', $rmv_date); ?></strong> have been archived and removed.</p><br>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-success btn-block">Continue</a>
					</div>			
					<?php } ?>
					
					<?php if (isset($_GET['sent-logs-rmvd']) && $_GET['sent-logs-rmvd'] == 0) { ?>
					<div class="alert alert-danger text-center alert-dismissible" role="alert">
						<p>There are no sent data logs to remove for <strong><?php echo date('M Y', $rmv_date); ?></strong>.</p><br>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-danger btn-block">Continue</a>
					</div>			
					<?php } ?>
					
					<div class="well well-lg">
					<h3 class="caps text-center" style="margin-top: 0px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid gray;">Zipped files</h3>
						<a href="<?php echo SITEROOT; ?>/signed/flushsig/?zip=all" class="btn btn-info btn-block btn-lg">Zip all signed folders <i class="glyphicon glyphicon-folder-close pull-right"></i></a>
						<a href="<?php echo SITEROOT; ?>/signed/rmvzip/?rmv=all" class="btn btn-danger btn-block btn-lg">Delete all zipped files <i class="glyphicon glyphicon-trash pull-right"></i></a>
					</div>	
					<div class="well well-lg">
						<h3 class="caps text-center" style="margin-top: 0px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid gray;">Log Archives</h3>
						<a href="<?php echo $_SERVER['REQUEST_URI']; ?>?rmv-email-logs=<?php echo date('Y-m', $rmv_date); ?>" class="btn btn-danger btn-block btn-lg">Remove email logs for <?php echo date('M Y', $rmv_date); ?><i class="glyphicon glyphicon-remove pull-right"></i></a>
						<a href="<?php echo $_SERVER['REQUEST_URI']; ?>?rmv-sent-logs=<?php echo date('Y-m', $rmv_date); ?>" class="btn btn-danger btn-block btn-lg">Remove sent logs for <?php echo date('M Y', $rmv_date); ?><i class="glyphicon glyphicon-remove pull-right"></i></a>
						<a href="<?php echo $_SERVER['REQUEST_URI']; ?>?rmv-insigned-logs=<?php echo date('Y-m', $rmv_date); ?>" class="btn btn-danger btn-block btn-lg">Remove unsigned logs for <?php echo date('M Y', $rmv_date); ?><i class="glyphicon glyphicon-remove pull-right"></i></a>
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