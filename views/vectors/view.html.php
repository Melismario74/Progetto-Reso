<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('vectors');
	$document->addViewJS('vectors');
	$document->setTitle("Vettori");
	
	class VectorsView extends DMView {
	
		function display() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				DMUrl::redirect('index.php', 'Non sei autorizzato a visualizzare questa pagina');
			}
			
			parent::display();
			
		}
		
	}

?>