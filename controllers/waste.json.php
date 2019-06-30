<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class WasteJsonController extends DMJsonController {
	
		/**
			Verifica se il barcode è di un cartone o di un articolo.
			Il cartone è identificato in due casi:
			- Ha il barcode 128 parlante
			- E' un codice articolo
		**/
		function jsonCheckBarcode() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			
			$data = new StdClass();
			
			$barcode = DMInput::getString('barcode', '');
			
			//Controllo se contiene sia (01) che (400)
			$pos01 = strpos($barcode, '(01)'); 
			$pos400 = strpos($barcode, '(400)');
			$myArticle = DMTable::getInstance('Article');
				
			if (($pos01 !== false) && ($pos400 !== false)) {
				$data->is_package = 1;
				
				$data->article_code = substr($barcode, ($pos01 + 4), (($pos400) - ($pos01 + 4)));
				$data->batch_in_code = substr($barcode, ($pos400 + 5));
				
				if ($myArticle->loadFromArticleCode($data->article_code)) {
					$data->article_id = $myArticle->article_id;
				} else {
					$data->article_id = -1;
				}
			} else if ($myArticle->loadFromArticleCode($barcode)) {
				$data->is_package = 0;
				$data->article_code = $myArticle->article_code;
				$data->batch_in_code = '';
				$data->article_id = $myArticle->article_id;				
			} else if ($myArticle->loadFromEanCode($barcode)) {
				$data->is_package = 0;
				$data->article_code = $myArticle->article_code;
				$data->batch_in_code = '';
				$data->article_id = $myArticle->article_id;				
			} else {
				$data->is_package = 0;
			}
						
			parent::outputResult(0, $data);
		
		}
		
		function jsonProcess() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			DMDatabase::query("BEGIN;");
			
			//Recupero i dati
			$stockTarget = DMInput::getInt('stockTarget', 0);
			$articleId = DMInput::getInt('articleId', -1);
			$batchInCode = DMInput::getString('batchInCode', '');
			$quantity = DMInput::getInt('quantity', 0);			
			
			//Carico l'articolo
			$myArticle = DMTable::getInstance('Article');
			if (!$myArticle->load($articleId)) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-300);
			}
			
			//Recupero il lotto di ingresso o lo creo se necessario
			if ($batchInCode != '') {
				$myBatchIn = DMTable::getInstance('BatchIn');
				if (!$myBatchIn->loadFromBatchInCode($batchInCode)) {
					$myBatchIn->batch_in_code = $batchInCode;
					if (!$myBatchIn->store()) {
						DMDatabase::query("ROLLBACK;");
						parent::outputError(-200);
					}
				}
				$batchInId = $myBatchIn->batch_in_id;
			} else {
				$batchInId = -1;
			}
			
			//Eseguo il movimento
			$myMovement = DMTable::getInstance('Movement');
			$myMovement->article_id = $articleId;
			$myMovement->batch_in_id = $batchInId;
			$myMovement->movement_type = "PROCESS";
			if (!$myMovement->store()) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-200);
			}
			
			$myMovementDetail = DMTable::getInstance('MovementDetail');
			$myMovementDetail->movement_id = $myMovement->movement_id;
			$myMovementDetail->stock_id = $stockTarget;
			$myMovementDetail->quantity_units = $quantity;
			if (!$myMovementDetail->store()) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-200);
			}
			
		
			
			$data = new StdClass();
			$data->aggregation_available = 0;
			$data->aggregation_packages = 0;
			$data->label_available = 0;
			
			/** SOLO BUONI **/
			if ($stockTarget == 1) {
				
				
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'batchinhelper.php');
				if (!FHBatchInHelper::addArticle($batchInId, $articleId, $quantity)) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-1000, "Errore nell'aggiornamento del lotto di ingresso");
				} 					
			}
			
			if (!DMDatabase::query("COMMIT;")) {
				parent::outputError(-1000, "Errore finalizzando la transazione");
			}
			
			parent::outputResult(0, $data);
			
		}
		
		function jsonAggregatePackage() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			DMDatabase::query("BEGIN;");
			
			$articleId = DMInput::getInt('articleId', -1);
			$packages = DMInput::getInt('packages', 1);
			$chargelistId = DMInput::getInt('chargelistId', 1);
			
			FHHelper::log("fh", "[waste.jsonAggregatePackage] Starting aggregation for $packages packages of article $articleId");
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'aggregationhelper.php');
			$aggregationResult = FHAggregationHelper::aggregateArticle($articleId, $packages, $myArticle, $myBatchOut, $myMovement, $myMovementDetail);
			if ($aggregationResult == -1001) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-1000, "Non ci sono unità sufficienti per eseguire l'aggregazione");
			} else if ($aggregationResult == -1002) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-1000, "Errore nella generazione del lotto di uscita");
			} else if ($aggregationResult < 0) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError($aggregationResult);
			}
			
			//Aggiorno la sessione
			$currentSession = DMSession::get('wasteChargelists', false);
			$currentSession[$chargelistId][$articleId] -= ($myArticle->package_units * $packages);
			
			$data = new StdClass();
			
			//Ora devo generare la stampa delle etichette
			$myPrintClassPath = DM_APP_PATH . DS . 'views' . DS . 'waste' . DS . 'print.php';
			require_once($myPrintClassPath);
			
			$myPrintClass = new DMPrintRecondition('portrait', 'pdf', 'Etichetta ' . $myArticle->article_code, array(0,0,283.464,348.696));
			
			$myPrintClass->articleId = $myArticle->article_id;
			$myPrintClass->batchOutId = $myBatchOut->batch_out_id;
			
			$printResult = $myPrintClass->execPrint('waste', 'default');
			if ($printResult['result'] >= 0) {
				$data->label_url = $printResult['printUrl'];
			}
			
			if (!DMDatabase::query("COMMIT;")) {
				parent::outputError(-1000, "Errore finalizzando la transazione");
			}
			
			parent::outputResult(0, $data);
			
		}
		
	}
?>