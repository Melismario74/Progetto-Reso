<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupAddLdv extends DMPopupClass {
		
		function open() {
			
			$this->title = "Inserisci Ldv / Ddt";
			
			$this->ldvId = DMInput::getInt('ldvId', -1);
			
			parent::open();
			
		}
		
	}
	
?>
