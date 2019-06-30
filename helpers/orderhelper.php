<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHOrderHelper {
		
		/**
			Ottiene la lista dei documenti di uscita correntemente su DB
			
			@param array i parametri di ricerca
			@return la lista dei documenti
		**/
		static function getOrders($searchParams, &$totalResults) {
		
			if (!isset($searchParams['limit'])) {
				$searchParams['limit'] = 0;
			}
			if (!isset($searchParams['offset'])) {
				$searchParams['offset'] = 0;
			}
			
			$whereConditions = "";
			
			
			if ((isset($searchParams['orderDateFrom'])) && ($searchParams['orderDateFrom'] != '')) {
				$whereConditions .= " AND i.order_date >= '" . DMDatabase::escape($searchParams['orderDateFrom']) . "'";
			}
			if ((isset($searchParams['orderDateTo'])) && ($searchParams['orderDateTo'] != '')) {
				$whereConditions .= " AND i.order_date <= '" . DMDatabase::escape($searchParams['orderDateTo']) . "'";
			}
			if ((isset($searchParams['order_archived'])) && ($searchParams['order_archived'] > -1)) {
				$whereConditions .= ' AND i.order_archived = ' . (int) $searchParams['order_archived'];
			}
			
			$myQuery = "
				SELECT SQL_CALC_FOUND_ROWS i.*
				FROM fh_order AS i
				WHERE 1 = 1
				$whereConditions
				ORDER BY i.order_date DESC, i.order_code DESC
			";
			
			$results = DMDatabase::loadObjectList($myQuery, $searchParams['offset'], $searchParams['limit']);
			
			$totalResults = DMDatabase::loadResult("SELECT FOUND_ROWS();");
			
			return $results;
			
		}
		
		/**
			Carica da DB il documento richiesto
			
			@param int l'id del documento richiesto
		**/
		function loadOrder($orderId) {
		
			$orderId = (int) $orderId;
			
			$myQuery = "
				SELECT *
				FROM fh_order
				WHERE order_id = $orderId
			";
			
			$myOrder = DMDatabase::loadObject($myQuery);
			
			$myQuery = "
				SELECT ir.*, s.name AS stock_name
				FROM fh_order_row AS ir
				LEFT JOIN fh_stock AS s ON (ir.stock_id = s.stock_id)
				WHERE ir.order_id = $orderId
			";
			
			$myOrder->rows = DMDatabase::loadObjectList($myQuery);
			
			return $myOrder;
			
		}		
	}
	
?>