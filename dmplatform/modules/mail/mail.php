<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Classe per utilizzo E-mail
		
		@package DMMail
		@author DM Digital SRL		
	*/
	class DMMail {
		
		/**
			Ottiene l'oggetto che si occupa dell'invio e-mail
		**/
		public function getInstance() {
	
			return JFactory::getMailer();
			
		}
	
	}
?>