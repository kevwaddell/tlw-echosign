<?php 
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER['HTTPS'] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER['SERVER_PORT'] != "80") {
  $pageURL .= $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
 } else {
  $pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
 }
 return $pageURL;
}
$url_array = parse_url(curPageURL());
$host = $url_array[host];
$scheme = $url_array[scheme]."://";

if ( isset($_GET[cref]) ) {
$cref = $_GET[cref];	
$raw_data = file_get_contents($scheme.$host.'/signed/'.$cref.'/data.txt');
$data = unserialize($raw_data);	
$signed_date = gmdate('g:ia, jS F, Y', $data[signed]);
$remove_date_raw = strtotime( '+ 1 day', $data[signed]);
$remove_date = date('g:ia, jS F, Y', $remove_date_raw);
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
			<img src="<?php echo $scheme; ?><?php echo $host; ?>/assets/img/tlw-logo-wide.gif" alt="TLW Solicitors" />		
		</div>
		
		<div class="content" style="padding: 20px 40px; font-size: 16px; min-height: 100px;">
			<h2><span style="color: #ca156e;"><?php echo $data[fullname]; ?></span> has signed their client agreement.</h2>
			<p>Client agreement PDF is attached to this email.</p>
			<p>Details:</p>
			<ul>
				<li><strong>Client Ref:</strong>  <?php echo $data[ref]; ?></li>
				<li><strong>Client name:</strong>  <?php echo $data[fullname]; ?></li>
				<li><strong>Client email:</strong>  <a href="mailto:<?php echo $data[email]; ?>"><?php echo $data[email]; ?></a></li>
				<li><strong>Date/time signed:</strong>  <?php echo $signed_date; ?></li>
				<li><strong>Private token:</strong>  <?php echo $data[tkn]; ?></li>
			</ul>
			
			<p>The clients data will be be removed from the web server automatically at:<br />
			<strong><?php echo $remove_date; ?></strong></p>
			
			<p>Or click on the link below and remove the data now.<br />
			<a href="<?php echo $scheme; ?><?php echo $host; ?>/logs/sent-data/">Remove data now !!!</a></p>
			
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