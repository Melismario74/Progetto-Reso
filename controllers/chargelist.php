<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class ChargelistController extends DMController {
	
		function display() {
			
			if (DMInput::get('view', '') === '') {
				DMInput::set('view', 'chargelists');
			}
			
			parent::display();
			
		}
		
		function exportChargelist() {
		
			if (!DMAcl::checkPrivilege("FH_CHARGELISTS")) {
				DMUrl::redirect('index.php', 'Non sei autorizzato', 'ERROR');
			}
			
			$chargelistId = DMInput::getInt('chargelistId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'chargelisthelper.php');
			$myChargelist = FHChargelistHelper::loadChargelist($chargelistId);
			$myContent = FHChargelistHelper::exportChargelist($chargelistId);
			
			header('Content-disposition: attachment; filename=' . $myChargelist->chargelist_code . '.txt');
			header('Content-type: text/plain');
			
			echo $myContent;
			
			exit;
		}
		
	}
?>