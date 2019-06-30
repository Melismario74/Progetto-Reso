<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Utilità di gestione URL e risposte
		
		@package DMUrl
		@author DM Digital SRL		
	*/
	class DMUrl {
	
		/**
			Ritorna la URL di base dell'app come da richiesta
			
			@return string la URL di base dell'app
		**/
		public static function getCurrentBaseUrl() {
		
			/* First we need to get the protocol the website is using */
        	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://' ? 'https://' : 'http://';
			
        	/* returns /myproject/index.php */
        	$path = $_SERVER['PHP_SELF'];
			
        	/*
        	 * returns an array with:
        	 * Array (
        	 *  [dirname] => /myproject/
        	 *  [basename] => index.php
        	 *  [extension] => php
        	 *  [filename] => index
        	 * )
        	 */
        	$path_parts = pathinfo($path);
        	$directory = $path_parts['dirname'];
        	/*
        	 * If we are visiting a page off the base URL, the dirname would just be a "/",
        	 * If it is, we would want to remove this
        	 */
        	$directory = ($directory == "/") ? "" : $directory;
			
        	/* Returns localhost OR mysite.com */
        	$host = $_SERVER['HTTP_HOST'];
			
        	/*
        	 * Returns:
        	 * http://localhost/mysite
        	 * OR
        	 * https://mysite.com
        	 */
        	return $protocol . $host . $directory . "/";
  			
		}
		
		/**
			Restituisce l'intera URL attuale
			
			@return string la URL corrente completa
		**/
		public static function getCurrentUrl() {
			
			$pageURL = 'http';
			if ($_SERVER["HTTPS"] == "on") {
				$pageURL .= "s";
			}
			
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
			}
			
			return $pageURL;

		}
		
		/**
			Ridireziona l'utente con un messaggio
			
			@param string url di destinazione
			@param string il messaggio da visualizzare
			@param string il tipo di messaggio, default MESSAGE
			@return boolean true
		**/
		public static function redirect($target, $message = "", $messageType = "MESSAGE") {
		
			if ($message != "") {
				$target = DMUrl::addMessageToUrl($target, $message, $messageType);
			}
			header('Location: ' . $target);
			
			exit;
			
		}
		
		/**
			Aggiunge un messaggio alla richiesta corrente
			
			@param string il messaggio da aggiungere
			@param string il tipo di messaggio, default "MESSAGE"
		**/
		public static function setSystemMessage($message, $messageType = "MESSAGE") {
		
				DMInput::set("systemMessage", $message);
				DMInput::set("systemMessageType", $messageType);
			
		}
		
		/**
			Aggiunge un messaggio alla url
			
			@param string la URL di destinazione
			@param string il messaggio da aggiungere
			@param string il tipo di messaggio, default "MESSAGE"
			@return string la URL rielaborata
		**/
		public static function addMessageToUrl($url, $message, $messageType = "MESSAGE") {
		
				if ($message != '') {
					$url = self::addVarToUrl($url, "systemMessage", $message);
					$url = self::addVarToUrl($url, "systemMessageType", $messageType);
				}
				
				return $url;
			
		}
		
		/**
			Aggiunge una variabile e rispettivo valore alla richiesta
			
			@param string la URL di destinazione
			@param string il nome della variabile da aggiungere
			@param string il valore della variabile da aggiungere
			@return string la URL rielaborata
		**/
		public static function addVarToUrl($url, $variableName, $variableValue){
		
			if (strpos($url, "?")) {
				$start_pos = strpos($url, "?");
				$url_vars_strings = substr($url, $start_pos + 1);
				$names_and_values = explode("&", $url_vars_strings);
				$url = substr($url, 0, $start_pos);
				
				foreach ($names_and_values as $value) {
					list($var_name, $var_value) = explode("=", $value);
					if ($var_name != $variableName) {
						if (strpos($url, "?") === false) {
							$url .= "?";
						} else {
							$url .= "&";
						}
						$url .= $var_name . "=" . $var_value;
					}
				}
			} 
			
			// add variable name and variable value
			if (strpos($url,"?") === false) {
				$url .= "?" . $variableName . "=" . $variableValue;
			} else {
				$url .= "&" . $variableName . "=" . $variableValue;
			}
			return $url;
		}
		
		/**
			Rimuove una variabile e rispettivo valore dalla richiesta
			
			@param string la URL di destinazione
			@param string il nome della variabile da rimuovere
			@return string la URL rielaborata
		**/
		public static function removeVarFromUrl($url, $variableName){
		
			if (strpos($url, "?")) {
				$start_pos = strpos($url, "?");
				$url_vars_strings = substr($url,$start_pos + 1);
				$names_and_values = explode("&", $url_vars_strings);
				$url = substr($url, 0, $start_pos);
				
				foreach ($names_and_values as $value) {
					list($var_name, $var_value) = explode("=", $value);
					if ($var_name != $variableName){
						if (strpos($url_string, "?") === false) {
							$url .= "?";
						} else {
							$url .= "&";
						}
						$url .= $var_name."=".$var_value;
					}
				}
			} 
			
			return $url_string;
		}
				
	}
	
?>