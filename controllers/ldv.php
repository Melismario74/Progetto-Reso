<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class LdvController extends DMController {
	
		function display() {
			
			DMInput::set('view', 'ldv');
					
			parent::display();
			
		}
		
		function exportLdv() {
		
			if (!DMAcl::checkPrivilege("FH_ARRIVAL")) {
				DMUrl::redirect('index.php', 'Non sei autorizzato', 'ERROR');
			}
			
			$ldvId = DMInput::getInt('ldvId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'ldvhelper.php');
			$myLdv = FHLdvHelper::loadLdv($ldvId);
			$myContent = FHLdvHelper::exportLdv($ldvId);
			
			header('Content-disposition: attachment; filename=' . $myLdv->ldv_code . '.txt');
			header('Content-type: text/plain');
			
			echo $myContent;
			
			exit;
		}
		
	}
?>