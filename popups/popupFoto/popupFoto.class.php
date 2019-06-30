<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupFoto extends DMPopupClass {
		
		function open() {
			
			$this->title = "Scatta Foto";
			
			$this->fotoId = DMInput::getInt('fotoId', -1);
			
			parent::open();
			
		}
		
	}
	
?>
