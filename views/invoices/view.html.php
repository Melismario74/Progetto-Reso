<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('invoices');
	$document->addViewJS('invoices');
	$document->setTitle("Documenti di uscita");
	
	class InvoicesView extends DMView {
	
		function display() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				DMUrl::redirect('index.php', 'Non sei autorizzato a visualizzare questa pagina');
			}
			
			parent::display();
			
		}
		
	}

?>