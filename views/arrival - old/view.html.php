<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('arrival');
	$document->addViewJS('arrival');
	$document->setTitle("Documento in arrivo");
	
	class ArrivalView extends DMView {
	
		function display() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				DMUrl::redirect('index.php', 'Non sei autorizzato a visualizzare questa pagina');
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'arrivalhelper.php');
			
			$arrivalId = DMInput::getInt('arrivalId', -1);
			
			if ($arrivalId > 0) {
				$this->arrival = FHArrivalHelper::loadArrival($arrivalId);
			} else {
				$this->arrival = new StdClass();
				$this->arrival->arrival_id = -1;
				$this->arrival->rows = array();
			}
			
			parent::display();
			
		}
		
	}

?>