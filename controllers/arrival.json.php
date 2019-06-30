<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class ArrivalJsonController extends DMJsonController {
	
		/**
			Ottiene la lista dei documenti di uscita
		**/
		function jsonGetArrivals() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'arrivalhelper.php');
			
			$page = DMInput::getInt('page', 1);
			
			$searchParams = array();
			$searchParams['limit'] = DMInput::getInt('limit', 20);
			$searchParams['offset'] = DMInput::getInt('offset', $searchParams['limit'] * ($page - 1));
			
			$searchParams['arrivalDateFrom'] = DMFormat::formatDate(DMInput::getString('arrivalDateFrom', ''), 'Y-m-d', 'd/m/Y');
			$searchParams['arrivalDateTo'] = DMFormat::formatDate(DMInput::getString('arrivalDateTo', ''), 'Y-m-d', 'd/m/Y');
			$searchParams['arrival_archived'] = DMInput::getInt('arrival_archived', -1);
			
			$arrivals = FHArrivalHelper::getArrivals($searchParams, $totalResults);
			
			foreach ($arrivals as $arrival) {
				$arrival->arrival_date_str = DMFormat::formatDate($arrival->arrival_date, 'd/m/Y', 'Y-m-d');
				if ($arrival->arrival_archived) {
					$arrival->arrival_archived_str = 'Si';
				} else {
					$arrival->arrival_archived_str = 'No';
				}
				$arrival->nLdv= FHArrivalHelper::getArrivalNumberLdvs($arrival->arrival_id);
				//$arrival->nDdt= FHArrivalHelper::getArrivalNumberDdts($arrival->arrival_id);
		
			}
			
			parent::outputResult(ceil($totalResults / $searchParams['limit']), $arrivals, 'arrivals');
		
		}
		
		/**
			Ottiene la lista delle ldv
		**/
		function jsonGetArrivalLdv() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'arrivalhelper.php');
			$ldvs = FHArrivalHelper::getArrivalLdvs();
			
			parent::outputResult(count($ldvs), $ldvs, 'ldvs');
			
		}
		
		/**
			Salva un documento di uscita
		**/
		function jsonSave() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
			
			DMDatabase::query("BEGIN;");
			
			$arrivalId = DMInput::getInt('arrivalId', -1);
			$myArrival = DMTable::getInstance('Arrival');
			
			if ($arrivalId > 0) {
				if (!$myArrival->load($arrivalId)) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-300);
				}
			}
			
			$myArrival->arrival_date = DMFormat::formatDate(DMInput::getString('arrivalDate', ''), 'Y-m-d', 'd/m/Y');
			$myArrival->vector_name = DMInput::getString('vectorName', '');
			$myArrival->notes = DMInput::getString('notes', '');
			$myArrival->subject = DMInput::getString('subject', '');
			
			
			//Assegno il numero, se c'è bisogno
			if ($arrivalId > 0) {
				$myArrival->arrival_code = DMInput::getString('arrivalCode', '');
				$myArrival->generateCodeStr();
			} else {
				$myArrival->generateCode();
			}
			
			if (!$myArrival->store()) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-200);
			}
			
			//Qui dovrei leggere le righe
			$rowsDataStr = DMInput::getString('rowsData', '');
			DMLog::log('arrival', $rowsDataStr);
			$rowsData = json_decode($rowsDataStr);
			
			//Prima cancello tutte le righe ed allegati
			$myArrival->clearRows();
			
			//Poi reinserisco
			foreach ($rowsData as $rowData) {
				$myArrivalRow = DMTable::getInstance('ArrivalRow');
				$myArrivalRow->arrival_id = $myArrival->arrival_id;				
				if ($rowData->ldv_id > 0) {
					$myArrivalRow->ldv_id = $rowData->ldv_id;
					$myArrivalRow->ldv_code = $rowData->ldv_code;
					$myArrivalRow->carton = $rowData->carton;
					$myArrivalRow->pallet = $rowData->pallet;
				} else {
					$myLdv = DMTable::getInstance('Ldv');
					$myLdv->ldv_code = $rowData->ldv_code;
					$myLdv->ldv_code_str = $rowData->ldv_code_str;
					$myLdv->ldv_date = $rowData->ldv_date;
					$myLdv->sender = $rowData->sender;
					$myLdv->carton = $rowData->carton;
					$myLdv->pallet = $rowData->pallet;
					$myLdv->notes = $rowData->notes;
						
					if (!$myLdv->store()) {
						DMDatabase::query("ROLLBACK;");
						parent::outputError(-1000, 'Errore nel salvataggio delle righe del documento');
					}
						
					$myArrivalRow->ldv_id = $myLdv->ldv_id;
					$myArrivalRow->ldv_code = $myLdv->ldv_code;
					$myArrivalRow->carton = $myLdv->carton;
					$myArrivalRow->pallet = $myLdv->pallet;
				}
				
				if (!$myArrivalRow->store()) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-1000, 'Errore nel salvataggio delle righe del documento');
				}
				
			}
			
			
			if (!DMDatabase::query("COMMIT;")) {
				parent::outputError(-1000, "Errore finalizzando la transazione");
			}
		
			parent::outputResult($myArrival->arrival_id);
			
		}
			
		
		/**
			Elimina un documento di uscita
		**/
		function jsonDelete() {
		
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
			
			$arrivalId = DMInput::getInt('arrivalId', -1);
			
			$myArrival = DMTable::getInstance('Arrival');
			
			if (!$myArrival->load($arrivalId)) {
				parent::outputError(-300);
			}
			
			$myArrival->clearRows();
			
			$myArrival->delete();
			
			parent::outputResult(0);
			
		}
		
		/**
			Carica un documento di uscita
		**/
		function jsonLoadArrival() {
		
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
			
			$arrivalId = DMInput::getInt('arrivalId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'arrivalhelper.php');
			
			$myArrival = FHArrivalHelper::loadArrival($arrivalId);
			$myArrival->arrival_date_str = DMFormat::formatDate($myArrival->arrival_date, 'd/m/Y', 'Y-m-d');
			
			//Aggiungo gli uniqueid alle ldvs
			foreach ($myArrival->ldvs as $ldv) {
				$ldv->uniqueId = uniqid();
				$ldv->ldv_date_str = DMFormat::formatDate($ldv->ldv_date, 'd/m/Y', 'Y-m-d');
			}
			
			parent::outputResult($arrivalId, $myArrival, 'arrival');
			
		}
		
		/**
			Restituisce righe corrispondenti al magazzino indicato
		**/
		function jsonGetStockRows() {
			
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
				
			$udmCodeDataIn = DMInput::getString('udmCodeData');
			$udmCodeData = json_decode($udmCodeDataIn);
			
			if (!$udmCodeData) {
				parent::outputError(-1000, "Dati non validi");
			}
			
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
			
			$results = array();
			
			foreach ($udmCodeData as $udmData) {
				$myRow = new StdClass();
				$udmCode =  $udmData->udm_code;		
				$articleCode = $udmData->article_code;		
				$quantity = $udmData->quantity;
				
				$myUdm = DMTable::getInstance('Udm');
				if (!$myUdm->loadFromUdmCode($udmCode)) {
					parent::outputError(-404);
				}
				$myArticle = DMTable::getInstance('Article');
				if (!$myArticle->loadFromArticleCode($articleCode)) {
					parent::outputError(-504);
				}
				
				$udmId = $myUdm->udm_id;
				$stockId = $myUdm->type;					
			
				$myStock = DMTable::getInstance('Stock');
				$myStock->load($stockId);					
		
				$udmArticle = FHLogisticsHelper::getUdmItem($udmId);

				$myRow->article_id = $myArticle->article_id;
				$myRow->article_code = $myArticle->article_code;
				$myRow->description = $myArticle->name;
				$myRow->stock_id = $stockId;
				$myRow->stock_name = $myStock->name;
				$myRow->udm_id = $udmId;
				$myRow->udm_code = $myUdm->udm_code;
				$myRow->udm_code_old = '';
				$myRow->ubicazione = $myUdm->ubicazione;
				$myRow->quantity_units = $quantity;
				$myRow->uniqueId = uniqid();				
				
				$results[] = $myRow;
			
			} 			
			
			parent::outputResult(count($myRow), $results, 'rows');
			
		}
		
		
		function jsonGetStockItems() {
			
			$stockId  = DMInput::getInt('stockId', -1);
			$myStock = DMTable::getInstance('Stock');
			$myStock->load($stockId);	
				
			$results = array();
			
			
			if ($stockId == 2) {	

				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
				$stockArticles = FHStockHelper::getStockArticles($stockId);
				
				foreach ($stockArticles as $stockArticle) {
				 $myRow = new StdClass();
				
				 $myArticle = FHArticleHelper::loadArticle($stockArticle->article_id);
					
				 $myRow->article_id = $stockArticle->article_id;
				 $myRow->article_code = $myArticle->article_code;
				 $myRow->description = $myArticle->name;
				 $myRow->stock_id = $stockId;
				 $myRow->stock_name = $myStock->name;
				 $myRow->udm_id = 'SCARTO';
				 $myRow->udm_code = 'SCARTO'; //$myUdm->udm_code;
				 $myRow->ubicazione = 'SCARTO'; // $myUdm->ubicazione;
				 $myRow->quantity_units = $stockArticle->quantity_units;
				 $myRow->uniqueId = uniqid();
				
				 $results[] = $myRow;
				}
			}
			
			
			parent::outputResult(count($myRow), $results, 'rows');
			
		}
		/**
			Esporta il TXT di un DDT
		**/
		function jsonExportArrival() {
			
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
			
			$arrivalId = DMInput::getInt('arrivalId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'arrivalhelper.php');
			
			$myArrival = FHArrivalHelper::loadArrival($arrivalId);
			
			$myContent = array();
			
			//Headers
			$myArray = array();	
			$myArray[] = "Serie";
			$myArray[] = "Riferimento";
			$myArray[] = "Data Doc";
			$myArray[] = "Riga";
			$myArray[] = "Data Rif";
			$myArray[] = "Fornitore";
			$myArray[] = "Cliente";
			$myArray[] = "Divisa";
			$myArray[] = "Articolo";
			$myArray[] = "Descrizione";
			$myArray[] = "Cf vs CT";
			$myArray[] = "Qta Cf";
			$myArray[] = "UDM";
			$myArray[] = "Prezzo";
			$myArray[] = "Importo";
			$myArray[] = "TR";
			
			$myContent[] = $myArray;
			
			foreach ($myArrival->rows as $row) {
			
				$myArticle = DMTable::getInstance('Article');
				$myArticle->load($row->article_id);
				
				if ($row->stock_id > 0) {
					$myStock = DMTable::getInstance('Stock');
					$myStock->load($row->stock_id);
					$stockCode = $myStock->stock_code_short;
				} else {
					$stockCode = '';
				}
				
				$myArray = array();	
				$myArray[] = $stockCode;
				$myArray[] = str_pad($myArrival->arrival_code, 10, '0', STR_PAD_LEFT);
				$myArray[] = DMFormat::formatDate($myArrival->arrival_date, 'dmY', 'Y-m-d');
				$myArray[] = str_pad($row->arrival_row_id, 7, '0', STR_PAD_LEFT);
				$myArray[] = DMFormat::formatDate($myArrival->arrival_date, 'dmY', 'Y-m-d');
				$myArray[] = '00000000';
				$myArray[] = '00003818078';
				$myArray[] = 'EUR';
				$myArray[] = $myArticle->article_code;
				$myArray[] = $myArticle->name;
				$myArray[] = $myArticle->package_units;
				$myArray[] = ($row->quantity_packages * $myArticle->package_units) + $row->quantity_units;
				$myArray[] = $row->udm_code;
				$myArray[] = '0,00000';
				$myArray[] = '0,00000';
				$myArray[] = 'TR';
				
				$myContent[] = $myArray;
				unset($myArticle);
			}
			
			
			
			$fileName = 'ddt_export_' . uniqid() . '.csv';
			$filePath = DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . $fileName;
			$fileUrl = DMUrl::getCurrentBaseUrl() . 'temp/export/' . $fileName;
			
			$fp = fopen($filePath, 'w');
			foreach ($myContent as $row) {
				dumbcsv($fp, $row, '"', ';', "\n");
			}
			fclose($fp);
			
			$data = new StdClass();
			$data->export_url = $fileUrl;
			
			parent::outputResult(0, $data);
			
		}
		
		function dumbcsv($file_handle, $data_array, $enclosure, $field_sep, $record_sep) {
 			    dumbescape(false, $enclosure);
 			    $data_array=array_map('dumbescape',$data_array);
 			    return fputs($file_handle, 
 			        "=" . $enclosure 
 			        . implode($enclosure . $field_sep . "=" . $enclosure, $data_array)
 			        . $enclosure . $record_sep);
 			}
 			
 		function dumbescape($in, $enclosure=false) {
 			   static $enc;
 			   if ($enclosure===false) {
 			       return str_replace($enc, '\\' . $enc, $in);
 			   }
 			   $enc=$enclosure;
 		}
		
		/**
			Stampa l'etichetta per l'articolo e il lotto di uscita indicato
		**/
		function jsonPrintArrival() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
			
			$arrivalId = DMInput::getInt('arrivalId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'arrivalhelper.php');
			
			$myArrival = FHArrivalHelper::loadArrival($arrivalId);
			
			$myPrintClassPath = DM_APP_PATH . DS . 'views' . DS . 'arrival' . DS . 'print.php';
			require_once($myPrintClassPath);
			
			$myPrintClass = new DMPrintRecondition('portrait', 'pdf', 'DDT ' . $myArrival->arrival_code_str, "A4");
			
			$myPrintClass->arrivalId = $myArrival->arrival_id;
			
			$printResult = $myPrintClass->execPrint('arrival', 'default');
			
			$data = new StdClass();
			
			if ($printResult['result'] >= 0) {
				$data->print_url = $printResult['printUrl'];
			}
			
			parent::outputResult(0, $data);
			
		}
		
		function archiveArrival() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege("FH_INVOICE")) {
				parent::outputError(-110);
			}
			
			$arrivalId = DMInput::getInt('arrivalId');
			$archived = DMInput::getInt('archived', 0);
			
			$myArrival = DMTable::getInstance('Arrival');
			if (!$myArrival->load($arrivalId)) {
				parent::outputError(-300);				
			}
			
			$myArrival->arrival_archived = $archived;
			if (!$myArrival->store()) {
				parent::outputError(-200);	
			}
			
			parent::outputResult($arrivalId);
			
		}
		
	}
?>