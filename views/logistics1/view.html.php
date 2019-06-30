<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('logistics');
	$document->addViewJS('logistics');
	$document->setTitle("Logistica");
	
	class LogisticsView extends DMView {
	
		function display() {
			
			parent::display();
			
		}
		
	}

?>