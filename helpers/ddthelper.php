<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHDdtHelper {
		
		
		/**
			Ottiene un ddt
			
			@param int l'id del ddt
			
			Mario/felsinea
		**/
		static function loadDdt($ddtId) {
		
			$ddtId = (int) $ddtId;
			
			$myQuery = "
				SELECT *
				FROM fh_ddt
				WHERE ddt_id = $ddtId
			";
			
			return DMDatabase::loadObject($myQuery);
			
		}
		
		/**
			Ottiene la lista delle Ddt
			
			Mario/felsinea
		**/
		static function getDdts() {
		
			$myQuery = "
				SELECT *
				FROM fh_ddt
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		
	}
	
?>