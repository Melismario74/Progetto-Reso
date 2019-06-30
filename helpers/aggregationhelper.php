<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHAggregationHelper {
	
		function aggregateArticle($articleId, $packages, &$myArticle, &$myBatchOut, &$myMovement, &$myMovementDetail) {
		
			//Carico l'articolo
			$myArticle = DMTable::getInstance('Article');
			if (!$myArticle->load($articleId)) {
				return -300;
			}
			
			//Prima controllo di avere sufficienti articoli nella "lista di attesa"
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'batchinhelper.php');
			$unitsAvailable = FHBatchInHelper::getArticleUnitsInWaitingList($articleId);
			FHHelper::log("aggregationhelper", "We have $unitsAvailable units available for aggregation of article $articleId (needed: " . ($myArticle->package_units * $packages) . ")");
			if ($unitsAvailable < ($myArticle->package_units * $packages)) {
				return -1001;
			}
			
			//Genero il movimento di aggregazione
			$myMovement = DMTable::getInstance('Movement');
			$myMovement->article_id = $articleId;
			$myMovement->movement_type = "AGGREGATE";
			if (!$myMovement->store()) {
				return -200;
			}
			
			$myMovementDetail = DMTable::getInstance('MovementDetail');
			$myMovementDetail->movement_id = $myMovement->movement_id;
			$myMovementDetail->stock_id = 1;
			$myMovementDetail->quantity_units = - ($myArticle->package_units * $packages);
			$myMovementDetail->quantity_packages = $packages;
			if (!$myMovementDetail->store()) {
				return -200;
			}
			
			//Ottengo il lotto di uscita
			$myBatchOut = DMTable::getInstance('BatchOut');
			if (!$myBatchOut->loadFromBatchOutCode(date('YW'))) {
				$myBatchOut->batch_out_code = '78' . date('WY');
			}
			if (!$myBatchOut->store()) {
				return -200;
			}
			
			$myMovement->batch_out_id = $myBatchOut->batch_out_id;
			if (!$myMovement->store()) {
				return -200;
			}
			
			//Adesso devo segnare nel lotto di uscita gli articoli che ci ho messo con i relativi lotti di ingresso
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'batchouthelper.php');
			if (!FHBatchOutHelper::moveArticleToBatch($articleId, $myArticle->package_units * $packages, $myBatchOut->batch_out_id)) {
				return -1002;
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			FHStockHelper::updateStockCache($articleId, 1);
			
			return $packages;
		
		}
		
		
		
	}
	
?>