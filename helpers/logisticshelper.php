<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHLogisticsHelper {
		
		/**
			Ottiene gli UDM dove è presente l'articolo
			
			@param int l'articolo da verificare
			@return array di object
		**/
		function getArticleUdms($articleId) {
			
			$articleId = (int) $articleId;
			
			$myQuery = "
				SELECT u.udm_code, rua.*
				FROM fh_udm AS u
				LEFT JOIN fh_r_udm_article AS rua ON (u.udm_id = rua.udm_id)
				WHERE rua.article_id = $articleId
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		function getItemUdms($articleId) {
			
			$articleId = (int) $articleId;
			
			$myQuery = "
				SELECT u.udm_id, u.udm_code, u.ubicazione, a.article_code, a.name AS article_name, rua.quantity_units
				FROM fh_udm AS u
				LEFT JOIN fh_r_udm_article AS rua ON (u.udm_id = rua.udm_id)
				LEFT JOIN fh_article AS a ON (rua.article_id = a.article_id)
				WHERE rua.article_id = $articleId
				AND rua.quantity_units > 0
				ORDER BY u.udm_code ASC
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		/**
			Ottiene gli UDM di un magazzino dove è presente l'articolo
			@param int del magazzino da verificare
			@param int l'articolo da verificare
			@return array di object
		**/
		function getArticleStockUdms($stockId,$articleId) {
			
			$articleId = (int) $articleId;
			$stockId = (int) $stockId;
			
			$myQuery = "
				SELECT u.udm_code, u.ubicazione, rua.*,  a.article_code, a.name AS article_name
				FROM fh_udm AS u
				LEFT JOIN fh_r_udm_article AS rua ON (u.udm_id = rua.udm_id)
				LEFT JOIN fh_article AS a ON (rua.article_id = a.article_id)
				WHERE rua.article_id = $articleId
				AND rua.stock_id = $stockId
				AND rua.quantity_units > 0
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		
		
		
		/**
			Ottiene lo stato di tutte le udm con quantità diversa da 0
		**/
		function getUdms() {
			
			$myQuery = "
				SELECT u.udm_id, u.udm_code, u.ubicazione, a.article_code, a.name AS article_name, rua.quantity_units
				FROM fh_udm AS u
				LEFT JOIN fh_r_udm_article AS rua ON (u.udm_id = rua.udm_id)
				LEFT JOIN fh_article AS a ON (rua.article_id = a.article_id)
				WHERE rua.quantity_units > 0
				ORDER BY u.udm_code ASC
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		/**
			Ottiene lo stato di tutte le udm con quantità diversa da 0 in un determinato magazzino
		**/
		function getUdmsStock($stockId) {
			
			$myQuery = "
				SELECT u.udm_id, u.udm_code, u.ubicazione, u.type, a.article_code, a.name AS article_name, rua.committed_units, rua.quantity_units
				FROM fh_udm AS u
				LEFT JOIN fh_r_udm_article AS rua ON (u.udm_id = rua.udm_id)
				LEFT JOIN fh_article AS a ON (rua.article_id = a.article_id)
				WHERE rua.quantity_units > 0
				AND u.type = $stockId
				ORDER BY u.udm_code ASC
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		
		
		/**
			Ottiene lo stato di tutte le udm con quantità diversa da 0
		**/
		function getudms1($searchParams, &$totalResults){
			
			if (!isset($searchParams['limit'])) {
				$searchParams['limit'] = 0;
			}
			if (!isset($searchParams['offset'])) {
				$searchParams['offset'] = 0;
			}
			
			$whereConditions = "";
			$joins = "";
			
			if ((isset($searchParams['articleCode'])) && ($searchParams['articleCode'] != '')) {
				$myArticle = DMTable::getInstance('Article');
				if ($myArticle->loadFromArticleCode($searchParams['articleCode'])) {
					$whereConditions .= " AND a.article_id =  " . (int) $myArticle->article_id ;
				}
			}
			if ((isset($searchParams['ubicazione'])) && ($searchParams['ubicazione'] != '')) {
				$whereConditions .= " AND u.ubicazione = '" . DMDatabase::escape($searchParams['ubicazione']) . "'";
			}
			
			$myQuery = "
				SELECT SQL_CALC_FOUND_ROWS u.*, rua.quantity_units, COALESCE(a.article_code, '') AS article_code, COALESCE(a.name, '') AS article_name
				FROM fh_udm AS u
				LEFT JOIN fh_r_udm_article AS rua ON (u.udm_id = rua.udm_id)
				LEFT JOIN fh_article AS a ON (rua.article_id = a.article_id)
				$joins
				WHERE 1=1
				$whereConditions
				ORDER BY u.udm_code ASC
			";
			
			$results = DMDatabase::loadObjectList($myQuery, $searchParams['offset'], $searchParams['limit']);
			
			$totalResults = DMDatabase::loadResult("SELECT FOUND_ROWS();");
			
			return $results;
			
		}

		/**
		 * Ottiene la lista degli articoli presenti su una UDM
		 *
		 * @param int $udmId
		 * @return list of objects
		 */
		public static function getUdmArticles($udmId) {
			$udmId = (int) $udmId;
			
			$myQuery = "
				SELECT rua.quantity_units, a.*
				FROM fh_r_udm_article AS rua
				LEFT JOIN fh_article AS a ON (rua.article_id = a.article_id)
				WHERE rua.udm_id = $udmId
				AND rua.quantity_units != 0
			";
			return DMDatabase::loadObjectList($myQuery); 
		}
		
		/**
		 * Ottiene l'articolo presente su una UDM
		 *
		 * @param int $udmId
		 * @return list of objects
		 */
		public static function getUdmItem($udmId) {
			$udmId = (int) $udmId;
			
			$myQuery = "
				SELECT rua.quantity_units, a.*
				FROM fh_r_udm_article AS rua
				LEFT JOIN fh_article AS a ON (rua.article_id = a.article_id)
				WHERE rua.udm_id = $udmId
				AND rua.quantity_units != 0
			";
			return DMDatabase::loadObjectList($myQuery); 
		}

		public static function createUdm() {

			$lastCodeInt = DMDatabase::loadResult("SELECT MAX(udm_code_int) FROM fh_udm WHERE udm_code_year = " . (int) date('Y') . " and type = '1' ");
			$lastCodeInt++;

			$myUdm = DMTable::getInstance('Udm');
			$myUdm->udm_code_year = date('Y');
			$myUdm->udm_code_int = $lastCodeInt;
			$myUdm->type = '1';
			$myUdm->udm_code = $myUdm->type . $myUdm->udm_code_year . str_pad($myUdm->udm_code_int, 5, '0', STR_PAD_LEFT);
			$myUdm->store();

			$myQuery = "
			SELECT * 
			FROM fh_udm 
			WHERE udm_id = " . (int) $myUdm->udm_id . " 			
			";

			return DMDatabase::loadObject($myQuery);

		}
		
		public static function createIbd() {

			$lastCodeInt = DMDatabase::loadResult("SELECT MAX(udm_code_int) FROM fh_udm WHERE udm_code_year = " . (int) date('Y') . " and type = '3' ");
			$lastCodeInt++;

			$myUdm = DMTable::getInstance('Udm');
			$myUdm->udm_code_year = date('Y');
			$myUdm->udm_code_int = $lastCodeInt;
			$myUdm->type = '3';
			$myUdm->udm_code =  $myUdm->type . $myUdm->udm_code_year . str_pad($myUdm->udm_code_int, 5, '0', STR_PAD_LEFT);
			$myUdm->store();

			$myQuery = "
			SELECT * 
			FROM fh_udm 
			WHERE udm_id = " . (int) $myUdm->udm_id . " 			
			";

			return DMDatabase::loadObject($myQuery);

		}


		public static function addToUdm($udmId, $articleId, $stockId, $quantity) {
			
			DMDatabase::query("
				INSERT INTO fh_r_udm_article (udm_id, article_id, stock_id, quantity_units) VALUES (
					" . (int) $udmId . ",
					" . (int) $articleId . ",
					" . (int) $stockId . ",
					" . (int) $quantity . "					
				) ON DUPLICATE KEY UPDATE
					quantity_units = quantity_units + " . (int) $quantity . "
			");

		}
		
		public static function addToUdmCommitted($udmId, $articleId, $stockId, $committed) {
			
			DMDatabase::query("
				INSERT INTO fh_r_udm_article (udm_id, article_id, stock_id, committed_units) VALUES (
					" . (int) $udmId . ",
					" . (int) $articleId . ",
					" . (int) $stockId . ",
					" . (int) $committed . "					
				) ON DUPLICATE KEY UPDATE
					committed_units = committed_units + " . (int) $committed . "
			");

		}

		public static function setToUdm($udmId, $articleId, $stockId, $quantity) {

			DMDatabase::query("
				INSERT INTO fh_r_udm_article (udm_id, article_id, stock_id, quantity_units) VALUES (
					" . (int) $udmId . ",
					" . (int) $articleId . ",
					" . (int) $stockId . ",
					" . (int) $quantity . "					
				) ON DUPLICATE KEY UPDATE
					quantity_units = " . (int) $quantity . "
			");

		}

		public static function getUdmArticle($udmId, $articleId) {

			$articleId = (int) $articleId;
			$udmId = (int) $udmId;
			
			$myQuery = "
			SELECT quantity_units
			FROM fh_r_udm_article 
			WHERE udm_id = $udmId 
			AND article_id = $articleId  			
			";

			return DMDatabase::loadResult($myQuery);

		}
		
		public static function getUdmArticleInfo($udmId, $articleId) {

			$articleId = (int) $articleId;
			$udmId = (int) $udmId;
			
			$myQuery = "
			SELECT committed_units
			FROM fh_r_udm_article 
			WHERE udm_id = $udmId 
			AND article_id = $articleId  			
			";

			return DMDatabase::loadResult($myQuery);

		}
		
		public static function getUdmArticleInvoice($udmId, $articleId, $invoiceId) {

			$articleId = (int) $articleId;
			$udmId = (int) $udmId;
			$invoiceId = (int) $invoiceId;
			
			$myQuery = "
			SELECT quantity_units
			FROM fh_invoice_row 
			WHERE udm_id = $udmId 
			AND article_id = $articleId 
			AND invoice_id = $invoiceId
			";

			return DMDatabase::loadResult($myQuery);

		}

			/**
			Ritorna i dati di magazzino per un articolo
			
			@param int l'id dell'articolo
			@param int l'id del magazzino
			@return object [total_units, total_packages]
		**/
		static function getArticleStockDispatchedData($articleId, $stockId) {
		
			// self::updateStockCache($articleId, $stockId);
			
			$articleId = (int) $articleId;
			$stockId = (int) $stockId;
			
			$myQuery = "
				SELECT 
					SUM(COALESCE(quantity_units, 0)) AS total_units,
					SUM(COALESCE(quantity_packages, 0)) AS total_packages
				FROM fh_r_udm_article
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
		static function getArticleStocksDispatchedData($articleId) {
		
			$stocks = self::getStocks();
			
			$result = array();
			
			foreach ($stocks as $stock) {
				$myStock = self::getArticleStockDispatchedData($articleId, $stock->stock_id);
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
			Salva l'ubicazione della UDM
			
			
		**/
		static function saveUdm($udmCode, $ubicazione) {
		
			$myQuery = '
				UPDATE fh_udm 
				SET ubicazione =
				"' . substr($ubicazione, 0, 7) . '"
				WHERE udm_code = ' . $udmCode 
			;
			
			return DMDatabase::query($myQuery);
		
		}
		
		/**
			Ottiene la lista dei dettagli legati ad un udm
			
			@param int id del udm
			@return array di object
		**/
		static function getUdmDetails($udmId) {
			
			$udmId = (int) $udmId;
			
			$myQuery = "
				SELECT *
				FROM fh_r_udm_article 
				WHERE udm_id = $udmId
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		
		static function exportUdmsCSV($params) {
		
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
			$udms = self::getudms1($params, $totalResults);
			
			$myContent = array();
			
			//Headers
			$myArray = array();	
			$myArray[] = "Udm";
			$myArray[] = "Articolo";
			$myArray[] = "Descrizione";
			$myArray[] = "Conf.";
			$myArray[] = "Ubicazione";
			
			$myContent[] = $myArray;
			
			foreach ($udms as $udm) {					
				$myArray = array();
				$myArray[] = $udm->udm_code;
				$myArray[] = $udm->article_code;
				$myArray[] = $udm->article_name;
				$myArray[] = $udm->quantity_units;
				$myArray[] = $udm->ubicazione;	
					
				$myContent[] = $myArray;
			}
			
			$fileName = 'udms_export_' . uniqid() . '.csv';
			$filePath = DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . $fileName;
			$fileUrl = DMUrl::getCurrentBaseUrl() . 'temp/export/' . $fileName;
			
			$fp = fopen($filePath, 'w');
			foreach ($myContent as $row) {
				dumbcsv($fp, $row, '"', ';', "\n");
			}
			fclose($fp);
			
			return $fileUrl;
			
		}
	}
	
?>