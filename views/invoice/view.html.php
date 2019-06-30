<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('invoice');
	$document->addViewJS('invoice');
	$document->setTitle("Documento di uscita");
	
	class InvoiceView extends DMView {
	
		function display() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				DMUrl::redirect('index.php', 'Non sei autorizzato a visualizzare questa pagina');
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'invoicehelper.php');
			
			$invoiceId = DMInput::getInt('invoiceId', -1);
			
			if ($invoiceId > 0) {
				$this->invoice = FHInvoiceHelper::loadInvoice($invoiceId);
			} else {
				$this->invoice = new StdClass();
				$this->invoice->invoice_id = -1;
				$this->invoice->rows = array();
			}
			
			parent::display();
			
		}
		
	}

?>