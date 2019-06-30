<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHInvoiceHelper {
		
		/**
			Ottiene la lista dei documenti di uscita correntemente su DB
			
			@param array i parametri di ricerca
			@return la lista dei documenti
		**/
		static function getInvoices($searchParams, &$totalResults) {
		
			if (!isset($searchParams['limit'])) {
				$searchParams['limit'] = 0;
			}
			if (!isset($searchParams['offset'])) {
				$searchParams['offset'] = 0;
			}
			
			$whereConditions = "";
			
			
			if ((isset($searchParams['invoiceDateFrom'])) && ($searchParams['invoiceDateFrom'] != '')) {
				$whereConditions .= " AND i.invoice_date >= '" . DMDatabase::escape($searchParams['invoiceDateFrom']) . "'";
			}
			if ((isset($searchParams['invoiceDateTo'])) && ($searchParams['invoiceDateTo'] != '')) {
				$whereConditions .= " AND i.invoice_date <= '" . DMDatabase::escape($searchParams['invoiceDateTo']) . "'";
			}
			if ((isset($searchParams['invoice_archived'])) && ($searchParams['invoice_archived'] > -1)) {
				$whereConditions .= ' AND i.invoice_archived = ' . (int) $searchParams['invoice_archived'];
			}
			
			$myQuery = "
				SELECT SQL_CALC_FOUND_ROWS i.*
				FROM fh_invoice AS i
				WHERE 1 = 1
				$whereConditions
				ORDER BY i.invoice_date DESC, i.invoice_code DESC
			";
			
			$results = DMDatabase::loadObjectList($myQuery, $searchParams['offset'], $searchParams['limit']);
			
			$totalResults = DMDatabase::loadResult("SELECT FOUND_ROWS();");
			
			return $results;
			
		}
		
		/**
			Carica da DB il documento richiesto
			
			@param int l'id del documento richiesto
		**/
		function loadInvoice($invoiceId) {
		
			$invoiceId = (int) $invoiceId;
			
			$myQuery = "
				SELECT *
				FROM fh_invoice
				WHERE invoice_id = $invoiceId
			";
			
			$myInvoice = DMDatabase::loadObject($myQuery);
			
			$myQuery = "
				SELECT ir.*, s.name AS stock_name
				FROM fh_invoice_row AS ir
				LEFT JOIN fh_stock AS s ON (ir.stock_id = s.stock_id)
				WHERE ir.invoice_id = $invoiceId
			";
			
			$myInvoice->rows = DMDatabase::loadObjectList($myQuery);
			
			return $myInvoice;
			
		}		
	}
	
?>