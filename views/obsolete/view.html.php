<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('obsolete');
	$document->addViewJS('obsolete');
	$document->setTitle("Obsolete");
	
	class ObsoleteView extends DMView {
	
		function display() {
			
			parent::display();
			
		}
		
	}

?>