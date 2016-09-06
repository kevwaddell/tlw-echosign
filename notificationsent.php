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
<title>TLW Solicitors | Secure Sign</title>
<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<link rel="shortcut icon" href="/favicon.ico">
<link rel="stylesheet" href="<?php echo SITEROOT; ?>/assets/css/global-css.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<?php 
$log_date = date('Y-m-d', time());

if ( isset($_GET['cref']) ) {	
	
	$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].$_GET['cref'].'/data.txt');
	$data = unserialize($raw_data);
	
	$name = $data['firstname']. " " .$data['lastname'];	
	
} else {
	header("Location: ". SITEROOT ."/");		
}
?>
</head>
<body>
	
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/col-strip.php'); ?>
	
	<header class="messages">
		
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
					
					<?php if (isset($_GET['sent']) && $_GET['sent'] == 1) { ?>
					<div class="alert alert-success text-center">
						<h2>Notification sent</h2>
						<p>The notification email has been sent successfully to:<br>
						Client name: <strong><?php echo $name; ?></strong>
						Client ref: <strong><?php echo $data['ref']; ?></strong>
						</p>
						<p>If the client does not complete the agreement in 2 days another email will be sent to <strong><?php echo $data['handler']; ?></strong> to request another notification email.</p><br>

						<a href="<?php echo SITEROOT; ?>/" class="btn btn-primary btn-lg btn-block caps"><i class="glyphicon glyphicon glyphicon-circle-arrow-left pull-left"></i>Continue</a>
					</div>
					<?php } ?>
					
					<?php if (isset($_GET['sent']) && $_GET['sent'] == 0) { ?>
					<?php
						$error_type = "";
	
						switch ($_GET['error']) {
						case "email": $error_type = "Email not sending";
						break;
						case "nodir": $error_type = "Client folder does not exist" ;
						break;
						}
					?>
					<div class="alert alert-danger text-center">
						<h2>Sorry</h2>
						<?php if ($error_type != "") { ?>
						<p class="bold">Error type: <?php echo $error_type; ?></p>
						<?php } ?>
						<p>There was a problem sending the notification email</p>
						<p>Please contact the website administrator and quote the error type.<br> 
						Email: <a href="mailto:webmaster@tlwsolicitors.co.uk">webmaster@tlwsolicitors.co.uk</a><br> 
						Ext: 210.</p><br>

						<a href="<?php echo SITEROOT; ?>/" class="btn btn-default btn-block"><i class="glyphicon glyphicon-remove pull-left"></i>Cancel</a>
					</div>
					<?php } ?>
						
				</div>
			</div>
		</div>

	</header>

	<footer class="app-info">
		<div class="container">
			<small>&copy; TLW Solicitors 2016. All rights reserved.</small>
		</div>
	</footer>

</body>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="<?php echo SITEROOT; ?>/assets/js/app.js"></script>
</html>