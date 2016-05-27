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
$log_path = "/logs/";
$archive_log_path = "/logs/archives/unsent-logs-archive/";

$log_files = glob($_SERVER['DOCUMENT_ROOT'] . "/admin".$log_path."unsigned-*.log");
$archive_log_files = glob($_SERVER['DOCUMENT_ROOT'] . "/admin".$archive_log_path."unsigned-*.log");

//pre($archive_log_files);

if (!empty($archive_log_files)) {
$log_files = array_merge($log_files, $archive_log_files);
}

if (!empty($log_files)) {
$dates = array();
	foreach($log_files as $file) {
	$date = substr($file , -14, 10);
	
		if (!in_array($date, $dates)) {
		$dates[] = $date;	
		}
	}	
rsort($dates);	 
//pre($dates);
}

if (isset($_POST['change_logs'])) {
	
	$change_logs_date = $_POST['log_date'];
	
	if ($change_logs_date != $log_date) {
	$log_path = $archive_log_path;	
	}

	$log_date = $change_logs_date;
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$log_path.'unsigned-'.$log_date.'.log')) {
$raw_unsigned_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin'.$log_path.'unsigned-'.$log_date.'.log');	
$unsigned_data = unserialize($raw_unsigned_data);	
//echo '<pre class="debug">';print_r($unsigned_data);echo '</pre>';
}	
?>
</head>
<body>
	
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/col-strip.php'); ?>
	
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/inc/html/admin-header.inc'); ?>
		
	<section class="messages">
		<div class="container">
			<div class="well well-lg text-center bg-gray no-border">
				<h2>Unsigned documents</h2>
				<p class="lead">If a document has not been signed by a client.<br>Use the 'Re-send email' button to send the client a notification email.</p>
			</div>
		</div>
	</section>

	<main class="main-content">
		
		<div class="container">

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
			
			<div class="well well-lg table-responsive">
				
				<?php if (count($log_files) > 1) { ?>
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
				
				<?php if ($unsigned_data) { ?>
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
						
						<?php foreach ($unsigned_data as $data) { ?>
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
								<?php if (is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$data['ref'])) { ?>
									<a href="<?php echo SITEROOT; ?>/sendmail/?cref=<?php echo $data['ref']; ?>" title="Re-send email" class="btn btn-<?php echo ($data['sent']) ? 'success ':'danger '; ?>btn-block">Re-send email</a>		
								<?php } else { ?>
									<strong class="text-success">Document is signed <i class="glyphicon glyphicon-ok pull-right"></i></strong>
								<?php } ?>
								
							</td>
						</tr>
						<?php } ?>	
						
					</tbody>
				</table>
				<?php } else { ?>
			</div>

			<div class="alert alert-info text-center">
				<h3>Nothing to sign</h3>
				<p>There are no unsigned documents at the moment.</p>
			</div>
			<?php } ?>
					
		</div><!-- .container -->
			
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