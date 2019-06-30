<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHMovementHelper {
		
		/**
			Ottiene la lista dei movimenti correntemente su DB
			
			@param array i parametri di ricerca
			@return la lista degli articoli
		**/
		static function getMovements($searchParams, &$totalResults) {
		
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
					$whereConditions .= " AND m.article_id =  " . (int) $myArticle->article_id ;
				}
			}
			
			if ((isset($searchParams['eanCode'])) && ($searchParams['eanCode'] != '')) {
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
				$articleSearchParams = array();
				$articleSearchParams['eanCode'] = $searchParams['eanCode'];
				$myArticles = FHArticleHelper::getArticles($articleSearchParams);
				if (count($myArticles) > 0) {
					$whereConditions .= ' AND (';
					$articleConditions = array();
					foreach ($myArticles as $myArticle) {
						$articleConditions[] = " m.article_id = " . (int) $myArticle->article_id . " ";
					}
					$whereConditions .= implode(' OR ', $articleConditions) . ') ';
				}
			}
			
			if ((isset($searchParams['movementDateFrom'])) && ($searchParams['movementDateFrom'] != '')) {
				$whereConditions .= " AND m.created_date >= '" . DMDatabase::escape($searchParams['movementDateFrom']) . "'";
			}
			if ((isset($searchParams['movementDateTo'])) && ($searchParams['movementDateTo'] != '')) {
				$whereConditions .= " AND m.created_date <= '" . DMDatabase::escape($searchParams['movementDateTo']) . "'";
			}
			if ((isset($searchParams['batchInCode'])) && ($searchParams['batchInCode'] != '')) {
				$whereConditions .= " AND bi.batch_in_code = '" . DMDatabase::escape($searchParams['batchInCode']) . "'";
			}
			if ((isset($searchParams['batchOutCode'])) && ($searchParams['batchOutCode'] != '')) {
				$whereConditions .= " AND bo.batch_out_code = '" . DMDatabase::escape($searchParams['batchOutCode']) . "'";
			}
			if ((isset($searchParams['movementType'])) && ($searchParams['movementType'] != '')) {
				$whereConditions .= " AND m.movement_type = '" . DMDatabase::escape($searchParams['movementType']) . "'";
			}
			if ((isset($searchParams['userId'])) && ($searchParams['userId'] > 0)) {
				$whereConditions .= " AND m.created_by = " . (int) $searchParams['userId'];
			}
			if ((isset($searchParams['stockId'])) && ($searchParams['stockId'] > 0)) {
				$whereConditions .= " AND m.stock_id = " . (int) $searchParams['stockId'];
			}
			if ((isset($searchParams['movementIdFrom'])) && ($searchParams['movementIdFrom'] > 0)) {
				$whereConditions .= " AND m.movement_id >= " . (int) $searchParams['movementIdFrom'];
			}
			
			$myQuery = "
				SELECT SQL_CALC_FOUND_ROWS m.*, COALESCE(bi.batch_in_code, '') AS batch_in_code, COALESCE(bo.batch_out_code, '') AS batch_out_code
				FROM fh_movement AS m
				LEFT JOIN fh_batch_in AS bi ON (bi.batch_in_id = m.batch_in_id)
				LEFT JOIN fh_batch_out AS bo ON (bo.batch_out_id = m.batch_out_id)
				$joins
				WHERE 1 = 1
				$whereConditions
				ORDER BY m.created_date DESC
			"; 
			
			$results = DMDatabase::loadObjectList($myQuery, $searchParams['offset'], $searchParams['limit']);
			
			$totalResults = DMDatabase::loadResult("SELECT FOUND_ROWS();");
			
			return $results;
			
		}
		
		/**
			Ottiene la lista dei dettagli legati ad un movimento
			
			@param int id del movimento
			@return array di object
		**/
		static function getMovementDetails($movementId) {
			
			$movementId = (int) $movementId;
			
			$myQuery = "
				SELECT md.*, s.name AS stock_name
				FROM fh_movement_detail AS md
				LEFT JOIN fh_stock AS s ON (md.stock_id = s.stock_id)
				WHERE movement_id = $movementId
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		

		static function exportMovements($params, &$movementIdMax) {
		
			$totalResults = 0;
			$movements = FHMovementHelper::getMovements($params, $totalResults);
			
			$txtContent = '';
			
			$movementIdMax = 0;
			
			foreach ($movements as $movement) {
			
				if ($movement->movement_id > $movementIdMax) {
					$movementIdMax = $movement->movement_id;
				}
				$movement->details = FHMovementHelper::getMovementDetails($movement->movement_id);
				
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
				$movement->article = FHArticleHelper::loadArticle($movement->article_id);
				
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'userhelper.php');
				$movement->user = new StdClass();
				$movement->user->name = FHUserHelper::getUserName($movement->created_by);
				
				foreach ($movement->details as $movementDetail) {
					if ($movementDetail->chargelist_id > 0) {
						$myChargelist = DMTable::getInstance('Chargelist');
						$myChargelist->load($movementDetail->chargelist_id);
						$movementDetail->chargelist_code = $myChargelist->chargelist_code;
					} else {
						$movementDetail->chargelist_code = "";
					}
					
					$stock = DMTable::getInstance('Stock');
					$stock->load($movementDetail->stock_id);
					
					$txtContent .= DMFormat::formatDate($movement->created_date, 'dmYHis', 'Y-m-d H:i:s');
					$txtContent .= '00000';
					$txtContent .= str_pad($stock->stock_code_short, 50, ' ', STR_PAD_RIGHT);
					$txtContent .= ' ';
					$txtContent .= str_pad($movement->article->article_code, 30, ' ', STR_PAD_RIGHT);
					$txtContent .= str_pad(($movementDetail->quantity_packages * $movement->article->package_units) + $movementDetail->quantity_units, 13, '0', STR_PAD_LEFT);
					$txtContent .= substr(str_pad($movement->user->name, 10, ' ', STR_PAD_RIGHT), 0, 10);
					$txtContent .= str_pad(' ', 50, ' ', STR_PAD_LEFT);
					$txtContent .= str_pad($movementDetail->chargelist_code, 50, ' ', STR_PAD_LEFT); //lista di carico
					$txtContent .= str_pad(' ', 10, ' ', STR_PAD_LEFT);
					
					$txtContent .= "\r\n";
				}
		
			}
			
			return $txtContent;
			
		}
		
		static function exportMovementsCSV($params) {
		
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
			$movements = self::getMovements($params, $totalResults);
			
			$myContent = array();
			
			//Headers
			$myArray = array();	
			$myArray[] = "ID";
			$myArray[] = "Data";
			$myArray[] = "Tipo";
			$myArray[] = "Articolo";
			$myArray[] = "Descrizione";
			$myArray[] = "Lotto ing.";
			$myArray[] = "Lotto usc.";
			$myArray[] = "Utente";
			$myArray[] = "Magaz.";
			$myArray[] = "Conf.";
			$myArray[] = "Cart";
			
			$myContent[] = $myArray;
			
		
			
			foreach ($movements as $movement) {
				
						
				$movement->details = self::getMovementDetails($movement->movement_id);
				
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
				$movement->article = FHArticleHelper::loadArticle($movement->article_id);
				
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'userhelper.php');
				$movement->user = new StdClass();
				$movement->user->name = FHUserHelper::getUserName($movement->created_by);
				
				foreach ($movement->details as $movementDetail) {
					if ($movementDetail->chargelist_id > 0) {
						$myChargelist = DMTable::getInstance('Chargelist');
						$myChargelist->load($movementDetail->chargelist_id);
						$movementDetail->chargelist_code = $myChargelist->chargelist_code;
					} else {
						$movementDetail->chargelist_code = "";
					}
										
					$stock = DMTable::getInstance('Stock');
					$stock->load($movementDetail->stock_id);
					
					$myArray = array();
					$myArray[] = $movement->movement_id;
					$myArray[] = DMFormat::formatDate($movement->created_date, 'd/m/Y H:i:s', 'Y-m-d H:i:s');
					$myArray[] = $movement->movement_type;
					$myArray[] = $movement->article->article_code;
					$myArray[] = $movement->article->name;
					$myArray[] = $movement->batch_in_code;
					$myArray[] = $movement->batch_out_code;
					$myArray[] = $movement->user->name;
					$myArray[] = $movementDetail->stock_name;
					$myArray[] = $movementDetail->quantity_units;
					$myArray[] = $movementDetail->quantity_packages;	
					
					$myContent[] = $myArray;				
				}
		
			}
			
			$fileName = 'movements_export_' . uniqid() . '.csv';
			$filePath = DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . $fileName;
			$fileUrl = DMUrl::getCurrentBaseUrl() . 'temp/export/' . $fileName;
			
			$fp = fopen($filePath, 'w');
			foreach ($myContent as $row) {
				dumbcsv($fp, $row, '"', ';', "\n");
			}
			fclose($fp);
			
			return $fileUrl;
			
		}
		
		static function exportDailyMovementsCSV($params) {
		
			function dumbcsv($file_handle, $data_array, $enclosure, $field_sep, $record_sep) {
 			    dumbescape(false, $enclosure);
 			    $data_array=array_map('dumbescape',$data_array);
 			    return fputs($file_handle, 
 			       $enclosure 
 			        . implode($enclosure . $field_sep . $enclosure, $data_array)
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
			$movements = self::getMovements($params, $totalResults);
			
			$myContent = array();
			
			//Headers
			$myArray = array();	
			$myArray[] = "ID";
			$myArray[] = "Data";
			$myArray[] = "Tipo";
			$myArray[] = "Articolo";
			$myArray[] = "Descrizione";
			//$myArray[] = "Lotto ing.";
			//$myArray[] = "Lotto usc.";
			//$myArray[] = "Utente";
			$myArray[] = "Magaz.";
			$myArray[] = "Conf.";
			//$myArray[] = "Cart";
			$myArray[] = "Costo";
			
			$myContent[] = $myArray;
			
		
			
			foreach ($movements as $movement) {
				
						
				$movement->details = self::getMovementDetails($movement->movement_id);
				
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
				$movement->article = FHArticleHelper::loadArticle($movement->article_id);
				
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'userhelper.php');
				$movement->user = new StdClass();
				$movement->user->name = FHUserHelper::getUserName($movement->created_by);
				
				foreach ($movement->details as $movementDetail) {
					if ($movementDetail->chargelist_id > 0) {
						$myChargelist = DMTable::getInstance('Chargelist');
						$myChargelist->load($movementDetail->chargelist_id);
						$movementDetail->chargelist_code = $myChargelist->chargelist_code;
					} else {
						$movementDetail->chargelist_code = "";
					}
										
					/*$stock = DMTable::getInstance('Stock');
					$stock->load($movementDetail->stock_id);*/
					
					if ($movementDetail->stock_id == 1) {					
					$myArray = array();
					$myArray[] = $movement->movement_id;
					$myArray[] = DMFormat::formatDate($movement->created_date, 'd/m/Y', 'Y-m-d');
					$myArray[] = $movement->movement_type;
					$myArray[] = "'". $movement->article->article_code;
					$myArray[] = $movement->article->name;
					//$myArray[] = $movement->batch_in_code;
					//$myArray[] = $movement->batch_out_code;
					//$myArray[] = $movement->user->name;
					$myArray[] = 'RL';
					$myArray[] = $movementDetail->quantity_units;
					//$myArray[] = $movementDetail->quantity_packages;	
					$myArray[] = '0,030';
					
					$myContent[] = $myArray;		
					}
				}
		
			}
			
			$fileName = 'movements_export_' . uniqid() . '.csv';
			$filePath = DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . $fileName;
			$fileUrl = DMUrl::getCurrentBaseUrl() . 'temp/export/' . $fileName;
			
			$fp = fopen($filePath, 'w');
			foreach ($myContent as $row) {
				dumbcsv($fp, $row, '', ';', "\n");
			}
			fclose($fp);
			
			return $fileUrl;
			
		}
		
	}
	
?>