<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class ReconditionController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'recondition');
			
			parent::display();
			
		}
		
	}
?>