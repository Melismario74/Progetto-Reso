<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Classe per la gestione della tabella dati dell'app
				
		@package DMAppData
		@author DM Digital SRL		
	*/
	class DMAppData extends StdClass {
		
		//Config
		protected static $config_table = "#__appdata";
		protected static $config_idField = "ckey";
		protected static $config_valueField = "cvalue";
		
		protected static $user = null;
		
		/**
			Inizializza il singleton
			
			@param object le config da utilizzare (devono avere i campi user_table, user_idField, user_valueField); di default utilizza le config dell'app
		**/
		public function init($config = null) {
		
			if ($config == null) {
				$config = new DMConfig();
			}
			
			self::$config_table = $config->appdata_table;
			self::$config_idField = $config->appdata_idField;
			self::$config_valueField = $config->appdata_valueField;
			
			self::loadFromSession();
			
		}
		
		/**
			Ottiene un valore
			
			@param string la chiave
			@param string il valore di default da utilizzare
			
			@return string il valore
		**/
		public function getValue($key, $default = '') {
	
			$myQuery = 
				"SELECT cvalue AS value
				 FROM " . self::$config_table ."
				 WHERE " . self::$config_idField . " = '" . DMDatabase::escape($key) . "'
				";
			
			$result = DMDatabase::loadResult($myQuery);
			
			if ($result == null) {
				$result = $default;
			}
			
			return $result;	
			
		}
		
		/**
			Imposta un valore nell'appdata
			
			@param string la chiave da settare
			@param string il valore da impostare
			
			@return il risultato dell'operazione
		**/
		public function setValue($key, $value) {
		
			$myQuery = 
				"INSERT INTO " . self::$config_table . " (" . self::$config_idField . ", " . self::$config_valueField . ")
				 VALUES ('" . DMDatabase::escape($key) . "', '" . DMDatabase::escape($value) . "')
				 ON DUPLICATE KEY UPDATE cvalue = '" . DMDatabase::escape($value) . "'
				";
			
			return DMDatabase::query($myQuery);	
			
		}
		
	}
	
	/**
		AppDataException
		
		@package DMController
		@author DM Digital SRL		
	*/
	class DMAppDataException extends DMException {
	
		public $backtrace;
		
		public function __construct($message = false, $code = false) {
		
			if (!$message) {
				$message = "Generic appdata error";
			}
			if (!$code) {
				$code = -100;
			}
		
			parent::__construct($message, $code);
		}
	}
?>