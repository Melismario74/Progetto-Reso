<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class IbridoController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'ibrido');
			
			parent::display();
			
		}
		
	}
?>