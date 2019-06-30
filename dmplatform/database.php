<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Wrapper sul Database
		
		@package DMDatabase
				
	*/
	class DMDatabase {
	
		private $db = null;
	
		/**
			Ottiene un'instanza di DMDatabase
			
			@param array la configurazione da utilizzare per la connessione al DB
			@return istanza DMDatabase
		**/
		public function __construct($dbConfig = null) {
		
			if (isset($dbConfig)) {
				$this->db = self::getDB($dbConfig);
			} else {
				$this->db = self::getDBO();
			}
			
			return $this;
		
		}
	
		/**
			Ottiene una istanza del database sulla base della configurazione fornita
			
			@param array la configurazione da utilizzare per la connessione al DB
			@return JDatabase l'oggetto DB da utilizzare
		**/
		public function getDB($dbConfig) {
		
			$db = JDatabaseDriver::getInstance($dbConfig);
			$db->select($dbConfig['database']);
			$db->setDebug(0);
			
			return $db;
			
		}
		
		/**
			Ottiene l'istanza di default del database
			
			@return JDatabase l'oggetto DB da utilizzare
		**/
		public function getDBO() {
			
			$config = new DMConfig();
			
			$dbConfig = array(
				'driver' => $config->db_driver,
				'host' => $config->db_host,
				'user' => $config->db_user,
				'password' => $config->db_password,
				'database' => $config->db_database,
				'prefix' => $config->db_prefix
			);
			
			return self::getDB($dbConfig);
			
		}
		
		/**
			Fa l'escaping della string
			@param string la query da filtrare
			
			@return la stringa pulita
		**/
		public function escape($string) {
			
			if ((isset($this)) && (isset($this->db))) {
				$db = $this->db;
			} else {
				$db =& self::getDBO();
			}
			
			return $db->escape($string);
			
		}
		
		/**
			Ritorna l'ultimo id inserito
			
			@return int l'ultimo id inserito
		**/
		public function getLastInsertId() {
			
			if ((isset($this)) && (isset($this->db))) {
				$db = $this->db;
			} else {
				$db =& self::getDBO();
			}
			
			return $db->insertid();
			
		}
		
		/**
			Shortcut per eseguire una query
			@param string la query da eseguire
			
			@return risultato query
		**/
		public function query($query) {
		
			if ((isset($this)) && (isset($this->db))) {
				$db = $this->db;
			} else {
				$db =& self::getDBO();
			}
			
			$db->setQuery($query);
			
			$queryResult = $db->execute();
			if ($queryResult) {
				return true;
			} else {
				print_r($db);
				return false;
			}
			
		}
		
		/**
			Shortcut per eseguire una query
			@param string la query da eseguire
			@param variant il valore da utilizzare come default in caso di risultato nullo
			
			@return risultato query
		**/
		public function loadResult($query, $default = null) {
		
			if ((isset($this)) && (isset($this->db))) {
				$db = $this->db;
			} else {
				$db =& self::getDBO();
			}
			
			$db->setQuery($query);
			
			$myResult = $db->loadResult();
			
			if (is_null($myResult) && !is_null($default)) {
				$myResult = $default;
			}
			
			return $myResult;
			
		}
		
		/**
			Shortcut per eseguire una query
			@param string la query da eseguire
			
			@return risultato query
		**/
		public function loadResultArray($query) {
		
			if ((isset($this)) && (isset($this->db))) {
				$db = $this->db;
			} else {
				$db =& self::getDBO();
			}
			
			$db->setQuery($query);
			
			return $db->loadColumn();
			
		}
		
		/**
			Shortcut per eseguire una query
			@param string la query da eseguire
			
			@return risultato query
		**/
		public function loadObjectList($query, $start = 0, $limit = 0) {
		
			if ((isset($this)) && (isset($this->db))) {
				$db = $this->db;
			} else {
				$db =& self::getDBO();
			}
			
			$db->setQuery($query, $start, $limit);
			
			return $db->loadObjectList();
			
		}		
		
		/**
			Shortcut per eseguire una query
			@param string la query da eseguire
			
			@return risultato query
		**/
		public function loadObject($query) {
		
			if ((isset($this)) && (isset($this->db))) {
				$db = $this->db;
			} else {
				$db =& self::getDBO();
			}
			
			$db->setQuery($query);
			
			return $db->loadObject();
			
		}
	
	}

?>