<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class GlassController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'glass');
			
			parent::display();
			
		}
		
	}
?>