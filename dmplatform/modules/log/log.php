<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Classe per utilizzo LOG
		
		@package DMLog
		@author DM Digital SRL		
	*/
	class DMLog {
		
		/**
			Logga il messaggio nel file di log caratterizzato dal nome target. I log finiscono su DM_LOG_PATH
			
			@param string il nome del file da utilizzare
			@param string il messaggio da scrivere nel log
			@return boolean il risultato della scrittura
		**/
		public function log($target, $message) {
	
			require_once(DM_PLATFORM_PATH . DS . "modules" . DS . "log" . DS . "libraries" . DS . "logging.php");
			
			if (defined("DM_LOG_PATH")) {
				$filePath = DM_LOG_PATH;
			} else {
				$filePath = DM_PLATFORM_PATH . DS . '..' . DS . "logs";
			}
	
			$logger = new Logging();
			$logger->lfile($filePath . DS . $target . '.log');
			return $logger->lwrite($message);
			
		}
	
	}
?>