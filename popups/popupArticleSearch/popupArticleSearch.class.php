<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupArticleSearch extends DMPopupClass {
		
		function open() {
			
			$this->title = "Ricerca articolo";
			
			$this->eanCode = DMInput::getString('eanCode', '');
			
			parent::open();
			
		}
		
	}
	
?>
