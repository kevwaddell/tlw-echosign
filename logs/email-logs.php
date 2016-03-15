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
<title>TLW Solicitors | Email logs</title>
<link rel="stylesheet" href="<?php echo SITEROOT; ?>/assets/css/global-css.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<?php
$imap_error_logs = false;
$email_data = false;
$log_date = date('Y-m-d', time());

$log_files = glob(dirname(__FILE__) . "/email-logs-*.log");

if (!empty($log_files)) {
$dates = array();
	foreach($log_files as $file) {
	$date = substr($file , -14, 10);
	
		if (!in_array($date, $dates)) {
		$dates[] = $date;	
		}
	}	
rsort($dates);	 
pre($dates);
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/logs/email-logs-'.$log_date.'.log')) {
$raw_email_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/email-logs-'.$log_date.'.log');	
$email_data = unserialize($raw_email_data);	
}	
?>
</head>
<body>
	
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/col-strip.php'); ?>
	
	<header class="messages">
		
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<nav class="navbar navbar-default">
				    <!-- Brand and toggle get grouped for better mobile display -->
				    <div class="navbar-header">
				      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				        <span class="sr-only">Toggle navigation</span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				      </button>
				      <a class="navbar-brand" href="/">
					      <img alt="TLW Solicitors Esign" src="<?php echo SITEROOT; ?>/assets/img/tlw-logo-wide.svg">
					   </a>
				    </div>
				
				    <!-- Collect the nav links, forms, and other content for toggling -->
				    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				      <ul class="nav navbar-nav navbar-right">
					    <li class="active"><a href="<?php echo SITEROOT; ?>/logs/email-logs/">Email Logs</a></li>
				        <li><a href="<?php echo SITEROOT; ?>/logs/unsigned/">Unsigned Documents</a></li>
				        <li><a href="<?php echo SITEROOT; ?>/logs/sent-data/">Signed documents</a></li>
				      </ul>
					</div><!-- /.navbar-collapse -->
				   
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					
					<div class="well well-lg text-center bg-gray no-border">
						<h2>Email success and error logs</h2>
						<p class="lead">The lists below details the inbound emails by date and any IMAP errors when fetching emails.</p>
					</div>
						
				</div>
			</div>
		</div>

	</header>

	<main class="main-content">
		
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<?php if ($email_data) { ?>
					<div class="well well-lg table-responsive">
						
						<?php if (count($log_files) > 1) { ?>
							<div class="filter-form">
								<form method="post" action="">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<select name="log-date" class="form-control">
													<option value="0">Select a log date</option>
													<?php foreach ($dates as $date) { ?>
													<option value="<?php echo $date; ?>"><?php echo date("jS F, Y", strtotime($date)); ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-sm-6">
											<input type="submit" value="View Logs" class="btn btn-default btn-block">
										</div>
									</div>
								</form>
							</div>
						<?php } ?>
						
						<h3 class="text-center">Successful inbound emails</h3>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Date/time</th>
									<th>Total messages</th>
									<th>Unread messages</th>
									<th>Deleted messages</th>
								</tr>
							</thead>
							<tbody>
								
								<?php foreach ($email_data as $data) { ?>
								<tr>
									<td><?php echo gmdate('jS F, Y, g:ia', $data['check-date']); ?></td>
									<td><?php echo $data['Nmsgs']; ?></td>
									<td><?php echo $data['Unread']; ?></td>
									<td><?php echo $data['Deleted']; ?></td>
								</tr>
								<?php } ?>	
								
							</tbody>
						</table>
					</div>
					<?php } else { ?>
					<div class="alert alert-info text-center">
						<h3>No email logs available</h3>
						<p>There is no logs available for inbound emails at the moment.</p>
					</div>
					<?php } ?>
					
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