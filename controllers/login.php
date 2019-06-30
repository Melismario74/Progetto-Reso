<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class LoginController extends DMController {
	
		function display() {
			
			if (!DMInput::getString('view', false)) {
				DMInput::set('view', 'login');
			}
			
			return parent::display();
			
		}
		
		function login() {
			
			$username = DMInput::getString('username');
			$password = DMInput::getString('password');
			
			if (!DMUser::loginUser($username, $password)) {
				DMUrl::redirect('index.php', DMLang::_("LOGIN_FAILED"), 'ERROR');
			} else {
				DMUrl::redirect('index.php', DMLang::_("LOGIN_OK"), 'SUCCESS');
			}
			
		}
		
		function logout() {
		
			DMUser::logoutUser();
			
			DMUrl::redirect('index.php');
			
		}
		
	}
?>