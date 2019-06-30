<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Classe per utilizzo ACL
		
		@package DMAcl
		@author DM Digital SRL		
	*/
	class DMAcl {
	
		private static $privileges = array();
		
		private static $groupPrivilegesTable = '#_acl_group_privilege';
		private static $userGroupsTable = '#_acl_user_group';
		private static $privilegesTable = '#_acl_privileges';
		private static $groupsTable = '#_acl_groups';

		/**
			Inizializza il singleton
			
			@param object le config da utilizzare (devono avere i campi user_table, user_idField, user_usernameField, user_passwordField); di default utilizza le config dell'app
		**/
		public function init($config = null) {
		
			if ($config == null) {
				$config = new DMConfig();
			}
			
			self::$groupPrivilegesTable = $config->acl_groupPrivilegesTable;
			self::$userGroupsTable = $config->acl_userGroupsTable;
			self::$privilegesTable = $config->acl_privilegesTable;
			self::$groupsTable = $config->acl_groupsTable;
			
		}
		
		/**
			Svuota i privilegi dell'utente indicato.
			
			@param int l'id utente di cui svuotare i privilegi
		**/
		static public function flushUserPrivileges($userId = -1) {
		
			if ($userId < 0) {
				$userId = DMUser::getUserId();
			}
			
			self::$privileges[$userId] = array();
			
		}
		
		/**
			Svuota tutti i privilegi
		**/
		public function flushPrivileges() {
		
			self::$privileges = array();
			
		}
				
		/**
			Controlla se l'utente indicato ha il privilegio richiesto
			
			@param string il privilegio da controllare
			@param int l'id dell'utente (default = utente loggato)
			@return boolean il risultato del controllo
		**/
		function checkPrivilege($privilegeType, $userId = -1) {
			
			if ($userId < 0) {
				$userId = DMUser::getUserId();
			}
			
			//se non sono stati caricati, carico i privilegi dell'utente
			if (!isset(self::$privileges[$userId])) {
				self::loadPrivileges($userId);
			}
			
			$myResult = in_array($privilegeType, self::$privileges[$userId]);
			
			return $myResult;
		}
	
	
		/**
			Carica i privilegi dell'utente indicato, o dell'utente loggato
			
			@param int l'id dell'utente
			@return array l'array dei privilegi dell'utente
		**/
		public function loadPrivileges($userId = -1) {
			
			if ($userId < 1) {
				$userId = DMUser::getUserId();
			}
			
			//Getting user's groups
			$myGroups = self::getAclUserGroups($userId);
			
			//Getting group's privileges...
			$myPrivileges = array();
			foreach ($myGroups as $group) {
				$myQuery =
					"
						SELECT c_privilege
						FROM " . self::$groupPrivilegesTable . "
						WHERE group_id = $group
					";
				$myPrivileges = array_merge($myPrivileges, DMDatabase::loadResultArray($myQuery));
			} 
			
			$myPrivileges = array_unique($myPrivileges);
			
			self::$privileges[$userId] = $myPrivileges;
			
			return $myPrivileges;
		
		}
	
		/**
			Ottiene i gruppi di appartenenza dell'utente indicato
			
			@param int l'id dell'utente
			@return array i gruppi di appartenenza (id)
		**/
		function getAclUserGroups($userId) {
		
			//Getting user's groups
			$myQuery = 
				"
					SELECT group_id FROM " . self::$userGroupsTable . "
					WHERE user_id = " . (int) $userId . "
				";
			$myGroups = DMDatabase::loadResultArray($myQuery); 
			
			return $myGroups;
			
		}
	
		/**
			Setta per l'utente indicato i gruppi di appartenenza
			
			@param int l'id dell'utente
			@param array i gruppi di appartenenza (id)
		**/
		function setAclUserGroups($userId, $groups) {
			
			//Cancello i permessi e poi li riassegno
			$myQuery = "DELETE FROM " . self::$userGroupsTable . " WHERE user_id = " . (int) $userId;
			
			DMDatabase::query($myQuery);
			
			if ($userId > 0) {
				foreach ($groups as $group) {
					$myQuery = "INSERT INTO " . self::$userGroupsTable . " (user_id, group_id) VALUES (" . (int) $userId . ", " . (int) $group . ")";
					DMDatabase::query($myQuery);
				}
			}
			
		}
	
		/**
			Ottiene i privilegi possibili
			
			@param boolean se ritornare soltanto i nomi
			@result array se nameOnly = false, altrimenti lista 
		**/
		function getAclPrivileges($nameOnly = true) {
			
			if ($nameOnly) {
				$selectFields = 'name';
			} else {
				$selectFields = '*';
			}
			
			$myQuery = 
				"
					SELECT $selectFields 
					FROM " . self::$privilegesTable . "
					ORDER BY name
				"; 
				
			if ($nameOnly) {
				return DMDatabase::loadResultArray($myQuery);
			} else {
				return DMDatabase::loadObjectList($myQuery);
			}
			
		}
	
		/**
			Ottiene i gruppi possibili
			
			@return list i gruppi possibili
		**/
		function getAclGroups() {
			
			$groupTable = '#_acl_group';
			
			$myQuery = 
				"
					SELECT * FROM " . self::$groupsTable . "
				"; 
			
			return DMDatabase::loadObjectList($myQuery);
			
		}
	
		/**
			Ottiene la lista dei privilegi del gruppo indicato
			
			@param int l'id del gruppo
			@return array la lista delle stringhe dei privilegi
		**/
		function getAclGroupPrivileges($groupId) {
			
			$groupTable = '#_acl_group_privilege';
			
			$myQuery = 
				"
					SELECT c_privilege FROM " . self::$groupPrivilegesTable . "
					WHERE group_id = " . (int) $groupId . "
				"; 
			
			return DMDatabase::loadResultArray($myQuery);
		}
	
		/**
			Imposta i privilegi per il gruppo indicato
			@param int l'id del gruppo
			@param array i privilegi da attribuirgli
		**/
		function setAclGroupPrivileges($groupId, $privileges) {
			
			//Cancello i permessi e poi li riassegno
			$myQuery = "DELETE FROM " . self::$groupPrivilegesTable . " WHERE group_id = " . (int) $groupId;
			
			DMDatabase::query($myQuery);
			
			foreach ($privileges as $privilege) {
				$myQuery = "INSERT INTO " . self::$groupPrivilegesTable . " (group_id, c_privilege) VALUES (" . (int) $groupId . ", '" . mysql_escape_string($privilege) . "')";
				DMDatabase::query($myQuery);
			}
			
			return true;
		}
	
	}
?>