<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Classe per la gestione degli accessi utente
		
		@package DMUser
		@author DM Digital SRL		
	*/
	class DMUser extends StdClass {
		
		//Config
		protected static $config_table = "#_user";
		protected static $config_idField = "user_id";
		protected static $config_usernameField = "username";
		protected static $config_passwordField = "password";
		
		protected static $user = null;
		
		/**
			Inizializza il singleton, caricando inoltre i dati dell'utente dalla sessione
			
			@param object le config da utilizzare (devono avere i campi user_table, user_idField, user_usernameField, user_passwordField); di default utilizza le config dell'app
		**/
		public function init($config = null) {
		
			if ($config == null) {
				$config = new DMConfig();
			}
			
			self::$config_table = $config->user_table;
			self::$config_idField = $config->user_idField;
			self::$config_usernameField = $config->user_usernameField;
			self::$config_passwordField = $config->user_passwordField;
			
			self::loadFromSession();
			
		}
		
		/**
			Salva in sessione i dati dell'utente
		**/
		private function saveToSession() {
		
			$mySession =& DMSession::getSession();
			$mySession->set('user', self::$user);
			
		}
		
		/**
			Carica da sessione i dati dell'utente
		**/
		private function loadFromSession() {
		
			$mySession =& DMSession::getSession();
			self::$user = $mySession->get('user', self::$user);
			
		}
		
		/**
			Restituisce l'oggetto utente, se questo è loggato, altrimenti false
			
			@return object l'utente loggato, oppure false
		**/
		public function getUser() {
		
			if (isset(self::$user)) {
				return self::$user;
			} else {
				return false;
			}
			
		}
		
		/**
			Restituisce l'id dell'utente loggato, altrimenti -1
			
			@return int l'id dell'utente loggato, oppure -1
		**/
		public function getUserId() {
		
			if (isset(self::$user)) {
				return self::$user->user_id;
			} else {
				return -1;
			}
			
		}
		
		/**
			Verifica che il nome utente sia valido come sintassi
			
			@param string il nome utente
			@return boolean se il nome utente è valido
		**/
		public function validateUsername($username) {
			
			if (strlen($username) < 5) {
				return false;
			}
			if (!DMFormat::validateRegex("'[a-zA-Z0-9\.@]*'", $username)) {
				return false;
			}
						
			return true;
			
		}
		
		/**
			Verifica che la password sia valida come sintassi
			
			@param string la password
			@return boolean se la password
		**/
		public function validatePassword($password) {
		
			if (strlen($password) < 5) {
				return false;
			}
			if (!DMFormat::validateRegex("'[a-zA-Z0-9\.&!@]*'", $password)) {
				return false;
			}
			
			return true;
			
		}
		
		/**
			Verifica se l'utente esiste già nel sistema
			
			@param il nome utente
			
			@return boolean se esiste già
		**/
		public function usernameExists($username) {
		
			$myQuery = "
				SELECT COUNT(*)
				FROM " . DMDatabase::escape(self::$config_table) . "
				WHERE " . DMDatabase::escape(self::$config_usernameField) . " = '" . DMDatabase::escape($username) . "'
			";
			
			if (DMDatabase::loadResult($myQuery) > 0) {
				return true;
			} else {
				return false;
			}
			
		}
		
		/**
			Registra un nuovo utente, restituendo falso in caso di problemi
			
			@param string il nome utente
			@param string la password
			@param string il salt da usare
			@param boolean se la password è già codificata
			@return int l'id dell'utente creato, oppure -100 se user non valido, -200 se password non valida, -300 se duplicato
		**/
		public function createUser($username, $password, $salt = null, $passwordAlreadyEncrypted = false) {
			
			if (!isset($salt)) {
				$config = new DMConfig();
				$salt = $config->secret;
			}
			
			$username = trim($username);
			$password = trim($password);
			
			//Verifico che user e password siano validi
			if (!self::validateUsername($username)) {
				return -100;
			}
			if (!self::validatePassword($password)) {
				return -200;
			}
			
			//Controllo duplicati
			$myQuery = "
				SELECT COUNT(*)
				FROM " . DMDatabase::escape(self::$config_table) . "
				WHERE " . DMDatabase::escape(self::$config_usernameField) . " = '" . DMDatabase::escape($username) . "'
			";
			
			if (DMDatabase::loadResult($myQuery) > 0) {
				return -300;
			}
			
			if (!$passwordAlreadyEncrypted) {
				$myPassword = md5($password . $salt);
			} else {
				$myPassword = $password;
			}
			
			//Ok, inserisco
			$myQuery = "
				INSERT INTO " . DMDatabase::escape(self::$config_table) . "
				(" . 
					DMDatabase::escape(self::$config_usernameField) . ", 
					" . DMDatabase::escape(self::$config_passwordField) . "
				) VALUES (
					'" . DMDatabase::escape($username) . "',
					'" . DMDatabase::escape($myPassword) . "'
				)
			";
			
			if (DMDatabase::query($myQuery)) {
				return DMDatabase::getLastInsertId();
			} else {
				return false;
			}
			
		}
		
		/**
			Effettua il login dell'utente
			
			@param string il nome utente
			@param string la password
			@param string il salt da utilizzare, null per default da config
			@param boolean se fare un fake login, cioè non loggare effettivamente l'utente
			@return int l'id dell'utente loggato, oppure false
		**/
		public function loginUser($username, $password, $salt = null, $fakeLogin = false) {
		
			if (!isset($salt)) {
				$config = new DMConfig();
				$salt = $config->secret;
			}
			
			$myQuery = "
				SELECT * 
				FROM " . self::$config_table . "
				WHERE " . self::$config_usernameField . " = '" . DMDatabase::escape($username) . "' 
				AND " . self::$config_passwordField . " = '" . md5($password . $salt) . "' 
			"; 
						
			$user = DMDatabase::loadObject($myQuery);
			if (!$user) {
				return false;
			} else {
				if (!$fakeLogin) {
					self::$user = $user;
				
					self::saveToSession();
				}
				
				return $user->{self::$config_idField};
			}
			
		}
		
		/**
			Effettua il logout dell'utente
		**/
		public function logoutUser() {
		
			self::$user = null;
			self::saveToSession();
			
		}
		
		/**
			Crea una riga sulla tabella #__user_registration
			
			@param object i dati dell'utente
			@param int il numero di ore in cui è valido il token di attivazione
			
			@return string l'activation token
		**/
		public function getActivationToken($userData, $activationTokenValidityHours = 24) {
			
			$config = new DMConfig();			
			$targetTable = $config->user_registration_table;
			
			$activationToken = md5(uniqid());
			$expiration = DMFormat::formatDate(date('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', $activationTokenValidityHours);
			$userDataJson = DMDatabase::escape(json_encode($userData));
			
			$myQuery = "
				INSERT INTO $targetTable (
					activation_token,
					expiration,
					user_data
				) VALUES (
					'$activationToken',
					'$expiration',
					'$userDataJson'
				)
			";
			
			if (!DMDatabase::query($myQuery)) {
				return false;
			} else {
				return $activationToken;
			}
			
		}
		
		/**
			Scambia un activation token con i dati salvati sulla tabella di preregistrazione
			
			@param string l'activation token
			@param boolean se cancellare la riga dopo la lettura (default true)
			
			@return i dati dell'utente, oppure false in caso di errore
		**/
		public function exchangeActivationToken($activationToken, $clearAfter = true) {
			
			$config = new DMConfig();			
			$targetTable = $config->user_registration_table;
			
			$myQuery = "SELECT * FROM $targetTable WHERE activation_token = '" . DMDatabase::escape($activationToken) . "'";
			
			$result = DMDatabase::loadObject($myQuery);
			
			if (!$result) {
				return false;
			} 
			
			$userData = $result->user_data;
			
			if ($clearAfter) {
				$myQuery = "DELETE FROM $targetTable WHERE activation_token = '" . DMDatabase::escape($activationToken) . "'";
				DMDatabase::query($myQuery);
			}
			
			return json_decode($userData);
			
		}
		
		/**
			Fa il purge della tabella di preregistrazione
		**/
		public function purgeActivationTokens() {
			
			$config = new DMConfig();			
			$targetTable = $config->user_registration_table;
			
			$myQuery = "DELETE FROM $targetTable WHERE expiration <= '" . date('Y-m-d H:i:s') . "'";
			return DMDatabase::query($myQuery);
			
		}
		
	}
	
	/**
		TableException
		
		@package DMController
		@author DM Digital SRL		
	*/
	class DMUserException extends DMException {
	
		public $backtrace;
		
		public function __construct($message = false, $code = false) {
		
			if (!$message) {
				$message = "Generic user error";
			}
			if (!$code) {
				$code = -100;
			}
		
			parent::__construct($message, $code);
		}
	}
	
	
?>