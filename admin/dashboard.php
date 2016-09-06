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

$log_date = date('Y-m-d', time());
$email_data = array();
$signed_data = array();
$unsigned_data = array();

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/email-logs-'.$log_date.'.log')) {
	$raw_email_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/email-logs-'.$log_date.'.log');	
	$email_data = unserialize($raw_email_data);	
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/signed-'.$log_date.'.log')) {
	$raw_signed_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/signed-'.$log_date.'.log');	
	$signed_data = unserialize($raw_signed_data);	
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log')) {
	$raw_unsigned_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log');	
	$unsigned_data = unserialize($raw_unsigned_data);	
}	

?>
</head>
<body>
	
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/col-strip.php'); ?>
	
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/inc/html/admin-header.inc'); ?>
	
	<main class="main-content">
		
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					
					<div class="panel panel-default">
					  <div class="panel-heading"><h3 class="panel-title">Todays Email Logs<i class="glyphicon glyphicon-envelope pull-right"></i></h3></div>
					  <div class="panel-body" style="min-height: 300px;">
					   <?php if (empty($email_data)) { ?>
					   	<p class="lg-txt">There are no email logs today.</p>
					   <?php } else { ?>
					   	<p>The Esign inbox has been checked <?php echo (count($email_data) > 1) ? count($email_data).' times':'once'; ?> today.</p>
					   	<a href="<?php echo SITEROOT; ?><?php echo '/admin/logs/email-logs-'.$log_date.'.log'; ?>" target="_blank" class="btn btn-lg btn-default btn-block">View todays logs <i class="glyphicon glyphicon-download pull-right"></i></a>	
					   <?php } ?>
					  </div>
					</div>	
									
				</div>
				<div class="col-md-6">
					
					<div class="panel panel-default">
						<div class="panel-heading"><h3 class="panel-title">Today's signature logs<i class="glyphicon glyphicon-pencil pull-right"></i></h3></div>
						<div class="panel-body" style="min-height: 300px;">
						   <h4>Signed documents</h4>
						   <?php if (empty($signed_data)) { ?>
						   	<p>There are no signed documents today.</p>
						   <?php } else { ?>
						   		
						   		<?php if (count($signed_data) > 1) { ?>
						   			<p>There have been <strong><?php echo count($signed_data); ?></strong> signed documents today.</p>	
						   		<?php } else { ?>	
						   			<p>There is <strong>1</strong> signed document today.</p>
						   		<?php } ?>
						   	
						   	<a href="<?php echo SITEROOT; ?><?php echo '/admin/logs/signed-'.$log_date.'.log'; ?>" target="_blank" class="btn btn-lg btn-default btn-block">View signed logs <i class="glyphicon glyphicon-download pull-right"></i></a>	
						   <?php } ?>
						   <hr>
						   <h4>Unsigned documents</h4>
						   <?php if (empty($unsigned_data)) { ?>
						   	<p>There are no unsigned documents today.</p>
						   <?php } else { ?>
						   
						   		<?php if (count($unsigned_data) > 1) { ?>
						   			<p>There are <strong><?php echo count($unsigned_data); ?></strong> unsigned documents today.</p>	
						   		<?php } else { ?>	
						   			<p>There is <strong>1</strong> unsigned document today.</p>
						   		<?php } ?>

						   	<a href="<?php echo SITEROOT; ?><?php echo '/admin/logs/unsigned-'.$log_date.'.log'; ?>" target="_blank" class="btn btn-lg btn-default btn-block">View unsigned logs <i class="glyphicon glyphicon-download"></i></a>	
						   <?php } ?>
					   
					  	</div>
					</div>	
								
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title">Quick Links<i class="glyphicon glyphicon-chain pull-right"></i></h3></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-6">
							<a href="<?php echo SITEROOT; ?>/getmail/" class="btn btn-success btn-lg btn-block" target="_blank">Get mail<i class="glyphicon glyphicon-menu-right pull-right"></i></a>
						</div>
						<div class="col-xs-6">
							<a href="<?php echo SITEROOT; ?>/checkunsigned/" class="btn btn-success btn-lg btn-block" target="_blank">Check unsigned emails<i class="glyphicon glyphicon-menu-right pull-right"></i></a>
						</div>
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