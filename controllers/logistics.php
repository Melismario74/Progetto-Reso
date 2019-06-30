<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class LogisticsController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'logistics');
			
			parent::display();
			
		}
		
	}
?>