<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHHelper {
		
		static function loggedOnly() {
			
			if (DMUser::getUserId() < 0) {
				DMUrl::redirect('index.php', JText::_("YOU_NEED_TO_LOGIN"), 'ERROR');
			}
			
		}
		
		static function log($target, $message) {
	    
	    	if (FH_LOG_ENABLED) {
	    		DMLog::log($target, $message);
	    	}
		}
		
	}
	
?>