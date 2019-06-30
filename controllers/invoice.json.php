<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class InvoiceJsonController extends DMJsonController {
	
		/**
			Ottiene la lista dei documenti di uscita
		**/
		function jsonGetInvoices() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'invoicehelper.php');
			
			$page = DMInput::getInt('page', 1);
			
			$searchParams = array();
			$searchParams['limit'] = DMInput::getInt('limit', 20);
			$searchParams['offset'] = DMInput::getInt('offset', $searchParams['limit'] * ($page - 1));
			
			$searchParams['invoiceDateFrom'] = DMFormat::formatDate(DMInput::getString('invoiceDateFrom', ''), 'Y-m-d', 'd/m/Y');
			$searchParams['invoiceDateTo'] = DMFormat::formatDate(DMInput::getString('invoiceDateTo', ''), 'Y-m-d', 'd/m/Y');
			$searchParams['invoice_archived'] = DMInput::getInt('invoice_archived', -1);
			
			$invoices = FHInvoiceHelper::getInvoices($searchParams, $totalResults);
			
			foreach ($invoices as $invoice) {
				$invoice->invoice_date_str = DMFormat::formatDate($invoice->invoice_date, 'd/m/Y', 'Y-m-d');
				if ($invoice->invoice_archived) {
					$invoice->invoice_archived_str = 'Si';
				} else {
					$invoice->invoice_archived_str = 'No';
				}
			}
			
			parent::outputResult(ceil($totalResults / $searchParams['limit']), $invoices, 'invoices');
		
		}
		
		/**
			Salva un documento di uscita
		**/
		function jsonSave() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
			
			DMDatabase::query("BEGIN;");
			
			$invoiceId = DMInput::getInt('invoiceId', -1);
			$myInvoice = DMTable::getInstance('Invoice');
			
			if ($invoiceId > 0) {
				if (!$myInvoice->load($invoiceId)) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-300);
				}
			}
			
			$myInvoice->invoice_date = DMFormat::formatDate(DMInput::getString('invoiceDate', ''), 'Y-m-d', 'd/m/Y');
			$myInvoice->client_name = DMInput::getString('clientName', '');
			$myInvoice->notes = DMInput::getString('notes', '');
			$myInvoice->subject = DMInput::getString('subject', '');
			
			
			//Assegno il numero, se c'è bisogno
			if ($invoiceId > 0) {
				$myInvoice->invoice_code = DMInput::getInt('invoiceCode', 0);
				$myInvoice->generateCodeStr();
			} else {
				$myInvoice->generateCode();
			}
			
			if (!$myInvoice->store()) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-200);
			}
			
			//Qui dovrei leggere le righe
			$rowsDataStr = DMInput::getString('rowsData', '');
			DMLog::log('invoice', $rowsDataStr);
			$rowsData = json_decode($rowsDataStr);
			
			//Prima cancello tutte le righe ed allegati
			$myInvoice->clearRows();
			
			//Poi reinserisco
			foreach ($rowsData as $rowData) {
				DMLog::log('invoice', 'Reading row... Article ID = ' . $rowData->article_id);
				$myInvoiceRow = DMTable::getInstance('InvoiceRow');
				$myInvoiceRow->invoice_id = $myInvoice->invoice_id;
				$myInvoiceRow->article_id = $rowData->article_id;
				$myInvoiceRow->article_code = $rowData->article_code;
				$myInvoiceRow->description = $rowData->description;
				$myInvoiceRow->quantity_units = $rowData->quantity_units;
				$myInvoiceRow->udm_id = $rowData->udm_id;
				$myInvoiceRow->ubicazione = $rowData->ubicazione;				
				$myInvoiceRow->stock_id = $rowData->stock_id;
				$myInvoiceRow->udm_code_old =  $rowData->udm_code + $rowData->udm_code_old ;				
				
				
				// $articleData = FHArticleHelper::loadArticle($myInvoiceRow->article_id);
				// if ($articleData) {
					// $totalUnits += $myInvoiceRow->quantity_units + ($myInvoiceRow->quantity_packages * $articleData->package_units);
				// } else {
					// $totalUnits += $myInvoiceRow->quantity_units;
				// }
				
				if (!$myInvoiceRow->store()) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-1000, 'Errore nel salvataggio delle righe del documento');
				}
				
				//Ok, salvo il movimento di magazzino
				$myMovement = DMTable::getInstance('Movement');
				$myMovement->article_id = $myInvoiceRow->article_id;
				$myMovement->source_id = $myInvoice->invoice_id;
				$myMovement->stock_id = $rowData->stock_id;
				$myMovement->movement_type = "INVOICE";
				$myMovement->created_date = $myInvoice->invoice_date;
				
				if (!$myMovement->store()) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-1000, 'Errore nel salvataggio dei movimenti');
				}
				
				$myMovementDetail = DMTable::getInstance('MovementDetail');
				$myMovementDetail->movement_id = $myMovement->movement_id;
				$myMovementDetail->stock_id = $rowData->stock_id;
				$myMovementDetail->quantity_units = - $myInvoiceRow->quantity_units;
				$myMovementDetail->udm_code = $rowData->udm_code;
				//$myMovementDetail->quantity_packages = - $myInvoiceRow->quantity_packages;
				
				if (!$myMovementDetail->store()) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-1000, 'Errore nel salvataggio dei dettagli movimenti');
				}
				
					if ($myInvoiceRow->stock_id ==1 || $myInvoiceRow->stock_id=3 ) {
						//Aggiorno la UDM
						if ($rowData->udm_code > 0) {
							$myUdm = DMTable::getInstance('Udm');
							$myUdm->load($rowData->udm_code);
							$myMovementDetail->udm_code = $rowData->udm_code;

							$udmArticleQuantity = FHLogisticsHelper::getUdmArticle($rowData->udm_id, $rowData->article_id);
							//$udmArticleInvoiceQuantity = FHLogisticsHelper::getUdmArticleInvoice($rowData->udm_id, $rowData->article_id, $myInvoiceRow->invoice_id );
							if ($udmArticleQuantity < $rowData->quantity_units) {
								DMDatabase::query("ROLLBACK;");
								parent::outputError(-1000, 'La UDM non contiene la quantità sufficente di confezioni');
							}
							

							FHLogisticsHelper::addToUdm($rowData->udm_id, $myInvoiceRow->article_id, $rowData->stock_id, - $rowData->quantity_units );
						}
						
						if (!$myMovementDetail->store()) {
							DMDatabase::query("ROLLBACK;");
							parent::outputError(-1000, 'Errore nel salvataggio dei movimenti (dopo UDM)');
						}
						
						if (!$myInvoiceRow->store()) {
							DMDatabase::query("ROLLBACK;");
							parent::outputError(-1000, 'Errore nel salvataggio delle righe del documento (dopo UDM)');
						}
					}
				
			}
			
			//$myInvoice->total_units = $totalUnits;
			if (!$myInvoice->store()) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-200);
			}
			
			if (!DMDatabase::query("COMMIT;")) {
				parent::outputError(-1000, "Errore finalizzando la transazione");
			}
			
			parent::outputResult($myInvoice->invoice_id);
			
		}
			
		
		/**
			Elimina un documento di uscita
		**/
		function jsonDelete() {
		
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
			
			$invoiceId = DMInput::getInt('invoiceId', -1);
			
			$myInvoice = DMTable::getInstance('Invoice');
			
			if (!$myInvoice->load($invoiceId)) {
				parent::outputError(-300);
			}
			
			$myInvoice->clearRows();
			
			$myInvoice->delete();
			
			parent::outputResult(0);
			
		}
		
		/**
			Carica un documento di uscita
		**/
		function jsonLoadInvoice() {
		
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
			
			$invoiceId = DMInput::getInt('invoiceId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'invoicehelper.php');
			
			$myInvoice = FHInvoiceHelper::loadInvoice($invoiceId);
			$myInvoice->invoice_date_str = DMFormat::formatDate($myInvoice->invoice_date, 'd/m/Y', 'Y-m-d');
			
			//Aggiungo gli uniqueid alle rows
			foreach ($myInvoice->rows as $row) {
				$row->uniqueId = uniqid();
			}
			
			parent::outputResult($invoiceId, $myInvoice, 'invoice');
			
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
		function jsonExportInvoice() {
			
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
			
			$invoiceId = DMInput::getInt('invoiceId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'invoicehelper.php');
			
			$myInvoice = FHInvoiceHelper::loadInvoice($invoiceId);
			
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
			
			foreach ($myInvoice->rows as $row) {
			
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
				$myArray[] = str_pad($myInvoice->invoice_code, 10, '0', STR_PAD_LEFT);
				$myArray[] = DMFormat::formatDate($myInvoice->invoice_date, 'dmY', 'Y-m-d');
				$myArray[] = str_pad($row->invoice_row_id, 7, '0', STR_PAD_LEFT);
				$myArray[] = DMFormat::formatDate($myInvoice->invoice_date, 'dmY', 'Y-m-d');
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
		function jsonPrintInvoice() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_INVOICE')) {
				parent::outputError(-110);
			}
			
			$invoiceId = DMInput::getInt('invoiceId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'invoicehelper.php');
			
			$myInvoice = FHInvoiceHelper::loadInvoice($invoiceId);
			
			$myPrintClassPath = DM_APP_PATH . DS . 'views' . DS . 'invoice' . DS . 'print.php';
			require_once($myPrintClassPath);
			
			$myPrintClass = new DMPrintRecondition('portrait', 'pdf', 'DDT ' . $myInvoice->invoice_code_str, "A4");
			
			$myPrintClass->invoiceId = $myInvoice->invoice_id;
			
			$printResult = $myPrintClass->execPrint('invoice', 'default');
			
			$data = new StdClass();
			
			if ($printResult['result'] >= 0) {
				$data->print_url = $printResult['printUrl'];
			}
			
			parent::outputResult(0, $data);
			
		}
		
		function archiveInvoice() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege("FH_INVOICE")) {
				parent::outputError(-110);
			}
			
			$invoiceId = DMInput::getInt('invoiceId');
			$archived = DMInput::getInt('archived', 0);
			
			$myInvoice = DMTable::getInstance('Invoice');
			if (!$myInvoice->load($invoiceId)) {
				parent::outputError(-300);				
			}
			
			$myInvoice->invoice_archived = $archived;
			if (!$myInvoice->store()) {
				parent::outputError(-200);	
			}
			
			parent::outputResult($invoiceId);
			
		}
		
	}
?>