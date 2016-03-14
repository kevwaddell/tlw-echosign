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
<title>TLW Solicitors | Unsigned Document logs</title>
<link rel="stylesheet" href="<?php echo SITEROOT; ?>/assets/css/global-css.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<?php
$unsigned_data = false;
$log_date = date('Y-m-d', time());

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned-'.$log_date.'.log')) {
$raw_unsigned_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/unsigned-'.$log_date.'.log');	
$unsigned_data = unserialize($raw_unsigned_data);	
echo '<pre class="debug">';print_r($unsigned_data);echo '</pre>';
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
					    <li><a href="<?php echo SITEROOT; ?>/logs/email-logs/">Email Logs</a></li>
				        <li class="active"><a href="<?php echo SITEROOT; ?>/logs/unsigned/">Unsigned Documents</a></li>
				        <li><a href="<?php echo SITEROOT; ?>/logs/sent-data/">Signed documents</a></li>
				      </ul>
					</div><!-- /.navbar-collapse -->
				   
					</nav>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-10 col-md-offset-1">

					<div class="well well-lg text-center bg-gray no-border">
						<h2>Unsigned documents</h2>
						<p class="lead">If a document has not been signed by a client.<br>Use the 'Re-send email' button to send the client a notification email.</p>
					</div>
						
				</div>
			</div>
		</div>

	</header>

	<main class="main-content">
		
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<?php if (isset($_GET['sent']) && $_GET['sent'] == 1) { ?>
						<div class="alert alert-success text-center alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<p>Notification Email for client ref <strong><?php echo $_GET['cref']; ?></strong> has been re-sent successfully.</p>
						</div>		
					<?php } ?>
					
					<?php if (isset($_GET['sent']) && $_GET['sent'] == 0) { ?>
						<div class="alert alert-danger text-center alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<p>Notification Email for client ref <strong><?php echo $_GET['cref']; ?></strong> has not been sent.</p>
							<p>Please try again. If the problem persists contact the <a href="mailto:webmaster@tlwsolicitors.co.uk">Website Administrator</a>.</p>
						</div>		
					<?php } ?>
					
					<?php if ($unsigned_data) { 
					//echo '<pre>';print_r($unsigned_data);echo '</pre>';
					?>
					<div class="well well-lg table-responsive">
						<h3 class="text-center">Document details</h3>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Client ref</th>
									<th class="text-center">Token</th>
									<th class="text-center">Sent date</th>
									<th class="text-center">Actions</th>
								</tr>
							</thead>
							<tbody>
								
								<?php foreach ($unsigned_data as $data) { 
								$data = unserialize($data);
								?>
								<tr>
									<td style="vertical-align: middle;"><?php echo $data['ref']; ?></td>
									<td class="text-center" style="vertical-align: middle;"><?php echo $data['tkn']; ?></td>
									<td class="text-center" style="vertical-align: middle;">
										<?php if ($data['sent']) { ?>
										<?php echo gmdate('jS F, Y @ g:ia', $data['sent']); ?>
										<?php } else { ?>
										<strong class="text-danger caps">Notification email not sent</strong>
										<?php } ?>
									</td>
									<td class="text-center" style="vertical-align: middle;">
									<a href="<?php echo SITEROOT; ?>/sendmail/?cref=<?php echo $data['ref']; ?>" title="Re-send email" class="btn btn-<?php echo ($data['sent']) ? 'success ':'danger '; ?>btn-block">Re-send email</a>	
									</td>
								</tr>
								<?php } ?>	
								
							</tbody>
						</table>
					</div>
					<?php } else { ?>
					<div class="alert alert-info text-center">
						<h3>Nothing to sign</h3>
						<p>There are no unsigned documents at the moment.</p>
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