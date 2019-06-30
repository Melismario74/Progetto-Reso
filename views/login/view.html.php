<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('login');
	$document->addViewJS('login');
	
	class LoginView extends DMView {
	
		function display() {
		
			if (DMUser::getUserId() > 0) {
				DMUrl::redirect('index.php', JText::_("ALREADY_LOGGED"));
			}
				
			parent::display();
			
		}
		
	}

?>