<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
//pre($host);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>TLW Solicitors | Secure Sign</title>
<meta name="robots" content="noindex, nofollow">
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
	$prev_log_date = date('Y-m-d', strtotime($log_date.'- 1 day'));
	$unsigned = false;	
	$noref = true;	
	//pre($prev_log_date);
?>
<?php if (isset($_POST['cref'])) {
$cref = $_POST['cref'];
$log_date = $_POST['date'];
$errors = array();
$messages = array();
$unsigned_data = array();

$raw_unsigned_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log');
$unsigned_data = unserialize($raw_unsigned_data);	
//pre($unsigned_data);


	if (!empty($unsigned_data)) {

		foreach ($unsigned_data as $ud) {
		
			if ($cref == $ud['ref']) {
			$ref = $ud['ref'];
			$tkn = $ud['tkn'];
			$fname = $ud['firstname'];
			$unsigned = true;
			$noref = false;
			}	
		}
	
	}
	
	if (!$unsigned && !$noref ) {
	$raw_signed_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/signed-'.$log_date.'.log');
	$signed_data = unserialize($raw_signed_data);	
	//pre($signed_data);
		foreach ($signed_data as $k => $sd) {
			if ($k == $cref) {
			$ref = $sd['ref'];
			$tkn = $sd['tkn'];	
			$sdate = gmdate('jS F, Y',$sd['sdate']);
			$stime = gmdate('g:ia', $sd['sdate']);
			$sby = $sd['sby'];
			
			$messages[] =  "<span>Signed by: <strong>$sby</strong></span><br>";
			$messages[] =  "<span>Signed on: <strong>$sdate</strong> @ <strong>$stime</strong></span>";
			
			if (is_dir('signed/'.$ref)) {
			$messages[] =  "<a href=\"signed/$ref/$ref.pdf\" style=\"margin-bottom: 0px; margin-top: 10px;\" target=\"_blank\" class=\"btn btn-success btn-block btn-lg\"><i class=\"glyphicon glyphicon-save pull-left\"></i>Download agreement</a>";	
			}
			
			$noref = false;
			}
		}		
	}
	
	if ( $noref ) {
	$errors[] = "<i class=\"glyphicon glyphicon-remove\"></i> There is no agreement with reference number <strong>$cref</strong>";		
	}
	
} ?>

</head>
<body>
	
	<header class="message">
		<div class="color-strip">
			<div class="bar bg-purple"></div>
			<div class="bar bg-green"></div>
			<div class="bar bg-pink"></div>
			<div class="bar bg-orange"></div>
			<div class="bar bg-blue"></div>
		</div>
		<div class="jumbotron text-center">
			<div class="container">
				<h1>TLW Solicitors</h1>
				<h2 class="caps txt-gray lite">Online Document signing</h2>
				<p class="lead">Using the form below enter your 6 digit cleint reference number.<br>
				<small>(e.g 365425)</small>
				</p>
			</div>
		</div>
	</header>

	<main class="main-content">
		
		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
					<div class="well well-lg fp-well bg-tlw-red">
						<?php if (!empty($errors)) { ?>
						<div class="alert alert-danger text-center">
							<h3>Sorry there was an error</h3>
								<?php foreach ($errors as $er) { ?>
								<span><?php echo $er; ?></span><br>
								<?php } ?>
						</div>
						<a href="<?php echo SITEROOT; ?>" class="btn btn-default btn btn-block caps"><i class="glyphicon glyphicon-repeat pull-left"></i>Please try again</a>
						<?php } ?>
						
						<?php if ( isset($_GET['signed']) ) { 
						$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/signed/'.$_GET['signed'].'/data.txt');
						$data = unserialize($raw_data);	
						?>
						<div class="alert alert-success text-center">
							<h3>Thank you <?php echo $data['firstname']; ?> for signing your agreement</h3>
							<p>Please contact us on <strong>0800 169 5925</strong> if you have any queries.</p><br />
							<a href="<?php echo SITEROOT; ?>/" class="btn btn-success btn-lg btn-block caps">Continue</a>
						</div>
						<?php } ?>
						
						<?php if (!empty($messages)) { ?>
						<div class="alert alert-success text-center">
							<h3>Your agreement has already been signed</h3>
							<?php foreach ($messages as $ms) { ?>
							<?php echo $ms; ?>
							<?php } ?>
						</div>
						<a href="<?php echo SITEROOT; ?>" class="btn btn-default btn btn-block caps"><i class="glyphicon glyphicon-refresh pull-left"></i>Continue</a>
						<?php } ?>
						
						<?php if ($unsigned) { ?>
						<div class="alert alert-success text-center">
							<h3>Welcome <?php echo $fname; ?></h3>
							<p>Your agreement is ready to be signed.</p><br />
							<a href="<?php echo SITEROOT; ?>/<?php echo $ref; ?>/sign/?tkn=<?php echo $tkn; ?>" class="btn btn-success btn-lg btn-block caps">View Aggreement</a>
						</div>
						<a href="<?php echo SITEROOT; ?>" class="btn btn-default btn btn-block caps"><i class="glyphicon glyphicon-remove-sign pull-left"></i>Cancel</a>
						<?php } ?>
						
						<?php if (!isset($_POST['cref']) || !isset($_GET['signed']) ) { ?>
						<form method="post" action="">
							<div class="form-group">
								<input type="text" name="cref" class="form-control input-lg text-center" placeholder="Enter your reference" maxlength="10">
								<input type="hidden" name="date" value="<?php echo $log_date; ?>">
							</div>
							<button class="btn btn-default btn-lg btn-block caps"><i class="glyphicon glyphicon-search pull-left"></i>Find</button>
						</form>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
			
	</main>

	<footer class="app-info">
		<div class="container-fluid">
			<small>&copy; TLW Solicitors 2016. All rights reserved.</small>	
		</div>
	</footer>
	
	<?php if ($_SERVER['REMOTE_ADDR'] === '194.73.123.212' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1') { ?>
		<a href="/admin/dashboard/" id="admin-btn" class="btn btn-default pull-left hidden-xs" target="_blank">Admin <i class="glyphicon glyphicon-new-window pull-right"></i></a>		
	<?php } ?>

</body>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</html>