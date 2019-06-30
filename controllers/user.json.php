<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class UserJsonController extends DMJsonController {
	
		/**
			Ottiene la lista degli utenti
		**/
		function jsonGetUsers() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'userhelper.php');
			$users = FHUserHelper::getUsers();
			
			parent::outputResult(count($users), $users, 'users');
			
		}
		
		/**
			Ottiene i dettagli di un utente
		**/
		function jsonLoadUser() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			$userId = DMInput::getInt('userId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'userhelper.php');
			$user = FHUserHelper::loadUser($userId);
			
			if (!$user) {
				parent::outputError(-300);
			}
			
			if (DMInput::getInt('getGroups', -1)) {
				$user->groups = DMAcl::getAclUserGroups($userId);
				$user->availableGroups = DMAcl::getAclGroups();
			}
			
			parent::outputResult(0, $user, 'user');
			
		}
		
		/**
			Ottiene la lista dei gruppi disponibili
		**/
		function jsonGetGroups() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			$groups = DMAcl::getAclGroups();
			
			parent::outputResult(0, $groups, 'groups');
			
		}
		
		/**
			Salva l'utente
		**/
		function jsonSave() {
		
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				parent::outputError(-110);
			}
			
			$userId = DMInput::getInt('userId', -1);
			$myUser = DMTable::getInstance('User');
			
			if ($userId > 0) {
				if (!$myUser->load($userId)) {
					parent::outputError(-300);
				}
			}
			
			$myUser->username = DMInput::getString('username', $myUser->username);
			$myUser->name = DMInput::getString('name', $myUser->name);
			
			$newPassword = DMInput::getString('password', '');
			if ($newPassword != '') {
				if (!$myUser->setPassword($newPassword)) {
					parent::outputError(-1000, 'Password non valida');
				}
			}
			
			if (!$myUser->store()) {
				parent::outputError(-200);
			}
			
			//Processo i gruppi di appartenenza
			$availableGroups = DMAcl::getAclGroups();
			$userGroups = array();
			foreach ($availableGroups as $availableGroup) {
				if (DMInput::getInt('group_' . $availableGroup->group_id, 0)) { 
					$userGroups[] = $availableGroup->group_id;
				}
			}
			
			DMAcl::setAclUserGroups($myUser->user_id, $userGroups);
			
			parent::outputResult($myUser->user_id);
			
		}
		
		/**
			Elimina l'utente
		**/
		function jsonDeleteUser() {
			
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				parent::outputError(-110);
			}
			
			$userId = DMInput::getInt('userId', -1);
			$myUser = DMTable::getInstance('User');
			
			if ($userId > 0) {
				if (!$myUser->load($userId)) {
					parent::outputError(-300);
				}
			}
			
			if (!$myUser->delete()) {
				parent::outputError(-400);
			}
			
			parent::outputResult(0);
			
		}
		
	}
?>