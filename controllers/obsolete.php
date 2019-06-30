<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class ObsoleteController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'obsolete');
			
			parent::display();
			
		}
		
	}
?>