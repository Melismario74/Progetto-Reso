<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('order');
	$document->addViewJS('order');
	$document->setTitle("Documento di uscita");
	
	class OrderView extends DMView {
	
		function display() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				DMUrl::redirect('index.php', 'Non sei autorizzato a visualizzare questa pagina');
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'orderhelper.php');
			
			$orderId = DMInput::getInt('orderId', -1);
			
			if ($orderId > 0) {
				$this->order = FHOrderHelper::loadOrder($orderId);
			} else {
				$this->order = new StdClass();
				$this->order->order_id = -1;
				$this->order->rows = array();
			}
			
			parent::display();
			
		}
		
	}

?>