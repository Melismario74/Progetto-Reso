<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class ArrivalController extends DMController {
	
		function display() {
			
			if (DMInput::get('view', '') === '') {
				DMInput::set('view', 'arrivals');
			}
			
			parent::display();
			
		}
		
	}
?>