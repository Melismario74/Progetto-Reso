<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class UdmJsonController extends DMJsonController {
	
		
		/**			
			Creo la pagnina delle UDM
		**/
		function jsonGetUdms() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_UDM')) {
				parent::outputError(-110);
			}			
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'udmhelper.php');
			$page = DMInput::getInt('page', 1);
			
			$searchParams = array();
			$searchParams['limit'] = DMInput::getInt('limit', 20);
			$searchParams['offset'] = DMInput::getInt('offset', $searchParams['limit'] * ($page - 1));
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['udmCode'] = DMInput::getString('udmCode', '');
			$searchParams['ubicazione'] = DMInput::getString('ubicazione', '');
			
			$udms = FHUdmHelper::getUdmsMod($searchParams, $totalResults);
			
			parent::outputResult(ceil($totalResults / $searchParams['limit']), $udms, 'udms');
		}
		
		
		/**			
			Salvataggio delle UDM selezionate
		**/		
		function jsonSaveUdm() {

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'udmhelper.php');

			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}
			DMDatabase::query("BEGIN;");
			
			$ubicazioneDataIn = DMInput::getString('ubicazioneData');
			$ubicazioneData = json_decode($ubicazioneDataIn);
			
			if (!$ubicazioneData) {
				parent::outputError(-1000, "Dati non validi");
			}
			foreach ($ubicazioneData as $udmData) {
			$udmCode =  $udmData->udm_code;
			$ubicazione = $udmData->ubicazione;
						

			$myUdm = DMTable::getInstance('Udm');
			if (!$myUdm->loadFromUdmCode($udmCode)) {
				parent::outputError(-404);
			}
						
			$myUdm = FHUdmHelper::saveUdm($udmCode, $ubicazione);
			}
			
			DMDatabase::query("COMMIT;");

			parent::outputResult(0);

		}
		/**			
			Stampa delle etichette per le UDM selezionate
		**/	
		function jsonPrintUdm() {

			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}

			$udmCodeDataIn = DMInput::getString('udmCodeData');
			$udmCodeData = json_decode($udmCodeDataIn);
			
			if (!$udmCodeData) {
				parent::outputError(-1000, "Dati non validi");
			}
			$success = 0;
			$printArray = array();
			foreach ($udmCodeData as $udmData) {
				$udmCode = $udmData->udm_code;
				

				//require_once(DM_APP_PATH . DS . 'helpers' . DS . 'udmhelper.php');

				$myUdm = DMTable::getInstance('Udm');
				if (!$myUdm->loadFromUdmCode($udmCode)) {
					parent::outputError(-404);				
				}
				$printData = new StdClass();
				$printData->udmId = $myUdm->udm_id;
				$printData->udmCode = $myUdm->udm_code;
				$printArray[] = $printData;
				$success++;
			}
			
			$data = new StdClass();
			$data->success = $success;
			
			$myPrintClassPath = DM_APP_PATH . DS . 'views' . DS . 'udm' . DS . 'print.php';
			require_once($myPrintClassPath);

			$myPrintClass = new DMPrintUdm('landscape', 'pdf', 'UDM' , array(0,0,283.464,348.696));

			$myPrintClass->printArray = $printArray;
			
			$printResult = $myPrintClass->printMultipleLabels('udm', 'multiple');
			if ($printResult['result'] >= 0) {
				$data->print_url = $printResult['printUrl'];
			}
			
			parent::outputResult(0, $data);

		}
		/**			
			Eliminazione dal database delle UDM selezionate
		**/	
		function jsonDeleteUdm() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}
			$udmCodeDataIn = DMInput::getString('udmCodeData');
			$udmCodeData = json_decode($udmCodeDataIn);
			
			if (!$udmCodeData) {
				parent::outputError(-1000, "Dati non validi");
			}
			foreach ($udmCodeData as $udmData) {
				$udmCode = $udmData->udm_code;
				$myUdm = DMTable::getInstance('Udm');
				if (!$myUdm->loadFromUdmCode($udmCode)) {
					parent::outputError(-404);
				}
			
				if (!$myUdm->delete()) {
					parent::outputError(-400);
				}
			}
						
			parent::outputResult(1);
		
		}
		
		/**
			Esporta le udm selezionate in CSV
		**/
		function jsonExportUdmsCSV() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'udmhelper.php');
			
			$searchParams = array();
			$searchParams['limit'] = 0;
			$searchParams['offset'] = 0;
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['udmCode'] = DMInput::getString('udmCode', '');
			$searchParams['ubicazione'] = DMInput::getString('ubicazione', '');
				
			$fileUrl = FHUdmHelper::exportUdmsCSVmod($searchParams);
			
			$data = new StdClass();
			$data->export_url = $fileUrl;
			
			parent::outputResult(0, $data);
			
		}
				
	}
?>