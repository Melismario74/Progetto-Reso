<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class UdmController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'udm');
			
			parent::display();
			
		}
		
	}
?>