<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class WasteController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'waste');
			
			parent::display();
			
		}
		
	}
?>