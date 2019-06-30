<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DefaultController extends DMController {
		
		function display() {
		
			DMInput::set('view', 'main');
			parent::display();
			
		}
		
		function loadScript() {
			
			$scriptName = DMInput::getFileName('scriptName', '');
			$scriptType = DMInput::getFileName('scriptType', 'js');
			
			$scriptPath = DM_APP_PATH . DS . 'scripts' . DS . $scriptName . '.' . $scriptType;
			
			if (@file_exists($scriptPath)) {
				require_once($scriptPath);
			}
			
			exit;
			
		}
		
	}
?>