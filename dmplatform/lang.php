<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Wrapper su JLanguage
		
		@package DMLang
		@author DM Digital SRL		
	*/
	class DMLang extends JLanguage {
	
		static $language = null;
		
		/**
			Restituisce un'istanza di DMLang, creandola se non esiste
		**/
		public static function getInstance() {
			
			if (self::$language == null) {
				self::$language = JLanguage::getInstance();
			}			
			
			return self::$language;
			
		}
		
		/**
			Inizializza DMLang
			
			@param string il codice lingua da utilizzare
		**/
		public static function init($langCode = "en") {
		
			self::$language = JLanguage::getInstance($langCode);
			
		}
		
		/**
			Traduce la chiave lingua fornita
			
			@param la chiave di traduzione
			@return la stringa tradotta
		**/
		public function _($langKey) {
			
			return self::$language->_($langKey);
			
		}
		
		/**
			Importa la lingua [$langCode].ini che si trova nella posizione $languageDir; se non esiste, prova con en.ini; se nemmeno questo esiste, fallisce
			
			@param string il codice lingua
			@param string il path della cartella dove si trovano le lingue
			@param string la lingua di default su cui fare il fallback in caso di problemi nel caricare la lingua scelta
			@return la stringa tradotta
		**/
		public function importLanguage($langCode, $languageDir, $defaultLangCode = "en") {
		
			if (!file_exists($languageDir . DS . $langCode . '.ini')) {
				$langCode = $defaultLangCode;
			}
			
			$languagePath = $languageDir . DS . $langCode . '.ini';
		
			if (file_exists($languagePath)) {
				if (isset($this)) {
					return $this->loadLanguage($languagePath);
				} else {
					return self::$language->loadLanguage($languagePath);
				}
			} else {
				return false;
			}
		
		}
		
	}
	
?>