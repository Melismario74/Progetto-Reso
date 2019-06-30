<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupUdmList extends DMPopupClass {
		
		function open() {
			
			$this->title = "UDM";
			
			$this->canSelect = DMInput::getInt('canSelect', 0);
			$this->canPrint = DMInput::getInt('canPrint', 0);
						
			parent::open();
			
		}
		
	}
	
?>
