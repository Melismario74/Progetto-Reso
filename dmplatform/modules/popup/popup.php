<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Popup support
		
		@package DMPopup
				
	*/
	class DMPopup {
	
		/**
			Carica il javascript necessario al funzionamento del sistema popup
		**/
		public static function init() {
		
			$document =& DMDocument::getInstance();
			$document->addJS(DMUrl::getCurrentBaseUrl() . 'dmplatform/modules/popup/popup.js');
			
			self::processRequest();
			
		}
			
		/**
			Controllo la richiesta e include eventuali popup
			- mode deve essere "popup"
			- task può essere includePopup oppure open
			- in entrambi i casi è necessario indicare popupName
			- open richiede anche il parametro action, che di default è open, e corrisponde alla funzione chiamata sulla classe del popup
		**/
		private static function processRequest() {
			
			$mode = DMInput::getString('mode', '');
			$task = DMInput::getString('task', '');
			
			if ($mode === 'popup') {
			
				if ($task === 'include') {
					
					$popupName = DMInput::getFileName('popupName', '');
					if ($popupName != '') {
						$popupHeaderPath = DM_APP_PATH . DS . 'popups' . DS . $popupName . DS . $popupName . '.header.php';
						
						if (@file_exists($popupHeaderPath)) {
						
							$includeData = new StdClass();
							ob_start();
							require_once($popupHeaderPath);
							$includeData->header = ob_get_clean();
							$includeData->script = DMUrl::getCurrentBaseUrl() . 'popups/'  . $popupName . '/' . $popupName . '.js';
							echo json_encode($includeData);
						} else {
							throw new DMPopupException("Popup $popupName.header does not exists");
						}
						
					} else {
						throw new DMPopupException("Popup name not provided");
					}
					
				} else if ($task === 'open') {
				
					$popupName = DMInput::getFileName('popupName', '');
					if ($popupName != '') {
					
						$action = DMInput::getFileName('action', 'open');
											
						$popupClassPath = DM_APP_PATH . DS . 'popups' . DS . $popupName . DS . $popupName . '.class.php';
						
						if (@file_exists($popupClassPath)) {
							require_once($popupClassPath);
							
							$popupClass = new $popupName();
			
							if (method_exists($popupClass, $action)) {
								$popupClass->$action();
							} else {
								throw new DMPopupException("Popup $popupName.class does not support $action action");
							}
							
						} else {
							throw new DMPopupException("Popup $popupName.class does not exists");
						}
						
					} else {
						throw new DMPopupException("Popup name not provided");
					}
				
				}
				
				exit;
				
			}
			
		}
		
	}
	
	/**
		Classe modello per i popup
	**/
	class DMPopupClass {
	
		/**
			Apre il template base, chiamato 'html' per il popup.
			
			@param string il template da utilizzare, default html
		**/
		function open($template = 'html') { 
			$myName = get_class($this);
			
			$templatePath = DM_APP_PATH . DS . 'popups' . DS . $myName . DS . $myName . '.' . $template . '.php';
			
			if (@file_exists($templatePath)) {
				require_once(DM_APP_PATH . DS . 'popups' . DS . $myName . DS . $myName . '.' . $template . '.php');
			} else {
				throw new DMPopupException("Popup $myName class does not support $template template");
			}
			
			exit;
		}
		
	}
	
	/**
		PopupException
		
		@package DMPopup
				
	*/
	class DMPopupException extends DMException {
	
		public $backtrace;
		
		public function __construct($message = false, $code = false) {
		
			if (!$message) {
				$message = "Generic popup error" ;
			}
			if (!$code) {
				$code = -100;
			}
		
			parent::__construct($message, $code);
		}
	}
?>