<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/global-settings.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/doc_reader.php');

$raw_data = file_get_contents('data.txt');
$data = unserialize($raw_data);

$file = $data['ref'].'-letter.doc';
$doc = parseWord($file);
$firstname = $data['firstname']; 
$lastname = $data['lastname']; 
$fullname = $firstname. " " .$lastname;
$doc_title = $data['doc_title'];
$changeNameErrors = array();
$updates = false;
$changed = false;
$total_pgs = 0;

//pre($_POST);
if ( isset($_POST['changeNameDate']) ) {
	if (trim($_POST['firstname']) == "") {
	$changeNameErrors['fname'] = "Please enter your first name.";	
	}	
	
	if (trim($_POST['lastname']) == "") {
	$changeNameErrors['lname'] = "Please enter your last name.";	
	}	
	
	if (empty($changeNameErrors)) {
	$fname = trim($_POST['firstname']);	
	$lname = trim($_POST['lastname']); 
		
		if ($fname !== $data['firstname']) {
		$data['firstname'] = $fname;
		$updates = true;
		}
		
		if ($lname !== $data['firstname']) {
		$data['lastname'] = $lname;
		$updates = true;
		}
		
		if ($updates) {	
		$firstname = $data['firstname']; 
		$lastname = $data['lastname']; 
		$fullname = $firstname. " " .$lastname;
		$new_data = file_put_contents('data.txt', serialize($data));
			if ($new_data) {
			$changed = true;	
			}
		}
		
	}
	
	//pre($changeNameErrors);

?>	
<script type="text/javascript">
var $_POST = <?php echo json_encode($_POST); ?>;
</script>
	
<?php } ?>

<?php 
if ( isset($_POST['rgen-files']) &&  $_POST['rgen-files'] !== 0) {
	
	if (file_exists('signature.png')) {
	unlink('signature.png');	
	}
	
	if (file_exists($data['ref'].'.pdf')) {
	unlink($data['ref'].'.pdf');	
	}
	
	for($i = 0; $i <= $_POST['rgen-files']; $i++) {
		if (file_exists($data['ref'].'_'.$i.'.jpg')) {
		unlink($data['ref'].'_'.$i.'.jpg');		
		}
	} 
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>TLW Solicitors Esign Document | <?php echo $fullname; ?></title>
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
				
				<?php if (isset($_POST['fullname'])) { ?>
				<?php ob_start() ; ?>
				<?php } ?>
				
				<div class="letter-text clear">
				<h1 class="text-center text-uppercase"><?php echo $doc_title; ?></h1>
				<?php echo $doc; ?>
				</div>
				
				<?php if ( !isset($_POST['fullname']) && empty($changeNameErrors) ) { ?>
				<button id="scroll-dwn" class="btn btn default btn-block">Continue<i class="glyphicon glyphicon-chevron-down pull-right"></i></button>
				<div id="generate-sig-form" class="show alert alert-success">
					<?php if ($changed) { ?>
					<div class="letter-message well text-success text-center" role="alert">
						Your name has been updated you may now generate your signature.
					</div>		
					<?php } else { ?>
					<div class="letter-message well text-danger text-center" role="alert">
						Please check that your name below is correct before generating your signature.
					</div>
					<?php } ?>
					
					<div class="name-details text-center">
						<?php echo $fullname; ?>
					</div>
				
					<form method="post" action="">
						<input type="hidden" name="cref" value="<?php echo $data['ref']; ?>">
						<input type="hidden" name="signDate" value="<?php echo gmdate('g:ia, jS F, Y'); ?>">
						<input type="hidden" name="fullname" value="<?php echo $fullname; ?>">
						
						<button type="submit" class="show btn btn-success btn-lg btn-block caps text-center"><i class="glyphicon glyphicon-ok pull-left"></i>Generate signature</button>
					</form>
				</div>
				
				<button id="change-name" class="btn btn-danger btn-lg btn-block caps text-center"><i class="glyphicon glyphicon-edit pull-left"></i>Change name</button>
				<?php } ?>
				
				<?php if ( !isset($_POST['fullname']) || $changeNameErrors) { ?>
				<div id="change-name-well" class="<?php echo ($changeNameErrors) ? "show":"hide"; ?> well">
					<form id="change-name-form" method="post" action="">
						<div class="form-group<?php echo (!empty($changeNameErrors) && $changeNameErrors['fname']) ? ' has-error':'' ?>">
							<label class="control-label" for="firstname">First name:</label>
							<input type="text" name="firstname" id="firstname" value="<?php echo (!empty($changeNameErrors) && $changeNameErrors['fname']) ? '': $firstname; ?>" class="form-control input-lg" aria-describedby="fnameError">
							<?php if (!empty($changeNameErrors) && $changeNameErrors['fname']) { ?>
							<span id="fnameError" class="help-block"><?php echo $changeNameErrors['fname']; ?></span>	
							<?php } ?>
						</div>
						<div class="form-group<?php echo (!empty($changeNameErrors) && $changeNameErrors['lname']) ? ' has-error':'' ?>">
							<label class="control-label" for="lastname">Last name:</label>
							<input type="text" name="lastname" id="lastname" value="<?php echo (!empty($changeNameErrors) && $changeNameErrors['lname']) ? '':$lastname ?>" class="form-control input-lg" aria-describedby="lnameError">
							<?php if (!empty($changeNameErrors) && $changeNameErrors['fname']) { ?>
							<span id="lnameError" class="help-block"><?php echo $changeNameErrors['lname']; ?></span>	
							<?php } ?>
						</div>
						
						<input type="hidden" name="changeNameDate" value="<?php echo gmdate('g:ia, jS F, Y'); ?>">
						
						<button type="submit" class="btn btn-success btn-lg btn-block caps text-center"><i class="glyphicon glyphicon-ok pull-left"></i>Save changes</button>
					</form>
				</div>
				<?php } ?>
				
				<?php if ( isset($_POST['fullname']) ) { ?>
								
				<div class="signature-details">
					Confirmed by: <?php echo ($_POST['fullname']); ?><br />
					Date: <?php echo ($_POST['signDate']); ?>
				</div>
				
				<div class="client-signature">
					<?php
					include_once($_SERVER['DOCUMENT_ROOT'].'/inc/text-to-img.php');
					//TextToImage ($text, $separate_line_after_chars=40,$size=24,$rotate=0,$padding=2,$transparent=true, $color=array('red'=>0,'grn'=>0,'blu'=>0), $bg_color=array('red'=>255,'grn'=>255,'blu'=>255))
					TextToImage ($_POST['fullname'], 40, 50, 0, 20, false);
					?>
					<div class="signature-img">
						<img src="<?php echo SITEROOT; ?>/<?php echo $data['ref']; ?>/signature.png" style="vertical-align: bottom;" alt="signature">
					</div>
				</div>

					<?php 
						$html = ob_get_contents();
						
						//pre($html);
						ob_end_clean(); 
						include_once($_SERVER['DOCUMENT_ROOT'].'/inc/gs-function.php');
						include_once($_SERVER['DOCUMENT_ROOT'].'/classes/mpdf/mpdf.php');
						$stylesheet = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/assets/css/pdf-styles.css');
						$mpdf = new mPDF('default', 'A4', '8', 'san-serif', '12', '12', '65', '45', '10', '10', 'P');
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
					?>
				<div class="actions-btns">
					<form method="post" action="<?php echo SITEROOT; ?>/sendsig/" id="sendsig">
				    	<button id="confirm-sig" type="submit" class="btn btn-success btn-lg btn-block caps"><i class="glyphicon glyphicon-ok pull-left"></i>Confirm and send</button>
						<input type="hidden" value="<?php echo $data['ref']; ?>" name="cref">
					</form>

					<form method="post" action="">
				    	<button id="resign-sig" type="submit" class="btn btn-danger btn-lg btn-block caps"><i class="glyphicon glyphicon-refresh pull-left"></i>Re-generate signature</button>
				    	<input type="hidden" value="<?php echo $total_pgs; ?>" name="rgen-files">
					</form>
				</div>
				<?php } ?>
								
			</div><!-- .letter-outer -->

			<?php } else { ?>
			
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-disabled-btns.php');?>
			
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-disabled-message.php');?>
			
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

<?php if (isset($_GET['tkn']) && $_GET['tkn'] == $data['tkn']) { ?>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-modal-step1.php');?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-modal-step2.php');?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-modal-step3.php');?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/inc/html/letter-modal-step4.php');?>

<?php } ?>
</body>
  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="<?php echo SITEROOT; ?>/assets/js/app.js"></script>

</html>