<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupLdv extends DMPopupClass {
		
		function open() {
			
			$this->title = "Lettera di vettura";
			
			$this->ldvId = DMInput::getInt('ldvId');
			
			parent::open();
			
		}
		
	}
	
?>
