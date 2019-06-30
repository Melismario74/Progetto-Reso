<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('good');
	$document->addViewJS('good');
	$document->setTitle("Good");
	
	class GoodView extends DMView {
	
		function display() {
			
			parent::display();
			
		}
		
	}

?>