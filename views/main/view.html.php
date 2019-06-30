<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('main');
	$document->addViewJS('main');
	
	class MainView extends DMView {
	
		function display() {
		
			parent::display();
			
		}
		
	}

?>