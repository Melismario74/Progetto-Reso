<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class MovementJsonController extends DMJsonController {
	
		/**
			Ottiene la lista dei movimenti
		**/
		function jsonGetMovements() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'movementhelper.php');
			
			$page = DMInput::getInt('page', 1);
			
			$searchParams = array();
			$searchParams['limit'] = DMInput::getInt('limit', 20);
			$searchParams['offset'] = DMInput::getInt('offset', $searchParams['limit'] * ($page - 1));
			
			$searchParams['movementDateFrom'] = DMFormat::formatDate(DMInput::getString('movementDateFrom', ''), 'Y-m-d', 'd/m/Y');
			$searchParams['movementDateTo'] = DMFormat::formatDate(DMInput::getString('movementDateTo', ''), 'Y-m-d', 'd/m/Y');
			$searchParams['batchInCode'] = DMInput::getString('batchInCode', '');
			$searchParams['batchOutCode'] = DMInput::getString('batchOutCode', '');
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['eanCode'] = DMInput::getString('eanCode', '');
			$searchParams['movementType'] = DMInput::getString('movementType', '');
			$searchParams['userId'] = DMInput::getInt('userId', -1);
			$searchParams['stockId'] = DMInput::getInt('stockId', -1);
			
			
			$movements = FHMovementHelper::getMovements($searchParams, $totalResults);
			
			foreach ($movements as $movement) {
				if (DMInput::getInt('getArticleData', 0)) {
					require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
					$movement->article = FHArticleHelper::loadArticle($movement->article_id);
				}
				
				if (DMInput::getInt('getUserData', 0)) {
					require_once(DM_APP_PATH . DS . 'helpers' . DS . 'userhelper.php');
					$movement->user = new StdClass();
					$movement->user->name = FHUserHelper::getUserName($movement->created_by);
				}
				
				if (DMInput::getInt('getMovementDetails', 0)) {
					$movement->details = FHMovementHelper::getMovementDetails($movement->movement_id);
				}
				
				$movement->created_date_str = DMFormat::formatDate($movement->created_date, 'd/m/Y', 'Y-m-d');
				$movement->movement_type_str = DMLang::_($movement->movement_type);
			
			}
			
			parent::outputResult(ceil($totalResults / $searchParams['limit']), $movements, 'movements');
		
		}
		
		/**
			Esporta i movimenti in TXT
		**/
		function jsonExportMovements() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'movementhelper.php');
			
			$searchParams = array();
			$searchParams['limit'] = 0;
			$searchParams['offset'] = 0;
			
			$searchParams['movementDateFrom'] = DMFormat::formatDate(DMInput::getString('movementDateFrom', ''), 'Y-m-d', 'd/m/Y');
			$searchParams['movementDateTo'] = DMFormat::formatDate(DMInput::getString('movementDateTo', ''), 'Y-m-d', 'd/m/Y');
			$searchParams['batchInCode'] = DMInput::getString('batchInCode', '');
			$searchParams['batchOutCode'] = DMInput::getString('batchOutCode', '');
			$searchParams['eanCode'] = DMInput::getString('eanCode', '');
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['movementType'] = DMInput::getString('movementType', '');
			$searchParams['userId'] = DMInput::getInt('userId', -1);
			
			$txtContent = FHMovementHelper::exportMovements($searchParams,$movementIdMax);
			
			$fileName = 'movements_export_' . uniqid() . '.txt';
			$filePath = DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . $fileName;
			$fileUrl = DMUrl::getCurrentBaseUrl() . 'temp/export/' . $fileName;
		
			if (!@file_put_contents($filePath, $txtContent)) {
				parent::outputError(-200);
			}
			
			$data = new StdClass();
			$data->export_url = $fileUrl;
			
			parent::outputResult(0, $data);
			
		}
		
		/**
			Esporta i movimenti in CSV
		**/
		function jsonExportMovementsCSV() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'movementhelper.php');
			
			$searchParams = array();
			$searchParams['limit'] = 0;
			$searchParams['offset'] = 0;
			
			$searchParams['movementDateFrom'] = DMFormat::formatDate(DMInput::getString('movementDateFrom', ''), 'Y-m-d', 'd/m/Y');
			$searchParams['movementDateTo'] = DMFormat::formatDate(DMInput::getString('movementDateTo', ''), 'Y-m-d', 'd/m/Y');
			$searchParams['batchInCode'] = DMInput::getString('batchInCode', '');
			$searchParams['batchOutCode'] = DMInput::getString('batchOutCode', '');
			$searchParams['eanCode'] = DMInput::getString('eanCode', '');
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['movementType'] = DMInput::getString('movementType', '');
			$searchParams['userId'] = DMInput::getInt('userId', -1);
			
			$fileUrl = FHMovementHelper::exportMovementsCSV($searchParams);
			
			$data = new StdClass();
			$data->export_url = $fileUrl;
			
			parent::outputResult(0, $data);
			
		}
		
	}
?>