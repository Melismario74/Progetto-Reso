<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('chargelists');
	$document->addViewJS('chargelists');
	$document->setTitle("Liste di carico");
	
	class ChargelistsView extends DMView {
	
		function display() {
			
			parent::display();
			
		}
		
	}

?>