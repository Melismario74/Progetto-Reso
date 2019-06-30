<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Classe per utilizzo di OAuth 2.0
		
		@package DMOAuth
		@author DM Digital SRL		
	*/
	class DMOAuth {
		
		private $client;
		private $initialized = false;
		
		private $client_id;
		private $client_secret;
		private $redirect_uri;
		private $authorization_endpoint;
		private $authorization_type = "web_server";
		private $token_endpoint;
		
		private $isDebug = false;
		
		/**
			Inizializza l'oggetto
			
			@param array configurazione (client_id, client_secret, redirect_uri, authorization_endpoint, token_endpoint)
		**/
		public function init($config) {
			
			if (!isset($this->$client)) {
			
				require_once(DM_PLATFORM_PATH . DS . 'modules' . DS . 'oauth' . DS . 'libraries' . DS . 'adoy' . DS . 'Client.php');
				require_once(DM_PLATFORM_PATH . DS . 'modules' . DS . 'oauth' . DS . 'libraries' . DS . 'adoy' . DS . 'GrantType' . DS . 'IGrantType.php');
        		require_once(DM_PLATFORM_PATH . DS . 'modules' . DS . 'oauth' . DS . 'libraries' . DS . 'adoy' . DS . 'GrantType' . DS . 'ClientCredentials.php');
        
				if (isset($config['client_id'])) {
					$this->client_id = $config['client_id'];
				}
				if (isset($config['client_secret'])) {
					$this->client_secret = $config['client_secret'];
				}
				if (isset($config['redirect_uri'])) {
					$this->redirect_uri = $config['redirect_uri'];
				}
				if (isset($config['authorization_endpoint'])) {
					$this->authorization_endpoint = $config['authorization_endpoint'];
				}
				if (isset($config['token_endpoint'])) {
					$this->token_endpoint = $config['token_endpoint'];
				}
				if (isset($config['authorization_type'])) {
					$this->authorization_type = $config['authorization_type'];
				}
				
				$this->client = new OAuth2\Client($this->client_id, $this->client_secret);
				$this->initialized = true;
			}
			
		}
		
		/**
			Inizializza l'oggetto partendo da file json
			
			@param string il path del file di configurazione
		**/
		public function initFromFile($configPath) {
			
			if (@file_exists($configPath)) {
				$myConfigContent = @file_get_contents($configPath);
				
				$config = json_decode($myConfigContent, true);
				
				return $this->init($config);
			} else {
				throw new DMOAuthException("Init file not found: " . $configPath, -404); 
			}
			
		}
		
		/**
			Setta lo stato di debug
			
			@param boolean debug mode
		**/
		function setDebug($isDebug) {
			$this->isDebug = $isDebug;
		}
		
		/**
			Ridirige l'utente alla schermata di autenticazione.
			Presuppone che DMOAuth sia già stato inizializzato
		**/
		public function requestAuthorization($params = array()) {
			
			$redirectUrl = $this->client->getAuthenticationUrl($this->authorization_endpoint, $this->redirect_uri, array_merge($params, array("type" => $this->authorization_type)));
			header("Location: " . $redirectUrl);
			die("Redirect");
			
		}
		
		/**
			Setta l'access token da utilizzare
			
			@param string access token
		**/
		public function setAccessToken($accessToken) {
			$this->client->setAccessToken($accessToken);
		}
		
		/**
			Scambia il codice ricevuto dalla richiesta di autorizzazione con un token
			
			@param string code
			@param array parametri aggiuntivi
		**/
		public function getAccessToken($code, $params = array()) {
			
			$requestParams = array_merge(array("redirect_uri" => $this->redirect_uri, "type" => $this->authorization_type, "code" => $code), $params);
			$response = $this->client->getAccessToken($this->token_endpoint, 'client_credentials', $requestParams);
			
			if ($response['code'] == 200) {
				$this->client->setAccessToken($response['result']['access_token']);
				return $response['result'];
			} else {
				return false;
			}
		}
		
		/**
			Refresha il 
			
			@param string code
			@param array parametri aggiuntivi
		**/
		public function refreshAccessToken($refreshToken, $params = array()) {
			
			$requestParams = array_merge(array("redirect_uri" => $this->redirect_uri, "type" => "refresh", "client_id" => $this->client_id, "client_secret" => $this->client_secret, "refresh_token" => $refreshToken), $params);
			$response = $this->fetch($this->token_endpoint, $requestParams, 'POST');
			
			if ($response['code'] == 200) {
				$this->client->setAccessToken($response['result']['access_token']);
				return $response['result'];
			} else {
				return false;
			}
		}
		
		/**
			Esegue una chiamata remota GET
			
			@param string URL di destinazione
			@param array parametri aggiuntivi
			@param string metodo da utilizzare, default GET
			@param array header http da inviare
		**/
		public function fetch($resourceUrl, $params = array(), $httpMethod = 'GET', $httpHeaders = array()) {
			
			if ($this->isDebug) {
				DMBase::loadModule('Log');
				DMLog::log('oauth', "Fetching: $resourceUrl ($httpMethod) with params " . print_r($params, true));
			}
			return $this->client->fetch($resourceUrl, $params, $httpMethod, $httpHeaders);
			
		}
		
		/**
			Ritorna se l'oggetto è già stato inizializzato oppure no
			
			@return boolean
		**/
		public function isInitialized() {
			
			return $this->initialized;
			
		}		
	
	}
	
	/**
		Factory per oggetti DMOAuth
		
		@package DMOAuth
		@author DM Digital SRL		
	*/
	class DMOAuthFactory {
		
		private static $oauth;
		
		/**
			Ritorna se un oggetto DMOAuth
			
			@return DMOAuth
		**/
		function getInstance() {
			
			if (!isset(self::$oauth)) {
				self::$oauth = new DMOAuth();
			}
			
			return self::$oauth;
			
		}
		
	}
	
	/**
		OAuthException
		
		@package DMOAuth
		@author DM Digital SRL		
	*/
	class DMOAuthException extends DMException {
	
		public $backtrace;
		
		public function __construct($message = false, $code = false) {
		
			if (!$message) {
				$message = "Generic oauth error";
			}
			if (!$code) {
				$code = -100;
			}
		
			parent::__construct($message, $code);
		}
	}
?>