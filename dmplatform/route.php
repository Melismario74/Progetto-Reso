<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Gestione routing
		
		@package DMRoute
		@author DM Digital SRL		
	*/
	class DMRoute {
		
		/**
			Gestisce la richiesta girandola al controller opportuno
		**/
		public function routeRequest() {
			
			$controllerName = DMInput::getFileName('controller', 'default');
			$requestType = DMInput::getFileName('type', false);
			
			if ($controllerName != '') {
				if ($requestType) {
					$controllerPath = "controllers" . DS . $controllerName . "." . $requestType . ".php";
				} else {
					$controllerPath = "controllers" . DS . $controllerName . ".php";
				}
				
				if (file_exists($controllerPath)) {
					require_once($controllerPath);
					
					if ($requestType) {
						$controllerClass = ucfirst($controllerName) . 'JsonController';
					} else {
						$controllerClass = ucfirst($controllerName) . 'Controller';
					}
					
					$controller = new $controllerClass();
					
					$task = DMInput::getString('task', 'display');
					$controller->execute($task);
				} else {
					throw new DMException("Controller " . $controllerName . " does not exist");
				}
			}
			
		}	
		
		/**
			Trasforma la url in input in una URL SEF, aggiungendo in testa anche l'host necessario (URL base)
			@param string la url da trasformare
			@return string la url SEF
		**/
		public function _($sourceUrl) {
		
			//Verifico l'esistenza di un router nell'app
			$routerPath = DM_APP_PATH . DS . 'router.php';
			if (!file_exists($routerPath)) {
				return $sourceUrl;
			}
			
			require_once($routerPath);
			if (!class_exists('DMAppRouter')) {
				return $sourceUrl;
			}
			
			//Ottengo un array (data) contenente tutti i parametri della query
			$arr = parse_url($sourceUrl);
			$parameters = $arr["query"];
			parse_str($parameters, $data);
		
			$segments = DMAppRouter::buildUrl($data);
			
			$resultUrl = DMUrl::getCurrentBaseUrl() . implode('/', $segments);
			
			//Se sono rimasti dei parametri, li aggiungo alla fine
			if (count($data) > 0) {
				$resultUrl .= '?';
				$params = array();
				foreach ($data as $key=>$value) {
					$params[] = $key . '=' . $value;
				}
				$resultUrl .= implode('&', $params);
			}
			
			return $resultUrl;
			
		}
		
		/**
			Interpreta la richiesta URL corrente
		**/
		public function parseUrl() {
			
			//Verifico l'esistenza di un router nell'app
			$routerPath = DM_APP_PATH . DS . 'router.php';
			if (!file_exists($routerPath)) {
				return;
			}
			
			require_once($routerPath);
			if (!class_exists('DMAppRouter')) {
				return;
			}
			
			$currentUrl = DMUrl::getCurrentUrl();
			
			//Rimuovo la parte di URL che è in realtà la mia base
			$dmConfig = new DMConfig();
			$currentUrl = str_replace($dmConfig->base_url, '', $currentUrl);
			
			//Ottengo l'array dei pezzi
			$urlPieces = explode('/', $currentUrl);
			
			DMAppRouter::parseUrl($urlPieces);
			
			return;
			
		}
		
		
	}
	
	DMError::init();
	
?>