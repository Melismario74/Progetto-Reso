<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class StockJsonController extends DMJsonController {
	
		/**
			Elimina un movimento
		**/
		function jsonDeleteMovement() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			$movementId = DMInput::getInt('movementId');
			
			$myMovement = DMTable::getInstance('Movement');
			if (!$myMovement->load($movementId)) {
				parent::outputError(-300);
			}
			
			if (!$myMovement->delete()) {
				parent::outputError(-400);
			}
						
			parent::outputResult(0);
		
		}
		
		function jsonGetStocks() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			$stocks = FHStockHelper::getStocks();
			
			parent::outputResult(count($stocks), $stocks, 'stocks');
			
		}
		
		function jsonSaveMovement() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			DMDatabase::query("BEGIN;");
			
			$myMovement = DMTable::getInstance('Movement');
			$myMovement->article_id = DMInput::getInt('articleId', -1);
			$myMovement->movement_type = DMInput::getString('movementType', 'MANUAL');
			$myMovement->created_date = date('Y-m-d H:i:s');
			
			$chargelistId = DMInput::getInt('chargelistId', -1);
			
			if (!$myMovement->store()) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-200);
			}
			
			$stockFromId = DMInput::getInt('stockFromId', -1);
			$stockToId = DMInput::getInt('stockToId', -1);
			
			if ($stockFromId > 0) {
				$myMovementDetail = DMTable::getInstance('MovementDetail');
				$myMovementDetail->movement_id = $myMovement->movement_id;
				$myMovementDetail->stock_id = $stockFromId;
				$myMovementDetail->quantity_units = - DMInput::getInt('quantityUnits', 0);
				$myMovementDetail->quantity_packages = - DMInput::getInt('quantityPackages', 0);
				$myMovementDetail->chargelist_id = $chargelistId;
				
				if (!$myMovementDetail->store()) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-200);
				}
				
				//Se ho messo un magazzino di partenza, nessuno di arrivo, e una lista di carico, devo devalidare la quantità anche dalla lista di carico
				if (($chargelistId > 0) && ($stockToId < 1)) {
					require_once(DM_APP_PATH . DS . 'helpers' . DS . 'chargelisthelper.php');
					if (!FHChargelistHelper::updateChargelistArticle($chargelistId, $myMovement->article_id, $myMovementDetail->quantity_units, $myMovementDetail->stock_id)) {
						DMDatabase::query("ROLLBACK;");
					}
				}
			}
			
			if ($stockToId > 0) {
				$myMovementDetail = DMTable::getInstance('MovementDetail');
				$myMovementDetail->movement_id = $myMovement->movement_id;
				$myMovementDetail->stock_id = $stockToId;
				$myMovementDetail->quantity_units = DMInput::getInt('quantityUnits', 0);
				$myMovementDetail->quantity_packages = DMInput::getInt('quantityPackages', 0);
				$myMovementDetail->chargelist_id = $chargelistId;
				
				if (!$myMovementDetail->store()) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-200);
				}
				
				//Se ho messo un magazzino di arrivo, nessuno di partenza, e una lista di carico, devo validare la quantità anche dalla lista di carico
				if (($chargelistId > 0) && ($stockFromId < 1)) {
					require_once(DM_APP_PATH . DS . 'helpers' . DS . 'chargelisthelper.php');
					if (!FHChargelistHelper::updateChargelistArticle($chargelistId, $myMovement->article_id, $myMovementDetail->quantity_units, $myMovementDetail->stock_id)) {
						DMDatabase::query("ROLLBACK;");
					}
				}
			}
			
			if (!DMDatabase::query("COMMIT;")) {
				parent::outputError(-1000, "Errore finalizzando la transazione");
			}
			
			parent::outputResult($myMovement->movement_id);
			
		}
		
		function jsonPrintStock() {		
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			
			$page = DMInput::getInt('page', 1);
			
			$searchParams = array();
			$searchParams['limit'] = 0;
			$searchParams['offset'] = 0;
			
			$searchParams['name'] = DMInput::getString('name', '');
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['eanCode'] = DMInput::getString('eanCode', '');
			$searchParams['batchInCode'] = DMInput::getString('batchInCode', '');
			$searchParams['inStockOnly'] = 1;
			
			$articles = FHArticleHelper::getArticles($searchParams, $totalResults);
			
			foreach ($articles as $article) {
				
				$article->stock = array();
				$article->stock[1] = FHStockHelper::getArticleStockData($article->article_id, 1);
				$article->stock[2] = FHStockHelper::getArticleStockData($article->article_id, 2);
				$article->stock[3] = FHStockHelper::getArticleStockData($article->article_id, 3);
				
			}
			
			//Procedo alla stampa
			$myPrintClassPath = DM_APP_PATH . DS . 'views' . DS . 'stock' . DS . 'print.php';
			require_once($myPrintClassPath);
			
			$myPrintClass = new DMPrintStock('portrait', 'pdf', 'Magazzino', 'A4');
			
			$myPrintClass->articles = $articles;
			
			$printResult = $myPrintClass->execPrint('stock', 'default');
			
			$data = new StdClass();
			
			if ($printResult['result'] >= 0) {
				$data->print_url = $printResult['printUrl'];
			}
			
			parent::outputResult(0, $data);
			
		}
		
		function jsonExportStockCSV() {		
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
						
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			
			$searchParams = array();
			$searchParams['limit'] = 0;
			$searchParams['offset'] = 0;			
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['eanCode'] = DMInput::getString('eanCode', '');
			$searchParams['name'] = DMInput::getString('name', '');
			$searchParams['inStockOnly'] = DMInput::getInt('inStockOnly', 0);
			
			
			$fileUrl = FHStockHelper::exportStockCSV($searchParams);
			
			$data = new StdClass();
			$data->export_url = $fileUrl;
			
			parent::outputResult(0, $data);
		
			
		}
		
	}
?>