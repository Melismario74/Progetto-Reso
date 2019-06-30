<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHStockHelper {
		
		/**
			Ritorna i dati di magazzino per un articolo
			
			@param int l'id dell'articolo
			@param int l'id del magazzino
			@return object [total_units, total_packages]
		**/
		static function getArticleStockData($articleId, $stockId) {
		
			self::updateStockCache($articleId, $stockId);
			
			$articleId = (int) $articleId;
			$stockId = (int) $stockId;
			
			$myQuery = "
				SELECT 
					COALESCE(quantity_units, 0) AS total_units,
					COALESCE(quantity_packages, 0) AS total_packages
				FROM fh_stock_cache
				WHERE article_id = $articleId
				AND stock_id = $stockId
			";
			
			$result = DMDatabase::loadObject($myQuery);
			
			if (!$result) {
				$result = new StdClass();
				$result->total_units = 0;
				$result->total_packages = 0;
			}
			
			return $result;
		
		}
		
		/**
			Ritorna i dati di magazzino completi di un articolo
			
			@param int l'id dell'articolo
			@return array[stockId] di object [total_units, total_packages]
		**/
		static function getArticleStocksData($articleId) {
		
			$stocks = self::getStocks();
			
			$result = array();
			
			foreach ($stocks as $stock) {
				$myStock = self::getArticleStockData($articleId, $stock->stock_id);
				$myStock->stock_id = $stock->stock_id;
				$myStock->name = $stock->name;
				
				$result[] = $myStock;
			}
			
			return $result;
			
		}
		
		/**
			Ritorna tutti gli stock
			
			@return array di object [stock_id, name]
		**/
		static function getStocks() {
		
			$myQuery = "
				SELECT *
				FROM fh_stock
			";
			
			return DMDatabase::loadObjectList($myQuery);
		
		}
		
		/**
			Ritorna uno stock
			
			@param int l'id dello stock
			@return object lo stock
		**/
		static function loadStock($stockId) {
		
			$stockId = (int) $stockId;
		
			$myQuery = "
				SELECT *
				FROM fh_stock
				WHERE stock_id = $stockId
			";
			
			return DMDatabase::loadObject($myQuery);
		
		}
		
		/**
			Aggiorna la stock cache per l'articolo e lo stock selezionato
			
			@param int l'id dell'articolo
			@param int l'id dello stock
		**/
		static function updateStockCache($articleId, $stockId) {
		
			FHHelper::log("stockhelper", "Updating stock cache for article $articleId and stock $stockId");
			
			$articleId = (int) $articleId;
			$stockId = (int) $stockId;
			
			$myQuery = "
				SELECT 
					COALESCE(SUM(md.quantity_units), 0) AS total_units,
					COALESCE(SUM(md.quantity_packages), 0) AS total_packages
				FROM fh_movement_detail AS md
				LEFT JOIN fh_movement AS m ON (md.movement_id = m.movement_id)
				WHERE m.article_id = $articleId
				AND md.stock_id = $stockId
			";
			$stockData = DMDatabase::loadObject($myQuery);
			
			$myQuery = "
				DELETE FROM fh_stock_cache
				WHERE article_id = " . $articleId . "
				AND stock_id = " . $stockId . "
			";
			DMDatabase::query($myQuery);
			
			$myQuery = "
				INSERT INTO fh_stock_cache (
					stock_id,
					article_id,
					quantity_units,
					quantity_packages
				) VALUES (
					" . $stockId . ",
					" . $articleId . ",
					" . $stockData->total_units . ",
					" . $stockData->total_packages . "
				)
			";
			
			DMDatabase::query($myQuery);
		}
		
		/**
			Ottiene la lista degli articoli con quantità nello stock selezionato
			
			@param int l'id dello stock
			@return array la lista risultante (article_id, quantity_units, quantity_packages
		**/
		function getStockArticles($stockId) {
			
			$stockId = (int) $stockId;
			
			$myQuery = "
				SELECT *
				FROM fh_stock_cache
				WHERE stock_id = $stockId
				AND (
					quantity_units > 0
					OR quantity_packages > 0
				)
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		static function exportStockCSV($params) {
		
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
		
			$totalResults = 0;
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			$articles = FHArticleHelper::getArticles($params, $totalResults);
			
			$myContent = array();
			
			//Headers
			$myArray = array();	
			$myArray[] = "ID";
			$myArray[] = "Articolo";
			$myArray[] = "Descrizione";
			$myArray[] = "Conf. OK";
			$myArray[] = "Cart. OK";
			$myArray[] = "Stock";
			$myArray[] = "Scarto";
			
			$myContent[] = $myArray;
			
			foreach ($articles as $article) {
				
				$article->stock = array();
					$article->stock= self::getArticleStocksData($article->article_id);
					
				
					$myArray = array();
					$myArray[] = $article->article_id;
					$myArray[] = $article->article_code;
					$myArray[] = $article->name;
					isset($article->stock[0]->total_units)?	$myArray[] = $article->stock[0]->total_units : 0 ;
					isset($article->stock[0]->total_packages)?	$myArray[] = $article->stock[0]->total_packages : 0 ;
					isset($article->stock[2]->total_units)?	$myArray[] = $article->stock[2]->total_units : 0 ;
					isset($article->stock[1]->total_units)?	$myArray[] = $article->stock[1]->total_units : 0 ;
											
					$myContent[] = $myArray;		
			
		
			}
			
			
			$fileName = 'stock_export_' . uniqid() . '.csv';
			$filePath = DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . $fileName;
			$fileUrl = DMUrl::getCurrentBaseUrl() . 'temp/export/' . $fileName;
			
			$fp = fopen($filePath, 'w');
			foreach ($myContent as $row) {
				dumbcsv($fp, $row, '"', ';', "\n");
			}
			fclose($fp);
			
			return $fileUrl;
			
			
		}
		
		static function getItemStocks($searchParams, &$totalResults) {
		
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
				  $whereConditions .= " AND sc.stock_id = 1";
			}	
			
			$myQuery = "
				SELECT SQL_CALC_FOUND_ROWS a.*
				FROM fh_article AS a
				$joins
				WHERE 1 = 1
				$whereConditions
			"; 
			
			$results = DMDatabase::loadObjectList($myQuery, $searchParams['offset'], $searchParams['limit']);
			
			$totalResults = DMDatabase::loadResult("SELECT FOUND_ROWS();");
			
			return $results;
			
		}
		
		
	}
	
?>