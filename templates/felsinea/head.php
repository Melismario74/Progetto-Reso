<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	//JQuery
	$this->addTemplateJS('jquery/jquery-1.9.0.min.js');
	
	//Bootstrap
	$this->addTemplateJS('bootstrap/js/bootstrap.js');
	$this->addTemplateCSS('bootstrap/css/bootstrap.css', 'screen');
	$this->addTemplateCSS('bootstrap/css/bootstrap-datepicker.css', 'screen');
	
	//Custom
	$this->addTemplateCSS('css/template.css');
	

?>