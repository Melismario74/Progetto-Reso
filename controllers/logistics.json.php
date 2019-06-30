<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class LogisticsJsonController extends DMJsonController {
	
		function jsonSetUdm() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}
			
			DMDatabase::query("BEGIN;");

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			
			$articleCode = DMInput::getString('articleCode', '');
			$udmCode = DMInput::getString('udmCode', '');
			$stockId = DMInput::getInt('stockId', 0); //aggiunto da Mario
			$articleId = DMInput::getInt('articleId', -1);
			$quantity = DMInput::getInt('quantity', 0);
			$forced = DMInput::getInt('forced', 0);
			//$barcode = DMInput::getString('barcode', '');
			
			if ($forced != 0) {
				if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
					parent::outputError(-110);
				}
			}
			
			//Provo a caricare l'articolo
			$myArticle = DMTable::getInstance('Article');
			$loaded = false;

			if ($articleId > -1) {
				if (!$myArticle->load($articleId)) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-300);
				} else {
					$loaded = true;
				}
			} else if ($articleCode != '') {
				$pos01 = strpos($articleCode, '(01)');
				$pos400 = strpos($articleCode, '(400)');
				if (($pos01 !== false) && ($pos400 !== false)) {
					$decodedCode = substr($articleCode, ($pos01 + 4), (($pos400) - ($pos01 + 4)));
					if ($myArticle->loadFromArticleCode($decodedCode)) {
						$loaded = true;
					}
				}
				//if ($myArticle->loadFromEanCode($articleCode)) {
				//$loaded = true;
				//}
			
				if (!$loaded) {
					if (!$myArticle->loadFromArticleCode($articleCode)) {
						DMDatabase::query("ROLLBACK;");
						parent::outputError(-300);
					}
				}
				$articleId = $myArticle->article_id;
			} else {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-300);
			}
			
			//Verifico che ci siano abbastanza confezioni per tipo di magazzino richiesto
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
			$unitsGood = FHStockHelper::getArticleStockData($articleId, $stockId);			
			$myArticle->dispatched = array();
			$myArticle->dispatched = FHLogisticsHelper::getArticleStockDispatchedData($myArticle->article_id, $stockId);
			$dispatchedUnits = $myArticle->dispatched->total_units;
			
			
			if (($unitsGood->total_units - $dispatchedUnits) < $quantity) {
				DMDatabase::query("ROLLBACK;");
				parent::outputError(-1000, "Non ci sono abbastanza cartoni buoni per completare l'operazione");
			}
			
			
			//Provo a caricare la UDM
			$myUdm = DMTable::getInstance('Udm');
			 if (!$myUdm->loadFromUdmCode($udmCode)) {
				parent::outputError(-1000, "L'UDM non esiste");
			} 

			FHLogisticsHelper::addToUdm($myUdm->udm_id, $myArticle->article_id, $stockId, $quantity); 
			
			if (!DMDatabase::query("COMMIT;")) {
				parent::outputError(-1000, "Errore finalizzando la transazione");
			}
			
			
			
			parent::outputResult($myUdm->udm_id);
			
		}
		
		function jsonGetUdms() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}			
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
			$udms = FHLogisticsHelper::getUdms();
			
			parent::outputResult(count($udms), $udms, 'udms');
			
		}
		
		function jsonGetUdmsStock() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}			
			
			$stockId  = DMInput::getInt('stockId', -1);
			
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
			$udms = FHLogisticsHelper::getUdmsStock($stockId);
			
			
			
			parent::outputResult(count($udms), $udms, 'udms');
			
			
			
			
		}
		
		function jsonGetUdms1() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}			
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
			$page = DMInput::getInt('page', 1);
			
			$searchParams = array();
			$searchParams['limit'] = DMInput::getInt('limit', 20);
			$searchParams['offset'] = DMInput::getInt('offset', $searchParams['limit'] * ($page - 1));
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['ubicazione'] = DMInput::getString('ubicazione', '');
			
			$udms = FHLogisticsHelper::getudms1($searchParams, $totalResults);
			
			parent::outputResult(ceil($totalResults / $searchParams['limit']), $udms, 'udms');
		}
		
		function jsonGetItemUdms() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}
					
			
			$articleId = DMInput::getInt('articleId', -1);
			
			//Provo a caricare l'articolo
			$myArticle = DMTable::getInstance('Article');
			if (!$myArticle->load($articleId)) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-300);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
						
			
			$udms = FHLogisticsHelper::getItemUdms($myArticle->article_id);
			
			
			parent::outputResult(count($udms), $udms, 'udms');
			
		}
		
		function jsonGetUdmsItem() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}
					
			
			$articleCode  = DMInput::getString('articleCode', '');
			
			//Provo a caricare l'articolo
			$myArticle = DMTable::getInstance('Article');
			if (!$myArticle->loadFromArticleCode($articleCode)) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-300);
			}
			$articleId = $myArticle->article_id;
			
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
						
			
			$udms = FHLogisticsHelper::getItemUdms($myArticle->article_id);
			
			
			parent::outputResult(count($udms), $udms, 'udms');
			
		}
		function jsonGetUdmsStockItem() {
			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}
					
			
			$articleCode  = DMInput::getString('articleCode', '');
			$stockId  = DMInput::getInt('stockId', -1);
			
			//Provo a caricare l'articolo
			$myArticle = DMTable::getInstance('Article');
			if (!$myArticle->loadFromArticleCode($articleCode)) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-300);
			}
			$articleId = $myArticle->article_id;
			
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
						
			
			$udms = FHLogisticsHelper::getArticleStockUdms($stockId,$myArticle->article_id);
			
			
			parent::outputResult(count($udms), $udms, 'udms');
			
		}

		function jsonGetUdm() {

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');

			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}

			$udmCode = DMInput::getString('udmCode', '');

			$myUdm = DMTable::getInstance('Udm');
			if (!$myUdm->loadFromUdmCode($udmCode)) {
				parent::outputError(-404);
			}

			$myResult = new StdClass();
			$myResult->udmCode = $udmCode;

			$myResult->articles = array();
			$udmArticles = FHLogisticsHelper::getUdmArticles($myUdm->udm_id);
			foreach ($udmArticles as $udmArticle) {

				$myArticle = new StdClass();
				$myArticle->articleCode = $udmArticle->article_code;
				$myArticle->name = $udmArticle->name;
				$myArticle->quantity_units = $udmArticle->quantity_units;

				$myResult->articles[] = $myArticle;

			}

			parent::outputResult(0, $myResult);

		}
		
		

		function jsonCreateUdm() {

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');

			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}

			$udmNew = FHLogisticsHelper::createUdm();
			parent::outputResult(0, $udmNew, 'udm');

		}
		
		function jsonCreateIbd() {

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');

			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}

			$ibdNew = FHLogisticsHelper::createIbd();
			parent::outputResult(0, $ibdNew, 'udm');

		}
		function jsonSaveSession() {

			
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}

			$udmCode = DMInput::getString('udmCode', '');
			$ubicazione = DMInput::getString('ubicazione', '');
			$stockId = DMInput::getInt('stockId', 0);
			$articlesDataString = DMInput::get('articlesData', '', 'RAW'); 
			$articlesData = json_decode($articlesDataString);
			
			


			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');

			DMDatabase::query("BEGIN;");
			
			
			$myUdm = DMTable::getInstance('Udm');
			if (!$myUdm->loadFromUdmCode($udmCode)) {
				parent::outputError(-404);
			}
			$myUdm->ubicazione = $ubicazione;
			
			FHLogisticsHelper::saveUdm($udmCode, $ubicazione);			

			foreach ($articlesData as $articleData) {
				
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
				$article = array();
				$article = FHArticleHelper::loadArticle($articleData->article_id);
				$myArticle = DMTable::getInstance('Article');
				if (!$myArticle->loadFromArticleCode($article->article_code)){
					parent::outputError(-404);
				}
				

				//Verifico che ci siano abbastanza cartoni aggregati buoni
			
				$unitsGood = FHStockHelper::getArticleStockData($myArticle->article_id, $stockId);			
				$dispatchedUnits = FHLogisticsHelper::getArticleStockDispatchedData($myArticle->article_id, $stockId);
				
				if (($unitsGood->total_units - $dispatchedUnits->total_units) < $articleData->quantity) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-1000, "Non ci sono abbastanza cartoni buoni per completare l'operazione: " . $articleData->name);
				}
				FHLogisticsHelper::addToUdm($myUdm->udm_id, $articleData->article_id, $stockId, $articleData->quantity);
				
				/*Verifico che ci siano abbastanza cartoni aggregati buoni
				$packagesGood = FHStockHelper::getArticleStockData($articleData->article_id, 1);
				$dispatchedPackages = FHArticleHelper::getDispatchedPackages($articleData->article_id);
				echo "total packages = " . $packagesGood->total_packages . ", dispatched = " . $dispatchedPackages . ", quantity = " . $articleData->quantity;
				if (($packagesGood->total_packages - $dispatchedPackages) < $articleData->quantity) {
					DMDatabase::query("ROLLBACK;");
					parent::outputError(-1000, "Non ci sono abbastanza cartoni buoni per completare l'operazione: " . $articleData->name);
				} 
				Aggiorno la UDM
				FHLogisticsHelper::addToUdm($myUdm->udm_id, $articleData->article_id, $articleData->quantity);
				echo $myUdm->udm_id . ', ' . $articleData->article_id . ', ', $articleData->quantity;
				*/
			}

			DMDatabase::query("COMMIT;");

			parent::outputResult(0);

		}
		
		function jsonSaveUdm() {

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');

			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}

			$udmCode = DMInput::getString('udmCode', '');
			$ubicazione = DMInput::getString('ubicazione', '');
						

			$myUdm = DMTable::getInstance('Udm');
			if (!$myUdm->loadFromUdmCode($udmCode)) {
				parent::outputError(-404);
			}
			DMDatabase::query("BEGIN;");
			
			$myUdm = FHLogisticsHelper::saveUdm($udmCode, $ubicazione);
			
			DMDatabase::query("COMMIT;");

			parent::outputResult(0);

		}

		function jsonPrintUdm() {

			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}

			$udmCode = DMInput::getString('udmCode', '');

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');

			$myUdm = DMTable::getInstance('Udm');
			if (!$myUdm->loadFromUdmCode($udmCode)) {
				parent::outputError(-404);
			}

			$myPrintClassPath = DM_APP_PATH . DS . 'views' . DS . 'logistics' . DS . 'print.php';
			require_once($myPrintClassPath);

			$myPrintClass = new DMPrintLogistics('landscape', 'pdf', 'UDM ' . $udmCode, array(0,0,283.464,348.696));

			$myPrintClass->udmId = $myUdm->udm_id;

			$data = new StdClass();

			$printResult = $myPrintClass->execPrint('logistics', 'default');
			if ($printResult['result'] >= 0) {
				$data->print_url = $printResult['printUrl'];
			}

			parent::outputResult(0, $data);

		}
		
		function jsonDeleteUdm() {
		
			//Controllo i permessi
			if (!DMAcl::checkPrivilege('FH_LOGISTICS')) {
				parent::outputError(-110);
			}
			
			$udmCode = DMInput::getString('udmCode', '');
			
			$myUdm = DMTable::getInstance('Udm');
			if (!$myUdm->loadFromUdmCode($udmCode)) {
				parent::outputError(-404);
			}
			
			if (!$myUdm->delete()) {
				parent::outputError(-400);
			}
						
			parent::outputResult(0);
		
		}
		
		/**
			Esporta le udm in CSV
		**/
		function jsonExportUdmsCSV() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
			
			$searchParams = array();
			$searchParams['limit'] = 0;
			$searchParams['offset'] = 0;
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['ubicazione'] = DMInput::getString('ubicazione', '');
				
			$fileUrl = FHLogisticsHelper::exportUdmsCSV($searchParams);
			
			$data = new StdClass();
			$data->export_url = $fileUrl;
			
			parent::outputResult(0, $data);
			
		}
				
	}
?>