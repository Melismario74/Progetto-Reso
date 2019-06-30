<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	DMBase::loadModule('log');

	/**
		Classe per accesso a API esterne e di supporto per lo sviluppo di API interne
		
		@package DMAPI
		@author DM Digital SRL		
	*/
	class DMAPI {
	
		/**
			Chiama l'API richiesta e restituisce il risultato sotto forma di oggetto
			
			@param string il punto di accesso
			@param array i parametri con cui chiamare l'API
			@return object l'oggetto contenente il risultato
		**/
		public function callAPI($endpoint, $params, $debug = false) {
		
			if (!defined("DM_API_SHAREDSECRET")) {
				define("DM_API_SHAREDSECRET", "dmd");
			}
		
			$myRequest = $endpoint;
			
			$params['signature'] = md5($endpoint . $params['signature'] . DM_API_SHAREDSECRET);
			
			$requestParams = array();
			foreach ($params as $paramName => $paramValue) {
				$requestParam = $paramName . '=' . urlencode($paramValue);
				$requestParams[] = $requestParam;
			}
			
			$postData = implode('&', $requestParams); 
			
			if ($debug) {
				echo $myRequest . '?' . $postData . '<br />';
			}
			
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$myRequest);
			curl_setopt($ch,CURLOPT_POST,count($requestParams));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			
			$requestResults = curl_exec($ch);
			
			curl_close($ch);
		
			$myResult = json_decode($requestResults);
			
			if ($myResult->result != 'OK') {
				DMLog::log('api', 'Error calling: ' . $myRequest . '?' . $postData . '), returned: ' . $requestResults);
			} else {
				
			}
			
			return $myResult;
			
		}
	
		/**
			Restituisce un resultArray completo di errore e descrizione
			
			@param int l'errore da ritornare
			@return array di errore compatibile con DMAPI
		**/
		public function getResultArrayFromErrorCode($errorCode) {
			
			$resultArray = array();
			
			if ($errorCode == 0) {				
				$resultArray['result'] = 'OK';
			} else if ($errorCode < 0) {				
				$resultArray['result'] = 'ERROR';
				$resultArray['errorCode'] = $errorCode;
				$resultArray['errorDescription'] = DMError::getErrorDescription($errorCode, 'Unknown error');
			}
			
			return $resultArray;
			
		}
		
		/**
			Ritorna in output il resultArray ed esce
			
			@param array i dati da encodare in json
		**/
		public function outputResult($resultArray) {
		
			echo json_encode($resultArray);
			exit;
			
		}
		
		/**
			Prepara il resultArray in caso di errore
			
			@param int l'errore da ritornare
			@param boolean determina se ritornare un errore HTTP oppure no
			@param string l'errore HTTP da ritornare (default 404)
		**/
		public function outputErrorCode($errorCode, $httpError = false, $httpErrorCode = '404 Not found') {
		
			$resultArray = self::getResultArrayFromErrorCode($errorCode);
			
			if ($httpError) {
				header('HTTP/1.0 ' . $httpErrorCode);
			}
			
			self::outputResult($resultArray);
			
		}
		
		/**
			Valida la signature fornita nella richiesta rispetto all'api corrente
			
			@param string la signature da validare
			@return int 0 se OK, -20 se errore
		**/
		public function validateSignature($mySignature) { 
		
			return 0;
			$api = DMInput::getString('api', '');
			
			$signature = md5($api . $mySignature . DM_API_SHAREDSECRET);
			
			if (DMInput::getString('signature') != $signature) { 
				return -20;
			} else {
				return 0;
			}
			
		}
	}
?>