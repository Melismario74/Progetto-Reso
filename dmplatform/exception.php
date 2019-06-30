<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Eccezione customizzata
		
		@package DMException
		@author DM Digital SRL		
	*/
	class DMException extends Exception {
	
		function __construct($message, $code) {
			
			$this->backtrace = debug_backtrace();
			
			parent::__construct($message, $code);
		}
		
	}
		
?>