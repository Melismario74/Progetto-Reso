<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class ChargelistJsonController extends DMJsonController {
	
		/**
			Ottiene la lista delle liste di carico disponibili sull'FTP
			
			@return array i nomi delle liste di carico
		**/
		function jsonGetChargelistFTPlist() {
			
			if (!DMUser::getUser()) {
				parent::outputError(-100); 
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'chargelisthelper.php');
			$chargelists = FHChargelistHelper::getChargelistFTPlist();
			
			if (!$chargelists) {
				parent::outputError(-300);
			}
			
			parent::outputResult(count($chargelists), $chargelists, 'chargelists');
			
		}
		
		/**
			Importa la lista indicata
		**/
		function jsonImportFTPChargelist() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege("FH_CHARGELIST_IMPORT")) {
				parent::outputError(-110);
			}
			
			$chargelistName = DMInput::getString('name');
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'chargelisthelper.php');
			$chargelistPath = FHChargelistHelper::downloadFTPChargelist($chargelistName);
			
			if (!$chargelistPath) {
				parent::outputError(-310);
			}
			
			if (!FHChargelistHelper::importChargelist($chargelistPath)) {
				parent::outputError(-200);
			}
			
			parent::outputResult(0);
			
		}
		
		/**
			Restituisce la lista delle liste di carico da DB
		**/
		function jsonGetChargelists() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege("FH_CHARGELISTS")) {
				parent::outputError(-110);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'chargelisthelper.php');
			
			$searchParams = array();
			$searchParams['archived'] = DMInput::getInt('archived', -1);
			$chargelists = FHChargelistHelper::getChargelists($searchParams);
			
			foreach ($chargelists as $chargelist) {
				$chargelist->chargelist_date_str = DMFormat::formatDate($chargelist->chargelist_date, 'd/m/Y', 'Y-m-d');
				if ($chargelist->archived) {
					$chargelist->archived_str = 'Si';
				} else {
					$chargelist->archived_str = 'No';
				}
			}
			
			parent::outputResult(count($chargelists), $chargelists, 'chargelists');
		
		}
		
		/**
			Restituisco la lista richiesta
		**/
		function jsonLoadChargelist() {
		
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'chargelisthelper.php');
			$chargelistId = DMInput::getInt('chargelistId', -1);

			
			$chargelist = FHChargelistHelper::loadChargelist($chargelistId);
			
			if (!$chargelist) {
				parent::outputError(-300);
			}
			
			$chargelist->chargelist_date_str = DMFormat::formatDate($chargelist->chargelist_date, 'd/m/Y', 'Y-m-d');
			
			if (DMInput::getInt('getRows', 0)) {
				$chargelist->rows = FHChargelistHelper::loadChargelistRows($chargelistId);				
			}
			
			parent::outputResult(1, $chargelist, 'chargelist');
			
		}
		
		function archiveChargelist() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege("FH_CHARGELIST_IMPORT")) {
				parent::outputError(-110);
			}
			
			$chargelistId = DMInput::getInt('chargelistId');
			$archived = DMInput::getInt('archived', 0);
			
			$myChargelist = DMTable::getInstance('Chargelist');
			if (!$myChargelist->load($chargelistId)) {
				parent::outputError(-300);				
			}
			
			$myChargelist->archived = $archived;
			if (!$myChargelist->store()) {
				parent::outputError(-200);	
			}
			
			parent::outputResult($chargelistId);
			
		}
	}
?>