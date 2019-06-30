<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class popupArticle extends DMPopupClass {
		
		function open() {
			
			$this->title = "Visualizza articolo";
			
			$this->articleId = DMInput::getInt('articleId');
			
			parent::open();
			
		}
		
	}
	
?>
