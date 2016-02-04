<?php
include_once('../inc/pre-function.php');
include_once('../inc/current_pg_function.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>TLW Solicitors | Sent client documents</title>
<link rel="stylesheet" href="<?php echo $scheme; ?><?php echo $host; ?>/assets/css/global-css.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<?php
if (file_exists('sent_data.txt')) {
$raw_sent_data = file_get_contents('sent_data.txt');	
$sent_data = unserialize($raw_sent_data);	
}	
?>
</head>
<body>
	
	<?php include_once('../inc/html/col-strip.php'); ?>
	
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
					      <img alt="TLW Solicitors Esign" src="<?php echo $scheme; ?><?php echo $host; ?>/assets/img/tlw-logo-wide.svg">
					   </a>
				    </div>
				
				    <!-- Collect the nav links, forms, and other content for toggling -->
				    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				      <ul class="nav navbar-nav navbar-right">
					    <li><a href="<?php echo $scheme; ?><?php echo $host; ?>/logs/email-logs/">Email Logs</a></li>
				        <li><a href="<?php echo $scheme; ?><?php echo $host; ?>/logs/unsigned/">Unsigned Documents</a></li>
				        <li class="active"><a href="<?php echo $scheme; ?><?php echo $host; ?>/logs/sent-data/">Signed documents</a></li>
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
								<a href="<?php echo $scheme; ?><?php echo $host; ?>/signed/<?php echo $_GET['tkn']; ?>@<?php echo $_GET['cref']; ?>.zip" class="btn btn-success btn-lg" target="_blank;"><i class="glyphicon glyphicon-share"></i> Download Zip</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo $scheme; ?><?php echo $host; ?>/logs/sent-data/" class="btn btn-default btn-lg" target="_blank;"><i class="glyphicon glyphicon-remove"></i> Cancel</a>
								</p>
						</div>		
					<?php } ?>
					
					<?php if (isset($_GET['dwnld']) && $_GET['dwnld'] == 0) { ?>
						<div class="alert alert-danger text-center alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<p class="lead">Sorry the document data you requested for<br>client ref <strong><?php echo $_GET['cref']; ?></strong> is no longer available to download</p>
						</div>		
					<?php } ?>
					
					<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1) { ?>
						<div class="alert alert-success text-center alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<p class="lead">All data for client ref <strong><?php echo $_GET['cref']; ?></strong> has been removed.</p>
							<p>A back-up copy of the data has been sent to <strong>webmaster@tlwsolicitors.co.uk</strong></p>
							<p style="margin-top: 10px;">
								<a href="<?php echo $scheme; ?><?php echo $host; ?>/logs/sent-data/" class="btn btn-success" target="_blank;"><i class="glyphicon glyphicon-refresh"></i> Continue</a>
							</p>
						</div>		
					<?php } ?>
					
					<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 0) { ?>
						<div class="alert alert-danger text-center alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<p class="lead">Data for client ref <strong><?php echo $_GET['cref']; ?></strong> cannot be removed.</p>
							<p>Please contact <strong><a href="mailto:webmaster@tlwsolicitors.co.uk">webmaster@tlwsolicitors.co.uk</a></strong> to remove this data.</p>
							<p style="margin-top: 10px;">
								<a href="<?php echo $scheme; ?><?php echo $host; ?>/logs/sent-data/" class="btn btn-danger" target="_blank;"><i class="glyphicon glyphicon-refresh"></i> Continue</a>
							</p>
						</div>		
					<?php } ?>
					
					<?php if ($sent_data) { 
					//pre($sent_data);
					?>
					<div class="well well-lg table-responsive">
						<h3 class="text-center">Signed documents</h3>
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
											<a href="<?php echo $scheme; ?><?php echo $host; ?>/signed/rmvzip/?tkn=<?php echo $data['tkn']; ?>&cref=<?php echo $data['ref']; ?>" title="Delete" class="btn btn-default"><i class="glyphicon glyphicon-trash"></i><span class="sr-only">Delete</span></a>			
											<a href="<?php echo $scheme; ?><?php echo $host; ?>/signed/dwnldzip/?tkn=<?php echo $data['tkn']; ?>&cref=<?php echo $data['ref']; ?>" title="Download" class="btn btn-default"><i class="glyphicon glyphicon-save"></i><span class="sr-only">Download</span></a>
										</div>
										</td>
								</tr>
								<?php } ?>	
								
							</tbody>
						</table>
					</div>
					<?php } else { ?>
					<div class="alert alert-info text-center">
						<h3>No signed documents available</h3>
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
<script src="<?php echo $scheme; ?><?php echo $host; ?>/assets/js/app.js"></script>
</html>