<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/PHPMailer/PHPMailerAutoload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-IT-attachment-email.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-client-attachemt-email.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/emails/send-handler-attachment-email.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/gs-function.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>TLW Solicitors | Secure Sign</title>
<link rel="stylesheet" href="<?php echo SITEROOT; ?>/assets/css/global-css.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<?php 
$log_date = date('Y-m-d', time());

if ( isset($_GET['sent']) && $_GET['sent'] == 1 ) {
	
	if (is_dir( $_SERVER['DOCUMENT_ROOT'].'/'.$_GET['cref'] )) {
		$src = $_SERVER['DOCUMENT_ROOT'].'/'.$_GET['cref'];
		$dest = $_SERVER['DOCUMENT_ROOT'].'/signed/'.$_GET['cref'];
		
		if ( rename($src, $dest) ) {
			$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/signed/'.$_GET['cref'].'/data.txt');
			$data = unserialize($raw_data);	
			sendITEmail();	
			sendHandlerEmail();
			sendClientPDFEmail();
		} else {
			$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$_GET['cref'].'/data.txt');
			$data = unserialize($raw_data);	
		}

	} else {
		if (is_dir( $_SERVER['DOCUMENT_ROOT'].'/signed/'.$_GET['cref'] )) {
		$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/signed/'.$_GET['cref'].'/data.txt');
		$data = unserialize($raw_data);	
		}
	}
	
	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/signed-'.$log_date.'.log') ) {
		$raw_signed_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/signed-'.$log_date.'.log');	
		$signed_data = unserialize($raw_signed_data);	
	} else {
		$signed_data = array();		
	}
	
	$signed_data[] = array(
		'ref' => $data['ref'], 
		'tkn' => $data['tkn'], 
		'sby' => $data['firstname']." ".$data['lastname'], 
		'sdate' =>  $data['signed'],
		'rdate' =>  date('g:ia, jS F, Y', strtotime( '+ 1 day', $data['signed']))
	);
	
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/signed-'.$log_date.'.log', serialize($signed_data));
	
	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log')) {
		$raw_unsigned_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log');
		$unsigned_data = unserialize($raw_unsigned_data);	
		
		foreach ($unsigned_data as $k => $ud) {
		
			if ($ud['ref'] == $_GET['cref']) {
			unset($unsigned_data[$k]);
			}	
			
		}
		
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/admin/logs/unsigned-'.$log_date.'.log', serialize($unsigned_data));
	}
	
//echo '<pre>';print_r($data);echo '</pre>';
} else if ( isset($_GET['sent']) && $_GET['sent'] == 0 ) {
	$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$_GET['cref'].'/data.txt');
	$data = unserialize($raw_data);		
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
						<h2>Thank you</h2>
						<p>Your signed agreement has been sent to:<br><strong>TLW Solicitors</strong></p>
						<p>A copy of the agreement has also been sent to the following email address.</p><br>
						<h4><?php echo $data['email']; ?></h4><br>
					
						<a href="<?php echo SITEROOT; ?>/signed/<?php echo $data['ref']; ?>/<?php echo $data['ref']; ?>.pdf" target="_blank" class="btn btn-success btn-lg btn-block caps"><i class="glyphicon glyphicon-save pull-left"></i>Download Agreement</a>
						<a href="<?php echo SITEROOT; ?>/?signed=<?php echo $data['ref']; ?>" class="btn btn-primary btn-lg btn-block caps"><i class="glyphicon glyphicon glyphicon-circle-arrow-left pull-left"></i>Finish process</a>
					</div>
					<?php } ?>
					<?php if (isset($_GET['sent']) && $_GET['sent'] == 0) { ?>
					<div class="alert alert-danger text-center">
						<h2>Sorry</h2>
						<p>There was a problem sending your agreement.</p>
						<br>
						<form action="<?php echo SITEROOT; ?>/sendsig/" method="post">
						<button id="try-again" class="btn btn-danger btn-lg btn-block"><i class="glyphicon glyphicon-repeat pull-left"></i> Try again</button>
						<input name="cref" type="hidden" value="<?php echo $data['ref']; ?>">
						</form>
						<p><a href="<?php echo SITEROOT; ?>/" class="btn btn-default btn-block"><i class="glyphicon glyphicon-remove pull-left"></i> Cancel</a></p>
					</div>
					<?php } ?>
						
				</div>
			</div>
		</div>

	</header>

	<main class="main-content">
		
		<div class="container">
				
			<div class="row">
				<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">

				<?php if ( is_dir( $_SERVER['DOCUMENT_ROOT'].'/signed/'.$_GET['cref'] ) ) { 
					
					if ( file_exists($_SERVER['DOCUMENT_ROOT'].'/signed/'.$_GET['cref'].'/'.$_GET['cref'].'.pdf') ) {
					$total_pgs = getNumPagesPdf($_SERVER['DOCUMENT_ROOT'].'/signed/'.$_GET['cref'].'/'.$_GET['cref'].'.pdf');
					$thumbs = array();
						for ($i = 1; $i <= $total_pgs; $i++) {
						$thumbs[] = $_GET['cref'] .'_'. $i .'.jpg';
						}
					}
				?>
				
				<?php if ( !empty($thumbs) ) { ?>
					<div class="thumb-viewer">
					<?php foreach ($thumbs as $thumb) { ?>
						<img src="<?php echo SITEROOT; ?>/signed/<?php echo $_GET['cref']; ?>/<?php echo $thumb; ?>" width="250" height="355">
					<?php } ?>
					</div>
				<?php } ?>
				
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