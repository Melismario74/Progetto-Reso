<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('movements');
	$document->addViewJS('movements');
	$document->setTitle("Movimenti");
	
	class MovementsView extends DMView {
	
		function display() {
			
			parent::display();
			
		}
		
	}

?>