<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHBatchInHelper {
		
		/**
			Aggiunge un articolo ad un lotto di ingresso aggiornando l'eventuale quantità
		**/
		static function addArticle($batchInId, $articleId, $quantity) {
		
			$batchInId = (int) $batchInId;
			$articleId = (int) $articleId;
			$quantity = (int) $quantity;
			
			$myQuery = "
				SELECT COUNT(*)
				FROM fh_batch_in_article
				WHERE batch_in_id = " . (int) $batchInId . "
				AND article_id = " . (int) $articleId . "
			";
			
			if (DMDatabase::loadResult($myQuery) < 1) {
				$myQuery = "
					INSERT INTO fh_batch_in_article (
						batch_in_id,
						article_id,
						quantity
					) VALUES (
						$batchInId,
						$articleId,
						$quantity
					)
				";
			} else {
				$myQuery = "
					UPDATE fh_batch_in_article
					SET quantity = quantity + $quantity
					WHERE batch_in_id = $batchInId
					AND article_id = $articleId
				";
			}
			
			return DMDatabase::query($myQuery);
			
		}
		
		/**
			Setta la quantità di un articolo rispetto al batch in
		**/
		static function setArticle($batchInId, $articleId, $quantity) {
		
			$batchInId = (int) $batchInId;
			$articleId = (int) $articleId;
			$quantity = (int) $quantity;
			
			$myQuery = "
				DELETE
				FROM fh_batch_in_article
				WHERE batch_in_id = " . (int) $batchInId . "
				AND article_id = " . (int) $articleId . "
			";
			
			if (!DMDatabase::query($myQuery)) {
				return false;
			}
			
			$myQuery = "
				INSERT INTO fh_batch_in_article (
					batch_in_id,
					article_id,
					quantity
				) VALUES (
					$batchInId,
					$articleId,
					$quantity
				)
			";
			
			return DMDatabase::query($myQuery);
			
		}
		
		/**
			Ottiene il numero di unità di un articolo nella lista di attesa
		**/
		static function getArticleUnitsInWaitingList($articleId) {
			
			$myQuery = "
				SELECT SUM(quantity)
				FROM fh_batch_in_article
				WHERE article_id = " . (int) $articleId . "
			";
			
			return DMDatabase::loadResult($myQuery);
			
		}
		
		/**
			Ottiene il codice di lotto di ingresso
		**/
		static function getBatchInCode($batchInId) {
			
			$myQuery = "
				SELECT batch_in_code
				FROM fh_batch_in
				WHERE batch_in_id = " . (int) $batchInId . "
			";
			
			return DMDatabase::loadResult($myQuery, '');
			
		}
		
	}
	
?>