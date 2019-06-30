<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupMovement extends DMPopupClass {
		
		function open() {
			
			$this->title = "Inserisci movimento";
			
			$this->articleId = DMInput::getInt('articleId', -1);
			
			parent::open();
			
		}
		
	}
	
?>
