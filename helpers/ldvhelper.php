<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHLdvHelper {
		
		
		/**
			Ottiene un ldv
			
			@param int l'id del ldv
			
			Mario/felsinea
		**/
		function loadLdv($ldvId) {
		
			$ldvId = (int) $ldvId;
			
			$myQuery = "
				SELECT *
				FROM fh_ldv
				WHERE ldv_id = $ldvId
			";
			
			$myLdv = DMDatabase::loadObject($myQuery);
			
			
			$myQuery = "
				SELECT ca.*, a.*
				FROM fh_ldv_row AS ca
				LEFT JOIN fh_ddt AS a ON (ca.ddt_id = a.ddt_id)
				WHERE ca.ldv_id = $ldvId
			";
			
			$myLdv->rows = DMDatabase::loadObjectList($myQuery);
			
			return $myLdv;
			
		}
		
		static function getLdv($ldvId) {
		
			$ldvId = (int) $ldvId;
			
			$myQuery = "
				SELECT *
				FROM fh_ldv
				WHERE ldv_id = $ldvId
			";
			
			return DMDatabase::loadObject($myQuery);
			
		}
		
		
		
		/**
			Ottiene la lista delle Ldv
			
			Mario/felsinea
		**/
		static function getLdvs() {
		
			$myQuery = "
				SELECT *
				FROM fh_ldv
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		
		/**
			Ottiene la lista l'Arrival a cui appartiene la Ldv
			
			Mario/felsinea
		**/
		
		static function getArrival($ldvId) {
			
			$ldvId = (int) $ldvId;
		
			$myQuery = "
				SELECT arrival_id
				FROM fh_arrival_row
				WHERE ldv_id = $ldvId
			";
			
			return DMDatabase::loadResult($myQuery);
			
		}
		
	}
	
?>