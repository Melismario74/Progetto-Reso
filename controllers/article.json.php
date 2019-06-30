<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class ArticleJsonController extends DMJsonController {
	
		/**
			Restituisce la lista delle liste di carico da DB
		**/
		function jsonGetArticles() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			
			$page = DMInput::getInt('page', 1);
			
			$searchParams = array();
			$searchParams['limit'] = DMInput::getInt('limit', 20);
			$searchParams['offset'] = DMInput::getInt('offset', $searchParams['limit'] * ($page - 1));
			
			$searchParams['name'] = DMInput::getString('name', '');
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['eanCode'] = DMInput::getString('eanCode', '');
			$searchParams['inStockOnly'] = DMInput::getInt('inStockOnly', 0);
			
			$articles = FHArticleHelper::getArticles($searchParams, $totalResults);
			
			//Se getStockData è settato, recupero le informazioni su cartoni e confezioni dello stock richiesto
			foreach ($articles as $article) {
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
				$getStockData = DMInput::getInt('getStockData', 0);
				if ($getStockData) {
					$article->stock = array();
					$article->stock[1] = FHStockHelper::getArticleStockData($article->article_id, 1);
					$article->stock[2] = FHStockHelper::getArticleStockData($article->article_id, 2);
					$article->stock[3] = FHStockHelper::getArticleStockData($article->article_id, 3);
				}
				
				if (DMInput::getInt('getArticleImage')) {
					$articleImagePath = DM_APP_PATH . DS . 'media' . DS . 'articles' . DS . $article->article_code . '.jpg'; 
					if (file_exists($articleImagePath)) {
						$article->image_url = DMUrl::getCurrentBaseUrl() . 'media/articles/' . $article->article_code . '.jpg';
					} else {
						$article->image_url = DMUrl::getCurrentBaseUrl() . 'media/articles/noimage.png';
					}
				}
				
			}
			
			parent::outputResult(ceil($totalResults / $searchParams['limit']), $articles, 'articles');
		
		}
		
		/**
			Restituisco l'articolo richiesto
			@param int articleId
			@param int checkChargelist determina se verificare la presenza dell'articolo in una lista di carico
			@param int chargelistId la lista su cui controllare la presenza dell'articolo
		**/
		function jsonLoadArticle() {
		
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			$articleId = DMInput::getInt('articleId', -1);
			$articleCode = DMInput::getString('articleCode', '');

			FHHelper::log("fh", "[article.jsonLoadArticle] Loading info for article $articleId");
			
			if ($articleId > 0) {
				$article = FHArticleHelper::loadArticle($articleId);
			} else {
				$article = FHArticleHelper::loadArticleFromCode($articleCode);
			}
			
			if (!$article) {
			$article = FHArticleHelper::loadArticleFromEanCode($articleCode);
			}
			
			if (!$article) {
				$pos01 = strpos($articleCode, '(01)');
				$pos400 = strpos($articleCode, '(400)');

				if (($pos01 !== false) && ($pos400 !== false)) {
					$decodedCode = substr($articleCode, ($pos01 + 4), (($pos400) - ($pos01 + 4)));
					$article = FHArticleHelper::loadArticleFromCode($decodedCode);
				}
			}
			
			if (!$article) {
				parent::outputError(-300);
			}
			
			$articleImagePath = DM_APP_PATH . DS . 'media' . DS . 'articles' . DS . $article->article_code . '.jpg'; 
			if (file_exists($articleImagePath)) {
				$article->image_url = DMUrl::getCurrentBaseUrl() . 'media/articles/' . $article->article_code . '.jpg';
			} else {
				$article->image_url = DMUrl::getCurrentBaseUrl() . 'media/articles/noimage.png';
			}
			
			
			//Se getStocksData è settato, recupero le informazioni su cartoni e confezioni di tutti gli stock
			$stockId = DMInput::getInt('getStocksData', 0);
			$getStocksData = DMInput::getInt('getStocksData', 0);
			if ($getStocksData) {
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
				$article->stocks = array();
				$article->stocks = FHStockHelper::getArticleStocksData($article->article_id);
				$article->dispatched = array();
				$article->dispatched = FHLogisticsHelper::getArticleStocksDispatchedData($article->article_id);
			}
			
			if (DMInput::getInt('getUdmsData', 0)) {
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
				$article->udms = FHLogisticsHelper::getArticleStockUdms($stockId,$article->article_id);				
			}
			
			if (DMInput::getInt('getUdmsData', 0)) {
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
				$article->listUdms = array();
				$article->listUdms = FHLogisticsHelper::getArticleUdms($article->article_id);				
			}
			
			
			parent::outputResult(1, $article, 'article');
			
		}
		
		/**
			Ritorna informazioni sulla qualità dell'articolo rispetto ad un particolare lotto
			@param int articleId l'id dell'articolo
			@param string batchInCode il lotto da verificare
		**/
		function jsonCheckArticleQuality() {
			
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			$articleId = DMInput::getInt('articleId', -1);
			
			$article = FHArticleHelper::loadArticle($articleId);
			
			if (!$article) {
				parent::outputError(-300);
			}
			
			$qualityBatches = explode(',', $article->quality_batch_in_codes);
			$batchInCode = DMInput::getString('batchInCode', '');
			
			$result = new StdClass();
			if (($batchInCode != '') && (in_array($batchInCode, $qualityBatches))) {
				$result->quality_alert = 1;
			} else {
				$result->quality_alert = 0;
			}

			$result->quality_message = $article->quality_message;
			$result->quality_batch_in_codes = $article->quality_batch_in_codes;
			
			parent::outputResult(1, $result, 'data');
			
		}
	
		/**
			Restituisce la lista delle liste di carico da DB
		**/
		function jsonGetArticleMovements() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'userhelper.php');
			
			$articleId = DMInput::getInt('articleId', -1);
			$page = DMInput::getInt('page', 1);
			
			$limit = DMInput::getInt('limit', 20);
			$offset = DMInput::getInt('offset', $limit * ($page - 1));
			
			$movements = FHArticleHelper::getArticleMovements($articleId, $offset, $limit, $totalResults);
			
			foreach ($movements as $movement) {
				$movement->created_date_str = DMFormat::formatDate($movement->created_date, 'd/m/Y H:i', 'Y-m-d H:i:s');
				$movement->created_by_str = FHUserHelper::getUsername($movement->created_by);
				$movement->stock = FHStockHelper::loadStock($movement->stock_id);
			}
			
			parent::outputResult(ceil($totalResults / $limit), $movements, 'movements');
		
		}
	
		/**
			Restituisce la lista dei lotti di ingresso dell'articolo
		**/
		function jsonGetArticleBatchIns() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'userhelper.php');
			
			$articleId = DMInput::getInt('articleId', -1);
			$page = DMInput::getInt('page', 1);
			
			$limit = DMInput::getInt('limit', 20);
			$offset = DMInput::getInt('offset', $limit * ($page - 1));
			
			$batchIns = FHArticleHelper::getArticleBatchIns($articleId, $offset, $limit, $totalResults);
			
			parent::outputResult(ceil($totalResults / $limit), $batchIns, 'batchIns');
		
		}
	
		/**
			Restituisce la lista dei lotti di uscita dell'articolo
		**/
		function jsonGetArticleBatchOuts() {
		
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'userhelper.php');
			
			$articleId = DMInput::getInt('articleId', -1);
			$page = DMInput::getInt('page', 1);
			
			$limit = DMInput::getInt('limit', 20);
			$offset = DMInput::getInt('offset', $limit * ($page - 1));
			
			$batchOuts = FHArticleHelper::getArticleBatchOuts($articleId, $offset, $limit, $totalResults);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'batchinhelper.php');
			
			foreach ($batchOuts as $batchOut) {
				$batchOut->batch_in_code = FHBatchInHelper::getBatchInCode($batchOut->batch_in_id);
			}
			
			parent::outputResult(ceil($totalResults / $limit), $batchOuts, 'batchOuts');
		
		}
		
		/**
			Elimina l'immagine dell'articolo
		**/
		function jsonDeleteArticleImage() {
		
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			$articleId = DMInput::getInt('articleId', -1);
		
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			
			if (!FHArticleHelper::deleteArticleImage($articleId)) {
				parent::outputError(-400);				
			}
			
			parent::outputResult(0);
			
		}
		
		/**
			Salva i dati sulla qualità di un articolo
		**/
		function jsonSaveArticleQuality() {
			
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			$articleId = DMInput::getInt('articleId', -1);
			
			$myArticle = DMTable::getInstance('Article');
			if (!$myArticle->load($articleId)) {
				parent::outputError(300);
			}
			
			$myArticle->quality_message = DMInput::getString('qualityMessage', '');
			$myArticle->quality_batch_in_codes = DMInput::getString('qualityBatchInCodes', '');
			
			if (!$myArticle->store()) {
				parent::outputError(200);
			}
			
			parent::outputResult($articleId);
			
		}
		
		/**
			Stampa l'etichetta per l'articolo e il lotto di uscita indicato
		**/
		function jsonPrintArticleBatchOutLabel() {
			
			//Controllo i permessi
			if (!DMUser::getUser()) {
				parent::outputError(-100);
			}
			
			$articleId = DMInput::getInt('articleId', -1);
			$batchOutId = DMInput::getInt('batchOutId', -1);
			
			$myArticle = DMTable::getInstance('Article');
			if (!$myArticle->load($articleId)) {
				parent::outputError(-300);
			}
			
			$myBatchOut = DMTable::getInstance('BatchOut');
			if (!$myBatchOut->load($batchOutId)) {
				parent::outputError(-300);
			}
			
			$myPrintClassPath = DM_APP_PATH . DS . 'views' . DS . 'recondition' . DS . 'print.php';
			require_once($myPrintClassPath);
			
			$myPrintClass = new DMPrintRecondition('portrait', 'pdf', 'Etichetta ' . $myArticle->article_code, array(0,0,283.464,348.696));
			
			$myPrintClass->articleId = $myArticle->article_id;
			$myPrintClass->batchOutId = $myBatchOut->batch_out_id;
			
			$printResult = $myPrintClass->execPrint('recondition', 'default');
			if ($printResult['result'] >= 0) {
				$data->label_url = $printResult['printUrl'];
			}
			
			parent::outputResult(0, $data);
			
		}
		
		/**
			Aggiorna la quantità di cartoni articolo in una UDM
		**/
		function jsonUpdateArticleUdmQuantity() {

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
		
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			$articleId = DMInput::getInt('articleId', -1);
			$udmId = DMInput::getInt('udmId', -1);
			$quantity = DMInput::getInt('quantity', 0);
			
			$myArticle = DMTable::getInstance('Article');
			if (!$myArticle->load($articleId)) {
				parent::outputError(-300);
			}

			FHLogisticsHelper::setToUdm($udmId, $articleId, $quantity);
			
			parent::outputResult($udmId);
			
		}
		
		/**
			Aggiorna la cache relativa agli stock per l'articolo
		**/
		function jsonUpdateArticleStockCache() {
		
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			$articleId = DMInput::getInt('articleId', -1);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			$stocks = FHStockHelper::getStocks();
			
			foreach ($stocks as $stock) {
				FHStockHelper::updateStockCache($articleId, $stock->stock_id);
			}
						
			parent::outputResult($articleId);
		
		}
		
		/**
			Aggiorna un batch in per l'articolo
		**/
		function jsonUpdateArticleBatchIn() {
			
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			$articleId = DMInput::getInt('articleId', -1);
			$batchInId = DMInput::getInt('batchInId', -1);
			$quantity = DMInput::getInt('quantity', 0);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'batchinhelper.php');
			if (!FHBatchInHelper::setArticle($batchInId, $articleId, $quantity)) {
				parent::outputError(-200);
			}
			
			parent::outputResult(0);
			
		}
		
		/**
			Aggiorna un batch out per l'articolo
		**/
		function jsonUpdateArticleBatchOut() {
		
			if (!DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) {
				parent::outputError(-110);
			}
			
			$articleId = DMInput::getInt('articleId', -1);
			$batchInId = DMInput::getInt('batchInId', -1);
			$batchOutId = DMInput::getInt('batchOutId', -1);
			$quantity = DMInput::getInt('quantity', 0);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'batchouthelper.php');
			if (!FHBatchOutHelper::setArticle($batchOutId, $batchInId, $articleId, $quantity)) {
				parent::outputError(-200);
			}
			
			parent::outputResult(0);
			
		}
	}
?>