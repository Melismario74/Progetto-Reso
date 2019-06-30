<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class LdvJsonController extends DMJsonController {
	
		/**
			Ottiene la lista delle Ldv
			
		**/
		function jsonGetLdvs() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'ldvhelper.php');
			$ldvs = FHLdvHelper::getLdvs();
			
			parent::outputResult(count($ldvs), $ldvs, 'ldvs');
			
		}
		
		/**
			Ottiene i dettagli di un Ldv
			Mario felsinea
		**/
		function jsonLoadLdv() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			$ldvId = DMInput::getInt('ldvId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'ldvhelper.php');
			$myLdv = FHLdvHelper::loadLdv($ldvId);
			if (!$myLdv) {
				parent::outputError(-300);
			}
			
			$myLdv->ldv_date_str = DMFormat::formatDate($myLdv->ldv_date, 'd/m/Y', 'Y-m-d');
			
			
			foreach ($myLdv->rows as $row) {
				$row->uniqueId = uniqid();
				$row->ddt_date_str = DMFormat::formatDate($row->ddt_date, 'd/m/Y', 'Y-m-d');
			}	
		
			
			parent::outputResult($ldvId, $myLdv, 'ldv');

			
		}
		
		
		/**
			Ottiene i dettagli di un Ldv
			Mario felsinea
		
		function jsonGetLdv() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			$ldvId = DMInput::getInt('ldvId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'ldvhelper.php');
			$myLdv = DMTable::getInstance('Ldv');
			
			if ($ldvId > 0) {
				if (!$myLdv->load($ldvId)) {
					parent::outputError(-300);
				}
			}
			
			$myLdv = FHLdvHelper::getLdv($ldvId);
			$myLdv->ldv_date_str = DMFormat::formatDate($myLdv->ldv_date, 'd/m/Y', 'Y-m-d');
			
			
			
			parent::outputResult($ldvId, $myLdv, 'ldv');

			
		}
		**/
	
		/**
			Salva ldv
			Mario felsinea
		**/
		function jsonAdd() {
		
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				parent::outputError(-110);
			}
			
			$ldvId = DMInput::getInt('ldvId', -1);
			$myLdv = DMTable::getInstance('Ldv');
			
			if ($ldvId > 0) {
				if (!$myLdv->load($ldvId)) {
					parent::outputError(-300);
				}
			}
			$myLdv->uniqueId = uniqid();
			$myLdv->ldv_date = DMFormat::formatDate(DMInput::getString('ldv_date', ''), 'Y-m-d', 'd/m/Y');
			$myLdv->ldv_date_str = DMFormat::formatDate($myLdv->ldv_date, 'd/m/Y', 'Y-m-d');
			$myLdv->sender = DMInput::getString('sender', '');
			$myLdv->notes = DMInput::getString('notes', '');
			$myLdv->carton = DMInput::getString('carton', '');
			$myLdv->pallet = DMInput::getString('pallet', '');
			
			//Assegno il numero, se c'è bisogno
			if ($myLdv->ldv_code = DMInput::getString('ldv_code', '')) {
				$myLdv->generateCodeStr1();
			} else {
				$myLdv->generateCode();
			}		
			
			
			parent::outputResult(0, $myLdv, 'ldv');
			
		}
		
		function jsonSave() {
		
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				parent::outputError(-110);
			}
			DMDatabase::query("BEGIN;");
			
			$ldvId = DMInput::getInt('ldvId', -1);
			$myLdv = DMTable::getInstance('Ldv');
			
			if ($ldvId > 0) {
				if (!$myLdv->load($ldvId)) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-300);
				}
			}
			
			//Qui dovrei leggere le righe
			$rowsDataStr = DMInput::getString('rowsData', '');
			DMLog::log('ldv', $rowsDataStr);
			$rowsData = json_decode($rowsDataStr);
			
			
			//Prima cancello tutte le righe ed allegati
			$myLdv->clearRows();
			
			//Poi reinserisco
			
			foreach ($rowsData as $rowData) {
				$myLdvRow = DMTable::getInstance('LdvRow');
				$myLdvRow->ldv_id = $myLdv->ldv_id;				
				if ($rowData->ddt_id > 0) {
					$myLdvRow->ddt_id = $rowData->ddt_id;
				} else {
					$myDdt = DMTable::getInstance('Ddt');
					$myDdt->ddt_code = $rowData->ddt_code;
					$myDdt->ddt_code_str = $rowData->ddt_code_str;
					$myDdt->ddt_date = $rowData->ddt_date;
					$myDdt->cargo = $rowData->cargo;
					$myDdt->notes = $rowData->notes;
											
					if (!$myDdt->store()) {
						DMDatabase::query("ROLLBACK;");
						parent::outputError(-1000, 'Errore nel salvataggio delle righe del documento');
					}
						
					$myLdvRow->ddt_id = $myDdt->ddt_id;
					
					
				}
				
				if (!$myLdvRow->store()) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-1000, 'Errore nel salvataggio delle righe del documento');
				}
				
			}
			
			if (!DMDatabase::query("COMMIT;")) {
				parent::outputError(-1000, "Errore finalizzando la transazione");
			}
	
			
			
			parent::outputResult($myLdv->ldv_id);
			
		}
		
		/**
			Elimina ldv
			Mario felsinea
		**/
		function jsonDeleteLdv() {
			
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				parent::outputError(-110);
			}
			
			$ldvId = DMInput::getInt('ldvId', -1);
			$myLdv = DMTable::getInstance('Ldv');
			
			if ($ldvId > 0) {
				if (!$myLdv->load($ldvId)) {
					parent::outputError(-300);
				}
			}
			
			if (!$myLdv->delete()) {
				parent::outputError(-400);
			}
			
			parent::outputResult(0);
			
		}
		
		/**
			Elimina ldv
			Mario felsinea
		**/
		function jsonGetArrival() {
			
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				parent::outputError(-110);
			}
			
			$ldvId = DMInput::getInt('ldvId', -1);
			
			$data= new StdClass();
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'ldvhelper.php');
			$data->arrival_id = FHLdvHelper::getArrival($ldvId);
			
			
			parent::outputResult($data->arrival_id);
			
		}
		
		
	}
?>