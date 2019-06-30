<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class DMPrintRecondition extends DMPrintClass {
	
		function getInput() {
		}
		
		function execPrint($view, $template) {
		
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			$this->article = FHArticleHelper::loadArticle($this->articleId);
			
			$this->batchOut = DMTable::getInstance('BatchOut');
			$this->batchOut->load($this->batchOutId);		
			
			//Provo a generare il barcode
			$this->barcode = '(01)' . $this->article->article_code . '(400)' . $this->batchOut->batch_out_code;
			$barcodeName = $this->article->article_code . '_' . $this->batchOut->batch_out_code;
			    		
			require_once(DM_APP_PATH . DS . 'libraries' . DS . 'barcode2' . DS . 'BCGcode128.barcode.php');
			require_once(DM_APP_PATH . DS . 'libraries' . DS . 'barcode2' . DS . 'BCGDrawing.php');
			
			@unlink(DM_APP_PATH . DS . 'temp' . DS . 'print' . DS . $barcodeName . '.png');
			
			
			$colorFront = new BCGColor(0, 0, 0);
			$colorBack = new BCGColor(255, 255, 255);  
    		
    		$code = new BCGcode128(); // Or another class name from the manual
			$code->setScale(4); // Resolution
			$code->setThickness(50); // Thickness
			$code->setFont(0); // Thickness
			$code->setForegroundColor($colorFront); // Color of bars
			$code->setBackgroundColor($colorBack); // Color of spaces
			$code->parse($this->barcode); 
			
			$drawing = new BCGDrawing(DM_APP_PATH . DS . 'temp' . DS . 'print' . DS . $barcodeName . '.png', $colorBack);
			$drawing->setBarcode($code);
			$drawing->draw();
			$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

			return parent::execPrint($view, $template);
		
		}
		
		function printMultipleLabels($view, $template) {
		
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php'); 
			foreach ($this->printArray as $printData) {
				$printData->article = FHArticleHelper::loadArticle($printData->articleId);
				
				$printData->batchOut = DMTable::getInstance('BatchOut');
				$printData->batchOut->load($printData->batchOutId);
				
				$printData->barcode = '(01)' . $printData->article->article_code . '(400)' . $printData->batchOut->batch_out_code;
				$barcodeName = $printData->article->article_code . '_' . $printData->batchOut->batch_out_code;
				
				require_once(DM_APP_PATH . DS . 'libraries' . DS . 'barcode2' . DS . 'BCGFontFile.php');
    			require_once(DM_APP_PATH . DS . 'libraries' . DS . 'barcode2' . DS . 'BCGcode128.barcode.php');
				require_once(DM_APP_PATH . DS . 'libraries' . DS . 'barcode2' . DS . 'BCGDrawing.php');
				
				@unlink(DM_APP_PATH . DS . 'temp' . DS . 'print' . DS . $barcodeName . '.png');				
				
				$colorFront = new BCGColor(0, 0, 0);
				$colorBack = new BCGColor(255, 255, 255);  
    			
    			$code = new BCGcode128(); // Or another class name from the manual
				$code->setScale(4); // Resolution
				$code->setThickness(50); // Thickness
				$code->setFont(0); // Thickness
				$code->setForegroundColor($colorFront); // Color of bars
				$code->setBackgroundColor($colorBack); // Color of spaces
				$code->parse($printData->barcode); 
				
				$drawing = new BCGDrawing(DM_APP_PATH . DS . 'temp' . DS . 'print' . DS . $barcodeName . '.png', $colorBack);
				$drawing->setBarcode($code);
				$drawing->draw();
				$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
			}
		
			return parent::execPrint($view, $template);
			
		}
		
	}