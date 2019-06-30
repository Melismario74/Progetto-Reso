<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Controller di base
		
		@package DMController
				
	*/
	class DMController {
		
		/**
			Esegue il task richiesto sul controller
			
			@param string
			@return il risultato del task sul controller
		**/
		public function execute($task = null) {
		
			if (!isset($task)) {
				$task = "display";
			}
			return $this->$task();
			
		}
		
		/**
			Visualizza la view specificata nella richiesta HTTP (o nel parametro della funzione)
			
			@param string nome della view da visualizzare
		**/
		public function display($view = null) {
		
			if (!isset($view)) {
				$view = DMInput::getFileName('view' , false);
			}
				
			if (!$view) {
				throw new DMControllerException("View " . $view . " does not exists", -200);
			}
			
			//Includo la view
			$viewPath = DM_APP_PATH . DS . 'views' . DS . $view . DS . 'view.html.php';
			if (!file_exists($viewPath)) {
				throw new DMControllerException("View file for view " . $view . " does not exists", -200);
			}
			
			require_once($viewPath);
			
			//Istanzio la classe
			$viewClass = ucfirst($view) . 'View';
			if (!class_exists($viewClass)) {
				throw new DMControllerException("View class " . $viewClass . " does not exists", -200);
			}
			
			$tpl = DMInput::getFileName("tpl", '');
			
			$viewInstance = new $viewClass();
			return $viewInstance->display($tpl);
		}
		
	}
	
	/**
		Controller JSON di base
		
		@package DMController
				
	*/
	class DMJsonController extends DMController {
	
		/**
			Annullo ogni richiesta display
		**/
		public function display($view = null) {
		
			return false;
			
		}
		
		/**
			Manda in output un json di errore
			
			@param int il codice di errore
			@param string l'eventuale stringa da forzare come descrizione
		**/
		public function outputError($code, $description = '') {
			
			if ($description == '') {
				$description = DMError::getErrorDescription($code);
			}
			
			$output = array();
			$output['result'] = $code;
			$output['description'] = $description;
			
			echo json_encode($output);
			exit;
			
		}
		
		/**
			Manda in output un risultato valido
			
			@param int il codice di successo da utilizzare,
			@param variant i dati da inserire nel campo data
			@param string il nome da dare al campo data
		**/
		public function outputResult($code = 0, $data = false, $dataName = 'data', $extras = array()) {
			
			$output = $extras;
			$output['result'] = $code;
			if ($data) {
				$output[$dataName] = $data;
			}
			
			echo json_encode($output);
			exit;
			
		}
		
	}
	
	/**
		ControllerException
		
		@package DMController
				
	*/
	class DMControllerException extends DMException {
	
		public $backtrace;
		
		public function __construct($message = false, $code = false) {
		
			if (!$message) {
				$message = "Generic controller error";
			}
			if (!$code) {
				$code = -100;
			}
		
			parent::__construct($message, $code);
		}
	}
?>