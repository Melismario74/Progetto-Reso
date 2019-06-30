<?php
	
	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		View di base
		
		@package DMView
			
	*/
	class DMView {
	
		private $name;
		
		function __construct() {
			
			$this->name = strtolower(preg_replace('/(.*)View/', '$1', get_class($this)));
			
		}
	
		/**
			Visualizza la view utilizzando il template fornito
			
			@param string template da usare
			@return boolean true
		**/
		function display($tpl = null) {
		
			$templateName = "default";
			if ($tpl != null) {
				$templateName = $templateName . '_' . $tpl;
			}
			$templateName .= '.php'; 
			
			$templatePath = DM_APP_PATH . DS . 'views' . DS . $this->name . DS . 'tmpl' . DS . $templateName;
			
			if (!file_exists($templatePath)) {
				throw new DMViewException("Template $templateName not found ($templatePath) ", -200);
			}
			
			require($templatePath);
		
			return true;
		}	
		
	}
	
	/**
		ViewException
		
		@package DMView
				
	*/
	class DMViewException extends DMException {
	
		public $backtrace;
		
		public function __construct($message = false, $code = false) {
		
			if (!$message) {
				$message = "Generic view error";
			}
			if (!$code) {
				$code = -100;
			}
		
			parent::__construct($message, $code);
		}
	}
	
?>