<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('udm');
	$document->addViewJS('udm');
	$document->setTitle("UDM");
	
	class UdmView extends DMView {
	
		function display() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				DMUrl::redirect('index.php', 'Non sei autorizzato a visualizzare questa pagina');
			}
			
			parent::display();
			
		}
		
	}

?>