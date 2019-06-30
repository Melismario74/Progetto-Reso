<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupChargelistImport extends DMPopupClass {
		
		function open() {
			
			$this->title = "Importa lista di carico da FTP";
			
			parent::open();
			
		}
		
	}
	
?>
