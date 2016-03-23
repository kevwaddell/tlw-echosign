<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/doc_reader.php');

$raw_data = file_get_contents('data.txt');
$data = unserialize($raw_data);

$file = $data['ref'].'-letter.doc';
$doc = parseWord($file);


//echo '<pre>';print_r($_POST);echo '</pre>';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>TLW | PHP Tests</title>
<link rel="stylesheet" href="<?php echo SITEROOT; ?>/assets/css/signaturepad.css">
<link rel="stylesheet" href="<?php echo SITEROOT; ?>/assets/css/global-css.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>

	<main class="main-content letter-main">
		
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/col-strip.php');?>
	
	<div class="container">
		<div class="row">
		<div id="letter" class="col-md-10 col-md-offset-1">
			
		<?php if (isset($_GET['tkn']) && $_GET['tkn'] == $data['tkn']) { ?>	
	
		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-steps-btns.php');?>
		
			<div class="letter-outer">
				
				<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-logo.php');?>	
				
				<?php if (isset($_POST['output'])) { ?>
				<?php ob_start() ; ?>
				<?php } ?>
				
				<div class="letter-text clear">
				<?php echo $doc; ?>
				</div>
				
				<div class="tlw-signature">
					<p>Signed: <img src="<?php echo SITEROOT; ?>/assets/img/TLW_signature.png" style="vertical-align: bottom;" alt="TLW_signature" width="300"></p>
				</div>
				<?php if ( isset($_POST['output']) ) { ?>
				<div class="client-signature">
					
					<?php if ( $_POST['output'] != "" ) { ?>
					<?php
					include("../inc/signature-to-image.php");
					$json = $_POST['output'];
				   	$img = sigJsonToImage($json, array('imageSize'=> array(400,100)));
					imagepng($img, 'signature.png');
					imagedestroy($img);
					?>
					<p>Your signature: <img src="<?php echo SITEROOT; ?>/<?php echo $data['ref']; ?>/signature.png" style="vertical-align: bottom;" alt="signature" width="300"></p><?php } else { ?>
					<p class="signature">Your signature: <span><?php echo ($_POST['name']); ?></span></p>
				<?php } ?>
				</div>
				
				<div class="signature-details">
					<p>
					Signed by: <?php echo ($_POST['name']); ?><br />
					Date: <?php echo ($_POST['signDate']); ?>
					</p>
				</div>

				<?php 
					$html = ob_get_contents();
					ob_end_clean(); 
					include_once($_SERVER['DOCUMENT_ROOT'].'/inc/gs-function.php');
					include($_SERVER['DOCUMENT_ROOT']."/classes/mpdf/mpdf.php");
					$stylesheet = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/assets/css/pdf-styles.css');
					$mpdf=new mPDF('default', 'A4', '8', 'san-serif', '12', '12', '65', '45', '10', '10', 'P');
					include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pdf-parts/pdf-header.php');
					include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pdf-parts/pdf-footer.php');
					$mpdf->WriteHTML($stylesheet,1);
					$mpdf->WriteHTML($html);
					$mpdf->Output($data['ref'].'.pdf', 'F');
					if ( file_exists($data['ref'].'.pdf') ) {
						$total_pgs = getNumPagesPdf($data['ref'].'.pdf');
						
						if ($total_pgs > 1) {
							for($i = 1; $i <= $total_pgs; $i++) {
							convert_pdf($data['ref'], $i, $i);	
							}
						} else {
						convert_pdf($data['ref'], 1, 1);	
						}
					}
					
					echo $html;
					
					if (isset($_POST['name'])) {
						$data['fullname'] = ucwords($_POST['name']);
						file_put_contents('data.txt', serialize($data));
						//echo '<pre>';print_r($data);echo '</pre>';
					}
				?>
				<form method="post" action="<?php echo SITEROOT; ?>/sendsig/">
			    	<button id="confirm-sig" type="submit" class="btn btn-success btn-lg btn-block caps">Confirm and send<i class="glyphicon glyphicon-ok pull-left"></i> </button>
					<a href="" class="btn btn-danger btn-lg btn-block caps">Re-sign<i class="glyphicon glyphicon-refresh pull-left"></i></a>
					<input type="hidden" value="<?php echo $data['ref']; ?>" name="cref">
				</form>
				<?php } else { ?>
				<button type="button" class="btn btn-primary btn-block btn-lg caps" data-toggle="modal" data-target="#sigFormModal">Click here to sign&nbsp;&nbsp;<i class="glyphicon glyphicon-pencil pull-left"></i></button>
				<?php } ?>
				
								
			</div><!-- .letter-outer -->

			<?php } else { ?>
			
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-disabled-btns.php');?>
			
				<?php if (isset($_GET['tkn']) && isset($data['old_tkn']) && $_GET['tkn'] == $data['old_tkn']) { ?>
				
				<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-renew-message.php');?>
				
				<?php } else { ?>
				
				<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-disabled-message.php');?>
				
				<?php } ?>
				
				<?php if (isset($_GET['cref']) && isset($_GET['sent']) ) { ?>
					<?php if ( $_GET['sent'] == 0 ) { ?>
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-renew-sent-message.php');?>
					<?php } else { ?>
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-renew-sent-error-message.php');?>
					<?php } ?>
				<?php } ?>
			
			<?php } ?>
			
			</div><!-- #letter -->
		</div><!-- row -->
	</div><!-- container -->
	
</main>

<footer class="app-info">
	<div class="container">
		<small>&copy; TLW Solicitors 2016. All rights reserved.</small>
	</div>
</footer>

<?php if (!isset($_POST['output'])) {?>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-modal-sigpad.php');?>

<?php } ?>

<?php if (isset($_GET['tkn']) && $_GET['tkn'] == $data['tkn']) { ?>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-modal-step1.php');?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-modal-step2.php');?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-modal-step3.php');?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-modal-step4.php');?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-modal-step5.php');?>

<?php } ?>
</body>
	<?php if (isset($_GET['tkn']) && $_GET['tkn'] == $data['tkn']) { ?>
	
	<script src="<?php echo SITEROOT; ?>/assets/js/jquery.signaturepad.js"></script>
	<script src="<?php echo SITEROOT; ?>/assets/js/json2.min.js"></script>
	
		<?php if (isset($_POST['name']) && $_POST['name'] != "") { ?>
		<script>
	    $(document).ready(function() {
		  var confirm_sig = $('button#confirm-sig');
		  $('html, body').animate({scrollTop: (confirm_sig.offset().top)}, 500);
	    });
		</script>
		<?php } ?>	
	
		<?php if (isset($_POST['output']) && $_POST['output'] != "") { ?>
		<script>
	    $(document).ready(function() {
		  var sig = <?php echo ($_POST['output']) ?>;
	      $('.sigPad').signaturePad({displayOnly:true}).regenerate(sig);
	    });
		</script>
		<?php } else { ?>
	  	<script>
	    $(document).ready(function() {
	      $('.sigPad').signaturePad();
	    });
	    </script>
		<?php } ?>
  
  <?php } ?>
  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="<?php echo SITEROOT; ?>/assets/js/app.js"></script>

</html>