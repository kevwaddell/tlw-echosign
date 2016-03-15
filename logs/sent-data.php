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
<title>TLW Solicitors | Sent client documents</title>
<link rel="stylesheet" href="<?php echo SITEROOT; ?>/assets/css/global-css.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<?php
$sent_data = false;
$log_date = date('Y-m-d', time());

$log_files = glob(dirname(__FILE__) . "/sent-data-*.log");

if (!empty($log_files)) {
$dates = array();
	foreach($log_files as $file) {
	$date = substr($file , -14, 10);
		if (!in_array($date, $dates)) {
		$dates[] = $date;	
		}
	}	
	
	if (!in_array($log_date, $dates)) {
	$dates[] = $log_date;
	}
	rsort($dates);	 
//pre($dates);
}

if (isset($_POST['change_logs'])) {
$log_date = $_POST['log_date'];		
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/logs/sent-data-'.$log_date.'.log')) {
$raw_sent_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/sent-data-'.$log_date.'.log');	
$sent_data = unserialize($raw_sent_data);	
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
				        <li><a href="<?php echo SITEROOT; ?>/logs/unsigned/">Unsigned Documents</a></li>
				        <li class="active"><a href="<?php echo SITEROOT; ?>/logs/sent-data/">Signed documents</a></li>
				      </ul>
					</div><!-- /.navbar-collapse -->
				   
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					
					<div class="well well-lg text-center bg-gray no-border">
						<?php //echo realpath('sent-data.php'); ?>
						<h2>Signed client documents</h2>
						<p class="lead">The lists below details the dates and client references of documents that have been signed.</p>
					</div>
						
				</div>
			</div>
		</div>

	</header>

	<main class="main-content">
		
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					
					<?php if (isset($_GET['dwnld']) && $_GET['dwnld'] == 1) { ?>
						<div class="alert alert-success text-center alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<p class="lead">The document data you requested for client ref <strong><?php echo $_GET['cref']; ?></strong> is ready do download</p>
							<p style="margin-top: 10px;">
								<a href="<?php echo SITEROOT; ?>/signed/<?php echo $_GET['tkn']; ?>@<?php echo $_GET['cref']; ?>.zip" class="btn btn-success btn-lg" target="_blank;"><i class="glyphicon glyphicon-share"></i> Download Zip</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo SITEROOT; ?>/logs/sent-data/" class="btn btn-default btn-lg" target="_blank;"><i class="glyphicon glyphicon-remove"></i> Cancel</a>
								</p>
						</div>		
					<?php } ?>
					
					<?php if (isset($_GET['dwnld']) && $_GET['dwnld'] == 0) { ?>
						<div class="alert alert-danger text-center alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<p class="lead">Sorry the document data you requested for<br>client ref <strong><?php echo $_GET['cref']; ?></strong> is no longer available to download</p>
							<p style="margin-top: 10px;">
								<a href="<?php echo SITEROOT; ?>/logs/sent-data/" class="btn btn-success" target="_blank;"><i class="glyphicon glyphicon-refresh"></i> Continue</a>
							</p>
						</div>		
					<?php } ?>
					
					<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1) { ?>
						<div class="alert alert-success text-center alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<p class="lead">All data for client ref <strong><?php echo $_GET['cref']; ?></strong> has been removed.</p>
							<p>A back-up copy of the data has been sent to <strong>webmaster@tlwsolicitors.co.uk</strong></p>
							<p style="margin-top: 10px;">
								<a href="<?php echo SITEROOT; ?>/logs/sent-data/" class="btn btn-success" target="_blank;"><i class="glyphicon glyphicon-refresh"></i> Continue</a>
							</p>
						</div>		
					<?php } ?>
					
					<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 0) { ?>
						<div class="alert alert-danger text-center alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<p class="lead">Data for client ref <strong><?php echo $_GET['cref']; ?></strong> cannot be removed.</p>
							<p>Please contact <strong><a href="mailto:webmaster@tlwsolicitors.co.uk">webmaster@tlwsolicitors.co.uk</a></strong> to remove this data.</p>
							<p style="margin-top: 10px;">
								<a href="<?php echo SITEROOT; ?>/logs/sent-data/" class="btn btn-danger" target="_blank;"><i class="glyphicon glyphicon-refresh"></i> Continue</a>
							</p>
						</div>		
					<?php } ?>
					
					<?php if (!empty($log_files)) { ?>
					<div class="filter-form">
						<form method="post" action="">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<select name="log_date" class="form-control">
											<option value="0">Select a log date</option>
											<?php foreach ($dates as $date) { ?>
											<option value="<?php echo $date; ?>"<?php echo($date == $log_date) ? ' selected="selected"':'' ?>><?php echo date("jS F, Y", strtotime($date)); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-sm-6">
									<input type="submit" name="change_logs" value="View Logs" class="btn btn-default btn-block">
								</div>
							</div>
						</form>
					</div>
				<?php } ?>
					
					<?php if ($sent_data) { ?>
					<div class="well well-lg table-responsive">

						<h3 class="text-center">Signed documents - <?php echo date("jS F, Y", strtotime($log_date)); ?></h3>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Date signed</th>
									<th>Removal date</th>
									<th width="15%" class="text-center">Client ref</th>
									<th width="20%" class="text-center">Actions</th>
								</tr>
							</thead>
							<tbody>
								
								<?php foreach ($sent_data as $data) { ?>
								<tr>
									<td style="vertical-align: middle;"><?php echo gmdate('jS F, Y, g:ia', $data['sdate']); ?></td>
									<td style="vertical-align: middle;"><?php echo gmdate('jS F, Y, g:ia', $data['rdate']); ?></td>
									<td class="text-center" style="vertical-align: middle;"><?php echo $data['ref']; ?></td>
									<td class="text-center" style="vertical-align: middle;">
										<div class="btn-group btn-group-lg" role="toolbar">
											<a href="<?php echo SITEROOT; ?>/signed/rmvzip/?tkn=<?php echo $data['tkn']; ?>&cref=<?php echo $data['ref']; ?>" title="Delete" class="btn btn-default"><i class="glyphicon glyphicon-trash"></i><span class="sr-only">Delete</span></a>			
											<a href="<?php echo SITEROOT; ?>/signed/dwnldzip/?tkn=<?php echo $data['tkn']; ?>&cref=<?php echo $data['ref']; ?>" title="Download" class="btn btn-default"><i class="glyphicon glyphicon-save"></i><span class="sr-only">Download</span></a>
										</div>
										</td>
								</tr>
								<?php } ?>	
								
							</tbody>
						</table>
					</div>
					<?php } else { ?>
					<div class="alert alert-info text-center">
						<h3>No signed documents for <?php echo date("jS F, Y", strtotime($log_date)); ?></h3>
						<p>There is no data available for signed client documents.</p>
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