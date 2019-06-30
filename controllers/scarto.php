<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class ScartoController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'scarto');
			
			parent::display();
			
		}
		
	}
?>