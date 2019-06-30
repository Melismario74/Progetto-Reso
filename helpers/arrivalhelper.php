<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHArrivalHelper {
		
		/**
			Ottiene la lista dei documenti di uscita correntemente su DB
			
			@param array i parametri di ricerca
			@return la lista dei documenti
		**/
		static function getArrivals($searchParams, &$totalResults) {
		
			if (!isset($searchParams['limit'])) {
				$searchParams['limit'] = 0;
			}
			if (!isset($searchParams['offset'])) {
				$searchParams['offset'] = 0;
			}
			
			$whereConditions = "";
			
			
			if ((isset($searchParams['arrivalDateFrom'])) && ($searchParams['arrivalDateFrom'] != '')) {
				$whereConditions .= " AND i.arrival_date >= '" . DMDatabase::escape($searchParams['arrivalDateFrom']) . "'";
			}
			if ((isset($searchParams['arrivalDateTo'])) && ($searchParams['arrivalDateTo'] != '')) {
				$whereConditions .= " AND i.arrival_date <= '" . DMDatabase::escape($searchParams['arrivalDateTo']) . "'";
			}
			if ((isset($searchParams['arrival_archived'])) && ($searchParams['arrival_archived'] > -1)) {
				$whereConditions .= ' AND i.arrival_archived = ' . (int) $searchParams['arrival_archived'];
			}
			
			$myQuery = "
				SELECT SQL_CALC_FOUND_ROWS i.*
				FROM fh_arrival AS i
				WHERE 1 = 1
				$whereConditions
				ORDER BY i.arrival_date DESC, i.arrival_code DESC
			";
			
			$results = DMDatabase::loadObjectList($myQuery, $searchParams['offset'], $searchParams['limit']);
			
			$totalResults = DMDatabase::loadResult("SELECT FOUND_ROWS();");
			
			return $results;
			
		}
		
			
		/**
			ottiene il nr di ldv per arrivo Mario - felsinea
		**/
		static function getArrivalNumberLdvs($arrivalId) {
		
			$myQuery = "
				SELECT COUNT(ldv_id) 
				FROM fh_arrival_row
				WHERE arrival_id = $arrivalId
			";
			
			return DMDatabase::loadResult($myQuery);
			
		}
		
		/**
			ottiene il nr di ddt per arrivo Mario - felsinea
		**/
		static function getArrivalNumberDdts($arrivalId) {
		
			$myQuery = "
				SELECT COUNT(ddt_id) 
				FROM fh_ldv_row AS a
				LEFT JOIN fh_arrival_row AS s ON (s.ldv_id = a.ldv_id)
				WHERE s.arrival_id = $arrivalId
			";
			
			return DMDatabase::loadResult($myQuery);
			
		}
		
			
		/**
			Ottiene la lista delle ldv Mario - felsinea
		**/
		static function getArrivalLdvs() {
		
			$myQuery = "
				SELECT *
				FROM fh_ldv
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		/**
			Carica da DB il documento richiesto
			
			@param int l'id del documento richiesto
		**/
		function loadArrival($arrivalId) {
		
			$arrivalId = (int) $arrivalId;
			
			$myQuery = "
				SELECT *
				FROM fh_arrival
				WHERE arrival_id = $arrivalId
			";
			
			$myArrival = DMDatabase::loadObject($myQuery);
			
			$myQuery = "
				SELECT ir.*, s.*
				FROM fh_arrival_row AS ir
				LEFT JOIN fh_ldv AS s ON (ir.ldv_id = s.ldv_id)
				WHERE ir.arrival_id = $arrivalId
			";
			
			$myArrival->ldvs = DMDatabase::loadObjectList($myQuery);
			
			return $myArrival;
			
		}		
	}
	
?>