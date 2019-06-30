<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('chargelist');
	$document->addViewJS('chargelist');
	$document->setTitle("Lista di carico");
	
	class ChargelistView extends DMView {
	
		function display() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_CHARGELISTS')) {
				DMUrl::redirect('index.php', 'Non sei autorizzato a visualizzare questa pagina');
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'chargelisthelper.php');
			
			$chargelistId = DMInput::getInt('chargelistId', -1);
			
			if ($chargelistId > 0) {
				$this->chargelist = FHChargelistHelper::loadChargelist($chargelistId);
			} else {
				$this->chargelist = new StdClass();
				$this->chargelist->chargelist_id = -1;
				$this->chargelist->rows = array();
			}
			
			parent::display();
			
		}
		
	}

?>