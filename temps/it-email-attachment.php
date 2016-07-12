<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');

if ( isset($_GET['cref']) ) {
$cref = $_GET['cref'];	
$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/signed/'.$cref.'/data.txt');
$data = unserialize($raw_data);	
$signed_date = gmdate('g:ia, jS F, Y', $data['signed']);
$remove_date = date('g:ia, jS F, Y', strtotime( '+ 1 month', $data['signed']));
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>TLW Esign Administration</title>
</head>
<body style="background-color: #908e8e; padding: 20px; font-family: Arial, sans-serif;">
	
	<div class="wrapper" style="width: 700px; background-color: white; margin: auto; box-shadow: 7px 7px 5px 0px rgba(50, 50, 50, 0.75);">
		
		<div class="col-strip">
			<table class="strips" width="100%" border="0" cellspacing="4">
				<tr>
					<td width="20%" height="30px" bgcolor="#A677B4">&nbsp;</td>
					<td width="20%" bgcolor="#ACD15C">&nbsp;</td>
					<td width="20%" bgcolor="#EA5677">&nbsp;</td>
					<td width="20%" bgcolor="#fbad4f">&nbsp;</td>
					<td bgcolor="#2d7ea9">&nbsp;</td>
				</tr>
			</table>
		</div>
		
		<div class="header" style="border-bottom: 1px solid #908e8e; margin: 0px 40px; text-align: center;">
			<img src="<?php echo SITEROOT; ?>/assets/img/tlw-logo-wide.gif" style="max-width: 250px;" alt="TLW Solicitors" />		
		</div>
		
		<div class="content" style="padding: 20px 40px; font-size: 16px; min-height: 100px;">
			<h2><span style="color: #ca156e;"><?php echo $data['firstname']; ?> <?php echo $data['lastname']; ?></span> has signed their client agreement.</h2>
			<p>Client agreement PDF is attached to this email.</p>
			<p>Details:</p>
			<ul>
				<li><strong>Client Ref:</strong>  <?php echo $data['ref']; ?></li>
				<li><strong>Client name:</strong>  <?php echo $data['firstname']; ?> <?php echo $data['lastname']; ?></li>
				<li><strong>Client email:</strong>  <a href="mailto:<?php echo $data['email']; ?>"><?php echo $data['email']; ?></a></li>
				<li><strong>Case handler email:</strong>  <a href="mailto:<?php echo $data['handler']; ?>"><?php echo $data['handler']; ?></a></li>
				<li><strong>Date/time signed:</strong>  <?php echo $signed_date; ?></li>
				<li><strong>Private token:</strong>  <?php echo $data['tkn']; ?></li>
			</ul>
			
			<p>The clients data will be be removed from the web server automatically at:<br />
			<strong><?php echo $remove_date; ?></strong></p>
			
			<p>Or click on the link below and remove the data now.<br />
			<a href="<?php echo SITEROOT; ?>/admin/logs/sent-data/">Remove data now !!!</a></p>
			
		</div>
		
		<div class="footer" style="background-color: #666; padding: 20px; color: white; font-size: 12px; text-align: center;">
			<p>9 Hedley Court, Orion Business, North Shields, NE29 7ST<br>
			TLW solicitors is a trading name of TLW LLP a limited liability partnership registered in England (Registration OC314139).<br>
			TLW LLP are a firm of solicitors authorised and regulated by the Solicitors Regulation Authority.</p>
		</div>
		
	</div>
	
</body>
</html>
<?php } ?>