<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class StockController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'stock');
			
			parent::display();
			
		}
		
	}
?>