<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHUserHelper {
		
		/**
			Ritorna il nome utente dell'utente fornito
			
			@param int l'id dell'utente
			@return string lo username
		**/
		static function getUsername($userId) {
		
			$userId = (int) $userId;
			
			$myQuery = "
				SELECT username
				FROM fh_user
				WHERE user_id = $userId
			";
			
			return DMDatabase::loadResult($myQuery);
		
		}
		
		/**
			Ottiene la lista degli utenti
		**/
		static function getUsers() {
		
			$myQuery = "
				SELECT user_id, username, name
				FROM fh_user
			";
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		/**
			Ottiene un utente
			
			@param int l'id dell'utente
		**/
		static function loadUser($userId) {
		
			$userId = (int) $userId;
			
			$myQuery = "
				SELECT user_id, username, name
				FROM fh_user
				WHERE user_id = $userId
			";
			
			return DMDatabase::loadObject($myQuery);
			
		}
		
	}
	
?>