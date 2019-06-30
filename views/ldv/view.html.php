<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('ldv');
	$document->addViewJS('ldv');
	$document->setTitle("Lettera di vettura");
	
	class LdvView extends DMView {
	
		function display() {
		
			//Controllo i permessi DA VERIFICARE MARIO FELSINEA
			if (!DMAcl::checkPrivilege('FH_ARRIVAL')) {
				DMUrl::redirect('index.php', 'Non sei autorizzato a visualizzare questa pagina');
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'ldvhelper.php');
			
			$ldvId = DMInput::getInt('ldvId', -1);
			
			if ($ldvId > 0) {
				$this->ldv = FHLdvHelper::loadLdv($ldvId);
			} else {
				$this->ldv = new StdClass();
				$this->ldv->ldv_id = -1;
				$this->ldv->rows = array();
			}
			
			parent::display();
			
		}
		
	}

?>