<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class UserController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'users');
			
			parent::display();
			
		}
		
	}
?>