<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Gestione errori
		
		@package DMError
		@author DM Digital SRL		
	*/
	class DMError {
		
		private static $errors = array();
		
		/**
			Carica i dati sui codici di errore
		**/
		public function init() {
			
			$errorsFile = DM_PLATFORM_PATH . DS . 'error.json';
			$errorsContent = file_get_contents($errorsFile); 
			
			self::$errors = json_decode($errorsContent, true);
			
		}	
		
		/**
			Restituisce la descrizione rispetto al codice di errore indicato
			
			@param int il codice di errore
			@param string la descrizione di default da utilizzare
			@return string la descrizione dell'errore
		**/
		public function getErrorDescription($code, $default = '') {
		
			if (isset(self::$errors[$code])) {
				return self::$errors[$code];
			} else {
				return $default;
			}
			
		}
	}
	
	DMError::init();
	
?>