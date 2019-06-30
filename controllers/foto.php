<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FotoController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'foto');
			
			parent::display();
			
		}
		
	}
?>