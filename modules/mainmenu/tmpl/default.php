<?php
	//modificata da Mario per il software Felsinea

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
?>


<div class="navbar">
	<div class="navbar-inner">
		<a class="brand" href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php"></a>
		<?php if ($user){ ?>
		<ul class="nav navbar-nav " >
			<li>
			<img src="<?php echo DMUrl::getCurrentBaseUrl(); ?>media/logo-200.png" class="transparent" height="100px" width="75px">
			</li>
			<?php if (DMAcl::checkPrivilege('FH_ARRIVAL')) { ?>
			<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
        Anagrafiche <b class="caret"></b></a>
				<ul class="dropdown-menu">
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=vector">Vettori</a></li>
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=user">Utenti</a></li>
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=file">Importazione</a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_ARRIVAL')) { ?>
			<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
        Arrivi <b class="caret"></b></a>
				<ul class="dropdown-menu">
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=arrival">Ingresso resi</a></li>
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=user">Altri ingressi</a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_ARRIVAL')) { ?>
			<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
        Produzione <b class="caret"></b></a>
				<ul class="dropdown-menu">
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=good">Merce Buona</a></li>
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=obsolete">Obsoleti</a></li>
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=waste">Scarti</a></li>
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=buono">Occhiali</a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_ARRIVAL')) { ?>
			<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
        Magazzino <b class="caret"></b></a>
				<ul class="dropdown-menu">
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=logistics">Inserimento UDM</a></li>
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=udm">Situazione Udm</a></li>
				  <li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=stock">Magazzino</a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_CHARGELISTS')) { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=chargelist">Picking</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_STOCK'))  { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=stock">Spedizione</a></li>
			<?php } ?>
			<?php if (DMAcl::checkPrivilege('FH_STOCK'))  { ?>
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=foto">Foto</a></li>
			<?php } ?>
		</ul>
		<?php } ?>
		<ul class="nav pull-right">
			<li><a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=login&task=logout">Logout</a></li>
		</ul>
		
	</div>
</div>
