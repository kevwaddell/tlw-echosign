<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/current_pg_function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/pre-function.php');
//pre($host);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>TLW Solicitors | 404 Error</title>
<link rel="stylesheet" href="<?php echo SITEROOT; ?>/assets/css/global-css.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

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
				<h2 class="caps txt-gray lite">Request Error</h2>
				<p>Sorry the page you are looking for is know longer available.</p>
			</div>
		</div>
	</header>

	<main class="main-content">
		
		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
					<div class="well well-lg fp-well bg-tlw-red">
						<div class="alert alert-danger text-center">
							<h3 style="margin-bottom: 0px;">Please return to our site home page.</h3>
						</div>
						<a href="<?php echo SITEROOT; ?>" class="btn btn-default btn-lg btn-block caps">Continue <i class="glyphicon glyphicon-chevron-right pull-right"></i></a>
						</div>
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

</html>