<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('recondition');
	$document->addViewJS('recondition');
	$document->setTitle("Ricondizionamento");
	
	class ReconditionView extends DMView {
	
		function display() {
			
			parent::display();
			
		}
		
	}

?>