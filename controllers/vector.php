<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class VectorController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'vectors');
			
			parent::display();
			
		}
		
	}
?>