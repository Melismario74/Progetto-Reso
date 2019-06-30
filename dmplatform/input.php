<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Wrapper su JInput
		
		@package DMInput
		@author DM Digital SRL		
	*/
	class DMInput {
	
		/**
			Ottiene un parametro dalla richiesta
			
			@param string il parametro da leggere
			@param string il valore da default da impostare in mancanza di valore utente
			@param string il filtro da usare
			@return variant il valore letto
		**/
		public static function get($field, $default, $filter = null) {
		
			if (!isset($filter)) {
				$filter = 'CMD';
			}
		
			$input = new JInput;
			return $input->get($field, $default, $filter);
			
		}
	
		/**
			Ottiene una stringa dalla richiesta
			
			@param string il parametro da leggere
			@param string il valore da default da impostare in mancanza di valore utente
			@return string il valore letto
		**/
		public static function getString($field, $default = null) {
		
			$input = new JInput;
			return $input->getString($field, $default);
			
		}
	
		/**
			Ottiene una stringa da utilizzare come nome file dalla richiesta
			
			@param string il parametro da leggere
			@param string il valore da default da impostare in mancanza di valore utente
			@return string il valore letto
		**/
		public static function getFileName($field, $default = null) {
		
			$value = self::getString($field, $default);
			$value = preg_replace('#[^A-Za-z0-9]#', '', trim($value));
			return $value;
			
		}
		
		/**
			Ottiene un int dalla richiesta
			
			@param string il parametro da leggere
			@param int il valore da default da impostare in mancanza di valore utente
			@return int il valore letto
		**/
		public static function getInt($field, $default = null) {
		
			$input = new JInput;
			return $input->getInt($field, $default);
			
		}
		
		/**
			Ottiene un float dalla richiesta
			
			@param string il parametro da leggere
			@param int il valore da default da impostare in mancanza di valore utente
			@return float il valore letto
		**/
		public static function getFloat($field, $default = null) {
		
			$input = new JInput;
			return $input->getFloat($field, $default);
			
		}
		
		/**
			Ottiene un file
			
			@param string il parametro da leggere
			@return object il file
		**/
		public static function getFile($field) {
		
			$fileFilter = new JInput($_FILES);
			return $fileFilter->get($field, null, 'array');
			
		}
		
		/**
			Setta un parametro nella richiesta
			
			@param string il parametro da settare
			@param string il valore da settare
		**/
		public static function set($field, $value) {
		
			$input = new JInput;
			return $input->set($field, $value);
			
		}
		
	}
	
?>