<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupVector extends DMPopupClass {
		
		function open() {
			
			$this->title = "Inserisci/Modifica vettore";
			
			$this->vectorId = DMInput::getInt('vectorId');
			
			parent::open();
			
		}
		
	}
	
?>
