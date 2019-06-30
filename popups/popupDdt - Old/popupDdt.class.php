<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupDdt extends DMPopupClass {
		
		function open() {
			
			$this->title = "Inserisci ddt";
			
			$this->ddtId = DMInput::getInt('ddtId', -1);
			
			parent::open();
			
		}
		
	}
	
?>
