<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Controller di base
		
		@package DMDocument
				
	*/
	class DMDocument {
	
		protected static $document = null;
		
		private $_doctype = '<!DOCTYPE html>';
		private $_lang = 'en-US';
		private $_dir = 'ltr';
		private $_base = '';
		private $_contentType = 'text/html; charset=utf-8';
		private $_title = '';
		
		private $_css = null;
		private $_js = null;
		private $_meta = null;
		
		private $_content = '';
		
		private $_template = null;
		
		/**
			Crea l'instanza
		**/
		function __construct() {
			
			$this->_css = array();
			$this->_js = array();
			$this->_meta = array();
			
			$config = new DMConfig();
			$this->_base = $config->base_url;
			
			return $this;
			
		}
	
		/**
			Ottiene l'instanza corrent
			
			@return l'instanza corrent
		**/
		public static function getInstance() {
		
			if (!isset(self::$document)) {
				self::$document = new DMDocument();
			}
			
			return self::$document;
			
		}
		
		/**
			Imposta il template
			
			@param string il nome del template
		**/
		public function setTemplate($templateName) {
		
			$this->_template = $templateName;
			
			$templateHeaderPath = DM_APP_PATH . DS . 'templates' . DS . $this->_template . DS . 'head.php';
			if (file_exists($templateHeaderPath)) {
				require_once($templateHeaderPath);
			}
		
		}
		
		/**
			Imposta la URL base
			
			@param string la URL base
		**/
		public function setBase($baseUrl) {
		
			$this->_base = $baseUrl;
		
		}
		
		/**
			Imposta il titolo della pagina
			
			@param string il nuovo titolo
		**/
		public function setTitle($title) {
		
			$this->_title = $title;
		
		}
		
		/**
			Imposta il content type
			
			@param string il content type
		**/
		public function setContentType($contentType) {
		
			$this->_contentType = $contentType;
		
		}
		
		/**
			Imposta la lingua e la direzione di lettura
			
			@param string la lingua
			@param string la direzione, ltr o rtl
		**/
		public function setLang($lang, $dir) {
		
			$this->_lang = $lang;
			$this->_dir = $dir;
		
		}
		
		/**
			Imposta il doctype
			
			@param string il doctype
		**/
		public function setDoctype($doctype) {
		
			$this->_doctype = $doctype;
		
		}
		
				
		/**
			Ritorna il titolo della pagina
			
			@return string il titolo della pagina
		**/
		public function getTitle() {
		
			return $this->_title;
		
		}
		
		/**
			Funzione privata per l'output di una riga con ritorno a capo
			
			@param string la linea da buttare fuori
		**/
		private function outputLine($line) {
			echo $line . "\n";
		}
		
		/**
			Effettua il rendering dell'head
		**/
		private function renderHead() {
		
			$this->outputLine('<head>');
			
			if ($this->_title != '') {
				$this->outputLine('	<title>' . $this->_title . '</title>');
			}
			
			$this->outputLine('	<base href="' . $this->_base . '" />');
			
			$this->outputLine('	<meta http-equiv="content-type" content="' . $this->_contentType . '" />');
			
			//Meta
			foreach ($this->_meta as $meta) {
				$this->outputLine('	<meta name="' . $meta->name . '" content="' . $meta->content . '" />');
			}
			
			//CSS
			foreach ($this->_css as $css) {
				$this->outputLine("	" . $css);
			}
			
			//JS
			foreach ($this->_js as $js) {
				$this->outputLine("	" . $js);
			}
			
			$this->outputLine('</head>');
		
		}
		
		/**
			Effettua il rendering del body
		**/
		private function renderBody() {
		
			$this->outputLine('<body>');
			
			if (isset($this->_template)) {
				$templatePath = DM_APP_PATH . DS . 'templates' . DS . $this->_template . DS . 'body.php';
				
				if (file_exists($templatePath)) {
					require_once($templatePath);
				} else {
					throw new DMDocumentException('Template file missing');
					return false;
				}
			} else {
				$this->outputLine($this->_content);
			}
			
			$this->outputLine('</body>');
		
		}
		
		/**
			Effettua il rendering del documento
		**/
		public function render() {
			
			$this->_content = ob_get_contents();
			ob_clean();
			
			$this->outputLine($this->_doctype);
			$this->outputLine('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $this->_lang . '" lang="' . $this->_lang . '" dir="' . $this->_dir . '">');
			
			$this->renderHead();
			
			$this->renderBody();
			
			$this->outputLine('</html>');
			
		}
		
		/**
			Manda in output il componente principale
		**/
		public function displayComponent() {
		
			$this->outputLine($this->_content);
			
		}
		
		/**
			Manda in output il modulo selezionato
			@param string il nome del modulo
		**/
		public function displayModule($moduleName) {
		
			$modulePath = DM_APP_PATH . DS . 'modules' . DS . $moduleName . DS . $moduleName . '.php';
			if (file_exists($modulePath)) {
				require($modulePath);
			} else {
				throw new DMDocumentException('Module not found');
			}
			
			
		}
		
		/**
			Manda in output i system messages
		**/
		public function displaySystemMessages() {
		
			$systemMessage = DMInput::getString('systemMessage', '');
			$systemMessageType = DMInput::getString('systemMessageType', 'MESSAGE');
			
			if ($systemMessage != '') {
				if ($systemMessageType === 'ERROR') {
					$alertClass = 'alert-error';
				} else if ($systemMessageType === 'WARNING') {
					$alertClass = '';
				} else if ($systemMessageType === 'SUCCESS') {
					$alertClass = 'alert-success';
				} else {
					$alertClass = 'alert-info';
				}
				
				$alertHtml = '<div class="alert ' . $alertClass . '"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>' . DMLang::_($systemMessageType) . '</strong> ' . DMLang::_($systemMessage) . '</div>';
				
				$this->outputLine($alertHtml);
				
			}
			
		}
		
		/**
			Aggiunge un meta, sostituendo l'eventuale doppione
			
			@param string il nome del meta
			@param string il contenuto del meta
		**/
		public function addMeta($name, $content) {
		
			$newMeta = new StdClass();
			$newMeta->name = $name;
			$newMeta->content = $content;
			
			foreach ($this->_meta as $meta) {
				if ($meta->name == $name) {
					$meta->content = $content;
					return;
				}
			}
			
			$this->_meta[] = $newMeta;
			
		}
		
		/**
			Aggiunge un file CSS al documento
			
			@param string l'URL del CSS
		**/
		public function addCSS($source, $media = null) {
		
			$cssString = '<link rel="stylesheet" href="' . $source . '" type="text/css"';
			if (isset($media)) {
				$cssString .= ' media="' . $media . '"';
			}
			
			$cssString .= ' />';
			
			$this->_css[] = $cssString;
			
		}
		
		/**
			Aggiunge un file JS al documento
			
			@param string l'URL del JS
		**/
		public function addJS($source) {
		
			$this->_js[] = '<script type="text/javascript" src="' . $source . '"></script>';
			
		}
	
		/**
			Aggiunge il css relativo alla view scelta (si deve trovare nella cartella della view dentro alla sottocartella css, con il nome della view stessa
			
			@param string la view di cui caricare il CSS
		**/
		public function addViewCSS($viewName, $media = null) {
		
			$source = DMURL::getCurrentBaseUrl() . 'views/' . $viewName . '/css/' . $viewName . '.css';
			return $this->addCSS($source, $media);
			
		}
		
		/**
			Aggiunge il js relativo alla view scelta (si deve trovare nella cartella della view dentro alla sottocartella js, con il nome della view stessa
			
			@param string la view di cui caricare il JS
		**/
		public function addViewJS($viewName) {
		
			$source = DMURL::getCurrentBaseUrl() . 'views/' . $viewName . '/js/' . $viewName . '.js';
			return $this->addJS($source);
			
		}
	
		/**
			Aggiunge il css relativo al template attivo
			
			@param string la view di cui caricare il CSS
		**/
		public function addTemplateCSS($cssFilename, $media = null) {
		
			$source = $this->getTemplateUrl() . '/' . $cssFilename;
			return $this->addCSS($source, $media);
			
		}
		
		/**
			Aggiunge il js relativo alla view scelta (si deve trovare nella cartella della view dentro alla sottocartella js, con il nome della view stessa
			
			@param string la view di cui caricare il JS
		**/
		public function addTemplateJS($jsFilename) {
		
			$source = $this->getTemplateUrl() . '/' . $jsFilename;
			return $this->addJS($source);
			
		}
		
		/**
			Restituisce la URL di base del template
			
			@return string la URL di base del template
		**/
		public function getTemplateUrl() {
			
			return DMURL::getCurrentBaseUrl() . 'templates/' . $this->_template;
			
		}
		
	}
	
	/**
		DocumentException
		
		@package DMDocument
		@author DM Digital SRL		
	*/
	class DMDocumentException extends DMException {
	
		public $backtrace;
		
		public function __construct($message = false, $code = false) {
		
			if (!$message) {
				$message = "Generic document error";
			}
			if (!$code) {
				$code = -100;
			}
		
			parent::__construct($message, $code);
		}
	}
?>