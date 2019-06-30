<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Utilità di formattazione
		
		@package DMFormat
		@author DM Digital SRL		
	*/
	class DMFormat {
		
		/**
			Ritorna un valore positivo
			
			@param number il numero da verificare
			@return number il valore positivo
		**/
		public static function positiveValue($value, $default) {
		
			if (($value == null) ||	($value < 1)) {
				$value = $default;
			}
			
			return $value;	
			
		}
		
		/**
			Ritorna $default se la stringa è vuota o nulla
			
			@param string la stringa in ingresso
			@return string la stringa non nulla e non vuota
		**/
		public static function stringValue($value, $default) {
		
			if (($value == null) ||	($value === '')) {
				$value = $default;
			}
				
			return $value;	
		
		}
		
		/**
			Aggiunge un valore all'inizio di un array
			
			@param array
			@return variant l'elemento da aggiungere
		**/
		public static function addItemOnTop($array, $item) {
			if (count($array) > 0) {
				array_unshift($array, $item);
			} else {
				$array[] = $item;
			}
			
			return $array;
		}
		
		/**
			Formatta una valuta
			
			@param double il valore da formattare
			@param boolean utilizzare i colori (rosso se negativo)
			@param string simbolo della valuta da utilizzare (false per utilizzare default)
			@param boolean visualizza la valuta prima del valore
			@param string separatore decimale da utilizzare (false per utilizzare default)
			@param string separatore migliaia da utilizzare (false per utilizzare default)
			@return string la valuta formattata
		**/
		public static function formatCurrency($value, $useColors = false, $currencySymbol = false, $currencySymbolBefore = false, $decimalSeparator = false, $thousandsSeparator = false) {
		
			if (is_null($value)) {
				return '';
			}
		
			$localeConv = localeconv(); 
			
			if (!$currencySymbol) {
				$currencySymbol = $localeConv['currency_symbol'];
			}
			if (!$decimalSeparator) {
				$decimalSeparator = $localeConv['mon_decimal_point'];
				if ($decimalSeparator === '') {
					$decimalSeparator = '.';
				}
			}
			if (!$thousandsSeparator) {
				$thousandsSeparator = $localeConv['mon_thousands_sep'];
			}
			
			$result = number_format($value, 2, $decimalSeparator, $thousandsSeparator); 
			
			if ($currencySymbolBefore) {
				$result = trim(trim($currencySymbol) . ' ' . $result);
			} else {
				$result = trim($result . ' ' . trim($currencySym));
			}
			
			if ($useColors) {
				if ($value < 0) {
					$result = '<span style="color: red">' . $result . '</span>';
				}
			}
			
			return $result;
			
		}
		
		/**
			Emula il vecchio date_parse_from_format
			
			@param string il formato da utilizzare
			@param datetime la data da convertire
			@return array i componenti della data
		**/
		function dateParseFromFormat($stFormat, $stData) {	
		
    		$aDataRet = array();
   		 	$aPieces = split('[:/.\ \-]', $stFormat);
    		$aDatePart = split('[:/.\ \-]', $stData);
   		 	foreach($aPieces as $key=>$chPiece) {
    	    	switch ($chPiece) {
    	        	case 'd':
    	    	    case 'j':
    		            $aDataRet['day'] = $aDatePart[$key];
 		               break;
    	            
    	        	case 'F':
    	        	case 'M':
    	        	case 'm':
    	    	    case 'n':
    		            $aDataRet['month'] = $aDatePart[$key];
  		              break;
    	            
    	        	case 'o':
    	        	case 'Y':
    	    	    case 'y':
    		            $aDataRet['year'] = $aDatePart[$key];
  		              break;
    	        
    	        	case 'g':
    	    	    case 'G':
    		        case 'h':
		            case 'H':
    	            	$aDataRet['hour'] = $aDatePart[$key];
    	        	    break;    
    	            
    	    	    case 'i':
    		            $aDataRet['minute'] = $aDatePart[$key];
		                break;
    	            
    	        	case 's':
    	            	$aDataRet['second'] = $aDatePart[$key];
    	        	    break;            
    	  		}
    	    
    		}
    		
    		if (empty($aDataRet['year'])) {
    			$aDataRet['year'] = 0;
    		} 
    		if (empty($aDataRet['month'])) {
    			$aDataRet['month'] = 0;
    		}
    		if (empty($aDataRet['day'])) {
    			$aDataRet['day'] = 0;
    		}
    		if (empty($aDataRet['hour'])) {
    			$aDataRet['hour'] = 0;
    		}
    		if (empty($aDataRet['minute'])) {
    			$aDataRet['minute'] = 0;
    		}
    		if (empty($aDataRet['second'])) {
    			$aDataRet['second'] = 0;
    		}
    		
    		return $aDataRet;
    		
		}
		
		/**
			Formatta la data secondo il formato richiesto
			
			@param datetime la data da convertire
			@param string il formato obiettivo
			@param string il formato iniziale
			@param int l'offset, in giorni, da applicare
			@param string la lingua di destinazione
			@return array i componenti della data
		**/
		function formatDate($date, $targetformat = 'd/m/Y', $sourceFormat = 'Y-m-d', $offset = 0, $targetLang = 'it') {
		
			if ($date === '0000-00-00') {
				return '';
			}
			if ($date === '00/00/0000') {
				return '';
			}
			if ($date === '00:00') {
				return '';
			}
			if ($date === '00:00:00') {
				return '';
			}
			
			if ($date == '') {
				return '';
			}
			
			$dateInfo = self::dateParseFromFormat($sourceFormat, $date);
			
			$myDate = mktime(
				$dateInfo['hour'], 
				$dateInfo['minute'], 
				$dateInfo['second'],
				$dateInfo['month'], 
				$dateInfo['day'], 
				$dateInfo['year']
			);
				
			$myDate = $myDate + ($offset * 60 * 60);
			$returnDate = date($targetformat, $myDate);
			
			if ($targetLang == 'it'){
				$replacements = array(
					'January'=>'Gennaio', 'February'=>'Febbraio', 'March'=>'Marzo', 'April'=>'Aprile', 'May'=>'Maggio', 
					'June'=>'Giugno', 'July'=>'Luglio', 'August'=>'Agosto', 'September'=>'Settembre', 'October'=>'Ottobre', 
					'November'=>'Novembre', 'December'=>'Dicembre', 'Sunday'=>'Domenica', 'Monday'=>'Lunedì', 'Tuesday'=>'Martedì', 
					'Wednesday'=>'Mercoledì', 'Thursday'=>'Giovedì', 'Friday'=>'Venerdì', 'Saturday'=>'Sabato', 
					'Sun'=>'Dom', 'Mon'=>'Lun', 'Tue'=>'Mar', 
					'Wed'=>'Mer', 'Thu'=>'Gio', 'Fri'=>'Ven', 'Sat'=>'Sab'
				);
				
				foreach ($replacements as $key=>$val) {
					$returnDate = preg_replace('/'.$key.'/i', $val, $returnDate);
				}
			}
			
			return $returnDate;
			
		}
		
		/**
			Formatta una quantità di minuti in formato ora
			
			@param int minuti
			@return string l'ora formattata HH:mm			
		**/
		function formatTimeFromMinutes($input) {
		
			$hours = floor($input / 60);
			$minutes = $input % 60;
			
			if ($minutes < 10) {
				$minutes = '0' . $minutes;
			}
			
			return $hours . ':' . $minutes;
			
		}
		
		/**
			Ritorna se una un valore rispetta una regex
			
			@param string il pattern da utilizzare
			@param string il valore da valutare
			@return boolean se è valido o no
		**/
		function validateRegex($pattern, $field) {
		
			$matches = array();
			preg_match($pattern, $field, $matches);
			
			if (is_array($matches) && (count($matches) > 0) && (strlen($matches[0]) == strlen($field))) {
				return true;
			} else {
				return false;
			}
			
		}
		
		/**
			Ritorna se una e-mail è valida oppure no
			
			@param string l'email da validare
			@return boolean se è valida o no
		**/
		function isValidEmail($email) {
		
			return self::validateRegex("'^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$'", $email);
			
		}
		
		/**
			Pulisce una string lasciando solo caratteri alfanumerici
			
			@param string la stringa da pulire
			@return string la stringa pulita
		**/
		function cleanString($string){

			$string = str_replace("à", "a", $string);		
			$string = str_replace("á", "a", $string);		
			$string = str_replace("â", "a", $string);		
			$string = str_replace("ä", "a", $string);
			
			$string = str_replace("è", "e", $string);		
			$string = str_replace("é", "e", $string);		
			$string = str_replace("ê", "e", $string);		
			$string = str_replace("ë", "e", $string);		
			 		
			$string = str_replace("ì", "i", $string);		
			$string = str_replace("í", "i", $string);		
			$string = str_replace("î", "i", $string);		
			$string = str_replace("ï", "i", $string);		
			 		
			$string = str_replace("ò", "o", $string);		
			$string = str_replace("ó", "o", $string);		
			$string = str_replace("ô", "o", $string);		
			$string = str_replace("ö", "o", $string);	 
			
			$string = str_replace("ù", "u", $string);		
			$string = str_replace("ú", "u", $string);		
			$string = str_replace("û", "u", $string);		
			$string = str_replace("ü", "u", $string);		 
			
			$string = ereg_replace("[^A-Za-z0-9 ]", "", $string);
		
			return $string;
		
		}
		
	}
	
?>