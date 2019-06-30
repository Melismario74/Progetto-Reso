<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FileController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'file');
			
			parent::display();
			
		}
		
	}
?>