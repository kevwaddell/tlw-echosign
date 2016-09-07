<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');

if ( isset($_GET['cref']) ) {
$cref = $_GET['cref'];	
//echo '<pre>';print_r($cref);echo '</pre>';
$raw_data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$cref.'/data.txt');
$data = unserialize($raw_data);	
$name = " ".$data['firstname']." ";
$cref = $data['ref'];
$token = $data['tkn'];
$doc_title = $data['doc_title'];
} else {
$name = "TLW client";	
$token = "null";
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>TLW Solicitors | Esign</title>
</head>
<body style="background-color: #908e8e; padding: 20px; font-family: Arial, sans-serif;">
	
	<table width="700px" align="center" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>	
				<td>
					
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
						
						<div class="content" style="padding: 20px 40px; font-size: 16px; line-height: 20px; min-height: 100px;">
							<p>Dear <?php echo $name; ?></p>
							<h2 align="center" style="color: #ca156e;">Your TLW Solicitors <strong><?php echo $doc_title; ?></strong> is ready to sign</h2>
							<p align="center">Please go to the following link and follow the instructions.</p>
							<p align="center"><a href="<?php echo SITEROOT; ?><?php echo (!$cref) ? "/" : "/".$cref."/sign/?tkn=".$token; ?>">View document</a></p>
							<p align="center">If you have any problems with viewing this client document please contact us as soon as possible.</p>
							<p align="center"><strong>Email us on <a href="mailto: info@tlwsolicitors.co.uk" style="color: #ca156e;">info@tlwsolicitors.co.uk</a> or call us on 0191 293 1500</strong></p>
						</div>
						
						<div class="footer" style="background-color: #666; padding: 20px; color: white; font-size: 12px; text-align: center;">
							<p>9 Hedley Court, Orion Business, North Shields, NE29 7ST<br>
							TLW solicitors is a trading name of TLW LLP a limited liability partnership registered in England (Registration OC314139).<br>
							TLW LLP are a firm of solicitors authorised and regulated by the Solicitors Regulation Authority.</p>
						</div>
						
					</div>
					
				</td>
			</tr>
		</tbody>
	</table>
	
</body>
</html>