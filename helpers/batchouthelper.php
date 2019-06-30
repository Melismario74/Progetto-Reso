<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHBatchOutHelper {
		
		/**
			Aggiunge un articolo ad un lotto di uscita aggiornando l'eventuale quantità
		**/
		static function addArticle($batchOutId, $batchInId, $articleId, $quantity) {
		
			$batchOutId = (int) $batchOutId;
			$batchInId = (int) $batchInId;
			$articleId = (int) $articleId;
			$quantity = (int) $quantity;
			
			$myQuery = "
				SELECT COUNT(*)
				FROM fh_batch_out_article
				WHERE batch_out_id = " . (int) $batchOutId . "
				AND batch_in_id = " . (int) $batchInId . "
				AND article_id = " . (int) $articleId . "
			"; 
			
			if (DMDatabase::loadResult($myQuery) < 1) {
				$myQuery = "
					INSERT INTO fh_batch_out_article (
						batch_out_id,
						batch_in_id,
						article_id,
						quantity
					) VALUES (
						$batchOutId,
						$batchInId,
						$articleId,
						$quantity
					)
				";
			} else {
				$myQuery = "
					UPDATE fh_batch_out_article
					SET quantity = quantity + $quantity
					WHERE batch_out_id = $batchOutId
					AND batch_in_id = $batchInId
					AND article_id = $articleId
				";
			}
			
			return DMDatabase::query($myQuery);
			
		}
		
		/**
			Imposta la quantità di un articolo rispetto ad un lotto di uscita
		**/
		static function setArticle($batchOutId, $batchInId, $articleId, $quantity) {
		
			$batchOutId = (int) $batchOutId;
			$batchInId = (int) $batchInId;
			$articleId = (int) $articleId;
			$quantity = (int) $quantity;
			
			$myQuery = "
				DELETE
				FROM fh_batch_out_article
				WHERE batch_out_id = " . (int) $batchOutId . "
				AND batch_in_id = " . (int) $batchInId . "
				AND article_id = " . (int) $articleId . "
			"; 
			
			if (!DMDatabase::query($myQuery)) {
				return false;
			}
			$myQuery = "
				INSERT INTO fh_batch_out_article (
					batch_out_id,
					batch_in_id,
					article_id,
					quantity
				) VALUES (
					$batchOutId,
					$batchInId,
					$articleId,
					$quantity
				)
			";
			
			return DMDatabase::query($myQuery);
			
		}
		
		/**
			Sposta un articolo dalla lista di attesa al lotto di uscita
			
			@param int l'id dell'articolo
			@param int le unità da spostare
			@param int l'id del lotto di uscita in cui spostare
		**/
		static function moveArticleToBatch($articleId, $quantity, $batchOutId) {
		
			$articleId = (int) $articleId;
		
			$quantityMoved = 0;
			
			//Trovo i blocchi che posso spostare dalla lista di attesa
			$myQuery = "
			    SELECT batch_in_id, quantity
			    FROM fh_batch_in_article
			    WHERE article_id = " . (int) $articleId . "
			    AND quantity > 0
			    ORDER BY batch_in_id ASC
			";
			$waitingList = DMDatabase::loadObjectList($myQuery);
			
			foreach ($waitingList as $waitingItem) {
				
				$quantityRemaining = $quantity - $quantityMoved;
				
				if ($quantityRemaining <= 0) {
					break;
				}
				
				$quantityMove = min($quantityRemaining, $waitingItem->quantity);
				
				//Tiro via dalla lista di attesa...
				$myQuery = "
					UPDATE fh_batch_in_article
					SET quantity = quantity - $quantityMove
					WHERE article_id = $articleId
					AND batch_in_id = " . (int) $waitingItem->batch_in_id . "
				";
				
				if (!DMDatabase::query($myQuery)) {
					return false;
				}
				
				//...e sposto nel lotto di uscita
				if (!self::addArticle($batchOutId, $waitingItem->batch_in_id, $articleId, $quantityMove)) {
					return false;
				}
				
				$quantityMoved += $quantityMove;
				
			}
			
			return true;
		
		}
		
	}
	
?>