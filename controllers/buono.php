<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class BuonoController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'buono');
			
			parent::display();
			
		}
		
	}
?>