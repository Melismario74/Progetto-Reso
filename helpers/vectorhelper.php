<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHVectorHelper {
		
		/**
			Ottiene la lista degli utenti
		**/
		static function getVectors() {
		
			$myQuery = "
				SELECT vector_id, name
				FROM fh_vector
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		/**
			Ottiene un utente
			
			@param int l'id dell'utente
		**/
		static function loadVector($vectorId) {
		
			$vectorId = (int) $vectorId;
			
			$myQuery = "
				SELECT vector_id, name
				FROM fh_vector
				WHERE vector_id = $vectorId
			";
			
			return DMDatabase::loadObject($myQuery);
			
		}
		
	}
	
?>