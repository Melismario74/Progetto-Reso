<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class DMPrintLogistics extends DMPrintClass {
	
		function getInput() {
		}
		
		function execPrint($view, $template) {
		
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
			$this->udm = DMTable::getInstance('Udm');
			$this->udm->load($this->udmId);
			$this->udm->articles = FHLogisticsHelper::getUdmArticles($this->udmId);

			$barcodeName = $this->udm->udm_code;

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
			$code->parse($barcodeName);

			$drawing = new BCGDrawing(DM_APP_PATH . DS . 'temp' . DS . 'print' . DS . $barcodeName . '.png', $colorBack);
			$drawing->setBarcode($code);
			$drawing->draw();
			$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
			
			return parent::execPrint($view, $template);
		
		}
		
		function printMultipleLabels($view, $template) {
		
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php'); 
			foreach ($this->printArray as $printData) {
				$printData->udm = DMTable::getInstance('Udm');
				$printData->udm->load($printData->udmId);
				$printData->articles = FHLogisticsHelper::getUdmArticles($printData->udmId);
								
				$printData->barcode = $printData->udm->udm_code;
				$barcodeName = $printData->udm->udm_code;
				
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