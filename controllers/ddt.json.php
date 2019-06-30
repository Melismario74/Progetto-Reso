<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DdtJsonController extends DMJsonController {
	
		
		/**
			Ottiene i dettagli di un Ddt
			Mario felsinea 
		**/
		function jsonLoadDdt() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			$ddtId = DMInput::getInt('ddtId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'ddthelper.php');
			$ddt = FHDdtHelper::loadDdt($ddtId);
			
			$myDdt = FHDdtHelper::loadDdt($ddtId);
			$myDdt->ddt_date_str = DMFormat::formatDate($myDdt->ddt_date, 'd/m/Y', 'Y-m-d');
			
			
			
			parent::outputResult($ddtId, $myDdt, 'ddt');

			
		}
		
	
		
		/**
			Salva Ddt
			Mario felsinea 
		**/
		function jsonAdd() {
		
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				parent::outputError(-110);
			}
			
			$ddtId = DMInput::getInt('ddtId', -1);			
			$myDdt = DMTable::getInstance('Ddt');
			
			if ($ddtId > 0) {
				if (!$myDdt->load($ddtId)) {
					parent::outputError(-300);
				}
			}
			
			$myDdt->ddt_date = DMFormat::formatDate(DMInput::getString('ddt_date', ''), 'Y-m-d', 'd/m/Y');
			$myDdt->ddt_date_str = DMFormat::formatDate($myDdt->ddt_date, 'd/m/Y', 'Y-m-d');
			$myDdt->cargo = DMInput::getString('cargo', '');
			$myDdt->notes = DMInput::getString('notes', '');
			//Assegno il numero, se c'è bisogno
			
			if ($myDdt->ddt_code = DMInput::getString('ddt_code', '')) {
				$myDdt->generateCodeStr1();
			} else {
				$myDdt->generateCode();
			}		
			
			$myDdt->uniqueId = uniqid();
			
			
			parent::outputResult(0, $myDdt, 'ddt');
			
		}
		
		/**
			Elimina Ddt
			Mario felsinea
		**/
		function jsonDelete() {
			
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				parent::outputError(-110);
			}
			
			$ddtId = DMInput::getInt('ddtId', -1);
			$myDdt = DMTable::getInstance('Ddt');
			
			
			if (!$myDdt->load($ddtId)) {
				parent::outputError(-300);
			}
			
			
			if (!$myDdt->delete()) {
				parent::outputError(-400);
			}
			
			parent::outputResult(0);
			
		}
		
		/**
			Ottiene i dettagli di un Ddt
			Mario felsinea
		**/
		function jsonGetDdt() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			$ddtId = DMInput::getInt('ddtId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'ddthelper.php');
			$ddt = FHDdtHelper::loadDdt($ddtId);
			
			$myDdt = FHDdtHelper::loadDdt($ddtId);
			$myDdt->ddt_date_str = DMFormat::formatDate($myDdt->ddt_date, 'd/m/Y', 'Y-m-d');
			
			
			
			parent::outputResult($ddtId, $myDdt, 'ddt');

			
		}		
		
	}
?>