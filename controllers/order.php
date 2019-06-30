<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class OrderController extends DMController {
	
		function display() {
			
			if (DMInput::get('view', '') === '') {
				DMInput::set('view', 'orders');
			}
			
			parent::display();
			
		}
		
	}
?>