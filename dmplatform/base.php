<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	/**
		Classe di base della DM Platform
		
		@package DMBase
				
	*/
	class DMBase {
	
		/**
			Carica un modulo dall'opportuna cartella dmplatform/modules
			
			@param string
			@return boolean true se il modulo esiste, altrimenti false
		**/
		public function loadModule($moduleName) { 
		
			$moduleName = strtolower($moduleName);
			
			if (file_exists(DM_PLATFORM_PATH . DS . 'modules' . DS . $moduleName . DS . $moduleName . '.php')) {
				require_once(DM_PLATFORM_PATH . DS . 'modules' . DS . $moduleName . DS . $moduleName . '.php');
				
				return true;
			} else {
				return false;
			}
		}
		
	}
	
?>