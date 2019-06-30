<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
?>



<div class="navbar">
	<div class="navbar-inner">
		<a class="brand" href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php"></a>
		<?php if ($user){ ?>
		<ul class="nav">
			<li>
			<img src="<?php echo DMUrl::getCurrentBaseUrl(); ?>media/logo-200.png" class="transparent" height="75px" width="75px">
			</li>
			<?php if (DMAcl::checkPrivilege('FH_ARRIVAL')) { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=arrival">Arrivi</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_RECONDITION')) { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=buono">Registrazione</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_LOGISTICS'))  { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=logistics">Logistica</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_LOGISTICS'))  { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=udm">UDM</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_CHARGELISTS')) { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=chargelist">Liste di carico</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_STOCK'))  { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=stock">Magazzino</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')){ ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=aggregation">Aggregazione</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_STOCK'))  { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=movement">Movimenti</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_INVOICE')) { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=order">Liste di prelievo</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_INVOICE')) { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=invoice">DDT</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_USERS'))  { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=user">Utenti</a></li>
			<?php } ?>
			<?php  if (DMAcl::checkPrivilege('FH_USERS')){ ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=file">File</a></li>
			<?php } ?>
		</ul>
		<?php } ?>
		<ul class="nav pull-right">
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=login&task=logout">Logout</a></li>
		</ul>
		
	</div>
</div>