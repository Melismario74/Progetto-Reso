<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('buono');
	$document->addViewJS('buono');
	$document->setTitle("Buono");
	
	class BuonoView extends DMView {
	
		function display() {
			
			parent::display();
			
		}
		
	}

?>