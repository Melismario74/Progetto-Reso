<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class MovementController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'movements');
			
			parent::display();
			
		}
		
	}
?>