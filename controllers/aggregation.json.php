<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class AggregationJsonController extends DMJsonController {
	
		/**
			Restituisce la lista degli articoli aggregabili
		**/
		function jsonGetAggragableArticles() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			
			$articles = FHArticleHelper::getAggregableArticles();
			
			foreach ($articles as $article) {
				$article->packages_available = floor($article->quantity_units / $article->package_units);
			}
			
			parent::outputResult(count($articles), $articles, 'articles');
		
		}
		
		/**
			Aggrega gli articoli forniti in aggregationData (array di object (article_id, packages))
		**/
		function jsonAggregateArticles() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			DMDatabase::query("BEGIN;");
			
			$aggregationDataIn = DMInput::getString('aggregationData');
			$aggregationData = json_decode($aggregationDataIn);
			
			if (!$aggregationData) {
				parent::outputError(-1000, "Dati non validi");
			}
			
			$success = 0;
			$fail = 0;
			$printArray = array();
			foreach ($aggregationData as $articleData) {			
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'aggregationhelper.php');
				$aggregationResult = FHAggregationHelper::aggregateArticle($articleData->article_id, $articleData->packages, $myArticle, $myBatchOut, $myMovement, $myMovementDetail);
				if ($aggregationResult > 0) {
					$printData = new StdClass();
					$printData->articleId = $articleData->article_id;
					$printData->batchOutId = $myBatchOut->batch_out_id;
					for ($i = 0; $i < $articleData->packages; $i++) {
						$printArray[] = $printData;
					}
					$success++;
				} else {
					$fail++;
				}
			}
			
			$data = new StdClass();
			$data->success = $success;
			$data->fail = $fail;
			
			//Procedo alla stampa
			$myPrintClassPath = DM_APP_PATH . DS . 'views' . DS . 'recondition' . DS . 'print.php';
			require_once($myPrintClassPath);
			
			$myPrintClass = new DMPrintRecondition('portrait', 'pdf', 'Etichette', array(0,0,283.464,348.696));
			
			$myPrintClass->printArray = $printArray;
			
			$printResult = $myPrintClass->printMultipleLabels('recondition', 'multiple');
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