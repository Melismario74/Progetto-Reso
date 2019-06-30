<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class VectorJsonController extends DMJsonController {
	
		/**
			Ottiene la lista degli vettori
		**/
		function jsonGetVectors() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'vectorhelper.php');
			$vectors = FHVectorHelper::getVectors();
			
			parent::outputResult(count($vectors), $vectors, 'vectors');
			
		}
		
		/**
			Ottiene i dettagli di un vettore
		**/
		function jsonLoadVector() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-110);
			}
			
			$vectorId = DMInput::getInt('vectorId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'vectorhelper.php');
			$vector = FHVectorHelper::loadVector($vectorId);
			
			if (!$vector) {
				parent::outputError(-300);
			}	
			
			parent::outputResult(0, $vector, 'vector');
			
		}
		
		
		
		/**
			Salva l'vettore
		**/
		function jsonSave() {
		
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				parent::outputError(-110);
			}
			
			$vectorId = DMInput::getInt('vectorId', -1);
			$myVector = DMTable::getInstance('Vector');
			
			if ($vectorId > 0) {
				if (!$myVector->load($vectorId)) {
					parent::outputError(-300);
				}
			}
			$myVector->name = DMInput::getString('name', $myVector->name);
			
			if (!$myVector->store()) {
				parent::outputError(-200);
			}
			
			parent::outputResult($myVector->vector_id);
			
		}
		
		/**
			Elimina l'vettore
		**/
		function jsonDeleteVector() {
			
			if (!DMAcl::checkPrivilege('FH_USERS')) {
				parent::outputError(-110);
			}
			
			$vectorId = DMInput::getInt('vectorId', -1);
			$myVector = DMTable::getInstance('Vector');
			
			if ($vectorId > 0) {
				if (!$myVector->load($vectorId)) {
					parent::outputError(-300);
				}
			}
			
			if (!$myVector->delete()) {
				parent::outputError(-400);
			}
			
			parent::outputResult(0);
			
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
	}
?>