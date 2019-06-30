<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class GoodController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'good');
			
			parent::display();
			
		}
		
	}
?>