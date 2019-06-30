<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class AggregationController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'aggregation');
			
			parent::display();
			
		}
		
	}
?>