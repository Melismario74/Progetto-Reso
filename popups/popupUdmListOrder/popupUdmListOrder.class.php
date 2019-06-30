<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupUdmListOrder extends DMPopupClass {
		
		function open() {
			
			$this->title = "UDM";
			
			$this->stockId = DMInput::getInt('stockId', 0);
			
						
			parent::open();
			
		}
		
	}
	
?>
