<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Print support
		
		@package DMPrint
		@author DM Digital SRL		
	*/
	class DMPrint {
	
		/**
			Carica il javascript necessario al funzionamento del sistema print
		**/
		public static function init() {
		
			$document =& DMDocument::getInstance();
			$document->addJS(DMUrl::getCurrentBaseUrl() . 'dmplatform/modules/print/print.js');
			
			self::processRequest();
			
		}
			
		/**
			Controllo la richiesta e eseguo eventuali stampe
			- mode deve essere "print"
		**/
		private static function processRequest() {
			
			$mode = DMInput::getString('mode', '');
			
			if ($mode !== 'print') {
				return false;
			}
			
			//Cerco di instanziare la classe giusta
			$view = DMInput::getFileName('view', '');
			$printClassPath = DM_APP_PATH . DS . 'views' . DS . $view . DS . 'print.php';
			if (!file_exists($printClassPath)) {
				throw new DMPrintException("Missing print class file");
			}
			
			$printClass = "DMPrint" . ucfirst($view);
			if (!class_exists($printClass)) {
				throw new DMPrintException("Print class not found");
			}
			
			$paperOrientation = DMInput::getString('paperOrientation', "portrait");
			$output = DMInput::getString('output', "pdf");
			
			$myPrint = new $printClass($paper, $output);
			$myPrint->getInput();
			
			$template = DMInput::getFileName('template', 'default');
			$title = DMInput::getString('title', 'Print');
			
			$resultArray = $myPrint->execPrint($view, $template, $title);
			
			echo json_encode($resultArray);
			
			exit;
			
		}
		
	}
	
	/**
		Classe modello per le classi di stampa
	**/
	class DMPrintClass {
	
		private $paperOrientation = "portrait";
		private $output = "pdf";
		private $title = "Print";
		private $format = "A4";
		
		function __construct($paperOrientation = "portrait", $output = "pdf", $title = "Print", $format = "A4") {
			
			$this->paperOrientation = $paperOrientation;
			$this->output = $output;
			$this->title = $title;
			$this->format = $format;
			
		}
		
		/**
			Esegue la stampa, ritornando un array contentente result e printUrl. Utilizza la cartella $DM_APP_PATH/temp/print/
			
			@param string la view da utilizzare
			@param string il template da utilizzare
			@return array (int result, string printUrl)
		**/
		function execPrint($view, $template) {
		
			$templatePath = DM_APP_PATH . DS . 'views' . DS . $view . DS . 'print' . DS . $template . '.php';
			
			if (!file_exists($templatePath)) {
				throw new DMPrintException("Missing print template file");
			}
			
			ob_start();
			require($templatePath);
			$rawDocument = ob_get_clean();
		
			if ($this->output === "pdf") {
				
				require_once(DM_PLATFORM_PATH . DS . 'modules' . DS . 'print' . DS . 'libraries' . DS . 'dompdf' . DS . 'dompdf_config.inc.php');
				
				$dompdf = new DOMPDF();
				$dompdf->set_paper($this->format, $this->paperOrientation);
				$dompdf->load_html($rawDocument);
				$dompdf->render();
				
				$documentContent = $dompdf->output();
				$documentExtension = ".pdf";
				
			} else if ($this->output === "html") {
			
				$documentContent = $rawDocument;
				$documentExtension = ".html";
				
			}
			
			$targetFileName = DMFormat::cleanString($this->title) . '_' . uniqid() . $documentExtension;
			$targetFilePath = DM_APP_PATH . DS . 'temp' . DS . 'print' . DS . $targetFileName;
			$targetFileUrl = DMUrl::getCurrentBaseUrl() . 'temp/print/' . $targetFileName;
			
			if (!file_put_contents($targetFilePath, $documentContent)) {
				throw new DMPrintException("Cannot save printed document");
			}
			
			$resultArray = array();
			$resultArray['result'] = 0;
			$resultArray['printUrl'] = $targetFileUrl;
			
			return $resultArray;
			
		}
	
	}
	
	/**
		PrintException
		
		@package DMPrint
		@author DM Digital SRL		
	*/
	class DMPrintException extends DMException {
	
		public $backtrace;
		
		public function __construct($message = false, $code = false) {
		
			if (!$message) {
				$message = "Generic print error";
			}
			if (!$code) {
				$code = -100;
			}
		
			parent::__construct($message, $code);
		}
	}
?>