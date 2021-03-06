<?php
$path = split("/", $_SERVER['REQUEST_URI']);
?>

<header class="messages">
	
	<div class="container">
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
			<form class="navbar-form navbar-right">
				<button type="button" onClick="window.opener=self;window.close()" class="btn btn-default"><i class="glyphicon glyphicon-log-out"></i> Log out</button>
			</form>
	      <ul class="nav navbar-nav navbar-right">
		    <li<?php echo (in_array("dashboard", $path)) ? ' class="active"':'' ; ?>><a href="<?php echo SITEROOT; ?>/admin/dashboard/"><i class="glyphicon glyphicon-dashboard"></i> Dashboard</a></li>
	        <li<?php echo (in_array("settings", $path)) ? ' class="active"':'' ; ?>><a href="<?php echo SITEROOT; ?>/admin/settings/"><i class="glyphicon glyphicon-cog"></i> Settings</a></li>
	        <li<?php echo (in_array("actions", $path)) ? ' class="active"':'' ; ?>><a href="<?php echo SITEROOT; ?>/admin/actions/"><i class="glyphicon glyphicon-flash"></i> Actions</a></li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-list-alt"></i> Logs <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <li><a href="<?php echo SITEROOT; ?>/admin/logs/email-logs/">Email logs</a></li>
	            <li><a href="<?php echo SITEROOT; ?>/admin/logs/signed/">Signed documents</a></li>
	            <li><a href="<?php echo SITEROOT; ?>/admin/logs/unsigned/">Unsigned documents</a></li>
	          </ul>
	        </li>
	      </ul>
		 
		</div><!-- /.navbar-collapse -->

		</nav>
	</div>
</header>
