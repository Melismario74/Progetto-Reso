<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$user = DMUser::getUser();
	
	require(DM_APP_PATH . DS . 'modules' . DS . 'mainmenu' . DS . 'tmpl' . DS . 'default.php');

?>