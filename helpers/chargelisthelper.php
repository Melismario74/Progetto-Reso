<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHChargelistHelper {
		
		/**
			Ottiene da FTP la lista delle liste di carico presenti
			
			@return array contenente i nomi delle liste di carico trovate
		**/
		static function getChargelistFTPlist() {
			
			$dmConfig = new DMConfig();
			
			$connection = ftp_connect($dmConfig->ftp_host, 21);
			
			if (!$connection) {
				return false;
			}
			
			$login = ftp_login($connection, $dmConfig->ftp_user, $dmConfig->ftp_password);
		
			if (!$login) {
				return false;
			}
			
			$chargelists = ftp_nlist($connection, $dmConfig->ftp_chargelist_base);
			$output = array();
			
			foreach ($chargelists as $chargelist) { 
				$chargelist = str_replace($dmConfig->ftp_chargelist_base . '/', '', $chargelist); 
				if (
					($chargelist != '..') &&
					($chargelist != '.')
				) {
					$output[] = $chargelist;
				}
			}
			
			return $output;
			
		}
		
		/**
			Scarica la chargelist richiesta in uploads
		**/
		static function downloadFTPChargelist($chargelistName) {
		
			$chargelistName = str_replace('..', '', $chargelistName);
			
			$dmConfig = new DMConfig();
			
			$connection = ftp_connect($dmConfig->ftp_host, 21);
			
			if (!$connection) {
				return false;
			}
			
			$login = ftp_login($connection, $dmConfig->ftp_user, $dmConfig->ftp_password);
		
			if (!$login) {
				return false;
			}
			
			$sourcePath = $dmConfig->ftp_chargelist_base . '/' . $chargelistName;
			$targetPath = DM_APP_PATH . DS . 'uploads' . DS . 'chargelists' . DS . $chargelistName;
			
			if (!ftp_get($connection, $targetPath, $sourcePath, FTP_ASCII)) {
				return false;
			} else {
				return $targetPath;
			}
			
		}
		
		/**
			Importa una lista di carico dal path fornito. Controlla l'id della lista di carico, se è un duplicato ritorna falso (non sono supportati gli aggiornamenti)
			
			@param string il path della lista di carico
			@param boolean se verificare il permesso FH_IMPORT_CHARGELIST
		**/
		static function importChargelist($filePath, $checkPermissions = true) {
			
			if ($checkPermissions) {
				if (!DMAcl::checkPrivilege("FH_CHARGELIST_IMPORT")) {
					FHHelper::log('chargelistImport', 'Non hai i permessi');
					return false;
				}
			}
			
			$fp = fopen($filePath,'r');
			
			if (!$fp) {
				FHHelper::log('chargelistImport', 'Non riesco ad aprire il file');
				return false;
			}
			
			$result = array();
			$result['unrecognized']=0;
			$result['success']=0;
			$result['fail']=0;
			
			//Carico la prima riga
			$s = fgets($fp, 165);
			
			$myChargelist = DMTable::getInstance('Chargelist');
			$chargelistCode = substr($s, 1, 50);
			
			if ($myChargelist->loadFromChargelistCode($chargelistCode)) {
				//Gli aggiornamenti non sono consentiti
				FHHelper::log('chargelistImport', 'Non si può aggiornare una lista di carico (' . $myChargelist->chargelist_id . ')');
				return false;
    		}
    		
    		$myChargelist->chargelist_code = $chargelistCode;
    		
    		$day = substr($s, 51, 2);
    		$month = substr($s, 53, 2);
    		$year = substr($s, 55, 4);    		
    		$myChargelist->chargelist_date = $year . '-' . $month . '-' . $day;
    		
    		if (!$myChargelist->store()) {
				FHHelper::log('chargelistImport', 'Errore nel salvataggio: ' . print_r($myChargelist, true));
    			return false;
    		}
			
			//Procedo col contenuto
			while ($s = fgets($fp, 165)) {
				//devo ottenere l'id dell'articolo dall'anagrafica
				$myArticle = DMTable::getInstance('Article');
				$articleCode = trim(substr($s, 109, 30));
				
				if (!$myArticle->loadFromArticleCode($articleCode)) {
					$result['unrecognized']++;
					continue;
				}
				
    			$myQuery = "
    				INSERT INTO fh_chargelist_article (
    					chargelist_id,
    					article_id,
    					quantity,
    					row_code
    				) VALUES (
    					" . $myChargelist->chargelist_id . ",
    					" . $myArticle->article_id . ",
    					" . (int) substr($s, 139, 13) . ",
    					'" . substr($s, 59, 50) . "'
    				)
    			";
    			
    			if (!DMDatabase::query($myQuery)) {
    				$result['fail']++;
    			} else {
    				$result['success']++;
    			}
			}
			
			fclose($fp);
			
			return $result;
			
		}
		
		/**
			Esporta una lista di carico con i relativi esiti
		**/
		static function exportChargelist($chargelistId) {
			
			if (!DMAcl::checkPrivilege("FH_CHARGELISTS")) {
				return false;
			}
			
			$myChargelist = self::loadChargelist($chargelistId);
			$myChargelist->rows = self::loadChargelistRows($chargelistId);
			
			$txtOutput = '';
			
			//Prima riga
			$txtOutput .= 'T';
			$txtOutput .= str_pad($myChargelist->chargelist_code, 50, '0', STR_PAD_LEFT);
			$txtOutput .= DMFormat::formatDate($myChargelist->chargelist_date, 'dmY', 'Y-m-d');
			$txtOutput .= "\n";
			
			//Right
			foreach ($myChargelist->rows as $row) {
				
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
				$myArticle = FHArticleHelper::loadArticle($row->article_id);
				$txtOutput .= 'R' . str_pad(' ', 58, ' ', STR_PAD_RIGHT); //Tipo riga
				$txtOutput .= str_pad($row->row_code, 50, ' ', STR_PAD_RIGHT); //Codice univoco riga
				$txtOutput .= str_pad($row->article_code, 30, ' ', STR_PAD_RIGHT); //Codice articolo
				$txtOutput .= str_pad($row->quantity, 13, '0', STR_PAD_LEFT); //Quantità in ingresso
				$txtOutput .= str_pad($row->quantity_ok, 13, '0', STR_PAD_LEFT); //Quantità in ingresso
				$txtOutput .= "\n";
			}
			
			return $txtOutput;
			
		}
		
		/**
			Ottiene la lista delle liste di carico correntemente su DB
			
			@param array i parametri di ricerca
			@return la lista delle liste di carico
		**/
		static function getChargelists($searchParams) {
		
			if (!isset($searchParams['limit'])) {
				$searchParams['limit'] = 0;
			}
			if (!isset($searchParams['start'])) {
				$searchParams['start'] = 0;
			}
			
			$whereConditions = "";
			if ((isset($searchParams['archived'])) && ($searchParams['archived'] > -1)) {
				$whereConditions .= ' AND archived = ' . (int) $searchParams['archived'];
			}
			
			$myQuery = "
				SELECT c.*
				FROM fh_chargelist AS c
				WHERE 1 = 1
				$whereConditions
				ORDER BY c.chargelist_code DESC, c.chargelist_date DESC
			";
			
			return DMDatabase::loadObjectList($myQuery, $searchParams['start'], $searchParams['limit']);
			
		}
		
		/**
			Carica da DB la lista di carico richiesta
			
			@param int l'id della lista di carico
		**/
		function loadChargelist($chargelistId) {
		
			$myQuery = "
				SELECT *
				FROM fh_chargelist
				WHERE chargelist_id = " . (int) $chargelistId . "
			";
			
			return DMDatabase::loadObject($myQuery);
			
		}
		
		/**
			Ottiene le righe di una lista di carico
			@param int l'id della lista di carico
			@return array di oggetti
		**/
		function loadChargelistRows($chargelistId) {
		
			$chargelistId = (int) $chargelistId;
			
			$myQuery = "
				SELECT ca.*, a.name AS article_name, a.article_code AS article_code
				FROM fh_chargelist_article AS ca
				LEFT JOIN fh_article AS a ON (ca.article_id = a.article_id)
				WHERE ca.chargelist_id = $chargelistId
			";
		
			return DMDatabase::loadObjectList($myQuery);
			
		}
		
		/**
			Ottiene i dati di un articolo relativamente alla lista di carico
			@param int l'id della lista di carico
			@param int l'id dell'articolo
			@return object la riga sotto forma di oggetto
		**/
		function getChargelistArticle($chargelistId, $articleId) {
			
			$myQuery = "
				SELECT * 
				FROM fh_chargelist_article
				WHERE chargelist_id = " . (int) $chargelistId . "
				AND article_id = " . (int) $articleId . "
			";
			
			return DMDatabase::loadObject($myQuery);
			
		}
		
		/**
			Aggiorna la quantità di un articolo in una lista di carico, creando la entry se necessario
			@param int l'id della lista di carico
			@param int l'id dell'articolo
			@param int la quantità da aggiungere
			@param int l'id del magazzino (0 per OK, 1 per SCARTO, resto per STOCK)
		**/
		function updateChargelistArticle($chargelistId, $articleId, $quantity, $stockId) {
			
			$quantity = (int) $quantity;
			$articleId = (int) $articleId;
			$chargelistId = (int) $chargelistId;
			
			if ($stockId == 1) {
				$quantityField = "quantity_ok";
			} else if ($stockId == 2) {
				$quantityField = "quantity_waste";
			} else {
				$quantityField = "quantity_stock";
			}
			
			if (self::getChargelistArticle($chargelistId, $articleId)) {
				$myQuery = "
					UPDATE fh_chargelist_article 
					SET $quantityField = $quantityField + $quantity
					WHERE article_id = $articleId
					AND chargelist_id = $chargelistId
				";
			} else {
				$myQuery = "
					INSERT INTO fh_chargelist_article (
						chargelist_id,
						article_id,
						$quantityField
					) VALUES (
						$chargelistId,
						$articleId,
						$quantity
					)
				";
			}
			
			FHHelper::log('chargelist', $stockId . ' -> ' . $myQuery);
			
			return DMDatabase::query($myQuery);
			
		}
		
	}
	
?>