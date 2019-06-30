<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHArticleHelper {
		
		/**
			Importa gli articoli da un file con campi di larghezza fissa
			
			@param string il path del file da importare
			@param boolean se verificare il permesso FH_IMPORT_ARTICLES
			@return array contenente inserts, success e fail
		**/
		static function importArticlesFromFile($filePath, $checkPermissions = true) {
			
			if ($checkPermissions) {
				if (!DMAcl::checkPrivilege("FH_ARTICLES_IMPORT")) {
					return false;
				}
			}
			
			$fp = fopen($filePath,'r');
			
			if (!$fp) {
				return false;
			}
			
			$result = array();
			
			
			while ($s = fgets($fp, 1677)) {
    			$articleCode = trim(substr($s, 1, 30));
    			
    			if (strlen($articleCode) < 2) {
    				continue;
    			}
    
    			$myArticle = DMTable::getInstance('Article');
    			
    			//Provo a caricare l'articolo, così evitiamo doppioni
    			if (!$myArticle->loadFromArticleCode($articleCode)) {
					$result['inserts']++;
				}    			
    			
    			//Carico i dati
    			$myArticle->article_code = $articleCode;
    			$myArticle->name = trim(substr($s, 31, 70));
    			$myArticle->ean_code = trim(substr($s, 101, 30));
    			$myArticle->description_lang_1 = trim(substr($s, 140, 250));
    			$myArticle->description_lang_2 = trim(substr($s, 390, 250));
    			$myArticle->description_lang_3 = trim(substr($s, 640, 250));
    			$myArticle->description_lang_4 = trim(substr($s, 890, 250));
    			$myArticle->package_code = trim(substr($s, 1140, 250));
    			$myArticle->package_description = trim(substr($s, 1390, 250));
    			$myArticle->package_units = ((int) substr($s, 131, 9)) / 10000;
    			$myArticle->width = (float) substr($s, 1640, 9);
    			$myArticle->height = (float) substr($s, 1649, 9);
    			$myArticle->depth = (float) substr($s, 1658, 9);
    			$myArticle->pallet_packages = round(((int) substr($s, 1667, 9)) / 10000);
    			
    			if ($myArticle->store()) {
    				$result['success']++;
					
    			} else {
    				$result['fail']++;
    			}
			}
			
			fclose($fp);
			
			return $result;
			
		}
		
		/**
			Ottiene la lista degli articoli correntemente su DB
			
			@param array i parametri di ricerca
			@return la lista degli articoli
		**/
		static function getArticles($searchParams, &$totalResults) {
		
			if (!isset($searchParams['limit'])) {
				$searchParams['limit'] = 0;
			}
			if (!isset($searchParams['offset'])) {
				$searchParams['offset'] = 0;
			}
			
			$whereConditions = "";
			$joins = "";
			
			if ((isset($searchParams['name'])) && ($searchParams['name'] != '')) {
				$whereConditions .= " AND name LIKE '%" . DMDatabase::escape($searchParams['name']) . "%'";
			}
			if ((isset($searchParams['eanCode'])) && ($searchParams['eanCode'] != '')) {
				$whereConditions .= " AND ean_code = '" . DMDatabase::escape($searchParams['eanCode']) . "'";
			}
			if ((isset($searchParams['articleCode'])) && ($searchParams['articleCode'] != '')) {
				$whereConditions .= " AND article_code = '" . DMDatabase::escape($searchParams['articleCode']) . "'";
			}			
			if ((isset($searchParams['inStockOnly'])) && ($searchParams['inStockOnly'] != 0)) {
				$joins .= " LEFT JOIN fh_stock_cache AS sc ON (a.article_id = sc.article_id)";
				$whereConditions .= " AND (sc.quantity_packages + sc.quantity_units) > 0";
			}
			
			$myQuery = "
				SELECT DISTINCT SQL_CALC_FOUND_ROWS a.*
				FROM fh_article AS a
				$joins
				WHERE 1 = 1
				$whereConditions
				ORDER BY a.article_code ASC
			"; 
			
			$results = DMDatabase::loadObjectList($myQuery, $searchParams['offset'], $searchParams['limit']);
			
			$totalResults = DMDatabase::loadResult("SELECT FOUND_ROWS();");
			
			return $results;
			
		}
		
		/**
			Carica da DB l'articolo richiesto
			
			@param int l'id dell'articolo richiesto
		**/
		function loadArticle($articleId) {
		
			$myQuery = "
				SELECT *
				FROM fh_article
				WHERE article_id = " . (int) $articleId . "
			";
			
			return DMDatabase::loadObject($myQuery);
			
		}

		function loadArticleFromCode($articleCode) {

			$myQuery = "
				SELECT *
				FROM fh_article
				WHERE article_code = '" . DMDatabase::escape($articleCode) . "'
			";

			return DMDatabase::loadObject($myQuery);

		}
		function loadArticleFromEanCode($barcode) {

			$myQuery = "
				SELECT *
				FROM fh_article
				WHERE ean_code = '" . DMDatabase::escape($barcode) . "'
			";

			return DMDatabase::loadObject($myQuery);

		}
		
		/**
			Cancella dal file system l'immagine di un articolo
			Non controlla i permessi
		**/
		function deleteArticleImage($articleId) {
		
			$myArticle = self::loadArticle($articleId);
			
			if (!$myArticle) {
				return false;
			}
			
			$articleImagePath = DM_APP_PATH . DS . 'media' . DS . 'articles' . DS . $myArticle->article_code . '.jpg'; 
			
			if (file_exists($articleImagePath)) {
				@unlink($articleImagePath);
			}
			
			return true;			
		
		}
		
		/**
			Ottiene la history dei movimenti dell'articolo
			
			@param int l'id dell'articolo
			@param int offset
			@param int limit
			@param &int il numero di risultati totali
			@return la lista dei movimenti
		**/
		static function getArticleMovements($articleId, $offset = 0, $limit = 0, &$totalResults) {
		
			$articleId = (int) $articleId;
			
			$myQuery = "
				SELECT SQL_CALC_FOUND_ROWS m.*, md.*
				FROM fh_movement_detail AS md
				LEFT JOIN fh_movement AS m ON (md.movement_id = m.movement_id)
				WHERE m.article_id = $articleId
				ORDER BY m.created_date DESC
			"; 
			
			$results = DMDatabase::loadObjectList($myQuery, $offset, $limit);
			
			$totalResults = DMDatabase::loadResult("SELECT FOUND_ROWS();");
			
			return $results;
			
		}
		
		/**
			Ottiene la history dei lotti di ingresso dell'articolo
			
			@param int l'id dell'articolo
			@param int offset
			@param int limit
			@param &int il numero di risultati totali
			@return la lista dei lotti di ingresso
		**/
		static function getArticleBatchIns($articleId, $offset = 0, $limit = 0, &$totalResults) {
		
			$articleId = (int) $articleId;
			
			$myQuery = "
				SELECT SQL_CALC_FOUND_ROWS bi.*, bia.*
				FROM fh_batch_in_article AS bia
				LEFT JOIN fh_batch_in AS bi ON (bi.batch_in_id = bia.batch_in_id)
				WHERE bia.article_id = $articleId
				ORDER BY bi.batch_in_id ASC
			"; 
			
			$results = DMDatabase::loadObjectList($myQuery, $offset, $limit);
			
			$totalResults = DMDatabase::loadResult("SELECT FOUND_ROWS();");
			
			return $results;
			
		}
		
		/**
			Ottiene la history dei lotti di uscita dell'articolo
			
			@param int l'id dell'articolo
			@param int offset
			@param int limit
			@param &int il numero di risultati totali
			@return la lista dei lotti di uscita
		**/
		static function getArticleBatchOuts($articleId, $offset = 0, $limit = 0, &$totalResults) {
		
			$articleId = (int) $articleId;
			
			$myQuery = "
				SELECT SQL_CALC_FOUND_ROWS bo.*, boa.*
				FROM fh_batch_out_article AS boa
				LEFT JOIN fh_batch_out AS bo ON (bo.batch_out_id = boa.batch_out_id)
				WHERE boa.article_id = $articleId
				ORDER BY bo.batch_out_code DESC
			"; 
			
			$results = DMDatabase::loadObjectList($myQuery, $offset, $limit);
			
			$totalResults = DMDatabase::loadResult("SELECT FOUND_ROWS();");
			
			return $results;
			
		}
		
		/**
			Ritorna la lista degli articoli aggregabili
		**/
		static function getAggregableArticles() {
		
			$myQuery = "
				SELECT a.*, sc.quantity_units
				FROM fh_stock_cache AS sc
				LEFT JOIN fh_article AS a ON (sc.article_id = a.article_id)
				WHERE sc.stock_id = 1
				AND sc.quantity_units >= a.package_units
				AND sc.quantity_units > 0
			";
			
			return DMDatabase::loadObjectList($myQuery);
				
		}
		
		/**
			Ritorna il totale dei cartoni dispatched per l'articolo
		**/
		static function getDispatchedPackages($articleId) {
		
			$articleId = (int) $articleId;
			
			$myQuery = "
				SELECT SUM(quantity_packages)
				FROM fh_r_udm_article
				WHERE article_id = $articleId				
			";
			
			return DMDatabase::loadResult($myQuery);
			
		}
		/**
			Ritorna il totale dei cartoni dispatched per l'articolo
		**/
		static function getDispatchedUnits($articleId) {
		
			$articleId = (int) $articleId;
			
			$myQuery = "
				SELECT SUM(quantity_units)
				FROM fh_r_udm_article
				WHERE article_id = $articleId				
			";
			
			return DMDatabase::loadResult($myQuery);
			
		}
	}
	
?>