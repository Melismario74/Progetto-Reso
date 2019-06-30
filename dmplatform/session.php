<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Wrapper su JSession
		
		@package DMSession
		@author DM Digital SRL		
	*/
	class DMSession {
	
		static protected $session = false;
		static protected $sessionHandler = 'none';
	
		/**
			Imposta il tipo di session handler
			
			@param string il session handler
		**/
		public function setHandler($handler) {
			
			self::$sessionHandler = $handler;
			
		}
		
		/**
			Ottiene un'instanza di sessione, creandola solo se necessario
			
			@return JSession sessione
		**/
		public function getSession() {
		
			$dmConfig = new DMConfig();
			$sessionName = 'dmPlatform_' . $dmConfig->secret;
		
			$options = array(
				'name' => $sessionName,
				'expire' => 900,
				'force_ssl' => false
			);
			
			if (!self::$session) {
				self::$session =& self::createSession($options);
			} else {
				if (self::$session->getState() == 'expired') {
					self::$session->restart();
				}
			}

			return self::$session;
			
		}

		/**
			Crea una sessione sulla base delle opzioni fornite
			
			@param array le opzioni
			@return JSession sessione
		**/
		protected static function createSession(array $options = array()) {
			
			$handler = self::$sessionHandler;
			
			$session =& JSession::getInstance($handler, $options);
			
			if ($session->getState() != 'active') {
				$session->initialise(new JInput);
				$session->start();
			}
		
			return $session;
		}
		
		/**
			Metodo statico per salvare un dato in sessione
			
			@param string key
			@param variant value
		**/
		public static function set($key, $value) {
		
			$mySession =& self::getSession();
			return $mySession->set($key, $value);
			
		}
		
		/**
			Metodo statico per leggere un dato dalla sessione
			
			@param string key
			@param variant default
			@return variant il valore letto
		**/
		public static function get($key, $default) {
			
			$mySession =& self::getSession();
			return $mySession->set($key, $default);
			
		}
		
	}
	
?>