<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class InvoiceController extends DMController {
	
		function display() {
			
			if (DMInput::get('view', '') === '') {
				DMInput::set('view', 'invoices');
			}
			
			parent::display();
			
		}
		
	}
?>