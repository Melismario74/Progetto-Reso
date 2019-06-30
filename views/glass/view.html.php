<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('glass');
	$document->addViewJS('glass');
	$document->setTitle("Glass");
	
	class GlassView extends DMView {
	
		function display() {
			
			parent::display();
			
		}
		
	}

?>