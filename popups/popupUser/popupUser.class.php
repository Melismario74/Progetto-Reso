<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupUser extends DMPopupClass {
		
		function open() {
			
			$this->title = "Inserisci/Modifica utente";
			
			$this->userId = DMInput::getInt('userId');
			
			parent::open();
			
		}
		
	}
	
?>
