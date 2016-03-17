<?php
$path = split("/", $_SERVER['REQUEST_URI']);
?>

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
				      <img alt="TLW Solicitors Esign" src="<?php echo SITEROOT; ?>/assets/img/tlw-logo-wide.svg">
				   </a>
			    </div>
			
			    <!-- Collect the nav links, forms, and other content for toggling -->
			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			      <ul class="nav navbar-nav navbar-right">
				    <li<?php echo (in_array("dashboard", $path)) ? ' class="active"':'' ; ?>><a href="<?php echo SITEROOT; ?>/admin/dashboard/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			        <li<?php echo (in_array("settings", $path)) ? ' class="active"':'' ; ?>><a href="<?php echo SITEROOT; ?>/admin/settings/"><i class="fa fa-cogs"></i> Settings</a></li>
			        <li<?php echo (in_array("actions", $path)) ? ' class="active"':'' ; ?>><a href="<?php echo SITEROOT; ?>/admin/actions/"><i class="fa fa-bolt"></i> Actions</a></li>
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Logs <span class="caret"></span></a>
			          <ul class="dropdown-menu">
			            <li><a href="<?php echo SITEROOT; ?>/admin/logs/email-logs/">Email logs</a></li>
			            <li><a href="<?php echo SITEROOT; ?>/admin/logs/sent-data/">Signed documents</a></li>
			            <li><a href="<?php echo SITEROOT; ?>/admin/logs/unsigned/">Unsigned documents</a></li>
			          </ul>
			        </li>
			        <li><a href="<?php echo SITEROOT; ?>" onClick="window.opener=self;window.close()"><i class="fa fa-power-off"></i> Log out</a></li>
			      </ul>
				</div><!-- /.navbar-collapse -->
			   
				</nav>
			</div>
		</div>
	</div>

</header>
