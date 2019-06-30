<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('waste');
	$document->addViewJS('waste');
	$document->setTitle("Waste");
	
	class WasteView extends DMView {
	
		function display() {
			
			parent::display();
			
		}
		
	}

?>